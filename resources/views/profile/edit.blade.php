@extends('layouts.app')
@section('title', 'Profil & Kata Sandi')

@section('content')
<div class="grid md:grid-cols-2 gap-4 max-w-3xl">
    <div class="card p-4 md:p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Informasi Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            @if ($user->isSiswa())
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                    <input name="kelas" value="{{ old('kelas', $user->kelas) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">No. HP</label>
                <input name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Simpan Profil</button>
        </form>
    </div>

    <div class="card p-4 md:p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Ubah Kata Sandi</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Baru</label>
                <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            </div>
            <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm">Ubah Kata Sandi</button>
        </form>
    </div>
</div>
@endsection
