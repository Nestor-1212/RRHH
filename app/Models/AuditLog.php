<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'event', 'auditable_type', 'auditable_id',
        'old_values', 'new_values', 'url', 'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(fn ($m) => $m->created_at ??= now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function scopeForModel(Builder $q, string $type, int $id): Builder
    {
        return $q->where('auditable_type', $type)->where('auditable_id', $id);
    }

    public function scopeRecent(Builder $q, int $limit = 50): Builder
    {
        return $q->orderByDesc('created_at')->limit($limit);
    }

    public static function record(string $event, mixed $model = null, array $old = [], array $new = []): void
    {
        static::create([
            'user_id'        => auth()->id(),
            'event'          => $event,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id'   => $model?->getKey(),
            'old_values'     => empty($old) ? null : $old,
            'new_values'     => empty($new) ? null : $new,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => substr(request()->userAgent() ?? '', 0, 500),
        ]);
    }
}
