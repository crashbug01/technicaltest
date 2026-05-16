<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'name' => 'Toyota Innova Zenix',
            'type' => 'person',
            'ownership' => 'owned',
            'plate_number' => 'B 1234 NKL',
            'fuel_consumption' => 10,
        ]);

        Vehicle::create([
            'name' => 'Mitsubishi Fuso Shogun',
            'type' => 'cargo',
            'ownership' => 'rented',
            'plate_number' => 'B 9999 TNT',
            'fuel_consumption' => 15,
        ]);

        Vehicle::create([
            'name' => 'Honda Brio Satya',
            'type' => 'person',
            'ownership' => 'owned',
            'plate_number' => 'N 1122 AB',
            'fuel_consumption' => 12,
        ]);

        Vehicle::create([
            'name' => 'Suzuki Ertiga GX',
            'type' => 'person',
            'ownership' => 'rented',
            'plate_number' => 'L 3344 CD',
            'fuel_consumption' => 11,
        ]);

        Vehicle::create([
            'name' => 'Daihatsu Gran Max Pickup',
            'type' => 'cargo',
            'ownership' => 'owned',
            'plate_number' => 'W 5566 EF',
            'fuel_consumption' => 13,
        ]);

        Vehicle::create([
            'name' => 'Isuzu Elf NMR',
            'type' => 'cargo',
            'ownership' => 'rented',
            'plate_number' => 'AG 7788 GH',
            'fuel_consumption' => 14,
        ]);

        Vehicle::create([
            'name' => 'Toyota Avanza Veloz',
            'type' => 'person',
            'ownership' => 'owned',
            'plate_number' => 'AE 9900 IJ',
            'fuel_consumption' => 11,
        ]);

        Vehicle::create([
            'name' => 'Hino Dutro Cargo',
            'type' => 'cargo',
            'ownership' => 'owned',
            'plate_number' => 'S 2233 KL',
            'fuel_consumption' => 16,
        ]);

        Vehicle::create([
            'name' => 'Nissan Livina',
            'type' => 'person',
            'ownership' => 'rented',
            'plate_number' => 'DK 4455 MN',
            'fuel_consumption' => 12,
        ]);

        Vehicle::create([
            'name' => 'Mitsubishi Colt Diesel',
            'type' => 'cargo',
            'ownership' => 'owned',
            'plate_number' => 'ED 6677 OP',
            'fuel_consumption' => 17,
        ]);

    }
}
