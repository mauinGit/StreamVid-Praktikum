<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ $title ?? 'Dashboard' }} | StreamVid</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sv-bg-primary: #0a0a0a;
            --sv-bg-secondary: #141414;
            --sv-bg-card: #1a1a2e;
            --sv-bg-elevated: #1e1e30;
            --sv-accent: #FF5C00;
            --sv-accent-hover: #FB923C;
            --sv-text-primary: #ffffff;
            --sv-text-secondary: #a3a3a3;
            --sv-text-muted: #737373;
            --sv-border: rgba(255,255,255,0.08);
        }
        * { margin:0;padding:0;box-sizing:border-box; }
        body { font-family:'Inter',sans-serif;background:var(--sv-bg-primary);color:var(--sv-text-primary);min-height:100vh;display:flex; }

        .admin-sidebar { width:260px;background:var(--sv-bg-secondary);border-right:1px solid var(--sv-border);padding:24px 0;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;z-index:100; }
        .admin-sidebar-brand { padding:0 24px 28px;font-size:1.4rem;font-weight:900;color:var(--sv-accent);display:flex;align-items:center;gap:10px; }
        .admin-sidebar-brand span { font-size:0.7rem;color:var(--sv-text-muted);font-weight:500;background:rgba(229,9,20,0.15);padding:2px 8px;border-radius:4px; }
        .admin-nav { list-style:none; }
        .admin-nav li a { display:flex;align-items:center;gap:12px;padding:12px 24px;color:var(--sv-text-secondary);text-decoration:none;font-size:0.9rem;font-weight:500;transition:all 0.2s;border-left:3px solid transparent; }
        .admin-nav li a:hover { background:rgba(255,255,255,0.03);color:white; }
        .admin-nav li a.active { background:rgba(229,9,20,0.08);color:white;border-left-color:var(--sv-accent); }
        .admin-nav-divider { height:1px;background:var(--sv-border);margin:12px 24px; }

        .admin-content { flex:1;margin-left:260px;padding:32px;min-height:100vh; }
        .admin-header { display:flex;justify-content:space-between;align-items:center;margin-bottom:32px; }
        .admin-header h1 { font-size:1.6rem;font-weight:800; }
        .admin-header-meta { display:flex;align-items:center;gap:16px;color:var(--sv-text-muted);font-size:0.85rem; }

        .admin-card { background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:12px;padding:24px; }
        .admin-stat-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:32px; }
        .admin-stat { background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:12px;padding:24px;transition:all 0.2s; }
        .admin-stat:hover { border-color:var(--sv-accent);transform:translateY(-2px); }
        .admin-stat-icon { font-size:1.8rem;margin-bottom:12px; }
        .admin-stat-value { font-size:2rem;font-weight:900;margin-bottom:4px; }
        .admin-stat-label { color:var(--sv-text-muted);font-size:0.85rem; }

        .admin-table { width:100%;border-collapse:collapse; }
        .admin-table th { text-align:left;padding:14px 16px;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--sv-text-muted);border-bottom:1px solid var(--sv-border); }
        .admin-table td { padding:14px 16px;border-bottom:1px solid var(--sv-border);font-size:0.9rem;color:var(--sv-text-secondary); }
        .admin-table tr:hover td { background:rgba(255,255,255,0.02); }

        .admin-badge { padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600; }
        .admin-badge-success { background:rgba(34,197,94,0.15);color:#22c55e; }
        .admin-badge-warning { background:rgba(234,179,8,0.15);color:#eab308; }
        .admin-badge-danger { background:rgba(239,68,68,0.15);color:#ef4444; }
        .admin-badge-info { background:rgba(59,130,246,0.15);color:#3b82f6; }

        .sv-btn { display:inline-flex;align-items:center;justify-content:center;padding:10px 24px;border-radius:6px;font-size:0.9rem;font-weight:600;text-decoration:none;transition:all 0.2s;cursor:pointer;border:none;gap:8px; }
        .sv-btn-primary { background:var(--sv-accent);color:white; }
        .sv-btn-primary:hover { background:var(--sv-accent-hover); }
        .sv-btn-outline { background:transparent;color:white;border:1px solid rgba(255,255,255,0.2); }
        .sv-btn-outline:hover { background:rgba(255,255,255,0.05); }
        .sv-btn-sm { padding:6px 14px;font-size:0.8rem; }
        .sv-btn-danger { background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3); }
        .sv-btn-danger:hover { background:rgba(239,68,68,0.25); }

        .sv-input { width:100%;padding:10px 14px;background:var(--sv-bg-primary);border:1px solid var(--sv-border);border-radius:8px;color:white;font-size:0.9rem;font-family:'Inter',sans-serif; }
        .sv-input:focus { outline:none;border-color:var(--sv-accent); }
        .sv-label { display:block;font-size:0.85rem;font-weight:600;margin-bottom:6px;color:var(--sv-text-secondary); }
        .sv-form-group { margin-bottom:20px; }

        .sv-flash { position:fixed;top:20px;right:20px;z-index:9999;padding:14px 24px;border-radius:8px;font-size:0.9rem;font-weight:500;animation:slideIn 0.3s ease; }
        .sv-flash-success { background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#22c55e; }
        @keyframes slideIn { from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1} }

        .sv-pagination { display:flex;justify-content:center;gap:6px;margin-top:24px; }
        .sv-pagination a,.sv-pagination span { padding:8px 14px;border-radius:6px;font-size:0.85rem;text-decoration:none; }
        .sv-pagination a { color:var(--sv-text-secondary);background:var(--sv-bg-card);border:1px solid var(--sv-border); }
        .sv-pagination a:hover { background:var(--sv-accent);color:white; }
        .sv-pagination .active span { background:var(--sv-accent);color:white; }
    </style>
</head>
<body>
    @if(session('success'))
        <div class="sv-flash sv-flash-success">✓ {{ session('success') }}</div>
    @endif

    <aside class="admin-sidebar">
        <img src="{{ asset('img/logo.png') }}" alt="StreamVid" style="width: 200px; height: 50px; margin-left: 24px; margin-bottom: 16px;">
        <ul class="admin-nav">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a></li>
            <li><a href="{{ route('admin.films.index') }}" class="{{ request()->routeIs('admin.films.*') ? 'active' : '' }}">🎬 Film</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Users</a></li>
            <li><a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">💳 Subscriptions</a></li>
            <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">📄 Reports</a></li>
            <div class="admin-nav-divider"></div>
            <li><a href="{{ route('home') }}">🏠 Ke Website</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();return false;">🚪 Logout</a>
                </form>
            </li>
        </ul>
    </aside>

    <main class="admin-content">
        @yield('admin-content')
    </main>

    <script>setTimeout(()=>{document.querySelectorAll('.sv-flash').forEach(el=>el.remove())},4000);</script>
    @stack('scripts')
</body>
</html>
