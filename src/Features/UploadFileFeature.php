<?php

namespace OZiTAG\Tager\Backend\Files\Features;

use Illuminate\Http\Request;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Features\Feature;

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

    protected function throwError($message)
    {
        abort(400, $message);
    }

    public function handle(Request $request, Storage $storage)
    {
        $scenario = $request->get('scenario');

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
