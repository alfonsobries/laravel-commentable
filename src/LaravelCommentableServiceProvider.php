<?php

declare(strict_types=1);

namespace Alfonsobries\LaravelCommentable;

use Illuminate\Support\ServiceProvider;

class LaravelCommentableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-commentable.php' => config_path('laravel-commentable.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_comments_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_comments_table.php'),
        ], 'migrations');
    }
}
