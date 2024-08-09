<?php

namespace Outl1ne\NovaMediaHub\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Outl1ne\NovaMediaHub\MediaHandler\Support\FileHelpers;
use Outl1ne\NovaMediaHub\MediaHandler\Support\Filesystem;
use Outl1ne\NovaMediaHub\MediaHandler\Support\MediaOptimizer;
use Outl1ne\NovaMediaHub\MediaHub;
use Outl1ne\NovaMediaHub\Models\Media;

class MediaHubOptimizeAndConvertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180;

    protected $mediaId = null;

    public function __construct(Media $media)
    {
        $this->mediaId = $media->id;
        $this->onQueue(MediaHub::getImageConversionsJobQueue());
    }

    public function handle()
    {
        $media = MediaHub::getQuery()->find($this->mediaId);
        if (! $media) {
            return;
        }

        $fileSystem = $this->getFileSystem();
        $localFilePath = $fileSystem->copyFromMediaLibrary($media, FileHelpers::getTemporaryFilePath('job-tmp-media-'));

        // Optimize original - saving to localFilePath is fine
        MediaOptimizer::optimizeOriginalImage($media, $localFilePath);

        // Create conversions
        $conversions = MediaHub::getConversionForMedia($media);
        foreach ($conversions as $conversionName => $conversion) {
            // Make copy of original file and manipulate that
            $copyOfOriginal = $fileSystem->makeTemporaryCopy($localFilePath);
            MediaOptimizer::makeConversion($media, $copyOfOriginal, $conversionName, $conversion);
            if (is_file($copyOfOriginal)) {
                unlink($copyOfOriginal);
            }
        }

        // Delete local file
        if (is_file($localFilePath)) {
            unlink($localFilePath);
        }
    }

    protected function getFileSystem(): Filesystem
    {
        return app()->make(Filesystem::class);
    }
}
