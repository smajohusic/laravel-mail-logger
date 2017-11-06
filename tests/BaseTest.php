<?php

namespace Smajo\MailLogger\Test;

use Smajo\MailLogger\Commands\DeleteLogs;
use Smajo\MailLogger\Models\MailLog;
use Mockery;

class BaseTest extends TestCase
{
    public function testCreate()
    {
//        $event = new EventFake(new Dispatcher(), ['Illuminate\Mail\Events\MessageSending']);
//        $test = $event->fire('Illuminate\Mail\Events\MessageSending');
//        $event->hasDispatched('Illuminate\Mail\Events\MessageSending')

        $model = app(MailLog::class);
        $data = json_decode(file_get_contents(__DIR__ . '/data/store.json'), true);

        $model->create($data);

        $this->assertEquals($model->get()->count(), 3);
    }

    public function testDelete()
    {
        // Mock MailLogs model and run DeleteLogs. Se then if $mock->shouldHaveRecieved->deleted() is fired
        $mock = Mockery::mock(new DeleteLogs());

        $mock->handle();

        $mock->shouldHaveReceived()->handle();

        dd('');


//        $thiryDaysBefore = date("Y-n-j", strtotime("-1 month")) . ' 00:00:00';
//        $data = json_decode(file_get_contents(__DIR__ . '/data/store.json'), true);
//        $data['created_at'] = $thiryDaysBefore;
//        $data['updated_at'] = $thiryDaysBefore;

//        $model = app(MailLog::class);
//        $model->create($data);

//        $this->assertEquals($model->count(), 3);

//        app(DeleteLogs::class)->handle();

//        $this->assertEquals($model->count(), 2);
    }
}
