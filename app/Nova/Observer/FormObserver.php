<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use App\Models\Form;
use Illuminate\Support\Arr;

class FormObserver
{
    public function saving(Form $form)
    {
        collect($form->getOriginal('fields'))->map(function ($field, $index) use ($form) {
            // check old filed not existed
            if (! $form->getAttribute('fields')->pluck('key')->contains(Arr::get($field, 'key'))) {
                throw new AppException('Can not delete field '.Arr::get($field['attributes'], 'name').' when update');
            }
            // get old name by key of filed and get in attributes field
            $new_name = Arr::get(Arr::get($form->getAttribute('fields')->where('key', Arr::get($field, 'key'))->first(), 'attributes'), 'name');

            if (Arr::get($field['attributes'], 'name') != $new_name) {
                throw new AppException('Can not edit Label in field '.($index + 1).', old Label was '.Arr::get($field['attributes'], 'name'));
            }
        });
    }
}
