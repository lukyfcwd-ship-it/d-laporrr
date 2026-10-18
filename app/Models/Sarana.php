<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarana extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_id', 'kode_sarana', 'nama_sarana', 'lokasi', 'kondisi'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
