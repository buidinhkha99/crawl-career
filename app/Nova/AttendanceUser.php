<?php

namespace App\Nova;

use App\Nova\Traits\HasCallbacks;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttendanceUser extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\AttendanceClassroom::class;

    public static $clickAction = 'edit';

    public function title(): string
    {
        return $this->user->name.'('.$this->user->employee_code.')' . ' - ' . $this->attendance->classroom->name . ' > ' . $this->attendance->name;
    }

    public static function label(): string
    {
        return __('Attendance User');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'user.name', 'user.employee_code', 'user.username',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
//    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
//    {
//        $attendance = \App\Models\Attendance::findOrFail($request->resourceId);
//        return $query->whereIn('id', $attendance->classroom->attendees->pluck('id'));
//    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make(__('Name User'), fn($resource) => $resource->user->name)
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Employee Code'), fn($resource) => $resource->user->employee_code)
                ->sortable(),
            Boolean::make(__('Attended'), fn ($resource) => $resource->created_at),
            DateTime::make(__('Attended At'), 'created_at'),
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

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.Attendance::uriKey().'/'.$resource->attendance_id;
    }
}
