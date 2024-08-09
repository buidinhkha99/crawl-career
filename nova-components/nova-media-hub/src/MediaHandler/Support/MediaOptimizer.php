<?php

namespace Outl1ne\NovaMediaHub\MediaHandler\Support;

use Illuminate\Support\Str;
use Outl1ne\NovaMediaHub\MediaHub;
use Outl1ne\NovaMediaHub\Models\Media;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class MediaOptimizer
{
    public static function optimizeOriginalImage(Media $media, $localFilePath = null)
    {
        if (! empty($media->optimized_at)) {
            return;
        }
        if (! Str::startsWith($media->mime_type, 'image')) {
            return;
        }
        if (! MediaHub::isOptimizable($media)) {
            return;
        }
        if (! $origOptimRules = MediaHub::shouldOptimizeOriginal($media)) {
            return;
        }

        $fileSystem = self::getFilesystem();

        $manipulations = (new Manipulations());
        $manipulations->optimize(config('nova-media-hub.image_optimizers'));

        if ($maxDimens = $origOptimRules['max_dimensions']) {
            $manipulations->fit(Manipulations::FIT_MAX, $maxDimens, $maxDimens);
        }

        $manipulations->apply();

        // Copy media from whatever disk to local filesystem for manipulations
        if (! $localFilePath || ! is_file($localFilePath)) {
            $localFilePath = FileHelpers::getTemporaryFilePath();
            $fileSystem->copyFromMediaLibrary($media, $localFilePath);
        }

        // Load and save modified version
        static::manipulate($localFilePath, $manipulations);

        $media->size = filesize($localFilePath);
        $media->optimized_at = now();

        $fileSystem->copyFileToMediaLibrary($localFilePath, $media, $media->file_name, Filesystem::TYPE_ORIGINAL, false);

        $media->save();
    }

    public static function makeConversion(Media $media, $localFilePath, $conversionName, $conversionConfig)
    {
        if (! empty($media->conversion[$conversionName])) {
            return;
        }
        if (! Str::startsWith($media->mime_type, 'image')) {
            return;
        }
        if (! MediaHub::isOptimizable($media)) {
            return;
        }

        $pathMaker = MediaHub::getPathMaker();
        $fileSystem = self::getFilesystem();

        // Check if has necessary data for resize
        $cFormat = $conversionConfig['format'] ?? null;
        $cFitMethod = $conversionConfig['fit'] ?? null;
        $cWidth = $conversionConfig['width'] ?? null;
        $cHeight = $conversionConfig['height'] ?? null;

        $manipulations = (new Manipulations())
            ->optimize(config('nova-media-hub.image_optimizers'))
            ->fit($cFitMethod, $cWidth, $cHeight);

        if ($cFormat) {
            $manipulations->format($cFormat);
        }

        $manipulations = $manipulations->apply();

        // Copy media from whatever disk to local filesystem for manipulations
        if (! $localFilePath || ! is_file($localFilePath)) {
            $localFilePath = FileHelpers::getTemporaryFilePath();
            $fileSystem->copyFromMediaLibrary($media, $localFilePath);
        }

        // Load and save modified version
        static::manipulate($localFilePath, $manipulations);

        $conversionFileName = $pathMaker->getConversionFileName($media, $conversionName);
        $fileSystem->copyFileToMediaLibrary($localFilePath, $media, $conversionFileName, Filesystem::TYPE_CONVERSION, false);

        $newConversions = $media->conversions;
        $newConversions[$conversionName] = $conversionFileName;

        $media->conversions = $newConversions;
        $media->save();
    }

    protected static function getFilesystem(): Filesystem
    {
        return app()->make(Filesystem::class);
    }

    protected static function manipulate($path, $manipulations)
    {
        $image = Image::load($path)->manipulate($manipulations);
        if ($driver = MediaHub::getImageDriver()) {
            $image->useImageDriver($driver);
        }
        $image->save();
    }
}
