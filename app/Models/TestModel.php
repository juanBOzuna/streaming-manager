<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;
// use Jenssegers\Mongodb\src\Model;
// use Illuminate\Database\Eloquent\Model as EloquentModel;

// use Jenssegers\Mongodb\src\Eloquent\Model as EloquentModel;
// use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class TestModel extends EloquentModel
{
    //use HasFactory;//dejo eso?
    protected $connection = 'mongodb';
    protected $table = "test";

    //dale un nombre a la tabla
    protected $fillable = [
        'name'//pero no se pueden poner normal?idea nose, 

    ];

    function nose(){
        //voy a dejarlo con ese id mientras, despues reviso si se puede cambair
        // cuanto tiempo le pusiste a la cafeina de heroku, eso se puede? oes
    }
}
