<?php

namespace App\Nova\Filters;

use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class CertificateEndTimeFilter extends EndTimeFilter
{
    public function name()
    {
        return __('Create to');
    }

    public function default()
    {
        return '';
    }
}
