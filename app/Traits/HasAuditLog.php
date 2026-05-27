<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait HasAuditLog
{
    // Holds dirty data captured during 'updating' event, keyed by spl_object_id
    private static array $_auditOld = [];

    public static function bootHasAuditLog(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::created(function ($model) {
            if (!Auth::check()) return;
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'loggable_type' => get_class($model),
                'loggable_id'   => $model->id,
                'action'        => 'created',
                'old_values'    => null,
                'new_values'    => json_encode(self::_filterAuditFields($model->toArray())),
                'ip_address'    => Request::ip(),
            ]);
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
            // Capture original values of dirty fields before save
            $old = [];
            foreach (array_keys($model->getDirty()) as $field) {
                if (!in_array($field, ['updated_by', 'updated_at', 'created_by', 'created_at'])) {
                    $old[$field] = $model->getOriginal($field);
                }
            }
            static::$_auditOld[spl_object_id($model)] = $old;
        });

        static::updated(function ($model) {
            if (!Auth::check()) return;

            $key = spl_object_id($model);
            $old = static::$_auditOld[$key] ?? [];
            unset(static::$_auditOld[$key]);

            $new = array_filter(
                $model->getChanges(),
                fn($k) => !in_array($k, ['updated_by', 'updated_at', 'created_at']),
                ARRAY_FILTER_USE_KEY
            );

            if (empty($new)) return;

            ActivityLog::create([
                'user_id'       => Auth::id(),
                'loggable_type' => get_class($model),
                'loggable_id'   => $model->id,
                'action'        => 'updated',
                'old_values'    => json_encode(array_intersect_key($old, $new)),
                'new_values'    => json_encode($new),
                'ip_address'    => Request::ip(),
            ]);
        });

        static::deleted(function ($model) {
            if (!Auth::check()) return;
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'loggable_type' => get_class($model),
                'loggable_id'   => $model->id,
                'action'        => 'deleted',
                'old_values'    => json_encode(self::_filterAuditFields($model->toArray())),
                'new_values'    => null,
                'ip_address'    => Request::ip(),
            ]);
        });
    }

    private static function _filterAuditFields(array $data): array
    {
        $exclude = ['created_by', 'updated_by', 'created_at', 'updated_at'];
        return array_filter($data, fn($k) => !in_array($k, $exclude), ARRAY_FILTER_USE_KEY);
    }

    public function createdByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable')->latest('created_at');
    }
}
