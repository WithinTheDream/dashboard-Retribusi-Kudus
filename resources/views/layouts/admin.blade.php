<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard Admin')
    </title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            background: #111827;
            color: white;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        /* Custom Scrollbar untuk sidebar agar rapi */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #111827;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
            padding-left: 8px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-title {
            font-size: 11px;
            color: #9ca3af;
            margin: 18px 0 8px 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .menu a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 14px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .menu a:hover {
            background: #1f2937;
            color: #ffffff;
        }

        .menu a.active {
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        .main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
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
            gap: 10px;
        }

        .logout button {
            border: none;
            background: #ef4444;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .logout button:hover {
            background: #dc2626;
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

            <div class="menu-title">
                Utama
            </div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

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

            @if(auth()->user()->hasPermission('petugas.view'))
                <a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                    Kelola Petugas
                </a>
            @endif

            <div class="menu-title">
                Laporan
            </div>

            @if(auth()->user()->hasPermission('laporan.view'))
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    Laporan
                </a>
            @endif

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

            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('banner.view'))
                <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    Banner Slideshow
                </a>
            @endif

        </div>

    </aside>

    <div class="main">

        <nav class="navbar">

            <div>
                <strong>
                    @yield('page-title', 'Dashboard')
                </strong>
            </div>

            <div class="profile">

                <span>
                    {{ auth()->user()->nama_lengkap }}
                </span>

                <span>
                    ({{ auth()->user()->role }})
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
    // QoL: Menjaga posisi scroll sidebar agar tidak reset ke atas saat berpindah halaman
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            const savedScroll = sessionStorage.getItem('admin_sidebar_scroll');
            if (savedScroll !== null) {
                sidebar.scrollTop = parseInt(savedScroll, 10);
            }

            sidebar.addEventListener('scroll', function () {
                sessionStorage.setItem('admin_sidebar_scroll', sidebar.scrollTop);
            });

            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    sessionStorage.setItem('admin_sidebar_scroll', sidebar.scrollTop);
                });
            });
        }
    });
</script>

@stack('scripts')

</body>
</html>