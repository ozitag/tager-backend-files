<?php

namespace OZiTAG\Tager\Backend\Files;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Files\Features\UploadFileFeature;

class TagerBackendFilesController extends Controller
{
    public function upload()
    {
        return $this->serve(UploadFileFeature::class, [
            'supportUrl' => true,
            'supportFile' => true
        ]);
    }

    public function uploadByUrl()
    {
        return $this->serve(UploadFileFeature::class, [
            'supportUrl' => true,
            'supportFile' => false
        ]);
    }

    public function uploadByFile()
    {
        return $this->serve(UploadFileFeature::class, [
            'supportUrl' => false,
            'supportFile' => true
        ]);
    }
}
