<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'vehicle_id' => 1,
            'driver_id' => 1,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addDays(3),
        ]);

        Booking::create([
            'vehicle_id' => 2,
            'driver_id' => 2,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(4),
        ]);

        Booking::create([
            'vehicle_id' => 3,
            'driver_id' => 3,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(5),
        ]);

        Booking::create([
            'vehicle_id' => 4,
            'driver_id' => 4,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(6),
        ]);

        Booking::create([
            'vehicle_id' => 5,
            'driver_id' => 5,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(2),
        ]);

        Booking::create([
            'vehicle_id' => 6,
            'driver_id' => 6,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(4),
            'end_date' => now()->addDays(7),
        ]);

        Booking::create([
            'vehicle_id' => 7,
            'driver_id' => 7,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(8),
        ]);

        Booking::create([
            'vehicle_id' => 8,
            'driver_id' => 8,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(7),
        ]);

        Booking::create([
            'vehicle_id' => 9,
            'driver_id' => 9,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->subDays(15),
            'end_date' => now()->subDays(12),
        ]);

        Booking::create([
            'vehicle_id' => 10,
            'driver_id' => 10,
            'admin_id' => 1,
            'approver_1_id' => 2,
            'approver_2_id' => 3,
            'status' => 'pending',
            'start_date' => now()->addDays(6),
            'end_date' => now()->addDays(9),
        ]);
    }
}
