<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    const REVENDEDOR = 2;
    use HasFactory;
    protected $table = "customers";
    protected $fillable = [
        'name',
        'number_phone',
        'date_sold'
    ];

}
