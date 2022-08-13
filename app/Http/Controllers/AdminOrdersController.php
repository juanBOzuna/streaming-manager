<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Screens;
use App\Models\TypeAccount;
use App\Models\Customers;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use phpDocumentor\Reflection\Types\Boolean;
use Session;
use Request;
use DB;

class AdminOrdersController extends \crocodicstudio\crudbooster\controllers\CBController
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
        $this->table = "orders";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "ID", "name" => "id"];
        $this->col[] = ["label" => "Cliente", "name" => "customers_id", "join" => "customers,name"];
        $this->col[] = ["label" => "Telefono", "name" => "customers_id", "join" => "customers,number_phone"];
        $this->col[] = ["label" => "Precio Total", "name" => "total_price"];
        $this->col[] = ["label" => "Estado", "name" => "is_discarded_all", "callback" => function ($row) {
            if ($row->is_discarded_all == 0) {
                return 'Normal';
            } else {
                return 'Descartada Totalmente';
            }
        }];
        $this->col[] = ["label" => "Pantallas Descartadas", "name" => "number_screens_discarded"];
        // $this->col[] = ["label" => "Pantallas Descartadas", "name" => "number_screens_discarded"];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Cliente', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,name', 'datatable_format' => 'name,\'  -  \',number_phone'];


        $columns = [];
        if (CRUDBooster::getCurrentMethod() == "getDetail") {
            // dd($_REQUEST);
            $columns[] = ['label' => 'Tipo de venta', 'name' => 'type_order', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'ID', 'name' => 'id', 'type' => 'number', 'required' => true];
        }

        $columns[] = ['label' => 'Tipo de Servicio', 'name' => 'type_account_id', 'type' => 'datamodal', 'datamodal_table' => 'type_account', 'datamodal_columns' => 'name', 'datamodal_select_to' => 'Nombre:name', 'required' => true];
        if (CRUDBooster::getCurrentMethod() == "getDetail") {

            $this->form[] = ["label" => "Telefono", "name" => "customers_id", 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,number_phone'];
            $this->form[] = ['label' => 'Precio Total', 'name' => 'total_price', 'type' => 'money', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];

            $columns[] = ['label' => 'Dias', 'name' => 'membership_days', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'Pantalla', 'name' => 'screen_id', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'Cuenta', 'name' => 'account_id', 'type' => 'number', 'required' => true];
            // $columns[] = ['label' => 'Precio', 'name' => 'price_of_membership_days', 'type' => 'money', 'required' => true];
            $columns[] = ['label' => 'Vendida', 'name' => 'created_at', 'type' => 'text', 'required' => true];
            $columns[] = ['label' => 'Vence', 'name' => 'finish_date', 'type' => 'text', 'required' => true];
            $columns[] = ['label' => 'Esta renovada', 'name' => 'is_renewed', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'Numero de renovaciones', 'name' => 'number_renovations', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'Venta padre', 'name' => 'parent_order_detail', 'type' => 'number', 'required' => true];
        } else {
            $columns[] = ['label' => 'Numero de pantallas', 'name' => 'number_screens', 'type' => 'number', 'required' => true];
            $columns[] = ['label' => 'Dias de membresia', 'name' => 'membership_days', 'type' => 'number', 'required' => true];
        }
        $this->form[] = ['label' => 'Venta', 'name' => 'order_details', 'type' => 'child', 'columns' => $columns, 'table' => 'order_details', 'foreign_key' => 'orders_id'];

        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label'=>'Cliente','name'=>'customer_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'customer,id'];
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
        // $this->table_row_color =['condition'=>"['is_discarded'] == '1'","color"=>"success"];
        $this->table_row_color[] = ['condition' => "[is_discarded_all] == '1'", "color" => "danger"];


        /*
        | ----------------------------------------------------------------------
        | You may use this bellow array to add statistic at dashboard
        | ----------------------------------------------------------------------
        | @label, @count, @icon, @color
        |
        */
        $this->index_statistic = array();


        /*
        | ----------------------------------------------------------------------
        | Add javascript at body
        | ----------------------------------------------------------------------
        | javascript code in the variable
        | $this->script_js = "function() { ... }";
        |
        */

        if (CRUDBooster::getCurrentMethod() == "getDetail") {
            $this->script_js = "
            let tabla = document.querySelector('#table-order_details');


            // let trs=  ;

            // for(let i =0 ; i<tabla.childNodes[3].childNodes.length){
            //     console.log(item[i]);
            // }
            let i=0;
            tabla.childNodes[3].childNodes.forEach(function (item) {
               let=i++;
               if(i%2==0){
                // console.log(item.children[8].innerText);
                if(item.children[8].innerText=='0'){
                    item.children[8].innerText = 'No';
                    // item.children[8].style.color = '#DD4B39';
                    item.children[8].style.fontWeight = 'bold';
                }else{
                    item.children[1].childElementCount=1;
                    item.children[8].innerText = 'Si';
                    item.children[8].style.color = '#04AA6D';
                    item.children[8].style.fontWeight = 'bold';
                }
                
               }
               
             });

             panel = document.querySelector('.panel-heading');
             panel.innerHTML+='
            
           
             ';
             console.log( panel);
            ";
        } else {
            $js_screens = " var screens = new Array();";

            $i = 0;
            foreach (TypeAccount::get() as $key) {
                # code...
                $pVendidas =  Screens::where('type_account_id', '=', $key->id)->where('is_sold', '=', '1')->where("is_account_expired", "=", "0")->count();
                $cuentas  = Accounts::where('type_account_id', '=', $key->id)->where('is_expired', '=', '0')->count();
                $disccount = ($key->total_screens - $key->available_screens) * $cuentas;
                $total_p = $cuentas * $key->total_screens;
                $total_p = (($total_p - $pVendidas) - $cuentas) - ($disccount - $cuentas);
                $js_screens .= '
                screens[' . $i . '] ={"name":"' . $key->name . '","screens":' . $total_p . ',"type_id":' . $key->id . '};
                ';
                $i++;
            }

            $this->script_js = "
                " . $js_screens . "
                let boton = document.getElementById('btn-add-table-venta');
                boton.onmouseover = function(){
                    let selectwe = document.querySelector('.input-id');
                    let inputNumberScreens = document.querySelector('#ventanumber_screens');

                    for(var clave in screens) {
                        if(selectwe.value == screens[clave]['type_id']  ){
                            if(inputNumberScreens.value>screens[clave]['screens'] ){
                                alert('No hay suficientes pantallas para lo que quieres vender ('+screens[clave]['name']+') , revisa y crea nuevas pantallas!');
                            }
                        }
                    }
                }
            ";
        }





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
        $query->where('is_venta_revendedor', '=', '0')->where('type_order', '=', Order::TYPE_INDIVIDUAL);
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
        $types = $this->ordenate_types();
        // dd();
        $index_types = 0;
        foreach ($types as $item) {
            $accounts_completed = 0;
            $id_account_selected = 0;
            $index_screens_gain = 0;
            $accountsVerified = $this->get_accounts_general($types[$index_types]['type_account_id']);
            // dd($accountsVerified);
            // dd(sizeof($types[$index_types]['screens_gain']));
            $iEcho = 0;
            $iEcho2 = 0;
            while ($types[$index_types]['number_screens_gain'] != $types[$index_types]['number_screens'] || $accounts_completed == $accountsVerified['total']) {
                # code...
                $iEcho++;

                $accounts = Accounts::where('type_account_id', '=', $types[$index_types]['type_account_id'])
                    ->where('is_sold_ordinary', '=', 0)
                    ->where('id', '>', $id_account_selected)
                    ->where('is_sold_extraordinary', '=', 0)
                    ->where('revendedor_id', '=', null)
                    ->where('is_expired', '=', 0)
                    ->first();

                if (!isset($accounts)) {
                    $id_account_selected = 0;
                } else {
                    // if ($iEcho == 6) {

                    // }
                    $id_account_selected = $accounts->id;
                    $type = TypeAccount::where('id', '=', $accounts->type_account_id)->first();
                    $screenConsult = Screens::where('account_id', '=', $accounts->id)->where('is_sold', '=', 0)->where('profile_number', '>', 1)->where('profile_number', '<', ($type->available_screens + 2))->orderBy('profile_number', 'asc');
                    if (sizeof($types[$index_types]['screens_gain']) != 0) {
                        foreach ($types as $itemAux) {
                            if ($itemAux['type_account_id'] == $item['type_account_id']) {
                                foreach ($itemAux['screens_gain'] as $key) {
                                    # code...
                                    $screenConsult->where('id', '!=', $key['screen_id']);
                                }
                            }
                        }
                    }
                    $screen = $screenConsult->first();


                    if (null != $screen) {
                        $types[$index_types]['number_screens_gain'] = ($types[$index_types]['number_screens_gain'] + 1);
                        $types[$index_types]['screens_gain'][$index_screens_gain] = [
                            'screen_id' => $screen->id,
                            'account_id' => $screen->account_id
                        ];

                        if ($screen->profile_number >= ($type->available_screens + 2)) {
                            $accounts_completed++;
                        }
                        $index_screens_gain++;
                    }

                    // if ($iEcho == 6) {
                    //     var_dump($types[$index_types]['number_screens_gain']);
                    //     dd($types[$index_types]['number_screens_gain'] != ($types[$index_types]['number_screens'] - 1));
                    // }
                }
            }
            $index_types++;
        }

        $this->construct_order($types);

        // dd($types);
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

        $details = OrderDetail::where('orders_id', '=', $id)->get();

        // dd($details);

        foreach ($details as $key) {
            # code...
            $screen = Screens::where('id', '=', $key->screen_id)->update([
                'client_id' => null,
                'date_sold' => null,
                'code_screen' => null,
                'date_expired' => null,
                'date_sold' => null,
                'price_of_membership' => 0,
                'date_expired' => null,
                'is_sold' => 0,
                'device' => null,
                'ip' => null
            ]);

            $account_of_screen =  Accounts::where('id', '=', $key->account_id)->first();
            // dd($account_of_screen);
            $account_of_screen->screens_sold = ($account_of_screen->screens_sold - 1);
            $account_of_screen->save();
            // Screens::where('id', $screen)

            $key->delete();
        }
        $order = Order::where('id', '=', $id)->delete();
        // $order->delete();
        // $order = Order::where('id', '=', $id)->delete();
        // $order->delete();

        \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Se creo el pedido exitosamente", "success");


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

    public static function construct_order($list_types)
    {
        // $host =  env('LINK_SYSTEM');
        // header("Location: $host/orders/edit/$order->id");
        $total = 0;
        foreach ($list_types as $key) {
            # code...
            $total += $key['price_of_membership_days'];
        }
        // dd($total);
        $customer_id = request()['customers_id'];
        $order = Order::create([
            'customers_id' => $customer_id,
            'type_order' => Order::TYPE_INDIVIDUAL,
            'total_price' => intval($total)
        ]);
        // dd($list_types);
        foreach ($list_types as $key) {
            # code...
            date_default_timezone_set('America/Bogota');
            $dateInstant = Carbon::parse('');
            $dateExpired = $dateInstant->addDays($key['membership_days']);

            foreach ($key['screens_gain'] as $screenClaimed) {
                # code...
                $screenSelected = Screens::where('id', $screenClaimed['screen_id'])->first();
                $account = Accounts::where('id', '=', $screenSelected->account_id)->first();
                $price = ($key['price_of_membership_days'] / $key['number_screens']);

                OrderDetail::create([
                    'orders_id' => $order->id,
                    'type_account_id' => $key['type_account_id'],
                    'customer_id' => $customer_id,
                    'screen_id' => $screenClaimed['screen_id'],
                    'type_order' => Order::TYPE_INDIVIDUAL,
                    'account_id' => $screenClaimed['account_id'],
                    'membership_days' => $key['membership_days'],
                    'price_of_membership_days' =>  $price,
                    'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s')
                ]);

                $screenSelected->update([
                    'client_id' => $customer_id,
                    'is_sold' => 1,
                    'date_sold' => Carbon::parse('')->format('Y-m-d H:i:s'),
                    'price_of_membership' =>  $price,
                    'date_expired' => (string)$dateExpired->format('Y-m-d H:i:s')
                ]);
                $account->screens_sold = ($account->screens_sold + 1);
                $account->save();
            }
        }


        // echo "
        // <script>
        // window.location.href = " . env('LINK_SYSTEM') . "'orders/edit/" . $order->id . "?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Forders&parent_id=&parent_field=';
        // </script>
        // ";

        // $host =  env('LINK_SYSTEM');
        // header("Location: $host/orders/edit/$order->id");
        // session(['id' => "$order->id"]);
        // echo "
        // <script>
        // window.location.href = " . env('LINK_SYSTEM') . "'orders/edit/" .  $order->id . "?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Forders&parent_id=&parent_field=';
        // </script>
        // ";
        $host = env('LINK_SYSTEM');
        CRUDBooster::redirect($_SERVER['HTTP_REFERER'], 'Se creo el pedido exitosamente' . '</br> <td><a href="' . $host . 'orders/detail/' . $order->id . '?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Forders&parent_id=&parent_field=">Ver Pedido</a>  </td>', "success");
    }

    public static function generateOrder($typeAccountToTypeAccount, $listToList, $searchToSearch, $errorsInSearch, $containsError, $request)
    {
        $total_price = 0;

        $order = Order::create([
            'customers_id' => $request['customers_id'],
            'type_order' => Order::TYPE_INDIVIDUAL,
            'total_price' => 0
        ]);

        $index = 0;
        foreach ($typeAccountToTypeAccount as $typeAccount) {
            $price_of_membership_days = $typeAccount->price_day * $request['venta-membership_days'][$index];
            date_default_timezone_set('America/Bogota');


            $screens = $listToList[$index];
            $dateInstant = Carbon::parse('');
            $dateExpired = $dateInstant->addDays($request['venta-membership_days'][$index]);

            //dd($dateExpired);

            foreach ($screens as $screen) {

                $screenSelected = Screens::where('id', $screen)->first();

                OrderDetail::create([
                    'orders_id' => $order->id,
                    'type_account_id' => $typeAccount->id,
                    'customer_id' => $request['customers_id'],
                    'screen_id' => $screenSelected->id,
                    'type_order' => Order::TYPE_INDIVIDUAL,
                    'account_id' => $screenSelected->account_id,
                    'membership_days' => $request['venta-membership_days'][$index],
                    'price_of_membership_days' => intval($price_of_membership_days),
                    'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s')
                ]);

                $screenSelected->update([
                    'client_id' => $request['customers_id'],
                    'is_sold' => 1,
                    'date_sold' => Carbon::parse('')->format('Y-m-d H:i:s'),
                    'price_of_membership' => intval($price_of_membership_days),
                    'date_expired' => $dateExpired->format('Y-m-d H:i:s')
                ]);
                $total_price = $price_of_membership_days + $total_price;
            }
            $index++;
        }

        $order->update([
            'total_price' => intval($total_price)
        ]);

        if ($containsError == 0) {
            CRUDBooster::redirect($_SERVER['HTTP_REFERER'] . '/is_sold_successfull=1', "Se creo el pedido exitosamente", "success");
            //     echo "
            // <script>
            // let datos = " . json_encode($datos) . "
            // let telefono = " . json_encode($telefono) . "
            // //alert('https://wa.me/'+telefono+'?text='+'*COMUNICADO%20MOSERCON*%0A%0AEstimado%20cliente%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20caducara%20esta%20noche%0A%0A' + datos + 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20blokeara%20su%20pantalla%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*');
            // window.open('https://wa.me/3044155592','_blank');
            // // window.location.href = 'http://streaming-manager.test/admin/customers_expired_tomorrow'
            // </script>
            // ";
        } else {
            CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Se creo el pedido exitosamente" . $errorsInSearch, "success");
        }
    }



    // public static function clearScreens($listScreens)
    // {
    //     foreach ($listScreens as $screen) {
    //         //            $screenAct = Screens::where('id', '=', $screen)->orderBy('profile_number', 'asc')->get();
    //         // $acc = 
    //         $scr = Screens::where('id', '=', $screen)->first();
    //         $acc = Accounts::where('id', '=', $screen->account_id)->first();
    //         if ($acc->screens_sold > 0) {
    //             $type_account = TypeAccount::where('id', '=', $acc->type_account_id);
    //             $acc->screens_sold = ($acc->screens_sold - 1);

    //             if ($acc->screens_Sold >= $type_account->available_screens) {
    //                 $acc->is_sold_ordinary = 1;
    //             } else {
    //                 $acc->is_sold_ordinary = 0;
    //             }
    //             $acc->save();
    //         }


    //         Screens::where('id', $screen)->update([
    //             'client_id' => null,
    //             'date_sold' => null,
    //             'code_screen' => null,
    //             'date_expired' => null,
    //             'price_of_membership' => 0,
    //             'date_sold' => null,
    //             'is_sold' => 0,
    //             'device' => null,
    //             'ip' => null
    //         ]);
    //     }
    // }

    public static function ordenate_types()
    {
        $types = [];
        $types_index = 0;
        foreach (request()['venta-type_account_id'] as $item) {
            $type = TypeAccount::where('id', '=', $item)->first();
            // dd($type)
            $types[$types_index] = [
                'index' => $types_index,
                'type_account_id' => $item,
                'membership_days' => request()['venta-membership_days'][($types_index)],
                'number_screens' => request()['venta-number_screens'][($types_index)],
                'price_of_membership_days' => ((intval(strval($type->price_day)) * intval(strval(request()['venta-membership_days'][($types_index)]))) * request()['venta-number_screens'][($types_index)]),
                'number_screens_gain' => 0,
                'screens_gain' => []
            ];
            $types_index++;
        }
        return $types;
    }

    public static function get_accounts_general($id_type)
    {
        $accounts = Accounts::where('type_account_id', '=', $id_type)
            ->where('is_sold_ordinary', '=', 0)
            ->where('is_sold_extraordinary', '=', 0)
            ->where('revendedor_id', '=', null)
            ->where('is_expired', '=', 0)
            ->get();
        $type_name = TypeAccount::where('id', '=', $id_type)->first()->name;
        return [
            'type_name' => $type_name,
            'total' => sizeof($accounts)
        ];
    }

    //By the way, you can still create your own method in here... :)
}
