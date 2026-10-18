<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        p.sub { margin-top: 0; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 6px 4px; vertical-align: top; }
        td.label { width: 160px; color: #475569; }
        .box { border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; margin-top: 6px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; background: #e2e8f0; font-size: 11px; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <h1>Tiket Pengaduan Sarana — D-Laporr</h1>
    <p class="sub">SMK Negeri 5 Surakarta &middot; Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</p>

    <table>
        <tr><td class="label">Kode Tiket</td><td>: {{ $pengaduan->kode_tiket }}</td></tr>
        <tr><td class="label">Judul</td><td>: {{ $pengaduan->judul }}</td></tr>
        <tr><td class="label">Pelapor</td><td>: {{ $pengaduan->user->name }} ({{ $pengaduan->user->kelas ?? $pengaduan->user->nis_nip }})</td></tr>
        <tr><td class="label">Kategori Sarana</td><td>: {{ $pengaduan->kategori?->nama_kategori ?? $pengaduan->sarana?->kategori?->nama_kategori ?? '-' }}</td></tr>
        <tr><td class="label">Lokasi Kerusakan</td><td>: {{ $pengaduan->lokasi_kerusakan ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal Kejadian</td><td>: {{ $pengaduan->tanggal_kejadian->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="label">Prioritas</td><td>: {{ ucfirst($pengaduan->prioritas) }}</td></tr>
        <tr><td class="label">Status</td><td>: <span class="badge">{{ \App\Models\Pengaduan::statusLabel($pengaduan->status) }}</span></td></tr>
    </table>

    <p><strong>Deskripsi Kerusakan</strong></p>
    <div class="box">{{ $pengaduan->deskripsi }}</div>

    @if ($pengaduan->catatan_admin)
        <p><strong>Catatan Petugas</strong></p>
        <div class="box">
            {{ $pengaduan->catatan_admin }}
            @if ($pengaduan->verifikator)
                <br><br><em>oleh {{ $pengaduan->verifikator->name }} — {{ $pengaduan->tanggal_verifikasi?->translatedFormat('d M Y H:i') }}</em>
            @endif
        </div>
    @endif

    <p class="footer">Surakarta, {{ now()->translatedFormat('d F Y') }}<br><br><br>Admin / Petugas Sarana</p>
</body>
</html>
