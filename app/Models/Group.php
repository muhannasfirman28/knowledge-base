<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Group extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
