<?php

namespace App\Nova\Flexible\Resolvers;

use App\Models\Section;
use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class PageStaticResolver implements ResolverInterface
{
    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @param  \Whitecube\NovaFlexibleContent\Layouts\Collection  $layouts
     * @return \Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {
        $sections = $resource->sections()->orderBy('page_static_section.order')->get();

        return $sections->map(function ($section) use ($layouts) {
            $layout = $layouts->find($section->layout);

            if (! $layout) {
                return null;
            }

            $structure = $section->structure;
            if (! is_array($structure)) {
                return null;
            }

            return $layout->duplicateAndHydrate($structure['key'], $structure['attributes']);
        })->filter()->values();
    }

    /**
     * Set the field's value
     *
     * @param  mixed  $model
     * @param  string  $attribute
     * @param  \Illuminate\Support\Collection  $groups
     * @return string
     */
    public function set($model, $attribute, $groups)
    {
        $class = get_class($model);
        $class::saved(function ($model) use ($groups) {
            if ($model->sections()->exists()) {
                $model->sections()->detach();
            }

            $groups->each(function ($group, $index) use ($model) {
                if ($group->name() === 'attach_section') {
                    $model->sections()->attach($group->getAttributes()['section'], ['order' => $index, 'key' => $group->getAttribute('key')]);

                    return;
                }

                $structure = $group->getAttributes();

                $section = Section::where('name', $structure['name'])->first();
                if (! $section) {
                    $section = Section::create([
                        'name' => $structure['name'],
                        'layout' => $group->name(),
                        'structure' => [
                            'key' => $group->key(),
                            'layout' => $group->name(),
                            'attributes' => $structure,
                        ],
                    ]);
                } else {
                    $section->structure = [
                        'key' => $group->key(),
                        'layout' => $group->name(),
                        'attributes' => $structure,
                    ];
                    $section->save();
                }

                $model->sections()->attach($section->id, ['order' => $index, 'key' => $group->getAttribute('key')]);
            });
        });
    }
}
