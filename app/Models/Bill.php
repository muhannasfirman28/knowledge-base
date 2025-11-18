<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Bill extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function tenancy()
    {
        return $this->belongsTo(Tenancy::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'bill_transactions');
    }

    public function getIsPaidAttribute()
    {
        return $this->transactions()
            ->whereIn('status', ['settlement', 'capture'])
            ->exists();
    }
}
