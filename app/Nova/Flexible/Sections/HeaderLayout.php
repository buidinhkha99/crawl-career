<?php

namespace App\Nova\Flexible\Sections;

use App\Jobs\CacheSectionStructureByLocale;
use App\Models\Setting;
use App\Nova\Flexible\Components\Background;
use App\Nova\Flexible\Components\Logo;
use App\Nova\Flexible\Layouts\NavigationGroupLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class HeaderLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'header';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Header';

    /**
     * The maximum amount of this layout type that can be added
     */
    protected $limit = 1;

    /**
     * Get the fieldHeaderLayout.phps displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules(['required'])
                ->sortable(),
            Text::make(__('Key'), 'key')
                ->sortable(),

            ...Background::fields(),

            Select::make(__('Position'), 'position')->options([
                'fixed' => 'Fixed',
                'sticky' => 'Sticky',
            ])->default('fixed')->rules('required'),

            ...Logo::fields(),

            Flexible::make(__('Components'), 'components')
                ->fullWidth()
                ->addLayout(new NavigationGroupLayout())
                ->button(__('Add Navigation Group')),
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
        $user = Auth::user();

        return [
            'type' => Arr::get($attributes, 'position', 'fixed'),
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'logo' => $this->logoRender(Arr::where($attributes, fn ($item, $key) => Str::startsWith($key, 'logo_'))),
            'nav' => ! isset($attributes['components']) ? [] : collect($attributes['components'])->pluck('attributes')->map(function ($item) {
                if (! isset($item['title'])) {
                    return null;
                }

                $nav_group = Arr::get($item, 'items');

                return [
                    'title' => $this->translate($item['title']),
                    'url' => Arr::get($item, 'url', '#'),
                    'nav_dropdown' => $nav_group ? collect($nav_group)->map(function ($item) {
                        if (! $item || ! isset($item['attributes'])) {
                            return null;
                        }

                        $attributes = (object) $item['attributes'];

                        return [
                            'url' => $attributes->url ?? '#',
                            'title' => $this->translate($attributes->title),
                        ];
                    }) : null,
                ];
            })->filter(),
            'language' => [
                'language_default' => Setting::get('default_language'),
                'language_list' => Setting::get('languages', []),
            ],
            'search' => [],
            'account' => [],
            'config_button_logout' => $user ? [
                'text' => 'Đăng Xuất',
                'button_type' => 'button',
                'url' => '/logout',
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [],
                'color_text' => '#ffffff',
            ] : null,
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
