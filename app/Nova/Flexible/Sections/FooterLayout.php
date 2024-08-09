<?php

namespace App\Nova\Flexible\Sections;

use App\Enums\ButtonType;
use App\Jobs\CacheSectionStructureByLocale;
use App\Models\Form;
use App\Nova\Flexible\Components\Background;
use App\Nova\Flexible\Layouts\FooterFormLayout;
use App\Nova\Flexible\Layouts\FooterInformationLayout;
use App\Nova\Flexible\Layouts\NavigationGroupLayout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class FooterLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'footer';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Footer';

    protected $limit = 1;

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

            Select::make(__('Layout'), 'layout')->options([
                'footer-01' => 'Footer #01',
            ])->default('footer-01'),

            Flexible::make(__('Components'), 'components')
                ->limit(4)
                ->fullWidth()
                ->confirmRemove()
                ->button(__('Add Component'))
                ->addLayout(new FooterInformationLayout())
                ->addLayout(new NavigationGroupLayout())
                ->addLayout(new FooterFormLayout()),
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
        $attributes = $this->attributes;

        return [
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'components' => ! isset($attributes['components']) ? [] : collect($attributes['components'])->map(function ($item) {
                $component = (object) $item;
                if (! $component || ! is_array($component?->attributes)) {
                    return null;
                }

                $attributes = $component->attributes;
                switch ($component?->layout) {
                    case 'information-footer':
                        return [
                            'type' => 'Info',
                            'logo' => $this->logoRender(Arr::where($attributes, fn ($item, $key) => Str::startsWith($key, 'logo_'))),
                            'text' => $this->translate(Arr::get($attributes, 'footer_information_introduction')),
                            'socials' => array_map(fn ($item) => [
                                'data' => $item ? Arr::get($item['attributes'], 'button_icon', null) : null,
                                'url' => $item ? Arr::get($item['attributes'], 'button_link', null) : null,
                            ], Arr::get($attributes, 'footer_information_icon', [])),
                        ];
                    case 'navigation-group':
                        return [
                            'type' => 'NavGroup',
                            'title' => $this->translate(Arr::get($attributes, 'title')),
                            'url' => Arr::get($attributes, 'url', '#'),
                            'navs' => ! isset($attributes['items']) ? [] : collect($attributes['items'])->pluck('attributes')->map(function ($item) {
                                return [
                                    'name' => $this->translate(Arr::get($item, 'title')),
                                    'url' => Arr::get($item, 'url'),
                                ];
                            }),
                        ];
                    case 'form-footer':
                        $form = Form::find(Arr::get($attributes, 'form_id'));

                        return [
                            'type' => 'Form',
                            'size' => 'large',
                            'form_id' => Arr::get($attributes, 'form_id'),
                            'title' => $this->translate($form->title),
                            'inputs' => $form->fields->map(function ($field) {
                                return match (Arr::get($field, 'layout')) {
                                    'text' => [
                                        'type' => Arr::get($field['attributes'], 'type'),
                                        'layout' => Arr::get($field['attributes'], 'layout'),
                                        'name' => Arr::get($field['attributes'], 'name'),
                                        'icon' => Arr::get($field['attributes'], 'icon', null),
                                        'disabled' => Arr::get($field['attributes'], 'disabled'),
                                        'placeholder' => Arr::get($field['attributes'], 'placeholder'),
                                        'default' => Arr::get($field['attributes'], 'default'),
                                        'rules' => [
                                            [
                                                'required' => Arr::get($field['attributes'], 'required'),
                                                'message' => Arr::get($field['attributes'], 'error_message'),
                                            ],
                                            Arr::get($field['attributes'], 'type') === 'email' ? [
                                                'type' => 'email',
                                                'message' => 'Please enter valid email',
                                            ] : null,
                                        ],
                                        'suffix' => [
                                            'text' => Arr::get($field['attributes'], 'detail_button_text', null),
                                            'button_type' => Arr::get($field['attributes'], 'detail_button_type', 'button'),
                                            'url' => Arr::get($field['attributes'], 'detail_button_link', '#'),
                                            'color_background' => Arr::get($field['attributes'], 'detail_button_color_background'),
                                            'detail_button_color_text' => Arr::get($field['attributes'], 'detail_button_color_text'),
                                            'icon' => [
                                                'data' => Arr::get($field['attributes'], 'detail_button_icon_option', null) ? Arr::get($field['attributes'], 'detail_button_icon', null) : null,
                                            ],
                                        ],
                                    ],
                                    'textarea' => [
                                        'type' => 'textarea',
                                        'name' => Arr::get($field['attributes'], 'name'),
                                        'icon' => Arr::get($field, 'icon_message'),
                                        'placeholder' => Arr::get($field['attributes'], 'placeholder'),
                                        'disabled' => Arr::get($field['attributes'], 'disabled'),
                                        'default' => Arr::get($field['attributes'], 'default'),
                                        'rows' => 5,
                                        'rules' => [
                                            [
                                                'required' => Arr::get($field['attributes'], 'required'),
                                                'message' => Arr::get($field['attributes'], 'error_message'),
                                            ],
                                        ],
                                    ],
                                };
                            }),
                            'button' => [
                                'icon' => '',
                                'url' => null,
                                'text' => Arr::get($attributes, 'button_text', 'SEND'),
                                'button_type' => ButtonType::Submit,
                            ],
                        ];
                    default:
                        return null;
                }
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
