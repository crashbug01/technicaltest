<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user yang memiliki role approver.
     */
    public function index()
    {
        $approvers = User::where('role', 'approver')
            ->withCount(['approvalsLevel1', 'approvalsLevel2'])
            ->latest()
            ->get();

        return view('admin.approver.index', compact('approvers'));
    }

    /**
     * Menampilkan form tambah approver baru.
     */
    public function create()
    {
        return view('admin.approver.create');
    }

    /**
     * Menyimpan data approver baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Buat user baru dengan paksaan role 'approver' demi keamanan
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password sebelum disimpan
            'role' => 'approver',
        ]);

        return redirect()->route('admin.approver.index')
            ->with('success', 'User Approver baru berhasil didaftarkan.');
    }
}
