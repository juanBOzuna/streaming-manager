<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class Usuarios extends EloquentModel
{

    // use HasFactory;
    protected $table = "cms_users";

    protected $fillable = [

        'name',
        'photo',
        'email',
        'password',
        'id_cms_privileges'

    ];
}
