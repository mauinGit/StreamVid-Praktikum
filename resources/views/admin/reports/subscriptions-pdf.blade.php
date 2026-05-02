<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Subscriptions - StreamVid</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #e50914; margin-bottom: 5px; }
        .meta { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a1a2e; color: white; padding: 10px 8px; text-align: left; font-size: 11px; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .active { background: #dcfce7; color: #166534; }
        .expired { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <h1>💳 Laporan Subscriptions - StreamVid</h1>
    <p class="meta">Tanggal: {{ date('d M Y H:i') }} | Total: {{ $data->count() }} subscriptions</p>
    <table>
        <thead>
            <tr><th>No</th><th>User</th><th>Paket</th><th>Mulai</th><th>Berakhir</th><th>Status</th><th>Pembayaran</th></tr>
        </thead>
        <tbody>
        @foreach($data as $i => $sub)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $sub->user->name }}</td>
                <td>{{ ucfirst($sub->package) }}</td>
                <td>{{ $sub->start_date->format('d M Y') }}</td>
                <td>{{ $sub->end_date->format('d M Y') }}</td>
                <td><span class="badge {{ $sub->status }}">{{ ucfirst($sub->status) }}</span></td>
                <td>{{ $sub->payment ? 'Rp ' . number_format($sub->payment->amount, 0, ',', '.') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
