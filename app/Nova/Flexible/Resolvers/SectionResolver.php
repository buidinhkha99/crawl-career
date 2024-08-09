<?php

namespace App\Nova\Flexible\Resolvers;

use Whitecube\NovaFlexibleContent\Value\Resolver;

class SectionResolver extends Resolver
{
    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @param  Whitecube\NovaFlexibleContent\Layouts\Collection  $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {
        $structure = $resource->{$attribute};
        if (! $structure || ! is_array($structure)) {
            return collect([]);
        }

        $layout = $layouts->find($structure['layout']);

        return collect([
            $layout->duplicateAndHydrate($structure['key'], (array) $structure['attributes']),
        ]);
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
        $group = $groups->first();

        $model->{$attribute} = [
            'key' => $group->key(),
            'layout' => $group->name(),
            'attributes' => $group->getAttributes(),
        ];
    }
}
