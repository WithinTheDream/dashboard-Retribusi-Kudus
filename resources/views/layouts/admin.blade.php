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
            background: #111827;
            color: white;
            padding: 20px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .menu-title {
            font-size: 12px;
            color: #9ca3af;
            margin: 20px 0 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .menu a:hover,
        .menu a.active {
            background: #2563eb;
            color: white;
        }

        .main {
            flex: 1;
        }

        .navbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .content {
            padding: 25px;
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

            <!-- Dashboard biasanya bisa diakses semua level admin, jadi tidak perlu dibungkus permission spesifik -->
            <a href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>

            <div class="menu-title">
                Data Master
            </div>

            @if(auth()->user()->hasPermission('wilayah.view'))
                <a href="{{ route('admin.wilayah.index') }}">
                    Wilayah
                </a>
            @endif

            @if(auth()->user()->hasPermission('jenis_retribusi.view'))
                <a href="{{ route('admin.jenis-retribusi.index') }}">
                    Jenis Retribusi
                </a>
            @endif

            @if(auth()->user()->hasPermission('tarif.view'))
                <a href="{{ route('admin.tarif.index') }}">
                    Tarif
                </a>
            @endif

            <div class="menu-title">
                Operasional
            </div>

            @if(auth()->user()->hasPermission('wajib_retribusi.view'))
                <a href="{{ route('admin.wajib-retribusi.index') }}">
                    Wajib Retribusi
                </a>
            @endif

            @if(auth()->user()->hasPermission('pengajuan.view'))
                <a href="{{ route('admin.pengajuan.index') }}">
                    Pengajuan
                </a>
            @endif

            @if(auth()->user()->hasPermission('tagihan.view'))
                <a href="{{ route('admin.tagihan.index') }}">
                    Tagihan
                </a>
            @endif

            @if(auth()->user()->hasPermission('pembayaran.view'))
                <a href="{{ route('admin.pembayaran.index') }}">
                    Pembayaran
                </a>
            @endif

            @if(auth()->user()->hasPermission('setoran.view'))
                <a href="{{ route('admin.setoran.index') }}">
                    Setoran Petugas
                </a>
            @endif

            <div class="menu-title">
                Laporan
            </div>

            @if(auth()->user()->hasPermission('laporan.view'))
                <a href="{{ route('admin.laporan.index') }}">
                    Laporan
                </a>
            @endif

            <div class="menu-title">
                Sistem
            </div>

            @if(auth()->user()->hasPermission('users.view'))
                <a href="{{ route('admin.pengguna.index') }}">
                    Pengguna
                </a>
            @endif

            @if(auth()->user()->hasPermission('roles.view'))
                <a href="{{ route('admin.roles.index') }}">
                    Role & Hak Akses
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

@stack('scripts')

</body>
</html>