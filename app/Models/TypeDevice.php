<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class TypeDevice extends Model
{
    use HasFactory;

    protected $table = "type_devices";

    protected $fillable = [

        'name',
        'emoji'



    ];
}
