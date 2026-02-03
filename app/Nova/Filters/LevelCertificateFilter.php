<?php

namespace App\Nova\Filters;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class LevelCertificateFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Level');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        foreach ($value as $item) {
            $query->orWhereJsonContains('card_info->level', $item);
        }

        return $query;
    }

    public function options(Request $request)
    {
        return Certificate::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(card_info, '$.level')) as save_level"))
            ->where('type', CertificateConstant::ELECTRICAL_SAFETY)
            ->distinct()
            ->get()->pluck('save_level', 'save_level');
    }
}
