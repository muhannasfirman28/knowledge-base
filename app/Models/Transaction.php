<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Transaction extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function tenancy()
    {
        return $this->belongsTo(Tenancy::class);
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'bill_transactions');
    }
}
