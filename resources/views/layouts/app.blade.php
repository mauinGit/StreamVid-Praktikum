<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="StreamVid - Platform streaming film terbaik dengan koleksi film terlengkap">
    <title>{{ $title ?? 'StreamVid' }} - Streaming Film</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sv-bg-primary: #0a0a0a;
            --sv-bg-secondary: #141414;
            --sv-bg-card: #1a1a2e;
            --sv-bg-elevated: #1e1e30;
            --sv-accent: #FF5C00;
            --sv-accent-hover: #FB923C;
            --sv-accent-glow: rgba(229, 9, 20, 0.3);
            --sv-text-primary: #ffffff;
            --sv-text-secondary: #a3a3a3;
            --sv-text-muted: #737373;
            --sv-border: rgba(255, 255, 255, 0.08);
            --sv-glass: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--sv-bg-primary);
            color: var(--sv-text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .sv-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 48px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.3s ease;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
        }

        .sv-navbar.scrolled {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--sv-border);
        }

        .sv-navbar-brand {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--sv-accent);
            text-decoration: none;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .sv-navbar-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .sv-navbar-links a {
            color: var(--sv-text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
        }

        .sv-navbar-links a:hover,
        .sv-navbar-links a.active {
            color: var(--sv-text-primary);
        }

        .sv-navbar-links a.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--sv-accent);
            border-radius: 1px;
        }

        .sv-navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .sv-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            gap: 8px;
        }

        .sv-btn-primary {
            background: var(--sv-accent);
            color: white;
        }

        .sv-btn-primary:hover {
            background: var(--sv-accent-hover);
            box-shadow: 0 0 20px var(--sv-accent-glow);
            transform: translateY(-1px);
        }

        .sv-btn-outline {
            background: transparent;
            color: #FB923C;
            border: 1px solid #FF5C00;
        }

        .sv-btn-outline:hover {
            background: #FF5C00;
            border-color: #FF5C00;
            color: white;
        }

        .sv-btn-ghost {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            backdrop-filter: blur(10px);
        }

        .sv-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sv-btn-sm {
            padding: 6px 16px;
            font-size: 0.8rem;
        }

        .sv-btn-lg {
            padding: 14px 32px;
            font-size: 1rem;
        }

        /* ===== DROPDOWN ===== */
        .sv-dropdown {
            position: relative;
        }

        .sv-dropdown-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--sv-text-secondary);
            font-size: 0.9rem;
            background: none;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .sv-dropdown-trigger:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .sv-dropdown-avatar {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--sv-accent), #ff6b6b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
        }

        .sv-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 200px;
            background: var(--sv-bg-elevated);
            border: 1px solid var(--sv-border);
            border-radius: 8px;
            padding: 8px;
            display: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .sv-dropdown-menu.open {
            display: block;
        }

        .sv-dropdown-menu a,
        .sv-dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            color: var(--sv-text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            border-radius: 6px;
            transition: all 0.15s;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .sv-dropdown-menu a:hover,
        .sv-dropdown-menu button:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .sv-dropdown-divider {
            height: 1px;
            background: var(--sv-border);
            margin: 6px 0;
        }

        /* ===== FILM CARDS ===== */
        .sv-film-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        .sv-film-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            aspect-ratio: 2/3;
        }

        .sv-film-card:hover {
            transform: scale(1.08);
            z-index: 10;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6);
        }

        .sv-film-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sv-film-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 14px 14px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.95));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sv-film-card:hover .sv-film-card-overlay {
            opacity: 1;
        }

        .sv-film-card-title {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .sv-film-card-meta {
            font-size: 0.7rem;
            color: var(--sv-text-muted);
        }

        .sv-film-card-genre {
            display: inline-block;
            font-size: 0.65rem;
            padding: 2px 6px;
            background: rgba(229, 9, 20, 0.3);
            color: #ff6b6b;
            border-radius: 3px;
            margin-top: 4px;
        }

        /* ===== SECTIONS ===== */
        .sv-section {
            padding: 0 48px;
            margin-bottom: 48px;
        }

        .sv-section-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sv-section-title .sv-accent-bar {
            width: 4px;
            height: 24px;
            background: var(--sv-accent);
            border-radius: 2px;
        }

        /* ===== HORIZONTAL SCROLL ===== */
        .sv-scroll-row {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 10px;
        }

        .sv-scroll-row::-webkit-scrollbar {
            display: none;
        }

        .sv-scroll-row > * {
            flex: 0 0 calc((100% - 60px) / 6); /* Show exactly 6 items (5 gaps of 12px = 60px) */
        }
        .sv-scroll-row .sv-film-card {
            width: 100%;
            height: 100%;
            aspect-ratio: 2/3;
        }

        /* ===== HERO ===== */
        .sv-hero {
            position: relative;
            height: 85vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            padding: 0 48px;
            overflow: hidden;
        }

        .sv-hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .sv-hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sv-hero-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(to right, rgba(10, 10, 10, 0.9) 0%, rgba(10, 10, 10, 0.4) 50%, transparent 100%),
                linear-gradient(to top, rgba(10, 10, 10, 1) 0%, rgba(10, 10, 10, 0.3) 40%, transparent 70%);
        }

        .sv-hero-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .sv-hero-title {
            font-size: 3.2rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .sv-hero-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            font-size: 0.9rem;
            color: var(--sv-text-secondary);
        }

        .sv-hero-meta .sv-badge {
            background: var(--sv-accent);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .sv-hero-description {
            font-size: 1rem;
            color: var(--sv-text-secondary);
            line-height: 1.6;
            margin-bottom: 24px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sv-hero-actions {
            display: flex;
            gap: 12px;
        }

        /* ===== FOOTER ===== */
        .sv-footer {
            background: var(--sv-bg-secondary);
            border-top: 1px solid var(--sv-border);
            padding: 48px 48px 24px;
            margin-top: 80px;
        }

        .sv-footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .sv-footer-brand {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--sv-accent);
            margin-bottom: 12px;
        }

        .sv-footer-desc {
            color: var(--sv-text-muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .sv-footer-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            color: var(--sv-text-secondary);
        }

        .sv-footer-links {
            list-style: none;
        }

        .sv-footer-links li {
            margin-bottom: 10px;
        }

        .sv-footer-links a {
            color: var(--sv-text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .sv-footer-links a:hover {
            color: white;
        }

        .sv-footer-bottom {
            border-top: 1px solid var(--sv-border);
            padding-top: 24px;
            text-align: center;
            color: var(--sv-text-muted);
            font-size: 0.8rem;
        }

        /* ===== FLASH MESSAGES ===== */
        .sv-flash {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            animation: slideIn 0.3s ease, fadeOut 0.3s ease 3s forwards;
            max-width: 400px;
        }

        .sv-flash-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }

        .sv-flash-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* ===== PAGINATION ===== */
        .sv-pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 32px;
        }

        .sv-pagination a,
        .sv-pagination span {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sv-pagination a {
            color: var(--sv-text-secondary);
            background: var(--sv-bg-card);
            border: 1px solid var(--sv-border);
        }

        .sv-pagination a:hover {
            background: var(--sv-accent);
            color: white;
            border-color: var(--sv-accent);
        }

        .sv-pagination .active span {
            background: var(--sv-accent);
            color: white;
        }

        .sv-pagination .disabled span {
            color: var(--sv-text-muted);
            background: var(--sv-bg-card);
            cursor: not-allowed;
        }

        /* ===== FORM INPUTS ===== */
        .sv-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--sv-bg-card);
            border: 1px solid var(--sv-border);
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }

        .sv-input:focus {
            outline: none;
            border-color: var(--sv-accent);
            box-shadow: 0 0 0 3px var(--sv-accent-glow);
        }

        .sv-input::placeholder {
            color: var(--sv-text-muted);
        }

        .sv-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--sv-text-secondary);
        }

        .sv-form-group {
            margin-bottom: 20px;
        }

        .sv-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        select.sv-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23a3a3a3' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .mobile-only {
            display: none !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .mobile-only {
                display: block !important;
            }
            .sv-film-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }

            .sv-section {
                padding: 0 16px;
            }

            .sv-navbar {
                padding: 0 16px;
            }

            .sv-navbar-links {
                display: none;
            }
            .mobile-hide {
                display: none !important;
            }
            .sv-footer-logo {
                width: 150px !important;
                height: 38px !important;
            }

            /* Hero adjustments */
            .sv-hero {
                height: 40vh;
                min-height: 250px;
                padding: 0 16px 40px;
            }

            .sv-hero-title {
                font-size: 1.4rem;
                margin-bottom: 8px;
            }

            .sv-hero-meta {
                font-size: 0.7rem;
                margin-bottom: 8px;
            }

            .sv-hero-description {
                font-size: 0.8rem;
                margin-bottom: 16px;
                -webkit-line-clamp: 2;
            }

            .sv-hero-actions .sv-btn {
                font-size: 0.75rem;
                padding: 6px 12px;
            }

            /* Scroll Row and Grids (3 items) */
            .sv-scroll-row > * {
                flex: 0 0 calc((100% - 24px) / 3);
            }
            .sv-all-films-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 12px !important;
            }

            /* Film card font reduction */
            .sv-film-card-title {
                font-size: 0.75rem;
            }
            .sv-film-card-meta {
                font-size: 0.65rem;
            }

            /* Footer */
            .sv-footer {
                padding: 32px 16px 16px;
                margin-top: 40px;
            }

            .sv-footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            
            .sv-footer-brand {
                font-size: 1.2rem;
            }
            .sv-footer-desc, .sv-footer-links a {
                font-size: 0.75rem;
            }

            /* Profile Page */
            .sv-profile-grid-row1, .sv-profile-grid-row2 {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }
        }

        /* ===== UTILITY ===== */
        .sv-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .sv-mt-nav {
            margin-top: 68px;
        }

        .sv-text-accent {
            color: var(--sv-accent);
        }

        .sv-text-muted {
            color: var(--sv-text-muted);
        }

        /* ===== LOADING SPINNER ===== */
        .sv-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--sv-border);
            border-top-color: var(--sv-accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .sv-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 10, 10, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            gap: 20px;
        }
    </style>
</head>

<body>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="sv-flash sv-flash-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="sv-flash sv-flash-error">✕ {{ session('error') }}</div>
    @endif

    {{-- Navbar --}}
    <nav class="sv-navbar" id="navbar">
        <div style="display:flex;align-items:center;gap:40px;">
            <a href="{{ route('home') }}" class="sv-navbar-brand">
                <img src="{{ asset('img/logo.png') }}" alt="StreamVid" style="width: 160px; height: 40px;">
            </a>
            <ul class="sv-navbar-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('films.index') }}"
                        class="{{ request()->routeIs('films.*') ? 'active' : '' }}">Films</a></li>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <li><a href="{{ route('collection.index') }}"
                                class="{{ request()->routeIs('collection.*') || request()->routeIs('mylist.*') || request()->routeIs('history.*') ? 'active' : '' }}">Koleksi Saya</a></li>
                    @endif
                @endauth
            </ul>
        </div>

        <div class="sv-navbar-right">
            @guest
                <a href="{{ route('login') }}" class="sv-btn sv-btn-ghost sv-btn-sm">Login</a>
                <a href="{{ route('register') }}" class="sv-btn sv-btn-primary sv-btn-sm">Sign Up</a>
            @else
                <div class="sv-dropdown" id="user-dropdown">
                    <button class="sv-dropdown-trigger" onclick="toggleDropdown(event)">
                        <div class="sv-dropdown-avatar">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <svg width="10" height="6" viewBox="0 0 10 6" fill="currentColor">
                            <path d="M1 1l4 4 4-4" />
                        </svg>
                    </button>
                    <div class="sv-dropdown-menu">
                        <a href="{{ route('collection.index') }}" class="mobile-only">📂 Koleksi Saya</a>
                        <div class="sv-dropdown-divider mobile-only"></div>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
                            <div class="sv-dropdown-divider"></div>
                        @else
                            @if(!auth()->user()->hasActiveSubscription())
                                <a href="{{ route('subscription.index') }}">💳 Subscription</a>
                            @endif
                            <a href="{{ route('collection.index') }}">📂 Koleksi Saya</a>
                            <div class="sv-dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}">👤 Profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">🚪 Logout</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="sv-footer">
        <div class="sv-footer-grid">
            <div>
                <img src="{{ asset('img/logo.png') }}" alt="StreamVid" class="sv-footer-logo" style="width: 240px; height: 60px;">
                <p class="sv-footer-desc mobile-hide">Platform streaming film terbaik dengan koleksi film terlengkap. Nikmati ribuan
                    film berkualitas tinggi kapan saja, di mana saja.</p>
            </div>
            <div>
                <h4 class="sv-footer-title mobile-hide">Navigation</h4>
                <ul class="sv-footer-links" style="display:flex;gap:16px;flex-wrap:wrap;">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('films.index') }}">Film</a></li>
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <li><a href="{{ route('collection.index') }}">Koleksi Saya</a></li>
                        @endif
                    @endauth
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="mobile-hide">
                <h4 class="sv-footer-title">Genre</h4>
                <ul class="sv-footer-links">
                    <li><a href="{{ route('films.index', ['genre' => 'Action']) }}">Action</a></li>
                    <li><a href="{{ route('films.index', ['genre' => 'Drama']) }}">Drama</a></li>
                    <li><a href="{{ route('films.index', ['genre' => 'Comedy']) }}">Comedy</a></li>
                    <li><a href="{{ route('films.index', ['genre' => 'Horror']) }}">Horror</a></li>
                </ul>
            </div>
            <div class="mobile-hide">
                <h4 class="sv-footer-title">Support</h4>
                <ul class="sv-footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Privacy</a></li>
                </ul>
            </div>
        </div>
        <div class="sv-footer-bottom">
            © {{ date('Y') }} StreamVid. All rights reserved.
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Auto-dismiss flash messages
        setTimeout(() => {
            document.querySelectorAll('.sv-flash').forEach(el => el.remove());
        }, 4000);

        // Click-based dropdown toggle
        function toggleDropdown(e) {
            e.stopPropagation();
            const menu = document.querySelector('#user-dropdown .sv-dropdown-menu');
            if (menu) menu.classList.toggle('open');
        }
        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                const menu = dropdown.querySelector('.sv-dropdown-menu');
                if (menu) menu.classList.remove('open');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>