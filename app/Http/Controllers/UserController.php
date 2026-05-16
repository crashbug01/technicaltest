<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user yang memiliki role approver.
     */
    public function index()
    {
        // Menggunakan withTrashed() agar Admin bisa melihat siapa saja approver yang dinonaktifkan
        $approvers = User::where('role', 'approver')
            ->withCount(['approvalsLevel1', 'approvalsLevel2'])
            ->withTrashed()
            ->latest()
            ->get();

        return view('admin.approver.tabel', compact('approvers'));
    }

    /**
     * 2. FORM TAMBAH APPROVER
     */
    public function create()
    {
        return view('admin.approver.form');
    }

    /**
     * 3. SIMPAN APPROVER BARU (Proteksi Email Duplikat)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Email harus unik di antara user yang aktif (deleted_at IS NULL)
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun approver aktif.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'approver',
        ]);

        return redirect()->route('admin.approver.index')
            ->with('success', 'Akun Approver baru berhasil didaftarkan.');
    }

    /**
     * 4. FORM EDIT DATA APPROVER
     */
    public function edit($id)
    {
        // Menggunakan withTrashed() agar akun yang sedang nonaktif tetap bisa diedit datanya
        $approver = User::where('role', 'approver')->withTrashed()->findOrFail($id);
        return view('admin.approver.edit', compact('approver'));
    }

    /**
     * 5. UPDATE DATA APPROVER
     */
    public function update(Request $request, $id)
    {
        $approver = User::where('role', 'approver')->withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Mengabaikan pengecekan unique untuk ID diri sendiri saat ini
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($approver->id)->whereNull('deleted_at')
            ],
            'password' => 'nullable|string|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
        ]);

        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $approver->update($dataUpdate);

        return redirect()->route('admin.approver.index')
            ->with('success', 'Data Akun Approver berhasil diperbarui.');
    }

    /**
     * 6. MENONAKTIFKAN AKUN (Soft Delete)
     */
    public function destroy($id)
    {
        $approver = User::where('role', 'approver')->findOrFail($id);

        // Menghapus secara soft delete (mengisi kolom`deleted_at`)
        // Riwayat booking aman dan tidak akan pecah/error foreign key
        $approver->delete();

        return redirect()->route('admin.approver.index')
            ->with('success', 'Akun Approver berhasil dinonaktifkan.');
    }

    /**
     * 7. MENGAKTIFKAN KEMBALI AKUN (Restore)
     */
    public function restore($id)
    {
        $approver = User::where('role', 'approver')->withTrashed()->findOrFail($id);

        // Mengembalikan kolom deleted_at menjadi NULL kembali
        $approver->restore();

        return redirect()->route('admin.approver.index')
            ->with('success', 'Akun Approver berhasil diaktifkan kembali.');
    }
}
