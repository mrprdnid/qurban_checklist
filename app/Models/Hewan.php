<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hewan extends Model
{
    protected $table = 'hewan';

    protected $fillable = ['nomor_urut', 'jenis', 'nama_hewan', 'nama_pekurban', 'nomor_wa', 'keterangan'];

    public function checklistKandang()
    {
        return $this->hasOne(ChecklistKandang::class);
    }

    public function checklistSembelih()
    {
        return $this->hasOne(ChecklistSembelih::class);
    }

    public function checklistSapi()
    {
        return $this->hasOne(ChecklistSapi::class);
    }

    public function checklistSeset()
    {
        return $this->hasOne(ChecklistSeset::class);
    }

    public function checklistPengambilan()
    {
        return $this->hasOne(ChecklistPengambilan::class);
    }
}
