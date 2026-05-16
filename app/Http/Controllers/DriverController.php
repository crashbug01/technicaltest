<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        // Mengambil data driver sekaligus menghitung total booking secara efisien
        $drivers = Driver::withCount('bookings')->latest()->get();

        return view('admin.driver.table', compact('drivers'));
    }

    /**
     * Menampilkan form untuk menambah driver baru.
     */
    public function create()
    {
        return view('admin.driver.form');
    }

    /**
     * Menyimpan data driver baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:drivers,phone',
        ], [
            'name.required' => 'Nama pengemudi wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon ini sudah terdaftar di sistem.',
        ]);

        Driver::create($request->only(['name', 'phone']));

        return redirect()->route('admin.driver.index')
            ->with('success', 'Data pengemudi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit driver berdasarkan ID.
     */
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.driver.edit', compact('driver'));
    }

    /**
     * Memperbarui data driver di database.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:drivers,phone,' . $driver->id,
        ], [
            'name.required' => 'Nama pengemudi wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon ini sudah digunakan oleh pengemudi lain.',
        ]);

        $driver->update($request->only(['name', 'phone']));

        return redirect()->route('admin.driver.index')
            ->with('success', 'Data pengemudi berhasil diperbarui.');
    }

    /**
     * Menghapus data driver dari database.
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);

        // Proteksi: Jika driver memiliki riwayat booking, cegah penghapusan demi integritas data
        if ($driver->bookings()->exists()) {
            return back()->with('error', 'Gagal menghapus! Pengemudi ini memiliki riwayat tugas pemesanan di sistem.');
        }

        $driver->delete();

        return redirect()->route('admin.driver.index')
            ->with('success', 'Data pengemudi berhasil dihapus.');
    }
}
