<?php

namespace App\Nova;

use App\Nova\Actions\AttachGroupUserInClass;
use App\Nova\Actions\DownloadAttendanceExcel;
use Carbon\Carbon;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Classroom extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Classroom>
     */
    public static $model = \App\Models\Classroom::class;

    public static function label(): string
    {
        return __('Classroom');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

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
            Text::make(__('Name'), "name")->rules('required', 'max:150')->sortable(),
            Number::make(__('Lessons count'), 'lessons_count')->min(1)->step(1)->rules('required'),
            Textarea::make(__('Description'), 'description'),
            Date::make(__('Start Date'), 'started_at'),
            Date::make(__('End Date'), 'ended_at'),
            BelongsToMany::make(__('Attendees'), 'attendees', User::class)->searchable()->sortable(),
            HasMany::make(__('Attendances'), 'attendances', Attendance::class),
        ];
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
        return [];
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

        return [
            (new AttachGroupUserInClass())->showInline()->showOnDetail(),
            (new DownloadAttendanceExcel())
                ->canRun(fn () => $request->user()->can('viewAny', \App\Models\Classroom::class))
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->onlyOnDetail(),
        ];
    }

    /**
     * @throws \Exception
     */
    protected static function afterValidation(NovaRequest $request, $validator): void
    {
        $start_at = Carbon::parse($request->post('started_at'));
        $end_at = Carbon::parse($request->post('ended_at'));

        if ($start_at->gt($end_at)) {
            $validator->errors()->add('ended_at', __('End time must be greater than start time.'));
        }
    }
}
