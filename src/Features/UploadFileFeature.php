<?php

namespace OZiTAG\Tager\Backend\Files\Features;

use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadFileFeature extends Feature
{
    protected bool $supportUrl;

    protected bool $supportFile;

    protected ?string $scenario;

    public function __construct(bool $supportFile = true, bool $supportUrl = false, ?string $scenario = null)
    {
        $this->supportFile = $supportFile;

        $this->supportUrl = $supportUrl;

        if (!$this->supportUrl && !$this->supportFile) {
            throw new \Exception('UploadFileFeature must have minimum one enabled mode');
        }

        $this->scenario = $scenario;
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
            /** @var Request $request */
            $request = app()->request;

            $requestFile = $request->file('file');
            if ($requestFile) {
                $file = $storage->createFromRequest($scenario);
                $usedFile = true;
            } else {
                $this->throwError('File is empty');
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
            $this->throwError($storage->getUploadError());
        }

        return $file->getShortJson();
    }
}
