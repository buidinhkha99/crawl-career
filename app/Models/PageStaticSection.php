<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PageStaticSection extends Pivot
{
    use HasFactory;

    protected $table = 'page_static_section';
}
