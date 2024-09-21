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
        // custom image to base64
        $fileData = file_get_contents($fields->get('signature_photo'));
        $base64 = base64_encode($fileData);

        $payload = [
            'type' => CertificateConstant::OCCUPATIONAL_SAFETY,
            'complete_from' => $fields->get('complete_from'),
            'complete_to' => $fields->get('complete_to'),
            'effective_to' => $fields->get('effective_to'),
            'place' => $fields->get('place'),
            'director_name' => $fields->get('director_name'),
            'signature_photo' => $base64,
            'ids' => $models->pluck('id'),
        ];

        $hash = base64_encode(json_encode($payload));
        session(['payload' => $hash]);

        return Action::openInNewTab("/media/certificates");
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Place'), 'place')->default(fn () => __('Lào Cai'))->rules('required'),
            Date::make(__('Complete From'), 'complete_from')->rules('required')->default(fn () => now()),
            Date::make(__('Complete To'), 'complete_to')->rules('required')->default(fn () => now()),
            Text::make(__('Director Name'), 'director_name')->rules('required')->default(fn () => 'MInh'),
            Image::make(__('Signature Image'), 'signature_photo')->rules('required'),
            Date::make(__('Effective To'), 'effective_to')->rules('required')->default(fn () => now()),
        ];
    }
}
