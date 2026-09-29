<?php

namespace App\Jobs;

use App\Models\VisitorAccessLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecordVisitorAccess implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @param array<string, mixed> $attributes */
    public function __construct(public array $attributes) {}

    public function handle(): void
    {
        VisitorAccessLog::create($this->attributes);
    }
}
