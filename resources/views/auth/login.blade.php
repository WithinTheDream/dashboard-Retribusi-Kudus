<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Retribusi Sampah Kudus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Retribusi Kudus</h2>
            <p class="text-sm text-gray-500">Silakan login ke akun Anda</p>
        </div>

        <!-- Tampilkan Pesan Error jika Gagal Login -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">
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
                <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600" placeholder="Masukkan username...">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600" placeholder="••••••••">
            </div>

            <!-- Kode Keamanan / Captcha Gambar -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Kode Keamanan (Captcha)</label>

                <div class="flex items-center gap-2 mb-2">
                    <img id="captcha-img" src="{{ route('captcha.image') }}" alt="Captcha" class="h-10 border border-gray-300 rounded-lg select-none">
                    <button type="button" onclick="refreshCaptcha()" title="Ganti Captcha" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg border border-gray-300 transition duration-150">
                        🔄
                    </button>
                </div>

                <input type="text" name="captcha" required placeholder="Ketik kode gambar di atas..." autocomplete="off" class="w-full px-3 py-2 border border-gray-300 rounded-lg uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-slate-600">
            </div>

            <button type="submit" class="w-full bg-slate-800 text-white py-2 rounded-lg font-semibold hover:bg-slate-700 transition duration-200">
                Masuk
            </button>
        </form>
    </div>

    <script>
        function refreshCaptcha() {
            document.getElementById('captcha-img').src = "{{ route('captcha.image') }}?" + Math.random();
        }
    </script>
</body>
</html>
