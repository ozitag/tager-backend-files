<?php

namespace OZiTAG\Tager\Backend\Files\Features;

use Illuminate\Http\Request;
use Ozerich\FileStorage\Storage;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UploadFileFeature extends Feature
{
    protected bool $supportUrl;

    protected bool $supportFile;

    public function __construct($supportFile = true, $supportUrl = false)
    {
        $this->supportFile = $supportFile;

        $this->supportUrl = $supportUrl;

        if (!$this->supportUrl || !$this->supportFile) {
            throw new \Exception('UploadFileFeature must have minimum one enabled mode');
        }
    }

    public function handle(Storage $storage)
    {
        $file = null;

        if ($this->supportFile) {
            /** @var Request $request */
            $request = app()->request;

            $requestFile = $request->file('file');
            if ($requestFile) {
                $file = $storage->createFromRequest();
            }
        }

        if (!$file && $this->supportUrl) {
            $url = $request->post('url');

            if (empty($url)) {
                abort(400, 'Empty URL');
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                abort(400, 'Invalid URL');
            }

            $file = $storage->createFromUrl($url);
        }

        if (!$file) {
            abort(400, $storage->getUploadError());
        }

        return $file->getShortJson();
    }
}
