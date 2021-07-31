<?php

namespace OZiTAG\Tager\Backend\Files\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
        $builder = $builder ? $builder : $this->model;

        return $builder->join('files', 'tager_userfiles.file_id', 'files.id')
            ->where('files.name', 'LIKE', '%' . $query . '%');
    }
}
