<?php

namespace App\Nova\LMS;

use App\Nova\Actions\DeteleQuestion;
use App\Nova\Actions\DownloadExcelTemplate;
use App\Nova\Actions\ImportQuestions;
use App\Nova\Filters\TopicQuestionNameFilter;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inspheric\Fields\Url;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Trin4ik\NovaSwitcher\NovaSwitcher;

class Question extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Question>
     */
    public static string $model = \App\Models\Question::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title(): string
    {
        if (\request()->get('search')) {
            return "ID: $this->id - ".Str::of((new \Html2Text\Html2Text(html_entity_decode($this->name)))->getText() ?? '');
        }

        if (Str::contains(\request()->getPathInfo(), '/nova-api/answers/')) {
            return Str::of((new \Html2Text\Html2Text(html_entity_decode($this->name)))->getText() ?? '');
        }

        return Str::of((new \Html2Text\Html2Text(html_entity_decode($this->name)))->getText() ?? '')->limit(50);
    }

    protected static function applySearch($query, $search): Builder
    {
        $search_name = htmlentities($search, ENT_QUOTES, 'UTF-8');

        return $query->where(function ($query) use ($search, $search_name) {
            $query->where('questions.id', $search)->orWhere('name', 'like', "%$search_name%");
        });
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('topic')->with('question_type');
    }

    /**
     * @throws \Exception
     */
    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Url::make(
                __('Topic'),
                fn () => sprintf('%s/resources/%s/%d', config('nova.path'), Topic::uriKey(), $this->topic?->getAttribute('id'))
            )
                ->customHtmlUsing(fn () => $this->topic?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->topic))
                ->sameTab()
                ->exceptOnForms(),
            BelongsTo::make(__('Question Type'), 'question_type', QuestionType::class)->filterable(),
            Text::make(__('Question'), 'name')
                ->sortable()
                ->displayUsing(fn ($name) => Str::of((new \Html2Text\Html2Text(html_entity_decode($name)))->getText() ?? '')->limit(50)),
        ];
    }

    public function fieldsForPreview(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @throws \Exception
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(),
            Url::make(
                __('Topic'),
                fn () => sprintf('%s/resources/%s/%d', config('nova.path'), Topic::uriKey(), $this->topic?->getAttribute('id'))
            )
                ->customHtmlUsing(fn () => $this->topic?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->topic))
                ->sameTab()
                ->exceptOnForms(),

            Multiselect::make(__('Topics'), 'topics')
                ->options(\App\Models\Topic::pluck('name', 'id'))
                ->singleSelect()
                ->withMeta(['value' => $request->get('viaResource') === 'topics' ? $request->get('viaResourceId') : $this->topic?->id])
                ->onlyOnForms()
                ->readonly($request->viaResource() === Topic::class && $request->get('editMode') !== 'update')
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })
                ->hideFromIndex(),
            BelongsTo::make(__('Question Type'), 'question_type', QuestionType::class)
                ->withoutTrashed()
                ->default(1)
                ->filterable(),
            TinymceEditor::make(__('Question'), 'name')
                ->showOnIndex()
                ->fullWidth()
                ->rules('required')
                ->creationRules('unique:questions,name')
                ->updateRules('unique:questions,name,{{resourceId}}')
                ->withMeta([
                    'shouldShow' => true
                ]),
            SimpleRepeatable::make(__('Answers'), 'answers', [
                NovaSwitcher::make(__('Is Correct'), 'is_correct')
                    ->withLabels(true: __('Correct'), false: __('In Correct'))
                    ->default(false),
                TinymceEditor::make(__('Value'), 'name')
                    ->showOnIndex()
                    ->fullWidth()
                    ->rules('required'),
                Hidden::make('', 'id'),
            ])->addRowLabel(__('Add Answer'))
                ->fillUsing(function ($request, $model, $attribute) {
                unset($model[$attribute]);
            })->onlyOnForms()->minRows(1)->required(),
            HasMany::make(__('Answers'), 'options', Answer::class),
            BelongsToMany::make(__('Quizzes'), 'quizzes', Quiz::class)
        ];
    }

    public static function afterSave(NovaRequest $request, $model)
    {
        if ((!$request->viaResource() || $request->get('editMode') === 'update') &&
            $request->get('topics') != $model->getAttribute('topic')?->getAttribute('id'))
        {
            $model->getAttribute('topic')?->questions()->detach($model->getAttribute('id'));
            \App\Models\Topic::find($request->get('topics'))?->questions()->attach($model->getAttribute('id'));
        }

        $model->saveAnswers(collect(json_decode($request->get('answers'))));
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new TopicQuestionNameFilter()
        ];
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
        if ($request->viaResource()) {
            return [(new DeteleQuestion())->onlyOnDetail(),];
        }

        return [
            (new DeteleQuestion())->onlyOnDetail()->confirmText(__('Do you want to delete this question?'))->confirmButtonText(__('Delete')),
            (new ImportQuestions($request->user()))->standalone()
                ->canSee(fn ($request) => $request->user()->can('create', \App\Models\Question::class))
                ->canRun(fn ($request) => $request->user()->can('create', \App\Models\Question::class)),
            (new DownloadExcelTemplate())->standalone()
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->confirmText(__('Are you sure you want to download'))
                ->setType('question'),
        ];
    }

    public static function label(): string
    {
        return __('Questions');
    }

    public static function softDeletes(): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        if ($request instanceof \Laravel\Nova\Http\Requests\ResourceDetailRequest) {
            return false;
        }
        return true;
    }

    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        return false;
    }

    /**
     * Determine if the user can attach models of the given type to the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @return bool
     */
    public function authorizedToAttach(NovaRequest $request, $model)
    {
        return false;
    }

    public function authorizedToDetach(NovaRequest $request, $model, $relationship)
    {
        return false;
    }
}
