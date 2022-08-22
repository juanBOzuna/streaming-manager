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
        'revendedor_id',
        'name',
        'code_screen',
        'profile_number',
        'is_sold',
        'screen_replace',
        'is_screen_replace_notified',
        'date_sold',
        'date_expired',
        'price_of_membership',
        'device',
        'ip',
        'type_account_id',
        'type_device_id',
        'is_sold_revendedor',
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
