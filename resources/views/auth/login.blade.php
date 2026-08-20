<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Retribusi Sampah Kudus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Retribusi Kudus</h2>
            <p class="text-sm text-gray-500">Silakan login ke akun Anda</p>
        </div>

        <!-- Tampilkan Pesan Error jika Gagal Login -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Username / Email</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600" placeholder="Masukkan username...">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-slate-800 text-white py-2 rounded-lg font-semibold hover:bg-slate-700 transition duration-200">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
