<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $stats = $user->isSiswa()
            ? [
                'Total laporan' => $user->pengaduans()->count(),
                'Selesai' => $user->pengaduans()->where('status', 'selesai')->count(),
            ]
            : [
                'Laporan diverifikasi' => Pengaduan::where('diverifikasi_oleh', $user->id)->count(),
            ];

        return view('profile.edit', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$user->id],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];

        // Nama siswa (dan NIS, kelas) berasal dari data sekolah, jadi tidak bisa diubah sendiri.
        if ($user->isAdmin()) {
            $rules['name'] = ['required', 'string', 'max:100'];
        }

        $user->update($request->validate($rules));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    /** Pasang atau ganti foto profil — khusus siswa. */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isSiswa(), 403);

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'foto.required' => 'Pilih foto terlebih dahulu.',
        ]);

        $path = $request->file('foto')->store('foto-profil', 'public');

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->update(['foto_profil' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function destroyPhoto()
    {
        $user = Auth::user();
        abort_unless($user->isSiswa(), 403);

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
        }

        return back()->with('success', 'Foto profil dihapus.');
    }
}
