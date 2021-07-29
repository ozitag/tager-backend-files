<?php

namespace OZiTAG\Tager\Backend\Files;

use Illuminate\Support\ServiceProvider;
use OZiTAG\Tager\Backend\Files\Enums\FilesScope;
use OZiTAG\Tager\Backend\Rbac\TagerScopes;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tager-files');

        $this->publishes([__DIR__ . '/../config.php' => config_path('tager-files.php'),]);

        TagerScopes::registerGroup(__('tager-files::scopes.group'), [
            FilesScope::FilesView => __('tager-blog::scopes.files_view'),
            FilesScope::FilesCreate => __('tager-blog::scopes.files_create'),
            FilesScope::FilesDelete => __('tager-blog::scopes.files_delete'),
        ]);
    }
}
