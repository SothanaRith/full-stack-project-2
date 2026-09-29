<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorAccessLog extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'accessed_at' => 'datetime',
            'response_status' => 'integer',
            'user_id' => 'integer',
            'duration_ms' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('accessed_at', '>=', $date))
            ->when($filters['to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('accessed_at', '<=', $date))
            ->when($filters['ip'] ?? null, fn (Builder $query, string $ip) => $query->where('ip_address', $ip))
            ->when($filters['user_id'] ?? null, fn (Builder $query, string $userId) => $query->where('user_id', $userId))
            ->when($filters['device_category'] ?? null, fn (Builder $query, string $category) => $query->where('device_category', $category))
            ->when($filters['status_code'] ?? null, fn (Builder $query, string $status) => $query->where('response_status', $status));
    }
}
