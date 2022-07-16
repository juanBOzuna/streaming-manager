<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;
use DateTime;

class Screens extends EloquentModel
{
    // use HasFactory;
    protected $connection = 'mongodb';
    protected $table = "screens";

    protected $fillable = [
        'account_id',
        'email',
        'client_id',
        'name',
        'code_screen',
        'profile_number',
        'is_sold',
        'date_sold',
        'date_expired',
        'price_of_membership',
        'device',
        'ip',
        'type_account_id',
        'is_account_expired',
        'type_account_id',
    ];



    public function scopeLastMonth($query)
    {

        $query->where(function ($query) {

            $primer_dia_mes = (new DateTime())->modify('first day of this month')->format('Y-m-d');
            $ultimo_dia_mes = (new DateTime())->modify('last day of this month')->format('Y-m-d');

            $query->whereBetween('screens.date_sold', [$primer_dia_mes, $ultimo_dia_mes]);
        });
    }
}
