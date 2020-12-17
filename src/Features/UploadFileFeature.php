<?php

namespace OZiTAG\Tager\Backend\Files\Features;

use Ozerich\FileStorage\Storage;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Core\Features\Feature;

class UploadFileFeature extends Feature
{
    public function handle(Storage $storage)
    {
        /** @var File $file */
        $file = $storage->createFromRequest();

        if (!$file) {
            abort(400, $storage->getUploadError());
        }

        return $file->getShortJson();
    }
}
