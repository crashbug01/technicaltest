<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('admin.vehicle.table', compact('vehicles'));
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
