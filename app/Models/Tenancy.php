<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Tenancy extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function mainTenant()
    {
        return $this->hasOne(Tenant::class)->where('is_main_tenant', true);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}