<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'nis_nip' => ['nullable', 'string', 'max:30'],
            'kelas' => ['nullable', 'string', 'max:30'],
        ]);

        // Peran selalu "siswa" — pendaftaran mandiri tidak pernah bisa membuat akun admin.
        // Akun admin/petugas hanya bisa dibuat oleh admin lain lewat menu Data Pengguna.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'siswa',
            'nis_nip' => $data['nis_nip'] ?? null,
            'kelas' => $data['kelas'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di D-Laporr!');
    }
}
