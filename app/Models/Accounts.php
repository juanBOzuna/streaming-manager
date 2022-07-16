<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;
use DateTime;

class Accounts extends EloquentModel
{
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "accounts";

    protected $fillable = [

        'email',
        'key_pass',
        'is_renewed',
        'is_sold_ordinary',
        'is_sold_extraordinary',
        'is_active',
        'is_expired',
        'date_renewed',
        'times_renewed',
        'screens_sold',
        'type_account_id',
        

    ];

}
