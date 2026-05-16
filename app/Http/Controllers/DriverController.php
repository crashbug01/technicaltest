<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil parameter dari URL untuk search dan sort (jika kosong, gunakan default)
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // 2. Query dasar dengan menghitung total booking secara efisien
        $query = Driver::withCount('bookings');

        // 3. Logika Pencarian (Search berdasarkan nama atau nomor telepon)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // 4. Logika Pengurutan (Sort dengan Whitelist Kolom)
        $allowedSorts = ['name', 'phone', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 5. Paginasi (Pagination) - Mengambil 10 data per halaman
        // appends() memastikan query string tidak hilang saat pindah halaman paginasi
        $drivers = $query->paginate(10)->appends($request->all());

        // Diarahkan ke file view 'admin.driver.table' sesuai dengan kode Anda
        return view('admin.driver.table', compact('drivers', 'search', 'sortBy', 'sortOrder'));
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
