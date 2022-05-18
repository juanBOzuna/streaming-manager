<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{

    use HasFactory;
    protected $table = "cms_users";

    protected $fillable = [

        'name',
        'photo',
        'email',
        'password',
        'id_cms_privileges'

    ];
}
