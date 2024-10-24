<?php

namespace App\Nova\LMS\AcademicReport;

use App\Models\Question;
use App\Models\QuestionUser;
use App\Nova\Filters\TopicNameFilter;
use App\Nova\Filters\TopicQuestionDoneFilter;
use App\Nova\Filters\TopicQuestionNameFilter;
use App\Nova\Filters\UserFilter;
use App\Nova\LMS\Topic;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Carbon\Carbon;
use Exception;
use Html2Text\Html2Text;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Murdercode\TinymceEditor\TinymceEditor;

class QuestionDone extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Question>
     */
    public static string $model = QuestionUser::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        if (\request()->get('search')) {
            return "ID: $this->id - " . Str::of((new Html2Text(html_entity_decode($this->question->name)))->getText() ?? '');
        }

        if (Str::contains(\request()->getPathInfo(), '/nova-api/answers/')) {
            return Str::of((new Html2Text(html_entity_decode($this->question->name)))->getText() ?? '');
        }

        return Str::of((new Html2Text(html_entity_decode($this->question->name)))->getText() ?? '')->limit(50);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['question', 'user']);
    }

    protected static function applySearch($query, $search): Builder
    {
        $search_name = htmlentities($search, ENT_QUOTES, 'UTF-8');

        return $query->where(function ($query) use ($search, $search_name) {
            $query->whereHas('question', function ($query) use ($search_name, $search) {
                $query->where('questions.id', $search)->orWhere('name', 'like', "%$search_name%");
            })->orWhereHas('user', function ($query) use ($search) {
                $query->where('id', $search)->orWhere('name', 'like', "%$search%")->orWhere('employee_code', $search);
            });
        });
    }

    /**
     * @throws Exception
     */
    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Employee Code'), 'user->employee_code')
                ->sortable(),
            Url::make(
                __('User'),
                fn() => sprintf('%s/resources/%s/%d', config('nova.path'), User::uriKey(), $this->user->id)
            )
                ->customHtmlUsing(fn() => $this->user?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->user))
                ->sameTab()
                ->exceptOnForms(),
            Text::make(__('Topic'), 'topic->name')
                ->sortable()
                ->displayUsing(fn($name) => Str::of((new Html2Text(html_entity_decode($name)))->getText() ?? '')->limit(50)),

            Text::make(__('Question'), 'question->name')
                ->sortable()
                ->displayUsing(fn($name) => Str::of((new Html2Text(html_entity_decode($name)))->getText() ?? '')->limit(50)),

            Boolean::make(__('Is Correct'), 'is_correct'),
            DateTime::make('Time answer', 'created_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('H:i:s d/m/Y') : null)
                ->sortable(),
        ];
    }

    public function fieldsForPreview(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @throws Exception
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make(__('Employee Code'), 'user->employee_code')
                ->sortable(),
            Url::make(
                __('User'),
                fn() => sprintf('%s/resources/%s/%d', config('nova.path'), User::uriKey(), $this->user->id)
            )
                ->customHtmlUsing(fn() => $this->user?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->user))
                ->sameTab(),
            Url::make(
                __('Topic'),
                fn() => sprintf('%s/resources/%s/%d', config('nova.path'), Topic::uriKey(), $this->topic->id)
            )
                ->customHtmlUsing(fn() => $this->topic->name ?: '—')
                ->alwaysClickable(isset($this->topic))
                ->sameTab(),
            TinymceEditor::make(__('Question'), 'question->name')
                ->showOnIndex()
                ->fullWidth()
                ->withMeta([
                    'shouldShow' => true
                ]),
            TinymceEditor::make(__('Answer'), 'answer->name')
                ->showOnIndex()
                ->fullWidth()
                ->withMeta([
                    'shouldShow' => true
                ]),
            Boolean::make(__('Is Correct'), 'is_correct')->sortable(),
            DateTime::make('Time answer', 'created_at')
                ->displayUsing(fn($value) => $value ? Carbon::parse($value)->format('H:i:s d/m/Y') : null)
                ->sortable(),
        ];
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
            new TopicQuestionDoneFilter(),
            (new UserFilter())->singleSelect()
        ];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
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
        return false;
    }

    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        return false;
    }

    /**
     * Determine if the user can attach models of the given type to the resource.
     *
     * @param NovaRequest $request
     * @param Model|string $model
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

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
