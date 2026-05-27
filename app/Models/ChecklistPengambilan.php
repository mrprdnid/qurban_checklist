<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistPengambilan extends Model
{
    protected $table = 'checklist_pengambilan';

    protected $fillable = ['hewan_id', 'nomor_wa_pemesan', 'data_pengambilan', 'paraf_pengambil', 'diambil_at'];

    protected $casts = [
        'diambil_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
