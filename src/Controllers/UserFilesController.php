<?php

namespace OZiTAG\Tager\Backend\Files\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Files\Models\UserFile;
use OZiTAG\Tager\Backend\Files\Operations\CreateUserFileOperation;
use OZiTAG\Tager\Backend\Files\Repositories\UserFileRepository;
use OZiTAG\Tager\Backend\Files\Requests\CreateUserFileRequest;

class UserFilesController extends AdminCrudController
{
    public bool $hasUpdateAction = false;

    public bool $hasCountAction = true;

    public function __construct(UserFileRepository $repository)
    {
        parent::__construct($repository);

        $fields = [
            'id',
            'name' => function (UserFile $userFile) {
                return $userFile->file->name;
            },
            'type' => function (UserFile $userFile) {
                return $userFile->file->mime;
            },
            'size' => function (UserFile $userFile) {
                return $userFile->file->size;
            },
            'url' => function (UserFile $userFile) {
                return $userFile->file->getUrl();
            },
        ];

        $this->setResourceFields($fields, true);

        $this->setStoreAction(new StoreOrUpdateAction(
            CreateUserFileRequest::class,
            CreateUserFileOperation::class
        ));
    }
}
