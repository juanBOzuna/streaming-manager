<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

class CmsUsers extends EloquentModel
{
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "cms_users";
    protected $fillable = [
        'name',
        'photo',
        'email',
        'password'
    ];

}
