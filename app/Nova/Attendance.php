<?php

namespace App\Nova;

use Carbon\Carbon;
use Chaseconey\ExternalImage\ExternalImage;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;

class Attendance extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Attendance>
     */
    public static $model = \App\Models\Attendance::class;

//    public static $clickAction = 'edit';

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

    public static function label(): string
    {
        return __('Attendance');
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

            Image::make(__('QR Code'), 'qr_code_url')
                ->readonly()
                ->thumbnail(fn() => $this->qr_code_url)
                ->preview(fn() => $this->qr_code_url)
                ->hideWhenCreating()
                ->disableDownload(),

            URL::make(__('QR Code URL'), 'qr_code_url')->onlyOnDetail(),

            BelongsTo::make(__('Classroom'), 'classroom', Classroom::class)->required()->sortable()->searchable(),
            Text::make(__('Name'), "name")->rules('required')->sortable(),
            Date::make(__('Date'), 'date')->rules('required')->sortable(),
            Textarea::make(__('Description'), 'description'),
            DateTime::make(__('Start Attendance'), 'start_attendance'),
            DateTime::make(__('End Attendance'), 'end_attendance'),

//            AttachMany::make(__('Attendees'), 'attendees', User::class)
//                ->showCounts()
//                ->showPreview(),

            HasMany::make(__('Attendees'), 'attendees', AttendanceUser::class),
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
        return [];
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * @throws \Exception
     */
    protected static function afterValidation(NovaRequest $request, $validator): void
    {
        $classroom = \App\Models\Classroom::find($request->post('classroom'));
        $start_at = Carbon::parse($request->post('start_attendance'));
        $end_at = Carbon::parse($request->post('end_attendance'));

        if (!empty($start_at) && ($start_at->lt($classroom->started_at->startOfDay()) || $start_at->gt($classroom->ended_at->endOfDay()))) {
            $validator->errors()->add('start_attendance', __('Start time must be greater than start time of class.'));
        }

        if (!empty($end_at  ) && ($end_at->lt($classroom->started_at->startOfDay()) || $end_at->gt($classroom->ended_at->endOfDay()))) {
            $validator->errors()->add('end_attendance', __('End time must be lesser than end time of class.'));
        }

        if ($start_at->gt($end_at)) {
            $validator->errors()->add('end_attendance', __('End time must be greater than start time.'));
        }
    }
}
