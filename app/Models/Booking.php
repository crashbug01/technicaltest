<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'admin_id',
        'approver_1_id',
        'approver_2_id',
        'status',
        'start_date',
        'end_date'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function approver1()
    {
        // Menghubungkan kolom approver_1_id ke kolom id di tabel users
        return $this->belongsTo(User::class, 'approver_1_id');
    }

    /**
     * Relasi ke model User sebagai Atasan 2
     */
    public function approver2()
    {
        // Menghubungkan kolom approver_2_id ke kolom id di tabel users
        return $this->belongsTo(User::class, 'approver_2_id');
    }
}
