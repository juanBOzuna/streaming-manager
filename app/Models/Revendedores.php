<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revendedores extends Model
{
    use HasFactory;
    protected $table = "revendedores";
    protected $fillable = [
        'name',
        'telefono'
    ];

}
