<?php

namespace Salt\NovaFlexibleContent\Layouts;

use Salt\NovaFlexibleContent\Flexible;

abstract class Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    abstract public function handle(Flexible $field);
}
