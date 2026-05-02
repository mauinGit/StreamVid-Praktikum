<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Users - StreamVid</title>
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
        .free { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <h1>📊 Laporan Users - StreamVid</h1>
    <p class="meta">Tanggal: {{ date('d M Y H:i') }} | Total: {{ $data->count() }} users</p>
    <table>
        <thead>
            <tr><th>No</th><th>Nama</th><th>Email</th><th>Status</th><th>Paket</th><th>Bergabung</th></tr>
        </thead>
        <tbody>
        @foreach($data as $i => $user)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge {{ $user->activeSubscription ? 'active' : 'free' }}">{{ $user->activeSubscription ? 'Active' : 'Free' }}</span></td>
                <td>{{ $user->activeSubscription ? ucfirst($user->activeSubscription->package) : '-' }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
