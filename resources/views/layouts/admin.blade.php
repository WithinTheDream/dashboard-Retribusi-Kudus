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
            width: 250px;
            background: #0f172a;
            color: white;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 24px;
            padding: 0 8px;
            color: #38bdf8;
            letter-spacing: 0.5px;
        }

        .menu {
            flex: 1;
        }

        .menu-title {
            font-size: 11px;
            color: #64748b;
            margin: 18px 8px 8px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 6px;
            margin-bottom: 3px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .menu a:hover {
            background: #1e293b;
            color: #ffffff;
        }

        .menu a.active {
            background: #2563eb;
            color: #ffffff;
        }

        .main {
            flex: 1;
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
            Retribusi Kudus
        </div>

        <div class="menu">

            <!-- 1. UTAMA -->
            @if(auth()->user()->hasPermission('dashboard.view'))
                <div class="menu-title">
                    Utama
                </div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @endif

            <!-- 2. DATA MASTER (Wilayah, Jenis Retribusi, Tarif) -->
            @if(auth()->user()->hasPermission('wilayah.view') || auth()->user()->hasPermission('jenis_retribusi.view') || auth()->user()->hasPermission('tarif.view'))
                <div class="menu-title">
                    Data Master
                </div>

                @if(auth()->user()->hasPermission('wilayah.view'))
                    <a href="{{ route('admin.wilayah.index') }}" class="{{ request()->routeIs('admin.wilayah.*') ? 'active' : '' }}">
                        Wilayah
                    </a>
                @endif

                @if(auth()->user()->hasPermission('jenis_retribusi.view'))
                    <a href="{{ route('admin.jenis-retribusi.index') }}" class="{{ request()->routeIs('admin.jenis-retribusi.*') ? 'active' : '' }}">
                        Jenis Retribusi
                    </a>
                @endif

                @if(auth()->user()->hasPermission('tarif.view'))
                    <a href="{{ route('admin.tarif.index') }}" class="{{ request()->routeIs('admin.tarif.*') ? 'active' : '' }}">
                        Tarif
                    </a>
                @endif
            @endif

            <!-- 3. OPERASIONAL -->
            @if(auth()->user()->hasPermission('wajib_retribusi.view') || auth()->user()->hasPermission('pengajuan.view') || auth()->user()->hasPermission('tagihan.view') || auth()->user()->hasPermission('pembayaran.view') || auth()->user()->hasPermission('setoran.view'))
                <div class="menu-title">
                    Operasional
                </div>

                @if(auth()->user()->hasPermission('wajib_retribusi.view'))
                    <a href="{{ route('admin.wajib-retribusi.index') }}" class="{{ request()->routeIs('admin.wajib-retribusi.*') ? 'active' : '' }}">
                        Wajib Retribusi
                    </a>
                @endif

                @if(auth()->user()->hasPermission('pengajuan.view'))
                    <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                        Pengajuan
                    </a>
                @endif

                @if(auth()->user()->hasPermission('tagihan.view'))
                    <a href="{{ route('admin.tagihan.index') }}" class="{{ request()->routeIs('admin.tagihan.*') ? 'active' : '' }}">
                        Tagihan
                    </a>
                @endif

                @if(auth()->user()->hasPermission('pembayaran.view'))
                    <a href="{{ route('admin.pembayaran.index') }}" class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                        Pembayaran
                    </a>
                @endif

                @if(auth()->user()->hasPermission('setoran.view'))
                    <a href="{{ route('admin.setoran.index') }}" class="{{ request()->routeIs('admin.setoran.*') ? 'active' : '' }}">
                        Setoran Petugas
                    </a>
                @endif
            @endif

            <!-- 4. LAPORAN -->
            @if(auth()->user()->hasPermission('laporan.view'))
                <div class="menu-title">
                    Laporan
                </div>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    Laporan Rekapitulasi
                </a>
            @endif

            <!-- 5. SISTEM (Pengguna & Role) -->
            @if(auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('roles.view'))
                <div class="menu-title">
                    Sistem
                </div>

                @if(auth()->user()->hasPermission('users.view'))
                    <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
                        Pengguna
                    </a>
                @endif

                @if(auth()->user()->hasPermission('roles.view'))
                    <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        Role & Hak Akses
                    </a>
                @endif
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

@stack('scripts')

</body>
</html>