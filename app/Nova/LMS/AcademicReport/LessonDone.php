<?php

namespace App\Nova\LMS\AcademicReport;

use App\Models\LessonUser;
use App\Models\Question;
use App\Nova\Filters\TopicQuestionDoneFilter;
use App\Nova\Filters\UserFilter;
use App\Nova\LMS\Lesson;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use App\Nova\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LessonDone extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Question>
     */
    public static string $model = LessonUser::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->lesson->name;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['lesson', 'user']);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'user.id', 'user.name', 'user.employee_code', 'lesson.name'
    ];

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
            ID::make()->sortable(),
            Text::make(__('Employee Code'), 'user->employee_code')
                ->sortable(),
            Url::make(
                __('User'),
                fn() => sprintf('%s/resources/%s/%d', config('nova.path'), User::uriKey(), $this->user?->id)
            )
                ->customHtmlUsing(fn() => $this->user?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->user))
                ->sameTab()
                ->exceptOnForms(),
            Url::make(
                __('Lesson'),
                fn() => sprintf('%s/resources/%s/%d', config('nova.path'), Lesson::uriKey(), $this->lesson?->id)
            )
                ->customHtmlUsing(fn() => $this->lesson?->getAttribute('name') ?: '—')
                ->alwaysClickable(isset($this->lesson))
                ->sameTab()
                ->exceptOnForms(),
            Boolean::make(__('Complete theory'), 'complete_theory'),
            Boolean::make('Complete lesson', 'is_complete'),
            DateTime::make('Time complete', 'created_at')
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
            new UserFilter()
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
        return __('Lesson');
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
