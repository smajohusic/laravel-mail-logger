<?php

namespace Smajo\MailLogger\Jobs;

use Smajo\MailLogger\Exceptions\MailLogsTableNotFoundException;
use Smajo\MailLogger\Models\MailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Bus\DispatchesJobs;

class StoreMailEventToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $event;
    private $formRequest;

    public function __construct($event, $formRequest)
    {
        $this->event = $event;
        $this->formRequest = $formRequest;
    }

    public function handle(MailLog $mailLog)
    {
        /*
         * Check if the mail_logs table has been created, if not throw a exception
         */
        if (!Schema::hasTable('mail_logs')) {
            throw new MailLogsTableNotFoundException;
        }

        /*
         * Save the request and event data to the database
         */
        $mailLog->create([
            'sent_to' => $this->getCustomerEmail($this->formRequest),
            'data' => json_encode(json_encode($this->formRequest)),
            'route' => request()->route()->getName(),
            'event' => $this->event->message->toString()
        ]);
    }

    private function getCustomerEmail($request)
    {
        foreach (config('mailLogger.toEmailAddresses') as $field) {
            if (key_exists($field, $request)) {
                return $request[$field];
            }
        }
    }
}
