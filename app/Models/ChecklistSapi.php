<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class ChecklistSapi extends Model
{
    use HasAuditLog;

    protected $table = 'checklist_sapi';

    protected $fillable = [
        'hewan_id',
        'foto_hidup', 'foto_hidup_at',
        'video_sembelih', 'video_sembelih_at',
        'mulai_seset', 'mulai_seset_at',
        'kesesuaian_bagian', 'kesesuaian_bagian_at',
        'otw_pengambilan', 'otw_pengambilan_at',
    ];

    protected $casts = [
        'foto_hidup' => 'boolean',
        'video_sembelih' => 'boolean',
        'mulai_seset' => 'boolean',
        'kesesuaian_bagian' => 'boolean',
        'otw_pengambilan' => 'boolean',
        'foto_hidup_at' => 'datetime',
        'video_sembelih_at' => 'datetime',
        'mulai_seset_at' => 'datetime',
        'kesesuaian_bagian_at' => 'datetime',
        'otw_pengambilan_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
