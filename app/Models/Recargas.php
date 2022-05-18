<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recargas extends Model
{
    use HasFactory;
    protected $table = "recargas";

    protected $fillable = [

        'operadors_id',
        'tipo_de_recargas_id',
        'user_id',
        'price'

    ];
}
