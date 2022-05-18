<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Screens extends Model
{
    use HasFactory;
    protected $table = "screens";

    protected $fillable = [
        'account_id',
        'email',
        'client_id',
        'name',
        'date_sold',
        'date_expired',
        'is_sold',
        'price_of_membership',
        'code_screen',
        'device',
        'profile_number',
        'type_account_id',
        'ip'
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
