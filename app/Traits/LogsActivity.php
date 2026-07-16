<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::logActivity('created', $model, 'Created '.$model->getLogName());
        });

        static::updated(function ($model) {
            static::logActivity('updated', $model, 'Updated '.$model->getLogName());
        });

        static::deleted(function ($model) {
            static::logActivity('deleted', $model, 'Deleted '.$model->getLogName());
        });
    }

    protected function getLogName(): string
    {
        return class_basename($this).' #'.$this->getKey();
    }

    protected static function logActivity(string $action, $model, string $description): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'description' => $description,
        ]);
    }
}
