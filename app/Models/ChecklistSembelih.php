<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistSembelih extends Model
{
    protected $table = 'checklist_sembelih';

    protected $fillable = ['hewan_id', 'video_sembelih', 'video_sembelih_at', 'otw_seset', 'otw_seset_at'];

    protected $casts = [
        'video_sembelih' => 'boolean',
        'otw_seset' => 'boolean',
        'video_sembelih_at' => 'datetime',
        'otw_seset_at' => 'datetime',
    ];

    public function hewan()
    {
        return $this->belongsTo(Hewan::class);
    }
}
