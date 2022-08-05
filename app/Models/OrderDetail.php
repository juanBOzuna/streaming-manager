<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = "order_details";

    protected $fillable = [
        'orders_id',
        'type_account_id',
        'customer_id',
        'membership_days',
        'screen_id',
        'account_id',
        'membership_days',
        'price_of_membership_days',
        'finish_date',
        'is_venta_revendedor',
        'is_notified',
        'is_discarded',
        'type_order',
        'number_renovations',
        'parent_order_detail',
        'is_renewed',
        'nombreCliente'
    ];
}
