<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BillTransaction extends Model
{
    // protected $table = 'bill_transaction';

    public $timestamps = false;

    protected $guarded = [];
}