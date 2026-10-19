<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · D-Laporr</title>
    @include('partials.head')
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="pop relative w-full max-w-4xl bg-white rounded-2xl shadow-xl shadow-slate-900/10 overflow-hidden flex">
        <div class="hero hidden md:flex md:w-5/12 flex-col justify-between p-10 !rounded-none">
            <div class="relative">
                <span class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center ring-1 ring-white/30"><x-icon name="logo" class="w-6 h-6 text-white" /></span>
                <h1 class="mt-6 text-3xl font-extrabold tracking-tight">D-Laporr</h1>
                <p class="text-sm text-indigo-100 mt-1">Sistem Pengaduan Sarana Sekolah</p>
            </div>
            <ul class="relative space-y-4 text-sm text-indigo-50">
                <li class="flex items-start gap-3"><x-icon name="check" class="w-5 h-5 text-indigo-200 shrink-0 mt-0.5" /> Lapor kerusakan sarana dalam hitungan detik</li>
                <li class="flex items-start gap-3"><x-icon name="check" class="w-5 h-5 text-indigo-200 shrink-0 mt-0.5" /> Pantau status laporan secara langsung</li>
                <li class="flex items-start gap-3"><x-icon name="check" class="w-5 h-5 text-indigo-200 shrink-0 mt-0.5" /> Rekap dan cetak laporan otomatis jadi PDF</li>
            </ul>
            <p class="relative text-xs text-indigo-200">SMK Negeri 5 Surakarta &middot; {{ date('Y') }}</p>
        </div>

        <div class="w-full md:w-7/12 p-8 sm:p-10">
            <div class="md:hidden text-center mb-6">
                <span class="brand-badge inline-flex w-12 h-12 rounded-2xl items-center justify-center"><x-icon name="logo" class="w-6 h-6 text-white" /></span>
                <h1 class="mt-3 text-xl font-extrabold text-slate-900">D-Laporr</h1>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900">Selamat datang kembali</h2>
            <p class="text-sm text-slate-500 mt-1 mb-7">Masuk untuk melanjutkan ke dashboard kamu.</p>

            @if ($errors->any())
                <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm" role="alert">
                    <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
                    <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input-fx w-full rounded-xl border border-slate-300 px-4 py-3 text-base">
                </div>
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required class="input-fx w-full rounded-xl border border-slate-300 pl-4 pr-12 py-3 text-base">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3.5 text-slate-400 hover:text-indigo-600 transition-colors" :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <span x-show="!show"><x-icon name="eye" class="w-5 h-5" /></span>
                            <span x-show="show" x-cloak><x-icon name="eye-off" class="w-5 h-5" /></span>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Ingat saya
                </label>
                <button type="submit" class="btn-primary w-full py-3 rounded-xl">Masuk</button>
            </form>

            <p class="mt-6 text-sm text-center text-slate-500">
                Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-bold transition-colors">Buat Akun</a>
            </p>
            @if (app()->isLocal())
                <p class="mt-4 text-xs text-center text-slate-400">
                    Akun demo (hanya tampil di komputer lokal) — Admin: admin@dlaporr.sch.id · Siswa: siswa@dlaporr.sch.id (password: <span class="font-mono">password</span>)
                </p>
            @endif
        </div>
    </div>
</body>
</html>
