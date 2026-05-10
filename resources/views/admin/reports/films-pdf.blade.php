<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Film - StreamVid</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #e50914; margin-bottom: 5px; }
        .meta { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a1a2e; color: white; padding: 10px 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Laporan Film - StreamVid</h1>
    <p class="meta">Tanggal: {{ date('d M Y H:i') }} | Total: {{ $data->count() }} film</p>
    <table>
        <thead>
            <tr><th>No</th><th>Judul</th><th>Genre</th><th>Tahun</th><th>Durasi</th><th>Views</th></tr>
        </thead>
        <tbody>
        @foreach($data as $i => $film)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $film->title }}</td>
                <td>{{ $film->genre_list }}</td>
                <td>{{ $film->release_year }}</td>
                <td>{{ $film->duration_formatted }}</td>
                <td>{{ number_format($film->views_count) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
