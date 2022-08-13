<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Orders;
use Nette\Utils\DateTime;
use phpDocumentor\Reflection\Types\Boolean;
use Session;
use Request;
use DB;
use CRUDBooster;
use Carbon\Carbon;

use Illuminate\Support\Arr;
use App\Models\Accounts;
use App\Models\TypeAccount;
use App\Models\Screens;
use crocodicstudio\crudbooster\helpers\CRUDBooster as HelpersCRUDBooster;
use Illuminate\Support\Facades\DB as FacadesDB;

class AdminAccountsController extends \crocodicstudio\crudbooster\controllers\CBController
{

    public function cbInit()
    {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "id";
        $this->limit = "20";
        $this->orderby = "id,desc";
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = true;
        $this->button_edit = true;
        $this->button_delete = true;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->table = "accounts";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "Id", "name" => "id"];
        $this->col[] = ["label" => "Correo", "name" => "email"];
        $this->col[] = ["label" => "Clave", "name" => "key_pass"];
        // $this->col[] = ["label" => "Tipo de cuenta", "name" => "type_account_id", "join" => "type_account,picture", "image" => true];
        $this->col[] = ["label" => "Tipo de cuenta", "name" => "type_account_id", "join" => "type_account,name"];
        $this->col[] = ["label" => "fecha de creacion", "name" => "created_at"];
        $this->col[] = ["label" => "Pantallas Vendidas", "name" => "screens_sold"];
        $this->col[] = ["label" => "esta renovada?", "name" => "is_renewed", "callback" => function ($row) {
            if ($row->is_renewed == 0) {
                return 'No';
            } else {
                return 'Si';
            }
        }];
        $this->col[] = ["label" => "ESTA CAIDA?", "name" => "is_expired", "callback" => function ($row) {
            if ($row->is_expired == 0) {
                return 'ESTABLE';
            } else {
                return 'CAIDA';
            }
        }];

        $this->col[] = ["label" => "Ult. renovacion", "name" => "date_renewed", "callback" => function ($row) {
            if ($row->is_renewed == 0) {
                return 'No se ha renovado nunca';
            } else {
                return $row->date_renewed;
            }
        }];

        $this->col[] = ["label" => "Cant. renovaciones", "name" => "times_renewed"];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Correo', 'name' => 'email', 'type' => 'email', 'validation' => 'required|min:1|max:255|email', 'width' => 'col-sm-10', 'placeholder' => 'Please enter a valid email address'];
        $this->form[] = ['label' => 'Clave', 'name' => 'key_pass', 'type' => 'text', 'validation' => 'min:3|max:32', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Tipo de cuenta', 'name' => 'type_account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'type_account,name'];

        if (HelpersCRUDBooster::getCurrentMethod() == "getDetail") {

            //             $this->form[] = ['label'=>'Numero de Orden','name'=>'order_number','type'=>'text','validation'=>'required|min:1|max:255','value'=>$order_number,'readonly'=>true];
            // $this->form[] = ['label'=>'Estado','name'=>'status','type'=>'text','readonly'=>true];
            $screens = [];
            $screens[] = ['label' => 'Id', 'name' => 'id', 'type' => 'text'];
            $screens[] = ['label' => 'Nombre', 'name' => 'name', 'type' => 'text'];
            $screens[] = ['label' => 'Pin', 'name' => 'code_screen', 'type' => 'text'];
            $screens[] = ['label' => 'Fecha de venta', 'name' => 'date_sold'];
            $screens[] = ['label' => 'Fecha de Vencimiento', 'name' => 'date_expired'];
            $screens[] = ['label' => 'Cliente #', 'name' => 'client_id'];
            $screens[] = ['label' => 'Dispositivo', 'name' => 'device'];
            $screens[] = ['label' => 'IP', 'name' => 'ip', 'type' => 'text'];
            $this->form[] = ['label' => 'Pantallas', 'name' => 'screens', 'type' => 'child', 'columns' => $screens, 'table' => 'screens', 'foreign_key' => 'account_id'];
        }

        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label' => 'Correo', 'name' => 'email', 'type' => 'email', 'validation' => 'required|min:1|max:255|email|unique:accounts', 'width' => 'col-sm-10', 'placeholder' => 'Please enter a valid email address'];
        //$this->form[] = ['label' => 'Clave', 'name' => 'password', 'type' => 'text', 'validation' => 'min:3|max:32', 'width' => 'col-sm-10'];
        //$this->form[] = ['label' => 'Tipo de cuenta', 'name' => 'type_account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'type_account,name'];
        # OLD END FORM

        /*
| ----------------------------------------------------------------------
| Sub Module
| ----------------------------------------------------------------------
| @label          = Label of action
| @path           = Path of sub module
| @foreign_key 	  = foreign key of sub table/module
| @button_color   = Bootstrap Class (primary,success,warning,danger)
| @button_icon    = Font Awesome Class
| @parent_columns = Sparate with comma, e.g : name,created_at
|
*/
        $this->sub_module = array();


        /*
        | ----------------------------------------------------------------------
        | Add More Action Button / Menu
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
        | @icon        = Font awesome class icon. e.g : fa fa-bars
        | @color 	   = Default is primary. (primary, warning, succecss, info)
        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
        |
        */
        $this->addaction = array();
        $dateActual = Carbon::parse('');
        //        echo $dateActual;
        $this->addaction[] = [
            'label' => '' . ($dateActual->month),
            'url' => CRUDBooster::mainpath('set-status/[id]'),
            'icon' => 'fa fa-refresh',
            'showIf' => "($dateActual->month < \Carbon\Carbon::parse([date_renewed])->month )|| ($dateActual->year < \Carbon\Carbon::parse([date_renewed])->year) "
        ];

        //  'showIf' => "$dateActual->month > \Carbon\Carbon::parse([created_at])->month && [is_renewed] == 0"

        $this->addaction[] = [
            'label' => 'Cuenta caida',
            'url' => CRUDBooster::mainpath('set-desactive/[id]'),
            'icon' => 'fa fa-refresh',
            'showIf' => "[is_expired]!=1"
        ];


        $this->addaction[] = [
            'label' => 'Levantar',
            'url' => CRUDBooster::mainpath('set-active/[id]'),
            'icon' => 'fa fa-refresh',
            'showIf' => "[is_expired]==1"
        ];


        /*
        | ----------------------------------------------------------------------
        | Add More Button Selected
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @icon 	   = Icon from fontawesome
        | @name 	   = Name of button
        | Then about the action, you should code at actionButtonSelected method
        |
        */
        $this->button_selected = array();


        /*
        | ----------------------------------------------------------------------
        | Add alert message to this module at overheader
        | ----------------------------------------------------------------------
        | @message = Text of message
        | @type    = warning,success,danger,info
        |
        */
        $this->alert = array();


        /*
        | ----------------------------------------------------------------------
        | Add more button to header button
        | ----------------------------------------------------------------------
        | @label = Name of button
        | @url   = URL Target
        | @icon  = Icon from Awesome.
        |
        */
        $this->index_button = array();


        /*
        | ----------------------------------------------------------------------
        | Customize Table Row Color
        | ----------------------------------------------------------------------
        | @condition = If condition. You may use field alias. E.g : [id] == 1
        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
        |
        */
        $this->table_row_color = array();
        // $this->table_row_color[] = ['condition' => "[is_expired] == '1'", "color" => "danger"];


        /*
        | ----------------------------------------------------------------------
        | You may use this bellow array to add statistic at dashboard
        | ----------------------------------------------------------------------
        | @label, @count, @icon, @color
        |
        */
        $primer_dia_mes = new DateTime();
        $primer_dia_mes->modify('first day of this month');

        $ultimo_dia_mes = new DateTime();
        $ultimo_dia_mes->modify('last day of this month');

        $screens_availables_info = [];

        $i = 0;

        foreach (TypeAccount::get() as $key) {
            # code...

            $total = Screens::where('type_account_id', '=', $key->id)->where('profile_number', '>', 1)->where('profile_number', '<', ($key->available_screens + 2))->where('is_sold', '=', 0)->count();
            $screens_availables_info[$i] = [
                'name' => $key->name,
                'screens' => $total
            ];
            $i++;
        }

        $this->index_statistic = array();
        foreach ($screens_availables_info as $key) {
            # code...
            $this->index_statistic[] = ['label' => 'P. ' . $key['name'], 'count' => $key['screens'], 'icon' => 'fa fa-exclamation-triangle', 'color' => 'green', "link" => url('' . env('LINK_SYSTEM') . "screens?filter_column%5Bscreens.id%5D%5Btype%5D=&filter_column%5Bscreens.id%5D%5Bsorting%5D=&filter_column%5Baccounts.id%5D%5Btype%5D=&filter_column%5Baccounts.id%5D%5Bsorting%5D=&filter_column%5Bcustomers.number_phone%5D%5Btype%5D=&filter_column%5Bcustomers.number_phone%5D%5Bsorting%5D=&filter_column%5Btype_account.name%5D%5Btype%5D=%3D&filter_column%5Btype_account.name%5D%5Bvalue%5D=" . $key['name'] . "&filter_column%5Btype_account.name%5D%5Bsorting%5D=&filter_column%5Bscreens.name%5D%5Btype%5D=&filter_column%5Bscreens.name%5D%5Bsorting%5D=&filter_column%5Bscreens.is_sold%5D%5Btype%5D=%3D&filter_column%5Bscreens.is_sold%5D%5Bvalue%5D=No&filter_column%5Bscreens.is_sold%5D%5Bsorting%5D=&filter_column%5Bscreens.date_expired%5D%5Btype%5D=&filter_column%5Bscreens.date_expired%5D%5Bsorting%5D=&filter_column%5Bscreens.price_of_membership%5D%5Btype%5D=&filter_column%5Bscreens.price_of_membership%5D%5Bsorting%5D=&filter_column%5Bscreens.device%5D%5Btype%5D=&filter_column%5Bscreens.device%5D%5Bsorting%5D=&filter_column%5Bscreens.ip%5D%5Btype%5D=&filter_column%5Bscreens.ip%5D%5Bsorting%5D=&lasturl=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Fscreens")];
        }
        // $this->index_statistic[] = ['label' => 'Total Data', 'count' => FacadesDB::table('screens')->count(), 'icon' => 'fa fa-check', 'color' => 'success'];

        /*
        | ----------------------------------------------------------------------
        | Add javascript at body
        | ----------------------------------------------------------------------
        | javascript code in the variable
        | $this->script_js = "function() { ... }";
        |
        */

        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getIndex") {
            $this->script_js = "

                        let list = document.querySelectorAll('td');
                        console.log(list);
                        let index = 0;
                        let index2 = 0;
                        list.forEach(function (item) {
                            index2 = 0;
                            index++;
                            if (item.innerText == 'CAIDA') {

                                list.forEach(function (item2) {
                                    index2++;
                                   for (let index3 = 0; index3 < 8; index3++) {
                                       if (index2 == index - index3 && index2 != index) {
                                         item2.style.backgroundColor = '#DD4B39';
                                       item2.style.color = '#FFFFFF';
                                       }
                                   }
                                   for (let index3 = 0; index3 < 4; index3++) {
                                       if (index2 == index + index3 && index2 != index) {
                                           item2.style.backgroundColor = '#DD4B39';
                                           item2.style.color = '#FFFFFF';
                                       }
                                   }

                                })
                               item.style.backgroundColor = '#DD4B39';
                                item.style.fontWeight= 'bold';
                               item.style.color = '#FFFFFF';

                            }
                            

                            if (item.innerText == 'ESTABLE') {

                                list.forEach(function (item2) {
                                    index2++;
                                    for (let index3 = 0; index3 < 8; index3++) {
                                        if (index2 == index - index3 && index2 != index) {
                                          item2.style.backgroundColor = '#47b425';
                                        item2.style.color = '#FFFFFF';
                                        }
                                    }
                                    for (let index3 = 0; index3 < 4; index3++) {
                                        if (index2 == index + index3 && index2 != index) {
                                            item2.style.backgroundColor = '#47b425';
                                            item2.style.color = '#FFFFFF';
                                        }
                                    }
    
                                 })
                                item.style.backgroundColor = '#47b425';
                                 item.style.fontWeight= 'bold';
                                item.style.color = '#FFFFFF';

                            }


                        });
                      ";
        }

        //        $this->script_js = "  console.log('asd');";


        /*
        | ----------------------------------------------------------------------
        | Include HTML Code before index table
        | ----------------------------------------------------------------------
        | html code to display it before index table
        | $this->pre_index_html = "<p>test</p>";
        |
        */
        $this->pre_index_html = null;


        /*
        | ----------------------------------------------------------------------
        | Include HTML Code after index table
        | ----------------------------------------------------------------------
        | html code to display it after index table
        | $this->post_index_html = "<p>test</p>";
        |
        */
        $this->post_index_html = null;


        /*
        | ----------------------------------------------------------------------
        | Include Javascript File
        | ----------------------------------------------------------------------
        | URL of your javascript each array
        | $this->load_js[] = asset("myfile.js");
        |
        */
        $this->load_js = array();


        /*
        | ----------------------------------------------------------------------
        | Add css style at body
        | ----------------------------------------------------------------------
        | css code in the variable
        | $this->style_css = ".style{....}";
        |
        */
        $this->style_css = NULL;


        /*
        | ----------------------------------------------------------------------
        | Include css File
        | ----------------------------------------------------------------------
        | URL of your css each array
        | $this->load_css[] = asset("myfile.css");
        |
        */
        $this->load_css[] = asset("/css/All.css");
    }


    /*
    | ----------------------------------------------------------------------
    | Hook for button selected
    | ----------------------------------------------------------------------
    | @id_selected = the id selected
    | @button_name = the name of button
    |
    */


    public function actionButtonSelected($id_selected, $button_name)
    {
        //Your code here

    }


    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate query of index result
    | ----------------------------------------------------------------------
    | @query = current sql query
    |
    */
    public function hook_query_index(&$query)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate row of index table html
    | ----------------------------------------------------------------------
    |
    */
    public function hook_row_index($column_index, &$column_value)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add(&$postdata)
    {
        //Your code here

        $acc = Accounts::where('type_account_id', '=', $postdata['type_account_id'])->where('email', '=', $postdata['email'])->get();
        // {

        // }

        if (sizeof($acc) >= 1) {
            \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Ojo, ya hay una cuenta de este tipo con este correo", "warning");
        }

        // dd();

        // if(){

        // }

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @id = last insert id
    |
    */
    public function hook_after_add($id)
    {
        $cuenta = Accounts::where('id', '=', $id)->first();
        $type = TypeAccount::where('id', '=', $cuenta->type_account_id)->first()->total_screens;

        for ($i = 1; $i <= $type; $i++) {

            $screen = new Screens;
            $screen->profile_number =  $i;
            $screen->account_id = $id;
            $screen->name = "Pantalla " .  $i;
            $screen->is_sold = 0;
            $screen->price_of_membership = 0;
            $screen->type_account_id = $cuenta->type_account_id;

            $screen->email = $cuenta->email;
            $screen->save();
        }
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit(&$postdata, $id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_after_edit($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_before_delete($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_after_delete($id)
    {
        //Your code here

    }



    public function getSetStatus($id)
    {
        $dateActual = Carbon::parse('');
        $cuenta = Accounts::where('id', '=', $id)->first();

        $cuenta->times_renewed = $cuenta->times_renewed + 1;
        $cuenta->is_renewed = 1;
        $cuenta->date_renewed = strval($dateActual);
        $cuenta->save();
        CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Cuenta renovada exitosamente");
    }

    public function getSetDesactive($id)
    {
        $account = Accounts::where("id", "=", $id)->first();
        $type = TypeAccount::where('id', '=', $account->type_account_id)->first();
        $detail = OrderDetail::where('account_id', '=', $account->id)->where('type_order', '=', Order::TYPE_FULL)->where('is_renewed', '=', 0)->where('is_discarded', '=', 0)->first();

        if ($account->screens_sold == 0) {
            $account->is_expired = 1;
            $account->is_sold_ordinary = 0;
            $account->revendedor_id = null;
            $account->is_sold_extraordinary = 0;
            $account->screens_sold = 0;
            $account->save();

            \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Los clientes fueron trasladados a otras pantallas exitosamente", "success");
        }

        if (isset($detail)) {
            $renovations = [];
            $to_renovation = [];
            $screensOfAccount = Screens::where('account_id', '=', $id)->get();
            $accReplace = Accounts::where('screens_sold', '=', 0)->where('is_expired', '=', 0)->first();
            if (isset($accReplace)) {
                foreach ($screensOfAccount as $item) {
                    $item->is_account_expired = 1;
                    $item->save();
                }

                $accReplace->is_sold_ordinary = $account->is_sold_ordinary;
                $accReplace->is_sold_extraordinary = $account->is_sold_extraordinary;
                $accReplace->screens_sold = $account->screens_sold;
                if ($account->revendedor_id != null) {
                    $accReplace->revendedor_id = $account->revendedor_id;
                }
                $accReplace->save();

                $detail->account_id =  $accReplace->id;
                $detail->save();

                $account->is_expired = 1;
                $account->is_sold_ordinary = 0;
                $account->revendedor_id = null;
                $account->is_sold_extraordinary = 0;
                $account->screens_sold = 0;
                $account->save();

                foreach ($screensOfAccount as $item) {
                    $item->is_account_expired = 1;
                    $item->save();
                }

                \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Los clientes fueron trasladados a otras pantallas exitosamente", "success");
            } else {
                \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "No tienes Suficientes pantallas disponibles", "warning");
            }
        } else {
            $screens_to_change = 0;
            $renovations = array();
            $on_renovation = array();
            $screensOfAccount = Screens::where('account_id', '=', $id)->get();
            $validation_screens_free = 0;


            foreach ($screensOfAccount as $screen) {
                if ($screen->client_id != null || $screen->revendedor_id != null) {
                    $screens_to_change++;
                }
            }
            $screens_total_to_change = Screens::where('is_sold', '=', '0')->where("is_account_expired", "=", "0")->get();
            foreach ($screens_total_to_change as $key) {
                # code...
                // $acc_screen_to_change_aux =Accounts::where('id','=',$key->id)->first();

                $detail = OrderDetail::where('account_id', '=', $key->account_id)->where('type_order', '=', Order::TYPE_FULL)->where('is_renewed', '=', 0)->where('is_discarded', '=', 0)->first();
                if (!isset($detail)) {
                    $validation_screens_free++;
                    // array_push($renovations,$key);
                }
            }
            // dd($validation_screens_free >= $screens_to_change);
            if ($validation_screens_free >= $screens_to_change) {
                foreach ($screensOfAccount as $item) {
                    $item->is_account_expired = 1;
                    $item->save();
                }

                foreach ($screensOfAccount as $screen) {
                    // $screen->save();
                    if ($screen->client_id != null) {

                        $screenToChange = Screens::where('is_sold', '=', '0')->where("is_account_expired", "=", 0)->get();
                        foreach ($screenToChange as $key) {
                            # code...
                            $detail = OrderDetail::where('account_id', '=', $key->account_id)->where('type_order', '=', Order::TYPE_INDIVIDUAL)->where('is_renewed', '=', 0)->where('is_discarded', '=', 0)->first();
                            if (!isset($detail)) {
                                $order_detail = OrderDetail::where('customer_id', '=', $screen->client_id)->where('screen_id', '=', $screen->id)->where('is_renewed', '=', 0)->where('is_discarded', '=', 0)->first();
                                // dd($order_detail);
                                $key->client_id = $screen->client_id;
                                $key->date_sold = $screen->date_sold;
                                $key->date_expired = $screen->date_expired;
                                $key->is_sold = $screen->is_sold;
                                $key->code_screen = $screen->code_screen;
                                $key->price_of_membership = $screen->price_of_membership;
                                $key->device = $screen->device;
                                $key->ip = $screen->ip;
                                $key->save();

                                $screen->client_id = null;
                                $screen->date_sold = null;
                                $screen->code_screen = null;
                                $screen->date_expired = null;
                                $screen->is_sold = 0;
                                $screen->price_of_membership = 0;
                                $screen->device = null;
                                $screen->ip = null;
                                $screen->save();

                                $order_detail->screen_id = $key->id;
                                $order_detail->account_id = $key->account_id;
                                $order_detail->save();

                                // dd($order_detail);

                                $accReplace = Accounts::where('id', '=', $key->account_id)->first();
                                $type = TypeAccount::where('id', '=', $accReplace->type_account_id)->first();
                                if ($accReplace->screens_sold >=   ($type->available_screens - 1)) {
                                    $accReplace->is_sold_ordinary = 1;
                                }
                                $accReplace->screens_sold = $accReplace->screens_sold + 1;
                                $accReplace->save();


                                $account->is_expired = 1;
                                $account->is_sold_ordinary = 0;
                                $account->revendedor_id = null;
                                $account->is_sold_extraordinary = 0;
                                $account->screens_sold = 0;
                                $account->save();


                                // $account->screens_sold = $account->screens_sold - 1;
                                $account->save();
                                break;
                            }
                        }
                    }
                }
                \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Los clientes fueron trasladados a otras pantallas exitosamente", "success");
            } else {
                \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "No tienes Suficientes pantallas disponibles", "warning");
            }
        }
    }

    public function getSetActive($id)
    {

        $account = Accounts::where("id", "=", $id)->first();
        $account->is_expired = 0;
        $account->save();


        $screensOfAccount = Screens::where('account_id', '=', $id)->get();

        foreach ($screensOfAccount as $item) {
            $item->is_account_expired = 0;
            $item->save();
        }


        \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Los clientes fueron trasladados a otras pantallas exitosamente", "success");
    }


    //By the way, you can still create your own method in here... :)


}
