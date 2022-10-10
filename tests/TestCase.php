<?php

declare(strict_types=1);

namespace Tests;

use Alfonsobries\LaravelCommentable\LaravelCommentableServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Tests\Fixtures\Models\Agent;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpServiceProvider($this->app);

        $this->setUpConfiguration();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpConfiguration()
    {
        config()->set('laravel-commentable', require __DIR__.'/../config/laravel-commentable.php');

        config()->set('laravel-commentable.models.agent', Agent::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('commentables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        // Run the comment migration
        $commentMigration = new (require __DIR__.'/../database/migrations/create_comments_table.php.stub');
        $commentMigration->up();

        // Run the comment reaction migration
        $commentReactionMigration = new (require __DIR__.'/../database/migrations/create_comment_reactions_table.php.stub');
        $commentReactionMigration->up();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpServiceProvider($app)
    {
        $provider = new LaravelCommentableServiceProvider($app);

        $provider->boot();
    }
}
