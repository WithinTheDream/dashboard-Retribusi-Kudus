<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'no_hp' => ['required', 'string', 'max:20'],
        ]);

        try {
            DB::beginTransaction();

            $userRole = Role::where('name', 'user')->first();

            $user = User::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'no_hp' => $validated['no_hp'],
                'role' => 'user',
                'role_id' => $userRole?->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login untuk mengajukan retribusi.',
                'data' => [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'username' => $user->username,
                    'email' => $user->email,
                    'no_hp' => $user->no_hp,
                    'role' => $user->role,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
