<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil parameter kueri dari URL (Query String)
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'id');       // Default sort berdasarkan ID jika kosong
        $sortOrder = $request->get('sort_order', 'desc'); // Default urutan terbaru jika kosong

        // 2. Buat instance query builder dari model Vehicle
        $query = Vehicle::query();

        // 3. Fitur Pencarian (Search) berdasarkan nama kendaraan atau nomor plat
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        // 4. Fitur Pengurutan (Sorting) dengan validasi kolom demi keamanan (SQL Injection Protection)
        $allowedColumns = ['id', 'name', 'plate_number', 'fuel_consumption'];
        $actualSortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'id';
        $actualSortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';

        $query->orderBy($actualSortBy, $actualSortOrder);

        // 5. Eksekusi query dengan Pagination (10 data per halaman)
        $vehicles = $query->paginate(10);

        // 6. Kirim data beserta state pencarian/sorting ke file view blade
        return view('admin.vehicle.table', compact('vehicles', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return view('admin.vehicle.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'type' => 'required|in:person,cargo',
            'ownership' => 'required|in:owned,rented',
            'fuel_consumption' => 'required|numeric|min:1',
        ]);

        Vehicle::create($validated);
        return redirect()->route('vehicle.index')->with('success', 'Kendaraan berhasil ditambah');
    }

    public function edit(Vehicle $vehicle)
    {
        // Mencari data berdasarkan ID otomatis dilakukan oleh Route Model Binding (Vehicle $vehicle)
        return view('admin.vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'type' => 'required|in:person,cargo',
            'ownership' => 'required|in:owned,rented',
            'fuel_consumption' => 'required|numeric|min:1',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicle.index')->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicle.index')->with('success', 'Kendaraan berhasil dihapus');
    }
}
