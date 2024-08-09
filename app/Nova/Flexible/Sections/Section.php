<?php

namespace App\Nova\Flexible\Sections;

use Illuminate\Http\Request;

interface Section
{
    public function render(Request $request, $id = null): mixed;

    public function cacheable(): bool;

    // returns array if cacheable === true
    // returns null if cacheable === false
    public function cacheableData(): array;
}
