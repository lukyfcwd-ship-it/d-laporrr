<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket', 'user_id', 'sarana_id', 'kategori_id', 'lokasi_kerusakan', 'judul', 'tanggal_kejadian', 'deskripsi',
        'bukti_foto', 'prioritas', 'status', 'catatan_admin', 'diverifikasi_oleh',
        'tanggal_verifikasi', 'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            'tanggal_verifikasi' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pengaduan $pengaduan) {
            if (empty($pengaduan->kode_tiket)) {
                $pengaduan->kode_tiket = 'TIK-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sarana()
    {
        return $this->belongsTo(Sarana::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public static function statusBadge(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
            'diproses' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
            'approved' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
            'rejected' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-200',
            'selesai' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200',
            default => 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-200',
        };
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'diproses' => 'Diproses',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'selesai' => 'Selesai',
            default => ucfirst($status),
        };
    }
}
