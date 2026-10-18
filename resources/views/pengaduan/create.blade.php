@extends('layouts.app')
@section('title', 'Buat Pengaduan')

@section('content')
<div class="card p-4 md:p-6 max-w-2xl">
    <h2 class="font-semibold text-slate-900 mb-4">Form Pengaduan Kerusakan Sarana</h2>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm" role="alert">
            @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="judul" class="block text-sm font-medium text-slate-700 mb-1">Judul Pengaduan</label>
            <input id="judul" name="judul" value="{{ old('judul') }}" required maxlength="150"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx"
                   placeholder="Contoh: Kipas angin kelas mati">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="kategori_id" class="block text-sm font-medium text-slate-700 mb-1">Kategori Sarana (opsional)</label>
                <select id="kategori_id" name="kategori_id" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx">
                    <option value="">-- Tidak diketahui --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Pilih kategori yang paling sesuai, mis. Elektronik atau Alat Olahraga.</p>
            </div>
            <div>
                <label for="lokasi_kerusakan" class="block text-sm font-medium text-slate-700 mb-1">Lokasi Kerusakan</label>
                <input id="lokasi_kerusakan" name="lokasi_kerusakan" value="{{ old('lokasi_kerusakan') }}" required maxlength="150"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx"
                       placeholder="Contoh: LAB RPL">
            </div>
        </div>

        <div>
            <label for="tanggal_kejadian" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kejadian</label>
            <input id="tanggal_kejadian" type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}"
                   class="w-full sm:w-1/2 rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx">
        </div>

        <div>
            <label for="prioritas" class="block text-sm font-medium text-slate-700 mb-1">Tingkat Prioritas</label>
            <select id="prioritas" name="prioritas" required class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx">
                <option value="rendah" @selected(old('prioritas') == 'rendah')>Rendah</option>
                <option value="sedang" @selected(old('prioritas', 'sedang') == 'sedang')>Sedang</option>
                <option value="tinggi" @selected(old('prioritas') == 'tinggi')>Tinggi (mengganggu aktivitas)</option>
            </select>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Kerusakan</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" required minlength="10"
                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base input-fx"
                      placeholder="Jelaskan kondisi kerusakan secara rinci...">{{ old('deskripsi') }}</textarea>
        </div>

        <div>
            <label for="bukti_foto" class="block text-sm font-medium text-slate-700 mb-1">Bukti Foto (opsional, maks 2MB)</label>
            <input id="bukti_foto" type="file" name="bukti_foto" accept="image/*"
                   class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg">Kirim Pengaduan</button>
            <a href="{{ route('pengaduan.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
