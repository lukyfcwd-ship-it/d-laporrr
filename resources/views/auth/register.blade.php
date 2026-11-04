<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Akun · D-Laporr</title>
    @include('partials.head')
    @include('partials.auth-assets')
</head>
<body class="auth-page min-h-screen antialiased">
@php
    // Jika server menolak (mis. NIS tidak cocok), kembali ke langkah 1; kesalahan lain (mis. email dipakai) ke langkah 2.
    $stepAwal = ($errors->has('nis') || $errors->has('name') || ! old('nis')) ? 1 : 2;
@endphp
<script>
function registerPage() {
    return {
        step: {{ $stepAwal }},
        nis: @js(old('nis', '')),
        nama: @js(old('name', '')),
        email: @js(old('email', '')),
        pw: '', pw2: '', show: false,
        verified: false, kelas: '', checking: false, msg: '',

        init() { if (this.step === 2 && this.nis && this.nama) this.verify(true); },

        async verify(silent = false) {
            if (!this.nis.trim() || !this.nama.trim()) { this.msg = 'Isi NIS dan nama lengkap dulu.'; return; }
            this.checking = true; this.msg = '';
            try {
                const r = await fetch(@js(route('register.cek')), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': @js(csrf_token()) },
                    body: JSON.stringify({ nis: this.nis, name: this.nama })
                });
                const d = await r.json();
                if (r.ok && d.ok) { this.verified = true; this.kelas = d.kelas; this.nama = d.nama; if (!silent) this.step = 2; }
                else { this.verified = false; this.msg = d.message || 'Data tidak cocok.'; if (silent) this.step = 1; }
            } catch (e) {
                this.msg = 'Tidak dapat terhubung ke server. Muat ulang halaman lalu coba lagi.';
                if (silent) this.step = 1;
            }
            this.checking = false;
        },

        get score() {
            if (!this.pw) return 0;
            let s = 0;
            if (this.pw.length >= 8) s++;
            if (/[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw)) s++;
            if (/\d/.test(this.pw)) s++;
            if (/[^A-Za-z0-9]/.test(this.pw) || this.pw.length >= 12) s++;
            return s;
        },
        get label() { return ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'][this.score]; },
        barClass(i) { return this.score < i ? 'bg-[#e6dfcf]' : ['', 'bg-red-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500'][this.score]; },
        get ready() { return this.email.includes('@') && this.pw.length >= 8 && this.pw === this.pw2; }
    };
}
</script>

<div class="min-h-screen grid lg:grid-cols-[1fr_1.05fr]" x-data="registerPage()">

    <!-- ===== Panel kiri: kartu pelapor yang terisi langsung ===== -->
    <section class="board relative hidden lg:flex flex-col justify-between p-10 xl:p-14 text-white overflow-hidden" data-parallax>
        <div class="relative flex items-center gap-3">
            <span class="brand-badge w-11 h-11 rounded-xl flex items-center justify-center"><x-icon name="logo" class="w-6 h-6" /></span>
            <div class="leading-tight">
                <p class="font-display text-2xl font-extrabold tracking-tight">D-Laporr</p>
                <p class="text-[10px] uppercase tracking-[.2em] text-sun-400">SMK Negeri 5 Surakarta</p>
            </div>
        </div>

        <div class="relative">
            <h1 class="font-display text-4xl xl:text-5xl font-extrabold leading-[1.05] max-w-md">
                Ini <span class="marker">kartu pelapor</span> kamu.
            </h1>
            <p class="mt-4 text-brand-200 max-w-sm">Ketik NIS dan namamu di sebelah. Kartunya terisi langsung, lalu distempel saat datamu cocok dengan data sekolah.</p>
        </div>

        <div class="relative flex justify-center py-4">
            <div class="idcard" data-depth="16">
                <div class="idcard-head"><span>SMK NEGERI 5 SURAKARTA</span><span>KARTU PELAPOR</span></div>
                <div class="p-5 flex gap-4">
                    <div class="w-20 h-24 rounded-lg bg-brand-900 grid place-items-center font-display text-4xl font-extrabold text-sun-500 shrink-0"
                         x-text="nama.trim() ? nama.trim().charAt(0).toUpperCase() : '?'"></div>
                    <div class="min-w-0 space-y-2.5">
                        <div><p class="idl">Nama</p><p class="idv" :class="nama.trim() ? '' : 'opacity-30'" x-text="nama.trim() || 'Nama lengkap kamu'"></p></div>
                        <div><p class="idl">NIS</p><p class="idv font-mono" :class="nis.trim() ? '' : 'opacity-30'" x-text="nis.trim() || '0000000000'"></p></div>
                        <div><p class="idl">Kelas</p><p class="idv" :class="verified ? '' : 'opacity-30'" x-text="verified ? kelas : 'diisi otomatis'"></p></div>
                    </div>
                </div>
                <div class="barcode mx-5 mb-4"></div>
                <div class="stamp" x-show="verified" x-cloak>TERVERIFIKASI</div>
            </div>
        </div>

        <p class="relative text-sm text-brand-200 flex items-start gap-2 max-w-md">
            <x-icon name="shield" class="w-5 h-5 text-sun-400 shrink-0 mt-0.5" />
            Hanya siswa yang NIS dan namanya ada di data resmi sekolah yang bisa membuat akun. Satu NIS untuk satu akun.
        </p>
    </section>

    <!-- ===== Panel kanan: wizard ===== -->
    <section class="flex items-center justify-center p-5 sm:p-10">
        <div class="w-full max-w-md pop">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 hover:text-brand-600 transition-colors mb-6">
                <x-icon name="arrow-left" class="w-4 h-4" /> Kembali ke Login
            </a>

            <h2 class="font-display text-4xl font-extrabold text-brand-900 leading-tight">Buat akun <span class="marker">siswa</span></h2>
            <p class="text-slate-600 mt-2 mb-6">Dua langkah singkat: cocokkan data sekolah, lalu atur akunmu.</p>

            <!-- Penunjuk langkah -->
            <ol class="flex items-center gap-3 mb-6" aria-label="Langkah pendaftaran">
                <li class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full grid place-items-center text-sm font-bold transition-colors" :class="step === 2 ? 'bg-emerald-500 text-white' : 'bg-brand-900 text-sun-500'">
                        <span x-show="step === 1">1</span><span x-show="step === 2" x-cloak><x-icon name="check" class="w-4 h-4" /></span>
                    </span>
                    <span class="text-sm font-semibold text-brand-900">Cek data</span>
                </li>
                <span class="flex-1 h-1 rounded-full bg-[#e6dfcf] overflow-hidden"><span class="block h-full bg-brand-900 transition-all duration-500" :style="'width:' + (step === 2 ? '100%' : '0%')"></span></span>
                <li class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full grid place-items-center text-sm font-bold transition-colors" :class="step === 2 ? 'bg-brand-900 text-sun-500' : 'bg-[#e6dfcf] text-slate-500'">2</span>
                    <span class="text-sm font-semibold" :class="step === 2 ? 'text-brand-900' : 'text-slate-400'">Atur akun</span>
                </li>
            </ol>

            @if ($errors->any())
                <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm swap" role="alert">
                    <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
                    <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Langkah 1 -->
                <div x-show="step === 1" class="space-y-4 swap">
                    <div class="field">
                        <input id="nis" name="nis" x-model="nis" placeholder=" " required inputmode="numeric" maxlength="30" autocomplete="off" autofocus @keydown.enter.prevent="verify()">
                        <label for="nis">NIS (seperti di kartu pelajar)</label>
                    </div>
                    <div class="field">
                        <input id="name" name="name" x-model="nama" placeholder=" " required maxlength="100" autocomplete="off" @keydown.enter.prevent="verify()">
                        <label for="name">Nama lengkap sesuai data sekolah</label>
                    </div>

                    <p x-show="msg" x-cloak class="swap flex items-start gap-2 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-3.5 py-2.5">
                        <x-icon name="warning" class="w-4 h-4 shrink-0 mt-0.5" /> <span x-text="msg"></span>
                    </p>

                    <button type="button" @click="verify()" :disabled="checking" class="btn-primary group w-full py-3.5 rounded-xl text-base inline-flex items-center justify-center gap-2 disabled:opacity-70">
                        <span x-text="checking ? 'Memeriksa data...' : 'Cek data saya'"></span>
                        <x-icon name="arrow-right" class="w-5 h-5 transition-transform group-hover:translate-x-1" />
                    </button>
                </div>

                <!-- Langkah 2 -->
                <div x-show="step === 2" x-cloak class="space-y-4 swap">
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-full bg-emerald-500 text-white grid place-items-center shrink-0"><x-icon name="check" class="w-5 h-5" /></span>
                        <div class="min-w-0">
                            <p class="font-bold text-emerald-900 truncate" x-text="nama"></p>
                            <p class="text-xs text-emerald-700" x-text="'NIS ' + nis + ' · ' + (kelas || '...')"></p>
                        </div>
                        <button type="button" @click="step = 1" class="ml-auto text-xs font-semibold text-emerald-800 underline underline-offset-2 hover:text-emerald-950">Ubah</button>
                    </div>

                    <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input id="email" type="email" name="email" x-model="email" placeholder=" " required autocomplete="email">
                        <label for="email">Email aktif</label>
                    </div>

                    <div>
                        <div class="field relative {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input id="password" name="password" x-model="pw" :type="show ? 'text' : 'password'" placeholder=" " required autocomplete="new-password" class="!pr-12">
                            <label for="password">Kata sandi</label>
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 text-slate-400 hover:text-brand-900 transition-colors"
                                    :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                <span x-show="!show"><x-icon name="eye" class="w-5 h-5" /></span>
                                <span x-show="show" x-cloak><x-icon name="eye-off" class="w-5 h-5" /></span>
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5 mt-2" aria-hidden="true">
                            <template x-for="i in 4" :key="i"><span class="h-1.5 flex-1 rounded-full transition-colors duration-300" :class="barClass(i)"></span></template>
                        </div>
                        <p class="text-xs mt-1 h-4 text-slate-500"><span x-show="pw" x-text="'Kekuatan: ' + label"></span><span x-show="!pw">Minimal 8 karakter, campurkan huruf besar-kecil dan angka.</span></p>
                    </div>

                    <div>
                        <div class="field">
                            <input id="password_confirmation" type="password" name="password_confirmation" x-model="pw2" placeholder=" " required autocomplete="new-password">
                            <label for="password_confirmation">Ulangi kata sandi</label>
                        </div>
                        <p class="text-xs mt-1 h-4" x-show="pw2" x-cloak :class="pw === pw2 ? 'text-emerald-600' : 'text-red-500'" x-text="pw === pw2 ? 'Kata sandi cocok' : 'Belum sama dengan kata sandi di atas'"></p>
                    </div>

                    <button type="submit" :disabled="!ready" class="btn-primary group w-full py-3.5 rounded-xl text-base inline-flex items-center justify-center gap-2 disabled:opacity-45 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0">
                        Buat akun <x-icon name="arrow-right" class="w-5 h-5 transition-transform group-hover:translate-x-1" />
                    </button>
                </div>
            </form>

            <p class="mt-7 text-sm text-center text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-brand-900 underline decoration-sun-500 decoration-[3px] underline-offset-4 hover:bg-sun-500 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </section>
</div>
</body>
</html>
