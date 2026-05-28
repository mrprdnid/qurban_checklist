<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class ChecklistSeset extends Model
{
    use HasAuditLog;

    protected $table = 'checklist_seset';

    protected $fillable = [
        'hewan_id',
        'mulai_seset', 'mulai_seset_at',
        'bagian_pekurban', 'bagian_pekurban_at',
        'kesesuaian_bagian', 'kesesuaian_bagian_at',
        'otw_pengambilan', 'otw_pengambilan_at',
    ];

    protected $casts = [
        'mulai_seset' => 'boolean',
        'bagian_pekurban' => 'boolean',
        'kesesuaian_bagian' => 'boolean',
        'otw_pengambilan' => 'boolean',
        'mulai_seset_at' => 'datetime',
        'bagian_pekurban_at' => 'datetime',
        'kesesuaian_bagian_at' => 'datetime',
        'otw_pengambilan_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
