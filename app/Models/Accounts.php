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
        'number_screens_extraordinary_sold',
        'is_active',
        'is_expired',
        'account_replace',
        'is_account_replace_notified',
        'date_renewed',
        'times_renewed',
        'screens_sold',
        'type_account_id',
        

    ];

}
