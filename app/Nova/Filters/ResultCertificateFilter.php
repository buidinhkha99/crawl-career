<?php

namespace App\Nova\Filters;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMultiselectFilter\MultiselectFilter;

class ResultCertificateFilter extends MultiselectFilter
{
    public function name()
    {
        return __('Result training');
    }

    public function apply(NovaRequest $request, $query, $value)
    {
        foreach ($value as $item) {
            $query->orWhereJsonContains('card_info->result', $item);
        }

        return $query;
    }

    public function options(Request $request)
    {
        return Certificate::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(card_info, '$.result')) as result_level"))
            ->where('type', CertificateConstant::PAPER_SAFETY)
            ->distinct()
            ->get()->pluck('result_level', 'result_level');
    }
}
