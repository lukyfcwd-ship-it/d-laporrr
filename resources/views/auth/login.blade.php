<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · D-Laporr</title>
    @include('partials.head')
    @include('partials.auth-assets')
</head>
<body class="auth-page min-h-screen antialiased">
<script>
function loginPage() {
    return {
        email: @js(old('email', '')),
        password: '',
        show: false,
        caps: false,
        fill(e, p) { this.email = e; this.password = p; },
        capsCheck(ev) { this.caps = ev.getModifierState ? ev.getModifierState('CapsLock') : false; }
    };
}
</script>

<div class="min-h-screen grid lg:grid-cols-[1.15fr_1fr]" x-data="loginPage()">

    <!-- ===== Panel kiri: papan laporan ===== -->
    <section class="board relative hidden lg:flex flex-col justify-between p-10 xl:p-14 text-white overflow-hidden" data-parallax>
        <div class="relative flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="brand-badge w-11 h-11 rounded-xl flex items-center justify-center"><x-icon name="logo" class="w-6 h-6" /></span>
                <div class="leading-tight">
                    <p class="font-display text-2xl font-extrabold tracking-tight">D-Laporr</p>
                    <p class="text-[10px] uppercase tracking-[.2em] text-sun-400">SMK Negeri 5 Surakarta</p>
                </div>
            </div>
            <svg viewBox="0 0 120 120" class="w-24 h-24 -mr-2" style="animation: spin 22s linear infinite" aria-hidden="true">
                <defs><path id="lingkar" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
                <text font-size="10.5" font-weight="800" letter-spacing="2.6" fill="#FFC933" style="font-family: Figtree, sans-serif"><textPath href="#lingkar">LAPOR · PANTAU · BERES · LAPOR · PANTAU · BERES ·</textPath></text>
            </svg>
        </div>

        <div class="relative">
            <h1 class="font-display text-5xl xl:text-6xl font-extrabold leading-[1.03] max-w-xl">
                Ada yang rusak?<br><span class="marker">Lapor</span> dulu,<br>pantau sampai <span class="marker">beres</span>.
            </h1>
            <p class="mt-5 text-brand-200 max-w-md">Satu tempat untuk melaporkan kerusakan sarana sekolah, melampirkan foto bukti, dan melihat sampai mana laporanmu ditangani.</p>
        </div>

        <!-- Tiket-tiket di papan (bergeser halus mengikuti kursor) -->
        <div class="relative h-[19rem]">
            <div class="ticket bob" style="top: 0; left: 2%; --r: -4deg;" data-depth="18"
                 x-data="{ s: 0, st: [ {t:'Menunggu', c:'bg-amber-100 text-amber-800'}, {t:'Diproses', c:'bg-sky-100 text-sky-800'}, {t:'Disetujui', c:'bg-emerald-100 text-emerald-800'}, {t:'Selesai', c:'bg-brand-100 text-brand-800'} ] }"
                 x-init="setInterval(() => s = (s + 1) % 4, 2200)">
                <span class="tape"></span>
                <p class="font-mono text-[10px] tracking-wider text-slate-500">TIK-20261102-A7K2</p>
                <p class="font-display font-bold text-[15px] leading-snug mt-1">Proyektor Lab RPL tidak menyala</p>
                <div class="track mt-3"><template x-for="i in 4" :key="i"><i :class="i <= s + 1 ? 'on' : ''"></i></template></div>
                <div class="flex items-center justify-between mt-2.5">
                    <span class="chip" :class="st[s].c" x-text="st[s].t"></span>
                    <span class="text-[11px] text-slate-500">XII RPL A</span>
                </div>
            </div>

            <div class="ticket" style="top: 5.5rem; right: 2%; --r: 3.5deg; width: 15rem;" data-depth="30">
                <span class="tape"></span>
                <p class="font-mono text-[10px] tracking-wider text-slate-500">TIK-20261030-K9PL</p>
                <p class="font-display font-bold text-[15px] leading-snug mt-1">Keran toilet siswa bocor</p>
                <div class="track mt-3"><i class="on"></i><i class="on"></i><i class="on"></i><i class="on"></i></div>
                <div class="flex items-center justify-between mt-2.5">
                    <span class="chip bg-brand-100 text-brand-800"><x-icon name="check" class="w-3.5 h-3.5" /> Selesai</span>
                    <span class="text-[11px] text-slate-500">Toilet Lt.1</span>
                </div>
            </div>

            <div class="ticket" style="top: 11rem; left: 18%; --r: -2deg; width: 14rem;" data-depth="42">
                <span class="tape"></span>
                <p class="font-mono text-[10px] tracking-wider text-slate-500">TIK-20261101-M3QD</p>
                <p class="font-display font-bold text-[15px] leading-snug mt-1">Kursi patah di kelas</p>
                <div class="track mt-3"><i class="on"></i><i></i><i></i><i></i></div>
                <div class="flex items-center justify-between mt-2.5">
                    <span class="chip bg-amber-100 text-amber-800">Menunggu</span>
                    <span class="text-[11px] text-slate-500">XII RPL B</span>
                </div>
            </div>
        </div>

        <div class="relative flex flex-wrap gap-x-6 gap-y-2 text-sm text-brand-200">
            <span class="inline-flex items-center gap-2"><x-icon name="shield" class="w-4 h-4 text-sun-400" /> Khusus siswa terdaftar</span>
            <span class="inline-flex items-center gap-2"><x-icon name="clock" class="w-4 h-4 text-sun-400" /> Status langsung terlihat</span>
            <span class="inline-flex items-center gap-2"><x-icon name="printer" class="w-4 h-4 text-sun-400" /> Cetak tiket ke PDF</span>
        </div>
    </section>

    <!-- ===== Panel kanan: formulir ===== -->
    <section class="flex items-center justify-center p-5 sm:p-10">
        <div class="w-full max-w-md pop">
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <span class="brand-badge w-11 h-11 rounded-xl flex items-center justify-center"><x-icon name="logo" class="w-6 h-6" /></span>
                <div class="leading-tight">
                    <p class="font-display text-2xl font-extrabold text-brand-900 tracking-tight">D-Laporr</p>
                    <p class="text-[10px] uppercase tracking-[.2em] text-brand-700">SMK Negeri 5 Surakarta</p>
                </div>
            </div>

            <h2 class="font-display text-4xl font-extrabold text-brand-900 leading-tight">Selamat datang <span class="marker">kembali</span></h2>
            <p class="text-slate-600 mt-2 mb-7">Masuk untuk melapor atau memantau laporanmu.</p>

            @if ($errors->any())
                <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm swap" role="alert">
                    <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
                    <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input id="email" type="email" name="email" x-model="email" placeholder=" " required autofocus autocomplete="email">
                    <label for="email">Email</label>
                </div>

                <div>
                    <div class="field relative">
                        <input id="password" name="password" x-model="password" :type="show ? 'text' : 'password'" placeholder=" " required autocomplete="current-password"
                               class="!pr-12" @keydown="capsCheck($event)" @keyup="capsCheck($event)">
                        <label for="password">Kata sandi</label>
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 text-slate-400 hover:text-brand-900 transition-colors"
                                :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                            <span x-show="!show"><x-icon name="eye" class="w-5 h-5" /></span>
                            <span x-show="show" x-cloak><x-icon name="eye-off" class="w-5 h-5" /></span>
                        </button>
                    </div>
                    <p x-show="caps" x-cloak class="swap mt-2 text-xs font-semibold text-amber-700 inline-flex items-center gap-1.5">
                        <x-icon name="warning" class="w-4 h-4" /> Caps Lock sedang aktif
                    </p>
                </div>

                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="sr-only peer">
                    <span class="relative w-11 h-6 rounded-full bg-[#d6cfbd] transition-colors peer-checked:bg-brand-900 peer-focus-visible:ring-4 peer-focus-visible:ring-sun-500/50
                                 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:shadow after:transition-all
                                 peer-checked:after:translate-x-5 peer-checked:after:bg-sun-500"></span>
                    <span class="text-sm text-slate-600">Ingat saya di perangkat ini</span>
                </label>

                <button type="submit" class="btn-primary group w-full py-3.5 rounded-xl text-base inline-flex items-center justify-center gap-2">
                    Masuk <x-icon name="arrow-right" class="w-5 h-5 transition-transform group-hover:translate-x-1" />
                </button>
            </form>

            <p class="mt-7 text-sm text-center text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-brand-900 underline decoration-sun-500 decoration-[3px] underline-offset-4 hover:bg-sun-500 transition-colors">Buat akun</a>
            </p>

            @if (app()->isLocal())
                <div class="mt-6 rounded-xl border border-dashed border-[#d6cfbd] p-3">
                    <p class="text-[10px] font-bold uppercase tracking-[.16em] text-brand-700 mb-2">Akun demo (hanya tampil di komputer lokal)</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="fill('admin@dlaporr.sch.id', 'password')" class="px-3 py-1.5 rounded-lg bg-white border border-[#d6cfbd] text-xs font-semibold text-brand-900 hover:bg-sun-500 transition-colors">Isi sebagai Admin</button>
                        <button type="button" @click="fill('siswa@dlaporr.sch.id', 'password')" class="px-3 py-1.5 rounded-lg bg-white border border-[#d6cfbd] text-xs font-semibold text-brand-900 hover:bg-sun-500 transition-colors">Isi sebagai Siswa</button>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
</body>
</html>
