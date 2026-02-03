<?php

namespace App\Nova\LMS;

use App\Enums\LessonConstant;
use App\Nova\Filters\TopicNameFilter;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Laravel\Nova\Fields\BelongsToMany;
use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Outl1ne\NovaMediaHub\Models\Media;
use Murdercode\TinymceEditor\TinymceEditor;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class Lesson extends Resource
{
    use HasCallbacks;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Lesson>
     */
    public static $model = \App\Models\Lesson::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static function label(): string
    {
        return __('Lesson');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users');
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users');
    }

    /**
     * @throws \Exception
     */
    public function fieldsForIndex(NovaRequest $request): array
    {

        return [
            ID::make(),
            Text::make(__('Lesson name'), 'name')->rules(['required']),
            Url::make(__('Topic'), fn () => sprintf(
                '%s/resources/%s/%d',
                config('nova.path'),
                Topic::uriKey(),
                $this->topic?->getAttribute('id')
            ))
                ->customHtmlUsing(fn () => $this->topic?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->topic))
                ->sameTab(),
            Number::make(__('Total of user'), 'users_count')->sortable(),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Lesson name'), 'name')->rules(['required']),
            Url::make(__('Topic'), fn () => sprintf(
                '%s/resources/%s/%d',
                config('nova.path'),
                Topic::uriKey(),
                $this->topic?->getAttribute('id')
            ))
                ->customHtmlUsing(fn () => $this->topic?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->topic))
                ->sameTab()
                ->exceptOnForms(),
            Multiselect::make(__('Topics'), 'topics')
                ->options(\App\Models\Topic::pluck('name', 'id'))
                ->singleSelect()
                ->withMeta(['value' => $request->get('viaResource') === 'topics' ? $request->get('viaResourceId') : $this->topic?->id])
                ->onlyOnForms()
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->readonly(fn () => ($request->viaResource() && $request->viaResource() != Lesson::class) && $request->get('editMode') != 'update'),
            Multiselect::make(__('Document type'), 'document_type')
                ->singleSelect()
                ->options([
                    LessonConstant::LESSON_TYPE_NORMAL_TEXT => __('Normal text'),
                    LessonConstant::LESSON_TYPE_LINK_DRIVER => __('Link driver'),
                ])
                ->required(),
            Text::make(__("Document link"), 'link')->rules([function ($attribute, $value, $fail) {
                if (\request()->get('document_type') == LessonConstant::LESSON_TYPE_LINK_DRIVER && empty($value)) {
                   return $fail(__('Document link is required'));
                }
            }]),
            Text::make(__("Video link"), 'link_media'),
            TinymceEditor::make(__('Lesson content'), 'content')
                ->showOnIndex()
                ->fullWidth()
                ->rules([function ($attribute, $value, $fail) {
                    if (\request()->get('document_type') == LessonConstant::LESSON_TYPE_NORMAL_TEXT && empty($value)) {
                        return $fail(__('Document content is required'));
                    }
                }])
                ->creationRules('unique:questions,name')
                ->updateRules('unique:questions,name,{{resourceId}}'),
            MediaHubField::make(__('Lesson document'), 'document')
                ->defaultCollection('lessons')->multiple(),
            Text::make(__("Total of user"), 'users_count')->exceptOnForms(),
            BelongsToMany::make(__('Users'), 'users', User::class),
            BelongsToMany::make(__('Questions'), 'questions', Question::class)->required()->searchable(),

        ];
    }

    public static function afterSave(Request $request, $model)
    {
        if ((!$request->viaResource() || $request->get('editMode') === 'update') &&
            $request->get('topics') != $model->getAttribute('topic')?->getAttribute('id')
        ) {
            $model->getAttribute('topic')?->lessons()->detach($model->getAttribute('id'));
            \App\Models\Topic::find($request->get('topics'))?->lessons()->attach($model->getAttribute('id'));
        }
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            (new TopicNameFilter())->singleSelect(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
