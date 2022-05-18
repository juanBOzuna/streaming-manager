<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAccount extends Model
{
    use HasFactory;

    protected $table = "type_account";

    protected $fillable = [
        'name',
        'total_screens',
        'available_screens',
        'extraordinary_available_screens',
        'price_day',
        'picture'
    ];

}
