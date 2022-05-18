<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Accounts extends Model
{
    use HasFactory;

    protected $table = "accounts";

    protected $fillable = [

        'email',
        'key_pass',
        'is_renewed',
        'is_sold_ordinary',
        'is_sold_extraordinary',
        'is_active',
        'date_renewed',
        'times_renewed',
        'type_account_id'

    ];

}
