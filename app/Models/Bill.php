<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // public function getIsPaidAttribute()
    // {
    //     return $this->transactions()
    //         ->whereIn('status', ['settlement', 'capture'])
    //         ->exists();
    // }

        // --- 1. ACCESSOR (Untuk Status Per Baris di Tabel) ---
    // Cara Panggil: $bill->is_paid
    public function getIsPaidAttribute()
    {
        return $this->transactions()
            ->whereIn('status', ['settlement', 'capture'])
            ->exists();
    }

    // --- 2. SCOPES (INI YANG HILANG DAN BIKIN ERROR) ---
    
    // Cara Panggil: Bill::unpaid()->get()
    public function scopeUnpaid(Builder $query)
    {
        // Cari tagihan yang TIDAK PUNYA transaksi sukses
        return $query->whereDoesntHave('transactions', function ($q) {
            $q->whereIn('status', ['settlement', 'capture']);
        });
    }

    // Cara Panggil: Bill::paid()->get()
    public function scopePaid(Builder $query)
    {
        // Cari tagihan yang PUNYA transaksi sukses
        return $query->whereHas('transactions', function ($q) {
            $q->whereIn('status', ['settlement', 'capture']);
        });
    }
}
