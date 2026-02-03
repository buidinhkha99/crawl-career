<?php

namespace App\Nova\Flexible\Sections;

use App\Jobs\CacheSectionStructureByLocale;
use App\Nova\Flexible\Components\Background;
use DinandMentink\Markdown\Markdown;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class FaqLayout extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'faq';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'FAQ Section';

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

            Text::make(__('Title'), 'title')
                    ->rules(['required'])
                    ->sortable(),

            Flexible::make(__('FAQ'), 'components')
                ->addLayout(__('Question-Answer'), 'faq',
                    [
                        Text::make(__('Question'), 'question')
                            ->rules(['required'])
                            ->sortable(),
                        Markdown::make(__('Answer'), 'answer'),
                    ]
                )
            ->fullWidth()
            ->button(__('Add new QA')),
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
            'type' => 'Faq',
            'title' => $this->translate(Arr::get($attributes, 'title')),
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'components' => ! isset($attributes['components']) ? [] :
                collect($attributes['components'])->pluck('attributes')->map(fn ($item) => [
                    'question' => $this->translate(Arr::get($item, 'question')),
                    'answer' => $this->translate(Arr::get($item, 'answer')),
                ]),
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
