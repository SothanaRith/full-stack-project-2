<?php

namespace App\Console\Commands;

use App\Models\VisitorAccessLog;
use Illuminate\Console\Command;

class PruneVisitorAccessLogs extends Command
{
    protected $signature = 'visitor-access:prune {--days= : Override the configured retention period}';

    protected $description = 'Delete visitor access logs older than the retention period';

    public function handle(): int
    {
        $days = filter_var(
            $this->option('days') ?? config('visitor-access.retention_days'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]],
        );

        if ($days === false) {
            $this->error('The retention period must be a positive number of days.');

            return self::FAILURE;
        }

        $deleted = VisitorAccessLog::query()
            ->where('accessed_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$deleted} visitor access log(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
