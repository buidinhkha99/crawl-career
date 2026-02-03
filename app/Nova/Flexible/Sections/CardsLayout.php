<?php

namespace App\Nova\Flexible\Sections;

use App\Jobs\CacheSectionStructureByLocale;
use App\Nova\Flexible\Components\Background;
use App\Nova\Flexible\Layouts\CardLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class CardsLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'card';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Cards Section';

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

            ...Background::fields(),

            Select::make(__('Type'), 'type')->options([
                'banner' => __('Banner'),
                'slider' => __('Slider'),
            ])->default('banner')->rules('required'),

            Select::make(__('Layout'), 'layout')->options([
                'card-one' => __('One card in row'),
                'card-two' => __('Two cards in row'),
                'card-three' => __('Three cards in row'),
                'card-four' => __('Four cards in row'),
                'card-six' => __('Six cards in row'),
            ])->default('card-one')->rules('required'),

            Text::make(__('Title'), 'title'),

            Flexible::make(__('Cards'), 'components')
                ->collapsed()
                ->fullWidth()
                ->button(__('Add Card'))
                ->addLayout(new (CardLayout::class)),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return true;
    }

    public function cacheableData(): array
    {
        $types = [
            'banner' => [
                'card-one' => 'CardContentOne',
                'card-two' => 'CardContentTwo',
                'card-three' => 'CardContentThree',
                'card-four' => 'CardContentFour',
                'card-six' => 'CardContentSix',
            ],
            'slider' => [
                'card-one' => 'CardSlideOne',
                'card-two' => 'CardSlideTwo',
                'card-three' => 'CardSlideThree',
                'card-four' => 'CardSlideFour',
                'card-six' => 'CardSlideSix',
            ],
        ];

        $attributes = $this->attributes;
        $type = Arr::get($types, Arr::get($attributes, 'type', 'banner'));

        return [
            'type' => $type ? Arr::get($type, Arr::get($attributes, 'layout', 'card-one')) : null,
            'title' => $this->translate(Arr::get($attributes, 'title', '')),
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'components' => ! isset($attributes['components']) ? [] : collect($attributes['components'])->pluck('attributes')->map(function ($item) {
                return [
                    'type' => 'cardSlide',
                    'layout' => Arr::get($item, 'layout', 'image-info'),
                    'img' => Arr::get($item, 'image_url', ''),
                    'title' => $this->translate(Arr::get($item, 'title')),
                    'description' => $this->translate(Arr::get($item, 'description')),
                    'description_ellipsis' => Arr::get($item, 'description_ellipsis', 4),
                    'config_button' => Arr::get($item, 'click_detail_option', 'none') != 'button' ? null : [
                        'text' => $this->translate(Arr::get($item, 'detail_button_text')),
                        'button_type' => Arr::get($item, 'detail_button_type', 'button'),
                        'url' => Arr::get($item, 'detail_button_link', '#'),
                        'color_background' => Arr::get($item, 'detail_button_color_background'),
                        'detail_button_color_text' => Arr::get($item, 'detail_button_color_text'),
                        'icon' => [
                            'data' => Arr::get($item, 'detail_button_icon_option') ? Arr::get($item, 'detail_button_icon', null) : null,
                        ],
                    ],
                    'config_direct' => Arr::get($item, 'click_detail_option', 'none') != 'direct' ? null : [
                        'url' => Arr::get($item, 'detail_button_link', '#'),
                    ],
                    'description_icon' => collect(Arr::get($item, 'description_icon'))->map(function ($des) {
                        $attributes = Arr::get($des, 'attributes');

                        return [
                            'title' => Arr::get($attributes, 'title'),
                            'icon' => Arr::get($attributes, 'icon'),
                        ];
                    }),

                ];
            }),
        ];
    }

    public function render(Request $request, $id = null): mixed
    {
        $data = $this->language === $this->locale ?
            $this->cacheableData() :
            Cache::get($this->model->structure_cache_key($this->locale), function () {
                CacheSectionStructureByLocale::dispatch($this->model, $this->locale)->onQueue('default');

                $this->locale = $this->language;

                return $this->cacheableData();
            });

        return array_merge([
            'id' => $id,
        ], $data);
    }
}
