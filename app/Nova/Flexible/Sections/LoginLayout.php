<?php

namespace App\Nova\Flexible\Sections;

use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Nova\Flexible\Components\Background;
use App\Nova\Flexible\Components\Button;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class LoginLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'login';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Login Section';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules(['required'])
                ->sortable(),
            Text::make(__('Key'), 'key')
                ->sortable(),

            Text::make(__('Redirect After Login'), 'redirect_after_login')
                ->sortable(),

            ...Background::fields(),

            Select::make(__('Click detail'), 'click_detail_option')->options([
                'none' => 'None',
                'direct' => 'Direct',
                'button' => 'Button',
            ])->default('none')->rules('required'),

            DependencyContainer::make([
                Text::make(__('Detail Link'), 'detail_button_link')->default('#'),
            ])->dependsOn('click_detail_option', 'direct'),

            DependencyContainer::make(
                Button::fields(__('Detail'), 'detail_')
            )->dependsOn('click_detail_option', 'button'),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return false;
    }

    public function cacheableData(): array
    {
        return [];
    }

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;

        if (auth()->user()) {
            return redirect(Arr::get($attributes, 'redirect_after_login', '/'));
        }

        return [
            'type' => 'LoginVimico',
            'id' => $id,
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'redirect_after_login' => Arr::get($attributes, 'redirect_after_login'),
            'config_button' => Arr::get($attributes, 'click_detail_option', 'none') != 'button' ? null : [
                'text' => $this->translate(Arr::get($attributes, 'detail_button_text')),
                'button_type' => 'submit',
                'url' => Arr::get($attributes, 'detail_button_link', 'null'),
                'color_background' => Arr::get($attributes, 'detail_button_color_background'),
                'color_text' => Arr::get($attributes, 'detail_button_color_background'),
                'detail_button_color_text' => Arr::get($attributes, 'detail_button_color_text'),
                'icon' => [
                    'data' => Arr::get($attributes, 'detail_button_icon_option') ? Arr::get($attributes, 'detail_button_icon', null) : null,
                ],
            ],
        ];
    }
}
