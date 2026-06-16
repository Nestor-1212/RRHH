<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::record('create', $model, [], $model->getAuditableAttributes());
        });

        static::updated(function ($model) {
            $old = collect($model->getOriginal())
                ->only(array_keys($model->getDirty()))
                ->toArray();
            $new = $model->getDirty();
            // Never log password or tokens
            foreach (['password', 'remember_token'] as $hidden) {
                unset($old[$hidden], $new[$hidden]);
            }
            if (!empty($new)) {
                AuditLog::record('update', $model, $old, $new);
            }
        });

        static::deleted(function ($model) {
            AuditLog::record('delete', $model, $model->getAuditableAttributes(), []);
        });
    }

    protected function getAuditableAttributes(): array
    {
        $hidden = array_merge($this->getHidden(), ['password', 'remember_token']);
        return collect($this->getAttributes())
            ->except($hidden)
            ->toArray();
    }
}
