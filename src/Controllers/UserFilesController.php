<?php

namespace OZiTAG\Tager\Backend\Files\Controllers;

use OZiTAG\Tager\Backend\Blog\Features\Admin\ListPostsByCategoryFeature;
use OZiTAG\Tager\Backend\Blog\Models\BlogCategory;
use OZiTAG\Tager\Backend\Crud\Actions\DeleteAction;
use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Blog\Operations\CreateCategoryOperation;
use OZiTAG\Tager\Backend\Blog\Operations\UpdateCategoryOperation;
use OZiTAG\Tager\Backend\Blog\Jobs\CheckIfCanDeleteCategoryJob;
use OZiTAG\Tager\Backend\Blog\Repositories\CategoryRepository;
use OZiTAG\Tager\Backend\Blog\Requests\CreateBlogCategoryRequest;
use OZiTAG\Tager\Backend\Blog\Requests\UpdateBlogCategoryRequest;
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
            'url' => function (UserFile $userFile) {
                return $userFile->file->getUrl();
            },
        ];

        $this->setResourceFields($fields, true);

        $this->setStoreAction(new StoreOrUpdateAction(CreateUserFileRequest::class, CreateUserFileOperation::class));
    }
}
