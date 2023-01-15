<?php

use Illuminate\Http\UploadedFile;

if (!function_exists('getArrayFile')) {
    function getArrayFile(?UploadedFile $file = null): ?array
    {
        if (!$file) {
            return null;
        }

        return [
            'tmp_name' => $file->getPathname(),
            'name' => $file->getFilename(),
            'type' => $file->getMimeType(),
            'error' => $file->getError(),
            'size' => $file->getSize(),
        ];
    }
}
