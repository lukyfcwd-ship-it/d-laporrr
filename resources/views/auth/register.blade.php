<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun · D-Laporr</title>
    @include('partials.head')
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="pop relative w-full max-w-4xl bg-white rounded-2xl shadow-xl shadow-slate-900/10 overflow-hidden flex my-6">
        <div class="hero hidden md:flex md:w-5/12 flex-col justify-between p-10 !rounded-none">
            <div class="relative">
                <span class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center ring-1 ring-white/30"><x-icon name="logo" class="w-6 h-6 text-white" /></span>
                <h1 class="mt-6 text-3xl font-extrabold tracking-tight">D-Laporr</h1>
                <p class="text-sm text-indigo-100 mt-1">Sistem Pengaduan Sarana Sekolah</p>
            </div>
            <div class="relative bg-white/10 rounded-xl p-4 text-sm text-indigo-50 ring-1 ring-white/15">
                Akun yang dibuat di sini otomatis berperan sebagai <span class="text-white font-bold">Siswa</span>. Akun Admin/Petugas hanya dibuat lewat menu Data Pengguna.
            </div>
            <p class="relative text-xs text-indigo-200">SMK Negeri 5 Surakarta &middot; {{ date('Y') }}</p>
        </div>

        <div class="w-full md:w-7/12 p-8 sm:p-10">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-6">
                <x-icon name="arrow-right" class="w-4 h-4 rotate-180" /> Kembali ke Login
            </a>

            <div class="md:hidden text-center mb-6">
                <span class="brand-badge inline-flex w-12 h-12 rounded-2xl items-center justify-center"><x-icon name="logo" class="w-6 h-6 text-white" /></span>
                <h1 class="mt-3 text-xl font-extrabold text-slate-900">D-Laporr</h1>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900">Buat akun siswa</h2>
            <p class="text-sm text-slate-500 mt-1 mb-6">Isi data di bawah untuk mulai melapor kerusakan sarana.</p>

            @if ($errors->any())
                <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm" role="alert">
                    <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
                    <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus class="input-fx w-full rounded-xl border border-slate-300 px-4 py-2.5 text-base">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input-fx w-full rounded-xl border border-slate-300 px-4 py-2.5 text-base">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="nis_nip" class="block text-sm font-semibold text-slate-700 mb-1">NIS</label>
                        <input id="nis_nip" name="nis_nip" value="{{ old('nis_nip') }}" class="input-fx w-full rounded-xl border border-slate-300 px-4 py-2.5 text-base">
                    </div>
                    <div>
                        <label for="kelas" class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                        <input id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="XII RPL A" class="input-fx w-full rounded-xl border border-slate-300 px-4 py-2.5 text-base">
                    </div>
                </div>
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required class="input-fx w-full rounded-xl border border-slate-300 pl-4 pr-12 py-2.5 text-base">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3.5 text-slate-400 hover:text-indigo-600 transition-colors" :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <span x-show="!show"><x-icon name="eye" class="w-5 h-5" /></span>
                            <span x-show="show" x-cloak><x-icon name="eye-off" class="w-5 h-5" /></span>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="input-fx w-full rounded-xl border border-slate-300 px-4 py-2.5 text-base">
                </div>
                <button type="submit" class="btn-primary w-full py-3 rounded-xl">Buat Akun</button>
            </form>

            <p class="mt-6 text-sm text-center text-slate-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-bold transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
