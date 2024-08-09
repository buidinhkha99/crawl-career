<?php

namespace Outl1ne\NovaMediaHub\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Outl1ne\NovaMediaHub\Models\Media;

class MediaCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return;
        }

        return Media::whereIn('id', json_decode($value, true))->get();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
