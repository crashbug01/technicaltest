<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['name', 'type', 'ownership', 'plate_number', 'fuel_consumption'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
