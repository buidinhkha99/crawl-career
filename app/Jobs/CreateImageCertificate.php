<?php

namespace App\Jobs;

use App\Enums\CertificateConstant;
use App\Http\Controllers\MediaController;
use App\Models\Certificate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickException;
use Outl1ne\NovaMediaHub\MediaHub;

class CreateImageCertificate implements ShouldQueue
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
                CertificateConstant::OCCUPATIONAL_SAFETY => $this->generateCertificateImageOccupation($card, [400, 570, 778, 20], [400, 570, 20, 20], [150, 150]),
                CertificateConstant::ELECTRICAL_SAFETY => $this->generateCertificateImageElectric($card, [530, 370, 651, 30], [530, 370, 30, 30], [150, 150]),
                CertificateConstant::PAPER_SAFETY => $this->generateCertificateImagePaper($card, [2600, 1600, 50, 100], null),
            };

        } catch (Exception $e) {
            Log::error('[IMAGE-CERTIFICATE-SERVICE] Create image for certificate failed. UserID:' . $this->cardID . ' Error: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate certificate image and save to MediaHub.
     *
     * @param $data
     * @param  $card
     * @param array $backCropParams
     * @param array|null $frontCropParams
     * @param array $resolution
     * @throws ImagickException
     */
    private function generateCertificateImage($data, $card, array $backCropParams, ?array $frontCropParams, array $resolution = []): void
    {
        $this->mediaController->handelPDF($data, 'save');

        // Process back image
        $this->processImage('app/public/file.pdf', 1, $backCropParams, $card, 'image_back', $resolution);

        // Process front image (if provided)
        if ($frontCropParams) {
            $this->processImage('app/public/file.pdf', 0, $frontCropParams, $card, 'image_font', $resolution);
        }
    }

    /**
     * Process the PDF to generate an image and save it to MediaHub.
     *
     * @param string $pdfPath
     * @param int $pageNumber
     * @param array $cropParams
     * @param Certificate $card
     * @param string $attribute
     * @param array $resolution
     * @throws ImagickException
     */
    private function processImage(string $pdfPath, int $pageNumber, array $cropParams, Certificate $card, string $attribute, array $resolution = []): void
    {
        $pdfFullPath = storage_path($pdfPath);
        $img = new Imagick();
        $img->setResolution($resolution[0] ?? 300, $resolution[1] ?? 300);
        $img->readImage("{$pdfFullPath}[{$pageNumber}]");

        // Crop the image based on parameters [width, height, x, y]
        $img->cropImage($cropParams[0], $cropParams[1], $cropParams[2], $cropParams[3]);

        $outputPath = storage_path('app/public/output_image.png');
        $this->deleteExistingFile('public/output_image.png');

        // Save the cropped image
        $img->writeImage($outputPath);
        $img->clear();
        $img->destroy();

        // Save the image to MediaHub
        $media = MediaHub::storeMediaFromBase64(base64_encode(file_get_contents($outputPath)), 'output_image.png', 'default', 'public', 'public');
        $card->setAttribute($attribute, $media->getAttribute('id'));
        $card->setAttribute("{$attribute}_url", $media->getAttribute('url'));
        $card->save();

        // Clear output image file
//        $this->deleteExistingFile('public/output_image.png');
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

    /**
     * @param $card
     * @param array $backCropParams
     * @param array $frontCropParams
     * @param int[] $resolution
     * @throws ImagickException
     */
    private function generateCertificateImageOccupation($card, array $backCropParams, array $frontCropParams, $resolution = [100, 100]): void
    {
        $defaultSignature = base64_encode(Storage::disk('public')->get('default_signature.png'));
        $data = (object)[
            "type" => $card->type,
            "complete_from" => now()->day(1),
            "complete_to" => now()->day(360),
            "effective_to" => now()->day(730),
            "place" => "Lào Cai",
            "director_name" => "Họ và Tên ",
            "signature_photo" => $defaultSignature,
            "ids" => [$this->cardID]
        ];
        $this->generateCertificateImage($data, $card, $backCropParams, $frontCropParams, $resolution);
    }

    /**
     * @param $card
     * @param array $backCropParams
     * @param array $frontCropParams
     * @throws ImagickException
     */
    private function generateCertificateImageElectric($card, array $backCropParams, array $frontCropParams, $resolution = [100, 100]): void
    {
        $defaultSignature = base64_encode(Storage::disk('public')->get('default_signature.png'));
        $data = (object)[
            "type" => $card->type,
            "director_name" => "Họ và Tên",
            "signature_photo" => $defaultSignature,
            "ids" => [$this->cardID]
        ];

        $this->generateCertificateImage($data, $card, $backCropParams, $frontCropParams, $resolution);
    }

    /**
     * @throws ImagickException
     */
    private function generateCertificateImagePaper($card, array $backCropParams): void
    {
        $defaultSignature = base64_encode(Storage::disk('public')->get('default_signature.png'));
        $data = (object)[
            "type" => $card->type,
            "director_name" => "Họ và Tên",
            "signature_photo" => $defaultSignature,
            "work_unit" => "Chi nhánh Luyện đồng Lào Cai - VIMICO",
            "place" => "Lào Cai",
            "ids" => [$this->cardID]
        ];

        $this->generateCertificateImage($data, $card, $backCropParams, null);
    }
}
