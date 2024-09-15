<?php

namespace App\Nova\Filters;

use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class CertificateStartTimeFilter extends StartTimeFilter
{
    public function name()
    {
        return __('Create from');
    }

    public function default()
    {
        return '';
    }
}
