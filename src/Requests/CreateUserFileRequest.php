<?php

namespace OZiTAG\Tager\Backend\Files\Requests;

use Ozerich\FileStorage\Rules\FileRule;
use OZiTAG\Tager\Backend\Files\Utils\TagerFilesConfig;
use OZiTAG\Tager\Backend\Core\Http\FormRequest;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

class CreateUserFileRequest extends CrudFormRequest
{
    public function fileScenarios()
    {
        return [
            'file' => TagerFilesConfig::getUserFileScenario(),
        ];
    }

    public function rules()
    {
        return [
            'file' => ['required', new FileRule()],
        ];
    }
}
