<?php

namespace OZiTAG\Tager\Backend\Files\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use OZiTAG\Tager\Backend\Core\Repositories\ISearchable;
use OZiTAG\Tager\Backend\Files\Models\UserFile;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class UserFileRepository extends EloquentRepository implements ISearchable
{
    public function __construct(UserFile $model)
    {
        parent::__construct($model);
    }

    public function searchByQuery(?string $query, Builder $builder = null): ?Builder
    {
        $builder = $builder ?: $this->model;

        return $builder->join('files', 'tager_userfiles.file_id', 'files.id')
            ->where('files.name', 'LIKE', '%' . $query . '%');
    }
}
