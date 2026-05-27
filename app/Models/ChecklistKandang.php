<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class ChecklistKandang extends Model
{
    use HasAuditLog;

    protected $table = 'checklist_kandang';

    protected $fillable = ['hewan_id', 'ambil_domba', 'ambil_domba_at', 'foto_hidup', 'foto_hidup_at', 'otw_sembelih', 'otw_sembelih_at'];

    protected $casts = [
        'ambil_domba' => 'boolean',
        'foto_hidup' => 'boolean',
        'otw_sembelih' => 'boolean',
        'ambil_domba_at' => 'datetime',
        'foto_hidup_at' => 'datetime',
        'otw_sembelih_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}


