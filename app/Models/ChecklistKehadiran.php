<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class ChecklistKehadiran extends Model
{
    use HasAuditLog;

    protected $table = 'checklist_kehadiran';

    protected $fillable = ['hewan_id', 'absensi', 'absensi_at', 'penyerahan_tagging', 'penyerahan_tagging_at', 'wa_manual_count'];

    protected $casts = [
        'absensi'            => 'boolean',
        'penyerahan_tagging' => 'boolean',
        'absensi_at'         => 'datetime',
        'penyerahan_tagging_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
