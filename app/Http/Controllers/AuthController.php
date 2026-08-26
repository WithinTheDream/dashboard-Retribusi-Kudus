<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function generateCaptcha()
    {
        // 1. Generate 5 karakter acak yang mudah dibaca
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captchaText = substr(str_shuffle($characters), 0, 5);

        // 2. Simpan ke session dalam huruf kecil
        session(['captcha_code' => strtolower($captchaText)]);

        // 3. Buat canvas gambar (GD)
        $width = 140;
        $height = 42;
        $image = imagecreate($width, $height);

        // Alokasi warna
        $bg = imagecolorallocate($image, 243, 244, 246);       // Latar belakang (#F3F4F6)
        $textColor = imagecolorallocate($image, 31, 41, 55);    // Teks (#1F2937)
        $lineColor = imagecolorallocate($image, 209, 213, 219); // Garis noise (#D1D5DB)
        $dotColor = imagecolorallocate($image, 156, 163, 175);  // Titik noise (#9CA3AF)

        // Tambah garis acak
        for ($i = 0; $i < 4; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Tambah bintik acak
        for ($i = 0; $i < 60; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
        }

        // Tulis teks karakter ke gambar
        $x = 20;
        for ($i = 0; $i < strlen($captchaText); $i++) {
            $y = rand(8, 14);
            imagestring($image, 5, $x, $y, $captchaText[$i], $textColor);
            $x += 22;
        }

        // 4. Output response binary image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha'  => ['required', 'string'],
        ], [
            'captcha.required' => 'Kode captcha wajib diisi.',
        ]);

        // Verifikasi kesesuaian captcha
        if (strtolower($request->captcha) !== session('captcha_code')) {
            session()->forget('captcha_code');
            return back()
                ->withErrors(['captcha' => 'Kode captcha tidak sesuai. Silakan coba lagi.'])
                ->withInput($request->only('username'));
        }

        // Hapus session captcha setelah divalidasi
        session()->forget('captcha_code');

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // Jika role biasa (warga) login di web admin
            Auth::logout();
            return back()
                ->withErrors(['username' => 'Akun Anda tidak memiliki akses ke panel administrasi. Silakan gunakan aplikasi mobile.'])
                ->onlyInput('username');
        }

        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
