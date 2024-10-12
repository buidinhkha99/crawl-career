<?php

namespace App\Jobs;

use App\Enums\CertificateConstant;
use App\Http\Controllers\MediaController;
use App\Models\Certificate;
use App\Models\Setting;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Outl1ne\NovaMediaHub\MediaHandler\Support\Filesystem;
use Outl1ne\NovaMediaHub\MediaHub;
use Outl1ne\NovaMediaHub\Models\Media;

class CreateImageCertificate implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cardID;
    protected MediaController $mediaController;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cardID)
    {
        $this->cardID = $cardID;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        try {
            $this->mediaController = app(MediaController::class);
            $card = Certificate::find($this->cardID);
            if (!$card) {
                throw new Exception('Could not find certificate');
            }

            match ($card->type) {
                CertificateConstant::OCCUPATIONAL_SAFETY => $this->generateCertificateImageOccupation($card),
                CertificateConstant::ELECTRICAL_SAFETY => $this->generateCertificateImageElectric($card),
                CertificateConstant::PAPER_SAFETY => $this->generateCertificateImagePaper($card),
            };

        } catch (Exception $e) {
            Log::error('[IMAGE-CERTIFICATE-SERVICE] Create image for certificate failed. UserID:' . $this->cardID . ' Error: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $card
     * @throws Exception
     */
    private function generateCertificateImageOccupation($card): void
    {
        $media = Media::find($card->signature_photo_printed);
        $defaultSignature = base64_encode(Storage::disk($media->disk)->get($media->path . $media->file_name));
        $data = (object)[
            "type" => $card->type,
            "complete_from" => $card->complete_from_printed,
            "complete_to" => $card->complete_to_printed,
            "effective_to" => $card->effective_to_printed,
            "place" => $card->place_printed,
            "director_name" => $card->director_name_printed,
            "signature_photo" => $defaultSignature,
            "ids" => [$this->cardID]
        ];

        $this->generateCertificateImage($data, $card);
    }

    /**
     * @param $card
     * @throws Exception
     */
    private function generateCertificateImageElectric($card): void
    {
        // custom image to base64
        $media = Media::find($card->signature_photo_printed);
        $defaultSignature = base64_encode(Storage::disk($media->disk)->get($media->path . $media->file_name));
        $data = (object)[
            "type" => $card->type,
            "director_name" => $card->director_name_printed,
            "signature_photo" => $defaultSignature,
            "ids" => [$this->cardID]
        ];

        $this->generateCertificateImage($data, $card);
    }

    /**
     * @param $card
     * @return void
     * @throws Exception
     */
    private function generateCertificateImagePaper($card): void
    {
        // custom image to base64
        $media = Media::find($card->signature_photo_printed);
        $defaultSignature = base64_encode(Storage::disk($media->disk)->get($media->path . $media->file_name));
        $data = (object)[
            "type" => $card->type,
            "director_name" => $card->director_name_printed,
            "signature_photo" => $defaultSignature,
            "work_unit" => $card->work_unit_printed,
            "place" => $card->place_printed,
            "ids" => [$this->cardID]
        ];

        $this->generateCertificateImage($data, $card);
    }

    /**
     * Generate certificate image and save to MediaHub.
     *
     * @param $data
     * @param  $card
     * @throws Exception
     */
    private function generateCertificateImage($data, $card): void
    {
        $data = $this->mediaController->handelPDF($data, 'save');

        if (empty($data['path-font']) || empty($data['path-back'])) {
            throw new Exception('Can not get image card');
        }

        $olderImageFont = $card->image_font;
        $olderImageBack = $card->image_back;
        $this->deleteMedia($olderImageFont);
        $this->deleteMedia($olderImageBack);

        $imageFont= $data['path-font'];
        $imageBack = $data['path-back'];
        $mediaFont = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents($imageFont)), 'output_font_image.png', 'default', 'public', 'public');
        $card->setAttribute('image_font', $mediaFont->getAttribute('id'));
        $card->setAttribute("image_font_url", $mediaFont->getAttribute('url'));

        $mediaBack = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents($imageBack)), 'output_back_image.png', 'default', 'public', 'public');
        $card->setAttribute('image_back', $mediaBack->getAttribute('id'));
        $card->setAttribute("image_back_url", $mediaBack->getAttribute('url'));
        $card->save();
    }

    /**
     * Delete file if it exists in storage.
     *
     * @param string $filePath
     */
    private function deleteExistingFile(string $filePath): void
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    private function deleteMedia($mediaID)
    {
        $media = MediaHub::getQuery()->find($mediaID);
        if (!empty($media)) {
            $fileSystem = app()->make(Filesystem::class);
            $fileSystem->deleteFromMediaLibrary($media);
            $media->delete();
        }
    }

    public function uniqueId(): string
    {
        return $this->cardID;
    }
}
