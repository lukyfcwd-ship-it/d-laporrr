<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        p.sub { margin-top: 0; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #1e3a8a; color: #fff; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background: #f8fafc; }
        .footer { margin-top: 24px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Rekapitulasi Pengaduan Sarana Sekolah — D-Laporr</h1>
    <p class="sub">SMK Negeri 5 Surakarta &middot; Periode: {{ $periode }} &middot; Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Judul</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Prioritas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengaduans as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->kode_tiket }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->kategori?->nama_kategori ?? $item->sarana?->nama_sarana ?? '-' }}</td>
                    <td>{{ $item->lokasi_kerusakan ?? '-' }}</td>
                    <td>{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                    <td>{{ ucfirst($item->prioritas) }}</td>
                    <td>{{ \App\Models\Pengaduan::statusLabel($item->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="9">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">Surakarta, {{ now()->translatedFormat('d F Y') }}<br><br><br>Admin / Petugas Sarana</p>
</body>
</html>
