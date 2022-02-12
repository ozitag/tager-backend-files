<?php

namespace OZiTAG\Tager\Backend\Files\Models;

use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class UserFile
 * @package OZiTAG\Tager\Backend\Files\Models
 *
 * @property File $file
 */
class UserFile extends TModel
{
    protected $table = 'tager_userfiles';

    static string $defaultOrder = 'tager_userfiles.created_at DESC';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $model) {
            $model->file()->delete();
        });
    }
}
