<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Room extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tenancies()
    {
        return $this->hasMany(Tenancy::class);
    }

    public function activeTenancy()
    {
        return $this->hasOne(Tenancy::class)->where('is_active', true);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true);
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('is_published', true)
                     ->whereDoesntHave('tenancies', function ($q) {
                         $q->where('is_active', true);
                     });
    }
}