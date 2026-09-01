<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard Admin - Retribusi Kudus')
    </title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 255px;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            background: #0f172a;
            color: white;
            padding: 20px 14px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        /* Custom Scrollbar untuk sidebar jika diperlukan */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 24px;
            padding: 0 10px;
            color: #38bdf8;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        /* Single Nav Link */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-item:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .nav-item.active {
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            stroke-width: 2;
        }

        /* Dropdown Group Container */
        .dropdown-group {
            display: flex;
            flex-direction: column;
        }

        /* Dropdown Trigger Button */
        .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            color: #cbd5e1;
            background: transparent;
            border: none;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
        }

        .dropdown-toggle:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .dropdown-toggle.has-active {
            color: #38bdf8;
            font-weight: 600;
            background: rgba(56, 189, 248, 0.08);
        }

        .toggle-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chevron-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            color: #64748b;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .dropdown-group.open .chevron-icon {
            transform: rotate(180deg);
            color: #94a3b8;
        }

        /* Dropdown Sub-Items Content */
        .dropdown-content {
            display: none;
            padding-left: 14px;
            margin: 4px 0 6px 14px;
            border-left: 2px solid #334155;
            flex-direction: column;
            gap: 2px;
        }

        .dropdown-group.open .dropdown-content {
            display: flex;
        }

        .sub-nav-item {
            display: block;
            color: #94a3b8;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .sub-nav-item:hover {
            color: #f8fafc;
            background: #1e293b;
            padding-left: 15px;
        }

        .sub-nav-item.active {
            color: #ffffff;
            background: #2563eb;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }

        /* Layout Main */
        .main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }

        .navbar {
            height: 64px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .content {
            padding: 25px;
            flex: 1;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge-role {
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .logout button {
            border: none;
            background: #fee2e2;
            color: #dc2626;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.15s ease;
        }

        .logout button:hover {
            background: #fecaca;
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="logo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            <span>Retribusi Kudus</span>
        </div>

        <div class="menu">

            <!-- 1. DASHBOARD UTAMA -->
            @if(auth()->user()->hasPermission('dashboard.view'))
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            @endif

            <!-- 2. DROPDOWN: DATA MASTER (Wilayah, Jenis Retribusi, Tarif) -->
            @if(auth()->user()->hasPermission('wilayah.view') || auth()->user()->hasPermission('jenis_retribusi.view') || auth()->user()->hasPermission('tarif.view'))
                @php
                    $isMasterActive = request()->routeIs('admin.wilayah.*') || request()->routeIs('admin.jenis-retribusi.*') || request()->routeIs('admin.tarif.*');
                @endphp
                <div class="dropdown-group {{ $isMasterActive ? 'open' : '' }}" data-menu="master">
                    <button type="button" class="dropdown-toggle {{ $isMasterActive ? 'has-active' : '' }}">
                        <div class="toggle-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                                <path d="M3 5V19A9 3 0 0 0 21 19V5"/>
                                <path d="M3 12A9 3 0 0 0 21 12"/>
                            </svg>
                            <span>Data Master</span>
                        </div>
                        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        @if(auth()->user()->hasPermission('wilayah.view'))
                            <a href="{{ route('admin.wilayah.index') }}" class="sub-nav-item {{ request()->routeIs('admin.wilayah.*') ? 'active' : '' }}">
                                Wilayah (Kec/Desa)
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('jenis_retribusi.view'))
                            <a href="{{ route('admin.jenis-retribusi.index') }}" class="sub-nav-item {{ request()->routeIs('admin.jenis-retribusi.*') ? 'active' : '' }}">
                                Jenis Retribusi
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('tarif.view'))
                            <a href="{{ route('admin.tarif.index') }}" class="sub-nav-item {{ request()->routeIs('admin.tarif.*') ? 'active' : '' }}">
                                Tarif Retribusi
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 3. DROPDOWN: OPERASIONAL (Wajib Retribusi, Pengajuan, Tagihan, Pembayaran, Setoran, Kelola Petugas) -->
            @if(auth()->user()->hasPermission('wajib_retribusi.view') || auth()->user()->hasPermission('pengajuan.view') || auth()->user()->hasPermission('tagihan.view') || auth()->user()->hasPermission('pembayaran.view') || auth()->user()->hasPermission('setoran.view') || auth()->user()->hasPermission('petugas.view'))
                @php
                    $isOpActive = request()->routeIs('admin.wajib-retribusi.*') || request()->routeIs('admin.pengajuan.*') || request()->routeIs('admin.tagihan.*') || request()->routeIs('admin.pembayaran.*') || request()->routeIs('admin.setoran.*') || request()->routeIs('admin.petugas.*');
                @endphp
                <div class="dropdown-group {{ $isOpActive ? 'open' : '' }}" data-menu="operasional">
                    <button type="button" class="dropdown-toggle {{ $isOpActive ? 'has-active' : '' }}">
                        <div class="toggle-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                <path d="M9 14l2 2 4-4"/>
                            </svg>
                            <span>Operasional</span>
                        </div>
                        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        @if(auth()->user()->hasPermission('wajib_retribusi.view'))
                            <a href="{{ route('admin.wajib-retribusi.index') }}" class="sub-nav-item {{ request()->routeIs('admin.wajib-retribusi.*') ? 'active' : '' }}">
                                Wajib Retribusi
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('pengajuan.view'))
                            <a href="{{ route('admin.pengajuan.index') }}" class="sub-nav-item {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                                Pengajuan WR
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('tagihan.view'))
                            <a href="{{ route('admin.tagihan.index') }}" class="sub-nav-item {{ request()->routeIs('admin.tagihan.*') ? 'active' : '' }}">
                                Tagihan
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('pembayaran.view'))
                            <a href="{{ route('admin.pembayaran.index') }}" class="sub-nav-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                                Pembayaran
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('setoran.view'))
                            <a href="{{ route('admin.setoran.index') }}" class="sub-nav-item {{ request()->routeIs('admin.setoran.*') ? 'active' : '' }}">
                                Setoran Petugas
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('petugas.view') || auth()->user()->isSuperAdmin() || auth()->user()->hasRole('admin_dinas'))
                            <a href="{{ route('admin.petugas.index') }}" class="sub-nav-item {{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                                Kelola Petugas
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 4. LAPORAN -->
            @if(auth()->user()->hasPermission('laporan.view'))
                <a href="{{ route('admin.laporan.index') }}" class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    <span>Laporan</span>
                </a>
            @endif

            <!-- 5. DROPDOWN: SISTEM (Pengguna, Role & Hak Akses, Banner) -->
            @if(auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('roles.view') || auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.view'))
                @php
                    $isSysActive = request()->routeIs('admin.pengguna.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.banners.*');
                @endphp
                <div class="dropdown-group {{ $isSysActive ? 'open' : '' }}" data-menu="sistem">
                    <button type="button" class="dropdown-toggle {{ $isSysActive ? 'has-active' : '' }}">
                        <div class="toggle-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>Sistem</span>
                        </div>
                        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="dropdown-content">
                        @if(auth()->user()->hasPermission('users.view'))
                            <a href="{{ route('admin.pengguna.index') }}" class="sub-nav-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
                                Pengguna
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('roles.view'))
                            <a href="{{ route('admin.roles.index') }}" class="sub-nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                Role & Hak Akses
                            </a>
                        @endif

                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.view'))
                            <a href="{{ route('admin.banners.index') }}" class="sub-nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                                Banner Slideshow
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </aside>

    <div class="main">

        <nav class="navbar">

            <div>
                <strong style="font-size: 16px; color: #1e293b;">
                    @yield('page-title', 'Dashboard')
                </strong>
            </div>

            <div class="profile">

                <span style="font-size: 14px; font-weight: 600; color: #334155;">
                    {{ auth()->user()->nama_lengkap }}
                </span>

                <span class="badge-role">
                    {{ auth()->user()->roleRelation?->display_name ?? ucfirst(auth()->user()->role) }}
                </span>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="logout"
                >
                    @csrf
                    <button type="submit">
                        Logout
                    </button>
                </form>

            </div>

        </nav>

        <main class="content">

            @yield('content')

        </main>

    </div>

</div> 

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                const group = this.closest('.dropdown-group');
                if (group) {
                    group.classList.toggle('open');
                }
            });
        });
    });
</script>

@stack('scripts')

</body>
</html>