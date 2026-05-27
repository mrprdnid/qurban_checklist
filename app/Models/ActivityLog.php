<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'loggable_type', 'loggable_id', 'action', 'old_values', 'new_values', 'ip_address'];

    protected $casts = ['created_at' => 'datetime'];

    public static array $modelLabels = [
        'App\Models\Hewan'                => 'Data Hewan',
        'App\Models\ChecklistKandang'     => 'Checklist Kandang',
        'App\Models\ChecklistSembelih'    => 'Checklist Sembelih',
        'App\Models\ChecklistSapi'        => 'Checklist Sapi',
        'App\Models\ChecklistSeset'       => 'Checklist Seset',
        'App\Models\ChecklistPengambilan' => 'Checklist Pengambilan',
    ];

    public static array $actionLabels = [
        'created' => ['label' => 'Dibuat',     'class' => 'bg-success'],
        'updated' => ['label' => 'Diperbarui', 'class' => 'bg-primary'],
        'deleted' => ['label' => 'Dihapus',    'class' => 'bg-danger'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    public function getModelLabelAttribute(): string
    {
        return static::$modelLabels[$this->loggable_type] ?? class_basename($this->loggable_type);
    }

    public function getOldValuesArrayAttribute(): array
    {
        return $this->old_values ? json_decode($this->old_values, true) : [];
    }

    public function getNewValuesArrayAttribute(): array
    {
        return $this->new_values ? json_decode($this->new_values, true) : [];
    }
}
