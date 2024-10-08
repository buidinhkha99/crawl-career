<?php

namespace App\Nova\Actions;

use App\Enums\CertificateConstant;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadPDFElectricCertificate extends Action
{
    public function name()
    {
        return __('Download PDF Certificate');
    }

    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        // custom image to base64
        $defaultSignature = base64_encode(Storage::disk('public')->get(Setting::get('signature_photo_electric')));
        $payload = [
            'type' => CertificateConstant::ELECTRICAL_SAFETY,
            'director_name' => Setting::get('director_name_electric', 'Họ và Tên'),
            'signature_photo' => $defaultSignature,
            'ids' => $models->pluck('id'),
        ];

        $hash = base64_encode(json_encode($payload));
        session(['payload' => $hash]);

        return Action::openInNewTab("/media/certificates");
    }

    public function fields(NovaRequest $request)
    {
        return [
//
        ];
    }
}
