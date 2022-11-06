<?php

namespace OZiTAG\Tager\Backend\Files\Features;

use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadFileFeature extends Feature
{
    public function __construct(protected bool    $supportFile = true,
                                protected bool    $supportUrl = false,
                                protected bool    $supportBase64 = false,
                                protected ?string $scenario = null
    )
    {
        if (!$this->supportUrl && !$this->supportFile && !$this->supportBase64) {
            throw new \Exception('UploadFileFeature must have minimum one enabled mode (file, url, base64)');
        }
    }

    protected function throwError($message)
    {
        throw new BadRequestHttpException($message ?? 'Unknown error');
    }

    public function handle(Request $request, Storage $storage)
    {
        $scenario = $this->scenario ?? $request->get('scenario');

        if (!empty($scenario)) {
            $scenarioInstance = Storage::getScenario($scenario);
            if (!$scenarioInstance) {
                $this->throwError('Scenario "' . $scenario . '" not found');
            }
        }

        $file = null;
        $usedFile = false;

        if ($this->supportFile) {
            $requestFile = $request->file('file');
            if ($requestFile) {
                $file = $storage->createFromRequest($scenario);
                $usedFile = true;
            }
        }

        if (!$usedFile && $this->supportBase64) {
            $base64 = $request->post('base64');
            $filename = $request->post('filename');

            if (str_starts_with($base64, 'data:image/png;base64,')) {
                $base64 = substr($base64, strlen('data:image/png;base64,'));
                if (!$filename) {
                    $filename = 'file.png';
                }
            } else if (str_starts_with($base64, 'data:image/jpeg;base64,')) {
                $base64 = substr($base64, strlen('data:image/jpeg;base64,'));
                if (!$filename) {
                    $filename = 'file.jpg';
                }
            }

            if (!empty($base64)) {
                if (base64_encode(base64_decode($base64, true)) !== $base64) {
                    $this->throwError('Invalid base64');
                }

                $file = $storage->createFromBase64($base64, $filename, $scenario);
                $usedFile = true;
            }
        }

        if (!$usedFile && $this->supportUrl) {
            $url = $request->post('url');

            if (empty($url)) {
                $this->throwError('Empty URL');
            } else if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $this->throwError('Invalid URL');
            } else {
                $file = $storage->createFromUrl($url, $scenario);
            }
        }

        if (!$file) {
            $this->throwError('Failure create file - ' . $storage->getUploadError());
        }

        return $file->getShortJson();
    }
}
