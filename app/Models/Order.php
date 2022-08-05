<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    const TYPE_INDIVIDUAL = 'Pantalla Individual';
    const TYPE_FULL = 'Cuenta Completa';
    protected $table = "orders";

    protected $fillable = [

        'customers_id',
        'total_price',
        'type_order',
        'is_venta_revendedor'

    ];
}
