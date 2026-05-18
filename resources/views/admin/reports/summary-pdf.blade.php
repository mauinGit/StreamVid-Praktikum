<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Summary StreamVid</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #FF5C00; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #FF5C00; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .summary-grid { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-grid td { padding: 15px; text-align: center; border: 1px solid #ddd; background: #f9f9f9; width: 20%; }
        .summary-value { font-size: 18px; font-weight: bold; color: #FF5C00; margin-bottom: 5px; }
        .summary-label { font-size: 11px; text-transform: uppercase; color: #666; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        table.data-table th { background-color: #f4f4f4; color: #333; font-weight: bold; }
        table.data-table tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef08a; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STREAMVID - Laporan Summary</h1>
        <p>
            Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} 
            - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
            <br>
            Filter Status: {{ $status && $status !== 'all' ? ucfirst($status) : 'Semua Status' }}
            <br>
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <table class="summary-grid">
        <tr>
            <td>
                <div class="summary-value">{{ number_format($totalUsers) }}</div>
                <div class="summary-label">Total Users</div>
            </td>
            <td>
                <div class="summary-value">{{ number_format($totalFilms) }}</div>
                <div class="summary-label">Total Film</div>
            </td>
            <td>
                <div class="summary-value">{{ number_format($totalSubscribers) }}</div>
                <div class="summary-label">Subscriber Aktif</div>
            </td>
            <td>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="summary-label">Total Pemasukan</div>
            </td>
            <td>
                <div class="summary-value">{{ number_format($totalTransactions) }}</div>
                <div class="summary-label">Total Transaksi</div>
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:5px;">Rincian Transaksi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>User</th>
                <th>Paket</th>
                <th>Metode</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $t->invoice_id }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ ucfirst($t->subscription->package ?? '-') }}</td>
                    <td>{{ $t->method_label }}</td>
                    <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td>{{ $t->status_label }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
