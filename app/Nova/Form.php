<?php

namespace App\Nova;

use AlexAzartsev\Heroicon\Heroicon;
use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Enums\ButtonType;
use App\Nova\Flexible\Components\Button;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text as TextField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;

class Form extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Form>
     */
    public static $model = \App\Models\Form::class;

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

    public static function label(): string
    {
        return __('Form');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            TextField::make(__('Title'), 'title')->rules(['required']),
            Flexible::make(__('Fields'), 'fields')
                ->addLayout(__('Text Box'), 'text', [
                    TextField::make(__('Label'), 'name')->rules(['required'])->help('You cannot update with the created label'),
                    Select::make(__('Type'), 'type')
                        ->options([
                            'text' => 'Text',
                            'email' => 'Email',
                            'password' => 'Password',
                        ])->default('text')->rules(['required']),
                    Select::make(__('Layout'), 'layout')
                        ->options([
                            'icon' => 'Icon',
                            'button' => 'Button',
                            'icon-button' => 'Icon Button',
                        ])->default('icon')->rules(['required']),
                    DependencyContainer::make([
                        Heroicon::make(__('Icon'), 'icon'),
                    ])->dependsOn('layout', 'icon')
                        ->dependsOn('layout', 'icon-button'),
                    DependencyContainer::make([
                        ...Button::fields(__('Suffix'), 'detail_'),
                    ])->dependsOn('layout', 'button')
                        ->dependsOn('layout', 'icon-button'),

                    TextField::make(__('Placeholder'), 'placeholder'),
                    Boolean::make(__('Disabled'), 'disabled')->default(false),
                    Boolean::make(__('Required'), 'required'),
                    DependencyContainer::make([
                        TextField::make(__('Required Message'), 'error_message')->default(''),
                    ])->dependsOn('required', true),

                    TextField::make(__('Default Value'), 'default')->help('If you disable and the field is required, you must enter a default value.'),
                ])
                ->addLayout(__('Textarea'), 'textarea', [
                    TextField::make(__('Label'), 'name')->rules(['required']),
                    Boolean::make(__('Disabled'), 'disabled')->default(false),
                    Boolean::make(__('Required'), 'required'),
                    DependencyContainer::make([
                        TextField::make(__('Required Message'), 'error_message')->default(''),
                    ])->dependsOn('required', true),
                    TextField::make(__('Placeholder'), 'placeholder'),
                    TextField::make(__('Default Value'), 'default')->help('If you disable and the field is required, you must enter a default value.'),
                ])->button(__('Add Fields')),
            TextField::make(__('Button Text'), 'button_text')->rules(['required']),
            Hidden::make('Button Type', 'button_type')->default(ButtonType::Submit),

            HasMany::make(__('Submissions'), 'submissions', 'App\Nova\FormSubmission'),
        ];
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

    public static function beforeSave(Request $request, $model)
    {
        collect($request->get('fields'))->map(function ($field) {
            $attributes = Arr::get($field, 'attributes');
            if (empty($attributes)) {
                return;
            }

            if (Arr::get($attributes, 'disabled') && Arr::get($attributes, 'required') && empty(Arr::get($attributes, 'default'))) {
                throw new \Exception('Default value in field '.Arr::get($attributes, 'name').' is required');
            }

            if (Arr::get($attributes, 'disabled') && Arr::get($attributes, 'type') === 'email' && ! filter_var(Arr::get($attributes, 'default'), FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Default value in field '.Arr::get($attributes, 'name').' invalid email format');
            }
        });
    }
}
