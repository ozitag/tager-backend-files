<?php

namespace OZiTAG\Tager\Backend\Files\Repositories;

use Illuminate\Support\Collection;
use OZiTAG\Tager\Backend\Files\Models\UserFile;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class UserFileRepository extends EloquentRepository
{
    public function __construct(UserFile $model)
    {
        parent::__construct($model);
    }
}
