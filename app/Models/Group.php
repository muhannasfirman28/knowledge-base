<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Group extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function tenancies()
    {
        return $this->hasManyThrough(Tenancy::class, Room::class);
    }
}
