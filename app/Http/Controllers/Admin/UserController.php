<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
        // Menampilkan Semua Daftar User
        public function index(): View
        {
                $users = User::orderBy('created_at', 'desc')->get();
                return view('admin.users.index', compact('users'));
        }

        // Menampilkan Daftar User Pending
        public function getPending(): View
        {
                $users = User::where('is_active', false)
                ->orderBy('created_at', 'asc')
                ->get();
                return view('admin.users.pending', compact('users'));
        }

        // Activate User
        public function activateUser($id): RedirectResponse
        {
                try {
                        $users = User::findOrFail($id);
                        if($users->is_active) {
                                return redirect()->back()->with('success', 'Akun User Ini Sudah Aktif');
                        }
                        $users->is_active = true;
                        $users->save();
                        return redirect()->back()->with('info', 'User Berhasil Diaktifkan');
                } catch (ModelNotFoundException $e) {
                        return redirect()->back()->with('error', 'Akun User Tidak Ditemukan');
                } catch (\Exception $e) {
                        return redirect()->back()->with('error', 'Gagal Mengaktifkan Akun: ' . $e->getMessage());
                }
        }
        
        // Deactivate User
        public function deactivateUser($id): RedirectResponse
        {
                try {
                        $users = User::findOrFail($id);
                        if($users->is_active) {
                                return redirect()->back()->with('success', 'Akun User Ini Sudah Dinonaktifkan');
                        }
                        $users->is_active = false;
                        $users->save();
                        return redirect()->back()->with('info', 'Berhasil Menonaktifkan User');
                } catch (ModelNotFoundException $e) {
                        return redirect()->back()->with('error', 'Akun User Tidak Ditemukan');
                } catch (\Exception $e) {
                        return redirect()->back()->with('error', 'Gagal Menonaktifkan User: ' . $e->getMessage());
                }
        }
}