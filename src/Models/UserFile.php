<?php

namespace OZiTAG\Tager\Backend\Files\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ozerich\FileStorage\Models\File;
use OZiTAG\Tager\Backend\Blog\Utils\TagerBlogSeoHelper;
use OZiTAG\Tager\Backend\Blog\Utils\TagerBlogUrlHelper;
use OZiTAG\Tager\Backend\Core\Models\Contracts\IPublicWebModel;
use OZiTAG\Tager\Backend\Core\Models\TModel;
use OZiTAG\Tager\Backend\Crud\Contracts\IModelPriorityConditional;

/**
 * Class UserFile
 * @package OZiTAG\Tager\Backend\Files\Models
 *
 * @property File $file
 */
class UserFile extends TModel
{
    protected $table = 'tager_userfiles';

    static $defaultOrder = 'tager_userfiles.created_at DESC';

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
