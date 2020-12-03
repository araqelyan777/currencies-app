<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = [
        'num_code',
        'char_code',
        'name',
        'nominal',
        'value',
        'date',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'num_code' => 'integer',
        'nominal' => 'integer',
        'value' => 'decimal:4',
    ];

}
