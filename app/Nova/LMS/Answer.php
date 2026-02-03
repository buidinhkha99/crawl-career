<?php

namespace App\Nova\LMS;

use App\Nova\Resource;
use Caddydz\NovaPreviewResource\NovaPreviewResource;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceCreateOrAttachRequest;
use Laravel\Nova\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Laravel\Nova\Http\Requests\UpdateResourceRequest;
use Murdercode\TinymceEditor\TinymceEditor;
use SLASH2NL\NovaBackButton\NovaBackButton;
use Trin4ik\NovaSwitcher\NovaSwitcher;

class Answer extends Resource
{
    public static $clickAction = 'preview';

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<QuestionOption>
     */
    public static string $model = \Harishdurga\LaravelQuiz\Models\QuestionOption::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title(): string
    {
        return Str::of((new \Html2Text\Html2Text(html_entity_decode($this->name)))->getText() ?? '')->limit(50);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * @throws \Exception
     */
    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make(__('Value'),
                fn () => Str::of((new \Html2Text\Html2Text(html_entity_decode($this->name)))->getText() ?? '')->limit(150)
            ),
            Boolean::make(__('Is Correct'), 'is_correct'),

        ];
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(),
            TinymceEditor::make(__('Value'), 'name')
                ->showOnIndex()
                ->fullWidth()
                ->withMeta([
                    'shouldShow' => true
                ])
                ->rules('required')->showOnPreview(),
            Boolean::make(__('Is Correct'), 'is_correct')
                ->onlyOnDetail()
                ->showOnUpdating(fn() => true)
                ->readonly(fn() => $request instanceof ResourceUpdateOrUpdateAttachedRequest)
                ->showOnPreview(),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [
            (new NovaBackButton())
                ->onlyOnDetail()
                ->url(sprintf(
                        '%s/resources/%s/%d', config('nova.path'),
                        Question::uriKey(),
                        QuestionOption::where('id', '=', $request->resourceId)->first()?->getAttribute('question_id'))
                ),
        ];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \App\Nova\Resource  $resource
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        if ($request->viaResource) {
            return '/resources/'.$request->viaResource.'/'.$request->viaResourceId;

        }

        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \App\Nova\Resource  $resource
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        if ($request->viaResource) {
            return '/resources/'.$request->viaResource.'/'.$request->viaResourceId;

        }

        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    public static function label(): string
    {
        return __('Answers');
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    public function authorizedToView(Request $request)
    {
        return false;
    }
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
