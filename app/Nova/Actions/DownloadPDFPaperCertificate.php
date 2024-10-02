<?php

namespace App\Nova\Actions;

use App\Enums\CertificateConstant;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadPDFPaperCertificate extends Action
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
            'type' => CertificateConstant::PAPER_SAFETY,
            'director_name' => $fields->get('director_name'),
            'signature_photo' => $base64,
            'work_unit' => $fields->get('work_unit'),
            'place' => $fields->get('place'),
            'ids' => $models->pluck('id'),
        ];

        $hash = base64_encode(json_encode($payload));
        session(['payload' => $hash]);

        return Action::openInNewTab("/media/certificates");
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Work unit'), 'work_unit')->rules('required')->default('Chi nhánh Luyện đồng Lào Cai - VIMICO'),
            Text::make(__('Place'), 'place')->default(fn () => __('Lào Cai'))->rules('required'),
            Text::make(__('Director Name'), 'director_name')->rules('required'),
            Image::make(__('Signature Image'), 'signature_photo')->rules('required'),
        ];
    }
}
