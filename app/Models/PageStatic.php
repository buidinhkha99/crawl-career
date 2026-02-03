<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class PageStatic extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $casts = [
        'seo_keywords' => 'array',
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function render(Request $request, $locale): mixed
    {
        $data = collect([]);

        foreach ($this->sections()->orderBy('page_static_section.order')->get() as $section) {
            $key = $section->pivot?->getAttribute('key');

            $render = $section->render($request, $key, $this->language, $locale);
            if (! is_array($render)) {
                return $render;
            }

            switch ($section->getAttribute('layout')) {
                case 'footer':
                    $data->put('footer', $render);
                    break;
                case 'header':
                    $data->put('header', $render);
                    break;
                default:
                    $component_data = $render;

                    // component overriding page SEO
                    if (isset($component_data['seo'])) {
                        $data->put('seo', $component_data['seo']);
                        unset($component_data['seo']);
                    }

                    $data->put('components', $data->get('components', collect())->push($component_data));
                    break;
            }
        }

        return $data;
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class)
            ->using(PageStaticSection::class)
            ->withPivot(['order', 'key']);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrderAsc($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
