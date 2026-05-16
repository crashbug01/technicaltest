<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::create([
            'name' => 'Ahmad Sopir',
            'phone' => '081234567890',
        ]);

        Driver::create([
            'name' => 'Budi Angkut',
            'phone' => '089911223344',
        ]);

        Driver::create([
            'name' => 'Cahyo Pratama',
            'phone' => '082145678901',
        ]);

        Driver::create([
            'name' => 'Dedi Kurniawan',
            'phone' => '083256789012',
        ]);

        Driver::create([
            'name' => 'Eko Santoso',
            'phone' => '085367890123',
        ]);

        Driver::create([
            'name' => 'Fajar Hidayat',
            'phone' => '087478901234',
        ]);

        Driver::create([
            'name' => 'Gilang Ramadhan',
            'phone' => '081589012345',
        ]);

        Driver::create([
            'name' => 'Hendra Saputra',
            'phone' => '082690123456',
        ]);

        Driver::create([
            'name' => 'Indra Wijaya',
            'phone' => '083701234567',
        ]);

        Driver::create([
            'name' => 'Joko Susilo',
            'phone' => '085812345678',
        ]);
    }
}
