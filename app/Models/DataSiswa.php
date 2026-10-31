<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    protected $fillable = ['nis', 'nama', 'kelas', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sudahTerdaftar(): bool
    {
        return $this->user_id !== null;
    }

    /** Rapikan NIS: buang spasi di awal/akhir dan spasi di tengah. */
    public static function normalisasiNis(?string $nis): string
    {
        return preg_replace('/\s+/', '', trim((string) $nis));
    }

    /** Samakan penulisan nama: huruf kecil, spasi ganda jadi satu, tanpa spasi di tepi. */
    public static function normalisasiNama(?string $nama): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $nama)));
    }
}
