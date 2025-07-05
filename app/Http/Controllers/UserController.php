<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Get/api/admin/users/pending
    // Endpoint untuk mengambil daftar user yang belum aktif
    public function getPendingUsers()
    {
        try {
            // Ambil Semua User yang belum aktif
            $users = User::where('is_active', false)
            ->OrderBy('created_at', 'asc')
            ->select('id_users', 'username', 'email', 'role', 'created_at')
            ->get();
            return response()->json($users , 200);
        } catch 
            (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal Mengambil Daftar User Pending: ' . $e->getMessage()], 500);
        }
    }

    // Patch/api/admin/users/{id}/activate
    // Admin validasi user
    public function activeUsers(Request $request, $id)
    {
        $userId = (int) $id;

        try {
            $user = User::findOrFail($userId);
            if ($user->is_active) {
                return response()->json([
                    'message' => 'Akun User ini Sudah Aktif. '], 200);
            }
            $user->is_active = true;
            $user->save();

            return response()->json([
                'message' => 'User Berhasil Diaktifkan',
                'user' => [
                    'id_user' => $user->id_users,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ]
                ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return respone()->json([
                'message' => 'User Tidak Ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengaktifkan User: ' . $e->getMessage()
            ], 500);
        }
    }

    // Patch/api/admin/users/{id}/deactivate
    // Admin menonaktifkan user
    public function deactivateUsers(Request $request, $id)
    {
        $userId = (int) $id;

        try {
            $user = User::findOrFail($userId);
            if (!$user->is_active){
                return response()->json([
                    'message' => 'Akun User ini Sudah Nonaktif. '
                ], 200);
            }
            $user->is_active = false;
            $user->save();

            return response()->json([
                'message' => 'User Berhasil Dinonaktifkan',
                'user' => [
                    'id_user' => $user->id_users,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ]
                ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User Tidak Ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menonaktifkan User'
            ], 500);
        }
    }

    // Get/admin/users
    public function index()
    {
        try {
            $users = User::orderBy('created_at', 'asc')
            ->select('id_users', 'username', 'email', 'role', 'is_active', 'created_at')
            ->get();
            return response()->json($users, 200);
        } catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Gagal Mengambil Daftar User: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get/admin/users/{id}
    public function destroy()
    {
        $userId = (int) $id;
        try {
            $users = User::findOrFail($userId);
            $users->delete();
            return response()->json([
                'message' => 'User Berhasil Dihapus! ',
            ],200);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User Tidak Ditemukan. '
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menghapus User: ' . $e->getMessage()
            ],500);
        }
    }
}
