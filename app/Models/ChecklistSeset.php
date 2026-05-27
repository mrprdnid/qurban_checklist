<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistSeset extends Model
{
    protected $table = 'checklist_seset';

    protected $fillable = [
        'hewan_id',
        'bagian_pekurban', 'bagian_pekurban_at',
        'kesesuaian_bagian', 'kesesuaian_bagian_at',
        'otw_pengambilan', 'otw_pengambilan_at',
    ];

    protected $casts = [
        'bagian_pekurban' => 'boolean',
        'kesesuaian_bagian' => 'boolean',
        'otw_pengambilan' => 'boolean',
        'bagian_pekurban_at' => 'datetime',
        'kesesuaian_bagian_at' => 'datetime',
        'otw_pengambilan_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
