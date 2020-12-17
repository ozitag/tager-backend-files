<?php

namespace OZiTAG\Tager\Backend\Files;

use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Files\Features\UploadFileFeature;

class TagerBackendFilesController extends Controller
{
    public function upload()
    {
        return $this->serve(UploadFileFeature::class);
    }
}
