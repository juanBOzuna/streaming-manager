<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class TypeAccount extends EloquentModel
{
    const NETFLIX_LIMIT = 5;
    const DISNEY_LIMIT=3;
    // use HasFactory;
    protected $connection = 'mongodb';
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
