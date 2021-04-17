<?php

namespace OZiTAG\Tager\Backend\Files;

use Illuminate\Support\ServiceProvider;
use Ozerich\FileStorage\Commands\RegenerateThumbnailsCommand;
use Ozerich\FileStorage\Repositories\FileRepository;
use Ozerich\FileStorage\Repositories\IFileRepository;
use Ozerich\FileStorage\Services\TempFile;
use OZiTAG\Tager\Backend\Files\Console\ClearNotUsedFilesCommand;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
