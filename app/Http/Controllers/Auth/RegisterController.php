<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DataSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $nis = DataSiswa::normalisasiNis($data['nis']);

        // Semua pemeriksaan + pembuatan akun dalam satu transaksi, dan baris siswa dikunci,
        // supaya satu NIS tidak bisa dipakai dua orang yang mendaftar bersamaan.
        $user = DB::transaction(function () use ($data, $nis) {
            $siswa = DataSiswa::where('nis', $nis)->lockForUpdate()->first();

            // Pesan sengaja dibuat sama untuk "NIS tidak ada" dan "nama tidak cocok"
            // agar orang luar tidak bisa menebak NIS mana yang terdaftar.
            if (! $siswa || DataSiswa::normalisasiNama($siswa->nama) !== DataSiswa::normalisasiNama($data['name'])) {
                throw ValidationException::withMessages([
                    'nis' => 'NIS dan nama tidak cocok dengan data siswa SMK Negeri 5 Surakarta. Periksa kembali penulisannya atau hubungi admin sekolah.',
                ]);
            }

            if ($siswa->sudahTerdaftar()) {
                throw ValidationException::withMessages([
                    'nis' => 'NIS ini sudah dipakai untuk membuat akun. Jika kamu belum pernah mendaftar, segera hubungi admin sekolah.',
                ]);
            }

            // Role selalu "siswa". Nama dan kelas diambil dari data sekolah, bukan dari isian pengguna.
            $user = User::create([
                'name' => $siswa->nama,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'siswa',
                'nis_nip' => $siswa->nis,
                'kelas' => $siswa->kelas,
            ]);

            $siswa->update(['user_id' => $user->id]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di D-Laporr!');
    }
}
