<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class ChecklistPengambilan extends Model
{
    use HasAuditLog;

    protected $table = 'checklist_pengambilan';

    protected $fillable = ['hewan_id', 'kesesuaian_bagian', 'kesesuaian_bagian_at', 'sudah_diambil', 'sudah_diambil_at'];

    protected $casts = [
        'kesesuaian_bagian'    => 'boolean',
        'sudah_diambil'        => 'boolean',
        'kesesuaian_bagian_at' => 'datetime',
        'sudah_diambil_at'     => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
