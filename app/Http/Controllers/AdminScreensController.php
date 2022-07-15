<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Customers;
use App\Models\Screens;
use App\Models\TypeAccount;
use Carbon\Carbon;
use Session;
use Request;
use DB;
use CRUDBooster;

class AdminScreensController extends \crocodicstudio\crudbooster\controllers\CBController
{

    public function cbInit()
    {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "name";
        $this->limit = "20";
        $this->orderby = "id,desc";
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = false;
        $this->button_edit = true;
        $this->button_delete = false;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->table = "screens";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "ID", "name" => "id"];
        $this->col[] = ["label" => "Cuenta", "name" => "account_id", "join" => "accounts,id"];
        $this->col[] = ["label" => "Cliente", "name" => "client_id", "join" => "customers,number_phone"];
        $this->col[] = ["label" => "Tipo de cuenta", "name" => "type_account_id", "join" => "type_account,name"];
        $this->col[] = ["label" => "Nombre", "name" => "name"];
        $this->col[] = ["label" => "Vendido", "name" => "is_sold", "callback" => function ($row) {
            if ($row->is_sold == 0) {
                return 'No';
            } else {
                return 'VENDIDA';
            }
        }];
        $this->col[] = ["label" => "Vence", "name" => "date_expired"];
        // $this->col[] = ["label" => "Esta vendida", "name" => "is_sold"];
        $this->col[] = ["label" => "Membresia", "name" => "price_of_membership", "type" => 'money'];
        $this->col[] = ["label" => "Dispositivo", "name" => "device"];
        $this->col[] = ["label" => "IP", "name" => "ip"];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Cuenta #', 'name' => 'account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'accounts,id'];
        $this->form[] = ['label' => 'Cliente', 'name' => 'client_id', 'type' => 'select2', 'validation' => 'min:0|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,id'];
        $this->form[] = ['label' => 'Tipo de cuenta', 'name' => 'type_account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'type_account,name'];
        $this->form[] = ['label' => 'Nombre', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:70', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        $this->form[] = ['label' => 'Fecha de venta', 'name' => 'date_sold', 'type' => 'text', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Fecha de expiracion', 'name' => 'date_expired', 'type' => 'text', 'width' => 'col-sm-10'];
        //$this->form[] = ['label'=>'Esta vendida?','name'=>'is_sold','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-10','dataenum'=>'Array'];
        //$this->form[] = ['label'=>'Pin','name'=>'pin','type'=>'number','validation'=>'min:3|max:32','width'=>'col-sm-10','help'=>'Minimum 5 characters. Please leave empty if you did not change the password.'];
        $this->form[] = ['label' => 'Pin', 'name' => 'code_screen', 'type' => 'number', 'validation' => 'required', 'width' => 'col-sm-10', 'readonly' => true];
        $this->form[] = ['label' => 'Dispositivo', 'name' => 'device', 'type' => 'text', 'width' => 'col-sm-9'];
        $this->form[] = ['label' => 'IP', 'name' => 'ip', 'type' => 'text', 'width' => 'col-sm-9'];

        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {
            $this->form[] = ['label' => 'Membresia', 'name' => 'price_of_membership', 'type' => 'money', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
        }

        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label'=>'Cuenta #','name'=>'account_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'account,id'];
        //$this->form[] = ['label'=>'Cliente','name'=>'client_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'client,id'];
        //$this->form[] = ['label'=>'Tipo de cuenta','name'=>'type_account_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'type_account,name'];
        //$this->form[] = ['label'=>'Nombre','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
        //$this->form[] = ['label'=>'Fecha de venta','name'=>'date_sold','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Fecha de expiracion','name'=>'date_expired','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Esta vendida?','name'=>'is_sold','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-10','dataenum'=>'Array'];
        //$this->form[] = ['label'=>'Pin','name'=>'pin','type'=>'password','validation'=>'min:3|max:32','width'=>'col-sm-10','help'=>'Minimum 5 characters. Please leave empty if you did not change the password.'];
        //$this->form[] = ['label'=>'Dispositivo','name'=>'device','type'=>'number','validation'=>'required','width'=>'col-sm-9'];
        //$this->form[] = ['label'=>'IP','name'=>'ip','type'=>'number','validation'=>'required','width'=>'col-sm-9'];
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
        //        $htmlButton = "<div><a class='btn btn-sm btn-default' title='Completar orden' onclick='' href='https://stock-manager.test/admin/orders/set-status/to-fill-order/10' target='_self'><i class='fa fa-retweet'></i> VERIFICAR PANTALLAS</a></div>";
        // $htmlButton = '<a style="margin-top:-51px" href="http://prueba.test/admin/screens/set-check-screens/" id="btn_advanced_filter" data-url-parameter="" title="Advanced Sort &amp; Filter" class="btn btn-sm btn-default ">
        //                 <i class="fa fa-bell"></i> NOTIFICAR CLIENTES
        //             </a>';

        $this->script_js = "
        //  let table = 

        //      let header  = document.getElementsByClassName('box-header')[0];
        //      let btnFilter  = document.getElementById('btn_advanced_filter');

        //      console.log(btnFilter.style);

        //       header.innerHTML+= table ;
        //     console.log(header);
        // console.log('s')
        let list = document.querySelectorAll('tr');
        // console.log(list.childNodes);
        let index = 0;
        let index2 = 0;

        list.forEach(function (item) {
            index++;
            let ele = item.childNodes[13];
            if(index %2 ==0){
                if(ele.innerText=='VENDIDA'){                    
                    item.style.backgroundColor = '#42AB49';
                    item.style.color = 'white';
                    item.style.fontWeight = 'bold';
                }
            }
            

        });

        // list.forEach(function (item) {
        //     index2 = 0;
        //     index++;
        //     if (item.innerText == 'VENDIDA') {

        //         list.forEach(function (item2) {
        //             index2++;

        //         })
        //                        item.style.backgroundColor = '#DD4B39';
        //                         item.style.fontWeight= 'bold';
        //                        item.style.color = '#FFFFFF';

        //     }
        // });
        ";


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

        //dd($postdata);
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

    public function getSetCheckScreens()
    {

        $urlsWhatsapp = '';

        $screens = Screens::where('is_sold', '=', '1')->get();

        foreach ($screens as $screen) {
            $dateExpired = Carbon::parse($screen->date_expired);
            $dateInstant = Carbon::parse('');

            //  dd($dateExpired->month == $dateInstant->month && $dateExpired->day >= $dateInstant->day);
            // dd($dateInstant->month);
            //var_dump($dateInstant->month );

            if ($dateExpired->year < $dateInstant->year) {
                $linkWp = $this->clearScreens($screen);

                $urlsWhatsapp .= '<br>' . $linkWp;
            } else {
                if ($dateExpired->month > $dateInstant->month) {
                    $linkWp = $this->clearScreens($screen);
                    $urlsWhatsapp .= '<br>' . $linkWp;
                } else {
                    if ($dateExpired->month == $dateInstant->month && $dateExpired->day <= $dateInstant->day) {
                        $linkWp = $this->clearScreens($screen);
                        $urlsWhatsapp .= '<br>' . $linkWp;
                    }
                }
            }
        }

        \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Pantallas Checkeadas Correctamente" . '<a style="margin-top:-51px" href="http://prueba.test/admin/screens/set-check-screens/" id="btn_advanced_filter" data-url-parameter="" title="Advanced Sort &amp; Filter" class="btn btn-sm btn-default ">
                        <i class="fa fa-retweet"></i> VERIFICAR PANTALLAS
                    </a>', "success");
    }

    public static function clearScreens($screen)
    {
        $client = Customers::where('id', '=', $screen->client_id)->first();

        $date_sold = Carbon::parse($screen->date_sold);

        Screens::where('id', $screen->id)->update([
            'client_id' => null,
            'date_sold' => null,
            'date_expired' => null,
            'is_sold' => 0,
            'device' => null,
            'ip' => null
        ]);

        $screensOfAccount = Screens::where('account_id', '=', $screen->account_id)->where('is_sold', '=', '1')->count();
        $type_account = TypeAccount::where('id', '=', $screen->type_account_id)->first();

        if ($screensOfAccount >= $type_account->available_screens) {
            Accounts::where('id', $screen->account_id)->update([
                'is_sold_ordinary' => 1
            ]);
        } else {
            Accounts::where('id', $screen->account_id)->update([
                'is_sold_ordinary' => 0
            ]);
        }

        $text = 'Señor@' . $client->name . ', Su Pantalla ' . $type_account->name . ' Comprada el dia ' . $date_sold->day . ' del mes ' . $date_sold->month . ' del año ' . $date_sold->year . ' Ha vencido Desea renovar? ';

        return 'https://api.whatsapp.com/send/?phone=57' . $client->number_phone . '&text=' . $text;
    }


    //By the way, you can still create your own method in here... :)


}
