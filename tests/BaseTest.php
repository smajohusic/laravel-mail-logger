<?php

namespace Smajo\MailLogger\Test;

use Smajo\MailLogger\Commands\DeleteLogs;
use Smajo\MailLogger\Jobs\StoreMailEventToDatabase;
use Smajo\MailLogger\Models\MailLog;

class BaseTest extends TestCase
{
    public function testCreateMailLogEntry()
    {
        $model = app(MailLog::class);
        $request = json_decode(file_get_contents(__DIR__ . '/data/request.json'), true);
        $event = json_decode(json_encode(['message' => file_get_contents(__DIR__ . '/data/event.txt')]));

        (new StoreMailEventToDatabase($event, $request))->handle(new MailLog());

        $this->assertEquals($model->get()->count(), 3);
    }

    public function testDeleteMailLogEntry()
    {
        $thiryDaysBefore = date("Y-n-j", strtotime("-1 month")) . ' 00:00:00';
        $data = json_decode(file_get_contents(__DIR__ . '/data/store.json'), true);
        $data['created_at'] = $thiryDaysBefore;
        $data['updated_at'] = $thiryDaysBefore;

        $model = app(MailLog::class);
        $model->create($data);

        $this->assertEquals($model->count(), 3);

        app(DeleteLogs::class)->handle();

        $this->assertEquals($model->count(), 2);
    }

    public function testDeletingMailLogEntryWhenDeletingIsDisabled()
    {
        $this->app['config']->set('mailLogger.enableAutoDeletion', false);

        app(DeleteLogs::class)->handle();

        $this->assertEquals(app(MailLog::class)->count(), 2);
    }
}
