<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Customers extends EloquentModel
{
    const REVENDEDOR = 3;
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "customers";
    protected $fillable = [
        'name',
        'number_phone',
        'date_sold',
        'revendedor_id'
    ];

}
