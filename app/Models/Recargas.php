<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Recargas extends EloquentModel
{
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "recargas";

    protected $fillable = [

        'operadors_id',
        'tipo_de_recargas_id',
        'user_id',
        'price'

    ];
}
