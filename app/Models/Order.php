<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Order extends EloquentModel
{
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "orders";

    protected $fillable = [

        'customers_id',
        'total_price'

    ];
}
