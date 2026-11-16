<?php

// app/Models/Facility.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Facility extends Model
{
    use HasUlids;
    protected $guarded = [];

    // Relasi ke Room (Many to Many)
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'facility_room');
    }
}