<?php

namespace App\Nova\Actions;

use App\Enums\CertificateConstant;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;

class DownloadPDFCertificate extends Action
{
    public function name()
    {
        return __('Download PDF Certificate');
    }

    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        $payload = [
            'type' => CertificateConstant::OCCUPATIONAL_SAFETY,
            'ids' => $models->pluck('id'),
        ];

        $hash = base64_encode(json_encode($payload));
        session(['payload' => $hash]);

        return Action::openInNewTab("/media/certificates");
    }

    public function fields(NovaRequest $request)
    {
        return [
        ];
    }
}
