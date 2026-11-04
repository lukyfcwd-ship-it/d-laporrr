@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
@php
    $isSiswa = $user->isSiswa();
    $dataSekolah = $isSiswa
        ? [['Nama lengkap', $user->name], ['NIS', $user->nis_nip], ['Kelas', $user->kelas]]
        : ($user->nis_nip ? [['NIP', $user->nis_nip]] : []);
@endphp

<script>
function passwordMeter() {
    return {
        cur: '', pw: '', pw2: '', show: false,
        get score() {
            if (!this.pw) return 0;
            var s = 0;
            if (this.pw.length >= 8) s++;
            if (/[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw)) s++;
            if (/\d/.test(this.pw)) s++;
            if (/[^A-Za-z0-9]/.test(this.pw) || this.pw.length >= 12) s++;
            return s;
        },
        get label() { return ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'][this.score]; },
        get ready() { return this.cur.length > 0 && this.pw.length >= 8 && this.pw === this.pw2; },
        barClass(i) {
            if (this.score < i) return 'bg-[#e6dfcf]';
            return ['', 'bg-red-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500'][this.score];
        }
    };
}
</script>

@if ($errors->any())
    <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm" role="alert">
        <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
        <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    </div>
@endif

<div class="space-y-5">
    <!-- ===== Kartu identitas ===== -->
    <section class="card overflow-hidden"
             x-data="{ preview: null,
                       pick(e) { const f = e.target.files[0]; if (f) this.preview = URL.createObjectURL(f); },
                       cancel() { this.preview = null; this.$refs.file.value = ''; } }">
        <div class="relative h-32 sm:h-40 bg-brand-900 overflow-hidden"
             style="background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size: 28px 28px;">
            <div class="absolute -right-10 -top-10 w-56 h-56 rounded-3xl rotate-12 opacity-25"
                 style="background: repeating-linear-gradient(135deg, #FFC933 0 14px, transparent 14px 30px);"></div>
            <p class="absolute left-5 sm:left-8 top-4 text-[11px] font-bold uppercase tracking-[.2em] text-sun-400">{{ $isSiswa ? 'Kartu Pelapor' : 'Petugas Sarana' }}</p>
            <p class="absolute right-5 sm:right-8 bottom-3 font-mono text-xs text-brand-300 hidden sm:block">D-LAPORR / {{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="px-5 sm:px-8 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 sm:gap-6 -mt-14 sm:-mt-16">
                <!-- Foto profil -->
                <form id="form-foto" method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="relative shrink-0 w-28 h-28 sm:w-32 sm:h-32">
                    @csrf
                    <div class="w-full h-full rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-brand-900">
                        <template x-if="preview"><img :src="preview" alt="Pratinjau foto baru" class="w-full h-full object-cover"></template>
                        <template x-if="!preview"><span class="block w-full h-full"><x-avatar :user="$user" class="w-full h-full text-5xl" /></span></template>
                    </div>
                    @if ($isSiswa)
                        <input type="file" name="foto" x-ref="file" accept="image/png,image/jpeg,image/webp" class="sr-only" @change="pick($event)">
                        <button type="button" @click="$refs.file.click()" aria-label="Pilih foto profil baru"
                                class="absolute bottom-1 right-1 w-10 h-10 rounded-full bg-sun-500 text-brand-900 ring-4 ring-white flex items-center justify-center shadow-md hover:scale-110 active:scale-95 transition">
                            <x-icon name="camera" class="w-5 h-5" />
                        </button>
                    @endif
                </form>

                <!-- Nama & keterangan -->
                <div class="flex-1 min-w-0 sm:pb-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-900 break-words">{{ $user->name }}</h2>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sun-500 text-brand-900">{{ $isSiswa ? 'Siswa' : 'Admin / Petugas' }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5 break-all">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-2 mt-3 text-xs font-semibold">
                        @if ($isSiswa && $user->kelas)<span class="px-2.5 py-1 rounded-lg bg-brand-50 text-brand-800 border border-brand-100">{{ $user->kelas }}</span>@endif
                        @if ($user->nis_nip)<span class="px-2.5 py-1 rounded-lg bg-brand-50 text-brand-800 border border-brand-100 font-mono">{{ $isSiswa ? 'NIS' : 'NIP' }} {{ $user->nis_nip }}</span>@endif
                        <span class="px-2.5 py-1 rounded-lg bg-[#faf7ef] text-slate-600 border border-[#e6dfcf]">Anggota sejak {{ $user->created_at?->translatedFormat('F Y') ?? '-' }}</span>
                    </div>
                </div>

                <!-- Statistik singkat -->
                <div class="flex gap-3 sm:pb-1">
                    @foreach ($stats as $label => $val)
                        <div class="rounded-xl border border-[#e6dfcf] bg-[#faf7ef] px-4 py-2.5 text-center min-w-[88px]">
                            <p class="font-display text-2xl font-extrabold text-brand-900" data-count="{{ $val }}">{{ $val }}</p>
                            <p class="text-[11px] font-semibold text-slate-500 leading-tight">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($isSiswa)
                <!-- Aksi foto -->
                <div class="mt-5 pt-4 border-t border-dashed border-[#e6dfcf] flex flex-wrap items-center gap-3">
                    <template x-if="preview">
                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            <button type="submit" form="form-foto" class="btn-primary px-4 py-2 rounded-xl text-sm inline-flex items-center gap-1.5"><x-icon name="check" class="w-4 h-4" /> Simpan Foto</button>
                            <button type="button" @click="cancel()" class="px-4 py-2 rounded-xl text-sm font-semibold border border-[#d6cfbd] text-slate-600 hover:bg-white transition-colors">Batal</button>
                            <span class="text-xs text-slate-500">Pratinjau — foto belum disimpan.</span>
                        </div>
                    </template>
                    <template x-if="!preview">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                            <button type="button" @click="$refs.file.click()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 hover:text-brand-600 transition-colors">
                                <x-icon name="camera" class="w-4 h-4" /> {{ $user->foto_profil ? 'Ganti foto profil' : 'Pasang foto profil' }}
                            </button>
                            <span class="text-xs text-slate-500">JPG, PNG, atau WEBP &middot; maksimal 2 MB</span>
                        </div>
                    </template>
                    @if ($user->foto_profil)
                        <form method="POST" action="{{ route('profile.photo.destroy') }}" class="ml-auto" onsubmit="return confirm('Hapus foto profil?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-500 hover:text-red-700 transition-colors"><x-icon name="trash" class="w-4 h-4" /> Hapus foto</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <div class="grid lg:grid-cols-5 gap-5">
        <!-- ===== Data diri ===== -->
        <section class="card p-5 sm:p-6 lg:col-span-3">
            <div class="flex items-center gap-3 mb-5">
                <span class="ico ico-ink w-11 h-11 rounded-xl shrink-0"><x-icon name="user" class="w-5 h-5" /></span>
                <div>
                    <h3 class="font-bold text-lg text-brand-900 leading-tight">Data Diri</h3>
                    <p class="text-xs text-slate-500">Informasi akun dan cara menghubungi kamu.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PUT')

                @if ($isSiswa)
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach ($dataSekolah as [$label, $value])
                            <div class="rounded-xl border border-dashed border-[#d6cfbd] bg-[#faf7ef] px-4 py-2.5 flex items-center justify-between gap-3 {{ $loop->first ? 'sm:col-span-2' : '' }}">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-brand-700">{{ $label }}</p>
                                    <p class="font-semibold text-brand-900 truncate">{{ $value ?: '-' }}</p>
                                </div>
                                <x-icon name="lock" class="w-4 h-4 text-slate-400 shrink-0" />
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500 -mt-1">Nama, NIS, dan kelas diambil dari data resmi sekolah sehingga tidak bisa diubah sendiri. Jika ada yang keliru, hubungi admin.</p>
                @else
                    <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" placeholder=" " required maxlength="100">
                        <label for="name">Nama lengkap</label>
                    </div>
                @endif

                <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" placeholder=" " required maxlength="150">
                    <label for="email">Email</label>
                </div>
                <div class="field {{ $errors->has('no_hp') ? 'has-error' : '' }}">
                    <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder=" " maxlength="20">
                    <label for="no_hp">No. HP (opsional)</label>
                </div>

                <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-sm">Simpan Perubahan</button>
            </form>
        </section>

        <!-- ===== Keamanan ===== -->
        <section class="card p-5 sm:p-6 lg:col-span-2" x-data="passwordMeter()">
            <div class="flex items-center gap-3 mb-5">
                <span class="ico ico-ink w-11 h-11 rounded-xl shrink-0"><x-icon name="shield" class="w-5 h-5" /></span>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-brand-900 leading-tight">Keamanan</h3>
                    <p class="text-xs text-slate-500">Ubah kata sandi akunmu.</p>
                </div>
                <button type="button" @click="show = !show" class="text-xs font-semibold text-brand-700 hover:text-brand-500 inline-flex items-center gap-1 transition-colors">
                    <span x-show="!show"><x-icon name="eye" class="w-4 h-4" /></span>
                    <span x-show="show" x-cloak><x-icon name="eye-off" class="w-4 h-4" /></span>
                    <span x-text="show ? 'Sembunyikan' : 'Tampilkan'"></span>
                </button>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf @method('PUT')
                <div class="field {{ $errors->has('current_password') ? 'has-error' : '' }}">
                    <input id="current_password" name="current_password" x-model="cur" :type="show ? 'text' : 'password'" placeholder=" " required autocomplete="current-password">
                    <label for="current_password">Kata sandi saat ini</label>
                </div>

                <div>
                    <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                        <input id="password" name="password" x-model="pw" :type="show ? 'text' : 'password'" placeholder=" " required autocomplete="new-password">
                        <label for="password">Kata sandi baru</label>
                    </div>
                    <div class="flex items-center gap-1.5 mt-2" aria-hidden="true">
                        <template x-for="i in 4" :key="i"><span class="h-1.5 flex-1 rounded-full transition-colors duration-300" :class="barClass(i)"></span></template>
                    </div>
                    <p class="text-xs mt-1 h-4 text-slate-500"><span x-show="pw" x-text="'Kekuatan: ' + label"></span><span x-show="!pw">Minimal 8 karakter, campurkan huruf besar-kecil dan angka.</span></p>
                </div>

                <div>
                    <div class="field">
                        <input id="password_confirmation" name="password_confirmation" x-model="pw2" :type="show ? 'text' : 'password'" placeholder=" " required autocomplete="new-password">
                        <label for="password_confirmation">Ulangi kata sandi baru</label>
                    </div>
                    <p class="text-xs mt-1 h-4" x-show="pw2" x-cloak :class="pw === pw2 ? 'text-emerald-600' : 'text-red-500'" x-text="pw === pw2 ? 'Kata sandi cocok' : 'Belum sama dengan kata sandi baru'"></p>
                </div>

                <button type="submit" :disabled="!ready" class="btn-primary px-5 py-2.5 rounded-xl text-sm disabled:opacity-45 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0">Perbarui Kata Sandi</button>
            </form>
        </section>
    </div>
</div>
@endsection
