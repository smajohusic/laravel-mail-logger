<?php

namespace Smajo\MailLogger\Commands;

use Illuminate\Console\Command;
use Smajo\MailLogger\Models\MailLog;
use Carbon\Carbon;

class DeleteLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all mail_logs that are newer then the defined count of days, and deletes them.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timeLimit = (new Carbon)->now()->subDays(config('mailLogger.keepLogsForDays'));
        app(MailLog::class)
            ->where('created_at', '<=', $timeLimit)
            ->get()
            ->each
            ->delete();
    }
}
