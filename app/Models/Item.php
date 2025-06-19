<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'mfd_date',
        'exp_date',
        'company_name',
        'image',
        'quantity',
        'cost_price',
        'selling_price'
    ];

    protected $dates = ['mfd_date', 'exp_date'];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];
}
