<?php

namespace Outl1ne\NovaMediaHub\MediaHandler\Support;

use Outl1ne\NovaMediaHub\Exceptions\FileTooLargeException;
use Outl1ne\NovaMediaHub\Exceptions\MimeTypeNotAllowedException;
use Outl1ne\NovaMediaHub\MediaHub;

class FileValidator
{
    public function validateFile(string $collectionName, string $localFilePath, string $fileName, string $extension, string $mimeType, int $fileSize): bool
    {
        $this->validateFileSize($fileSize, $fileName);
        $this->validateMimeType($mimeType, $fileName);

        return true;
    }

    protected function validateFileSize(int $fileSize, string $fileName)
    {
        $maxSizeBytes = MediaHub::getMaxFileSizeInBytes();

        if ($maxSizeBytes && $fileSize > $maxSizeBytes) {
            throw new FileTooLargeException(__("File size :fileSize megabytes(MB) exceeds the maximum allowed of :maxSizeBytes (:fileName).", [
                    'fileSize' => round( $fileSize/1000000, 2),
                    'maxSizeBytes' => round( $maxSizeBytes/1000000, 2),
                    'fileName' => $fileName
                ]));
        }
    }

    protected function validateMimeType(string $mimeType, string $fileName)
    {
        $allowedMimeTypes = MediaHub::getAllowedMimeTypes();

        if (! in_array($mimeType, $allowedMimeTypes)) {
            throw new MimeTypeNotAllowedException("Mime type {$mimeType} is not allowed ({$fileName}).");
        }
    }
}
