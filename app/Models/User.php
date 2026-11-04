<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nis_nip', 'kelas', 'no_hp', 'foto_profil',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /** Alamat foto profil (null bila belum punya foto). */
    public function fotoUrl(): ?string
    {
        return $this->foto_profil ? asset('storage/'.$this->foto_profil) : null;
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
