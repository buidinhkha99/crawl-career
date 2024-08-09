<?php

namespace App\Nova;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Stepanenko3\NovaJson\JSON;

class FormSubmission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FormSubmission>
     */
    public static $model = \App\Models\FormSubmission::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        if ($request instanceof ResourceDetailRequest) {
            if (! $this->status) {
                \App\Models\FormSubmission::findOrFail($this->id)->update(['status' => ! $this->status]);
                $this->status = ! $this->status;
            }
        }

        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Author'), 'author', User::class)
                ->sortable()
                ->nullable(),
            Boolean::make(__('Read'), 'status')->sortable(),
            JSON::make(__('Values'), 'values', $this->dynamicFields($request)),
            Date::make(__('Date'), 'created_at')->exceptOnForms()->sortable(),
        ];
    }

    public function dynamicFields($request)
    {
        $fields = collect();
        if ($request->get('viaResource') == 'forms' && $request->get('viaResourceId')) {
            $fields = \App\Models\Form::find($request->get('viaResourceId'))->fields->map(function ($field) {
                return [
                    'name' => Arr::get($field['attributes'], 'name'),
                    'type' => Arr::get($field, 'layout'),
                ];
            });
        }

        if ($this->form) {
            $fields = $this->form?->fields?->map(function ($field) {
                return [
                    'name' => Arr::get($field['attributes'], 'name'),
                    'type' => Arr::get($field, 'layout'),
                ];
            });
        }

        if (! $this->form && $request->resource === 'form-submissions' && $request->resourceId) {
            $fields = \App\Models\FormSubmission::find($request->resourceId)->form?->fields?->map(function ($field) {
                return [
                    'name' => Arr::get($field['attributes'], 'name'),
                    'type' => Arr::get($field, 'layout'),
                ];
            });
        }

        return $fields?->map(function ($field) {
            if ($field['type'] === 'text') {
                return Text::make($field['name'], $field['name']);
            }
            if ($field['type'] === 'textarea') {
                return Textarea::make($field['name'], $field['name']);
            }
        });
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function label(): string
    {
        return __('Submissions');
    }
}
