<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register User
 public function register(Request $request) 
 {
    // Validasi Input User
    $request->validate([
        'username' => 'required|string|max:50|unique:users',
        'email' => 'required|email|max:100|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'nullable|in:admin,user',
    ]);

    // Buat User Baru
    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role ?? 'user',
        'is_active' => false,
    ]);

    // Kirim Response
    return response()->json([
        'message' => 'Register Akun Berhasil, Akun anda akan aktif setelah dikonfimasi Admin',
        'user' => [
            'id_users' => $user->id_users,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
        ]
    ], 201);
 }

//  Login User
 public function login(Request $request) {

    // Validasi Input
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);
    
    $user = User::where('email', $request->email)->first();

    // Verifikasi Email dan Password
    if(!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Email atau Password salah.'],
        ]);
    }

    // Cek Status Aktif User
    if(!$user->is_active) {
        throw ValidationException::withMessages([
            'email' => ['Akun anda belum aktif. Silahkan Tunggu Konfirmasi Admin.'],
        ]);
    }

    // Membuat Token Api
    $token = $user->createToken('auth_token', ['user'])->plainTextToken;

    return response()->json([
        'message' => 'Login Berhasil',
        'user' => [
            'id_users' => $user->id_users,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
        ],
        'token' => $token,
    ]);
 }

//  Logout User
 public function logout(Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message' => 'Logout Berhasil',
    ], 200);
 }
 
}
