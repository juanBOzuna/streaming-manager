<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    const TYPE_INDIVIDUAL = 'Pantalla Individual';
    const TYPE_FULL = 'Cuenta Completa';
    const ONLY_SCREEN = 'Solo pantalla';


    protected $fillable = [

        'customers_id',
        'is_discarded',
        'number_screens_discarded',
        'total_price',
        'type_order',
        'is_venta_revendedor',
        'is_discarded_all'

    ];
}
