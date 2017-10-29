<?php

namespace Smajo\MailLogger\Test;

use Illuminate\Database\Schema\Blueprint;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Smajo\MailLogger\MailLogServiceProvider;
use Smajo\MailLogger\Models\MailLog;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
//        $this->registerMocks();
    }

    protected function getPackageProviders($app)
    {
        return [
            MailLogServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory() . '/database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('laravelEntitySyncEndpoint.entities', [
            'article' => Article::class,
            'page' => Page::class,
        ]);

        $app['config']->set('laravelEntitySyncEndpoint.routeUrlPrefix', '');
        $app['config']->set('laravelEntitySyncEndpoint.api_auth_token', null);
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();

        $this->createTables('mail_logs');
        $this->seedModels(MailLog::class);
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory() . '/database.sqlite', null);
    }

    public function getTempDirectory()
    {
        return __DIR__ . '/temp';
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function ($tableName) {
            $this->app['db']
                ->connection()
                ->getSchemaBuilder()
                ->create($tableName, function (Blueprint $table) use ($tableName) {
                    $table->increments('id');

                    if ($tableName === 'mail_logs') {
                        $table->string('sender')->nullable()->default(null);
                        $table->text('data');
                        $table->text('route');
                        $table->text('event');
                    }

                    $table->timestamps();
                });
        });
    }

    protected function seedModels(...$modelClasses)
    {
        collect($modelClasses)->each(function ($modelClass) {
            foreach (range(1, 0) as $index) {
                $modelClass::create([
                    'sender' => 'smajohusic@gmail.com',
                    'data' => '',
                    'route' => 'http://mail-logger.app/send-mail',
                    'event' => '',
                ]);
            }
        });
    }

    public function doNotMarkAsRisky()
    {
        $this->assertTrue(true);
    }

//    private function registerMocks()
//    {
//        Mockery::mock('App\Http\Controllers\Controller');
//    }
}
