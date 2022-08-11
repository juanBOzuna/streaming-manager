<?php

namespace App\Http\Controllers;

use App\Models\CmsUsers;
use App\Models\Customers;
use App\Models\TypeAccount;
use App\Models\Screens;
use App\Models\Accounts;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Cassandra\Custom;
use Session;
use Request;
use DB;
use CRUDBooster;

class AdminCustomersController extends \crocodicstudio\crudbooster\controllers\CBController
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
        $this->button_add = true;
        $this->button_edit = true;
        $this->button_delete = false;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->table = "customers";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "Nombre", "name" => "name"];

        $this->col[] = ["label" => "Telefono", "name" => "number_phone"];
        $this->col[] = ["label" => "Revendedor", "name" => "revendedor_id", "callback" => function ($row) {

            if ($row->revendedor_id != null) {
                $cliente = CmsUsers::where('id', '=', $row->revendedor_id)->first();
                return  "#" . $row->revendedor_id . " | " . $cliente->name;
            } else {
                return 'No tiene';
            }
        }];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Nombre', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:70', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        $this->form[] = ['label' => 'Telefono', 'name' => 'number_phone', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
        // $this->form[] = ['label' => 'Revendedor', 'name' => 'number_phone', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10',"callback" => function ($row) {
        //          if($row->revendedor_id==null){
        //            return 'No tiene';
        //          }else{
        //              return "Revendedor: ". $row->revendedor_id;
        //          }
        //        }];
        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label' => 'Nombre', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:70', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        //$this->form[] = ['label' => 'Telefono', 'name' => 'number_phone', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
        # OLD END FORM

        // dd(\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod());


        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {

            $this->form[] = ["label" => "ID", "name" => "name", "callback" => function ($row) {
                return $row->id;
            }];


            $screens = [];
            $screens[] = ['label' => 'Id', 'name' => 'id', 'type' => 'number'];
            $screens[] = ['label' => 'Cuenta', 'name' => 'email', 'type' => 'text'];
            $screens[] = ['label' => 'Vence', 'name' => 'date_expired'];
            $screens[] = ['label' => 'Pin', 'name' => 'code_screen', 'type' => 'number'];
            $screens[] = ['label' => 'Dispositvo', 'name' => 'device', 'type' => 'text'];
            $screens[] = ['label' => 'IP', 'name' => 'ip', 'type' => 'number'];

            // $this->form[]  = ['label' => 'Pantallas', 'name' => 'screens', 'type' => 'child', 'columns' => $screens, 'table' => 'screens', 'foreign_key' => 'client_id'];
        }

        $this->addaction = array();

        //  $this->addaction[]= [
        //   'label' => '',
        //  'url' => CRUDBooster::mainpath('set-status/[id]'),
        //   'icon' => 'fa fa-eye',
        //  ];


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
        //        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {
        //            $this->script_js = "
        //             console.log('Entro al js')
        //             let area = document.getElementById(parent-form-area);
        //             console.log(area);
        //            ";
        //        }


        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {
            $urlPage = $_SERVER['REQUEST_URI'];
            $porciones = explode("?", $urlPage);
            $porciones = explode("/", $porciones[0]);
            //
            //            $screens[] = ['label' => 'Id', 'name' => 'id', 'type' => 'number'];
            //            $screens[] = ['label' => 'Cuenta', 'name' => 'email', 'type' => 'text'];
            //            $screens[] = ['label' => 'Vence', 'name' => 'date_expired'];
            //            $screens[] = ['label' => 'Pin', 'name' => 'code_screen', 'type' => 'number'];
            //            $screens[] = ['label' => 'Dispositvo', 'name' => 'device', 'type' => 'text'];
            //            $screens[] = ['label' => 'IP', 'name' => 'ip', 'type' => 'number'];

            $screens = Screens::where('client_id', '=', $porciones[sizeof($porciones) - 1])->where('is_sold', '=', '1')->get();
            $accountsFull = OrderDetail::where('customer_id', '=', $porciones[sizeof($porciones) - 1])->where('screen_id', '=', null)->where('is_renewed', '=', 0)->where('is_venta_revendedor', '=', 0)->get();

            $trHtml = '';
            $trHtml2 = '';

            foreach ($screens as $screen) {
                $type = TypeAccount::where('id', '=', $screen->type_account_id)->first();
                $nameType = $type->name;
                $detail = OrderDetail::where('screen_id', '=', $screen->id)->where('account_id', '=', $screen->account_id)->where('customer_id', '=', $porciones[sizeof($porciones) - 1])->where('is_renewed', '=', 0)->where('is_discarded', '=', 0)->where('screen_id', '!=', null)->where('type_order', '!=', Order::TYPE_FULL)->first();
                // dd(env('LINK_SYSTEM'));
                $trHtml .= '  <tr>
               <th scope="row">' . $detail->orders_id . '</th>
               <th scope="row">' . $screen->id . '</th>
               <td>' . $nameType . ' </td>
               <td>' . $screen->email . ' </td>
               <td>' . Carbon::parse($screen->date_expired)->format('Y-m-d H:i:s') . '</td>
               <td>' . $screen->code_screen . '</td>
               <td>' . $screen->device . '</td>
               <td>' . $screen->ip . ' </td>
              <td> <a href="' . env('LINK_SYSTEM') . 'screens/edit/' . $screen->id . '?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Fcustomers" target="_blank" >Editar</a> </td>
               <!-- <td> <button onclick ="actualizar()" > sdfsd </button>  </td> -->
               </tr>';
            }

            foreach ($accountsFull as $detail) {
                $accountSelected = Accounts::where('id', '=', $detail->account_id)->first();
                $type = TypeAccount::where('id', '=', $accountSelected->type_account_id)->first();
                $nameType = $type->name;
                $detail = OrderDetail::where('account_id', '=', $accountSelected->id)->where('customer_id', '=', $porciones[sizeof($porciones) - 1])->where('is_renewed', '=', 0)->where('screen_id', '=', null)->where('type_order', '=', Order::TYPE_FULL)->first();
                $trHtml2 .= '  <tr>
               <th scope="row">' . $detail->orders_id . '</th>
               <th scope="row">' . $accountSelected->id . '</th>
               <td>' . $nameType . ' </td>
               <td>' . $accountSelected->email . ' </td>
               <td>' . Carbon::parse($detail->date_finish)->format('Y-m-d H:i:s') . '</td>
               
               <!-- <td> <button onclick ="actualizar()" > sdfsd </button>  </td> -->
               </tr>';
            }


            $htmlForTable = '
                <br>
                <span><strong>  PANTALLAS INDIVIDUALES DE ESTE CLIENTE</strong></span>
                <br>
                <br>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">ORDEN</th>
                      <th scope="col">ID pantalla</th>
			<th scope="col">Tipo</th>
                      <th scope="col">Cuenta</th>
                      <th scope="col">Vence</th>
                      <th scope="col">Pin</th>
                      <th scope="col">Dispositvo</th>
                      <th scope="col">IP</th>
                       <th scope="col"> Editar </th>
                    </tr>
                  </thead>
                  <tbody>
                    ' . $trHtml . '
                  </tbody>
                </table>';

            $htmlForTable2 = '
                <br>
                <br>
                <span><strong>  CUENTAS COMPLETAS DE ESTE CLIENTE</strong></span>
                <br>
                <br>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">ORDEN</th>
                      <th scope="col">ID cuenta</th>
                      <th scope="col">Tipo</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Vence</th>
                    </tr>
                  </thead>
                  <tbody>
                    ' . $trHtml2 . '
                  </tbody>
                </table>';

            $this->script_js = "
             let table = " . json_encode($htmlForTable) . "
             let table2 = " . json_encode($htmlForTable2) . "
             console.log('Entro al js')
             let area = document.getElementById('parent-form-area');
             area.innerHTML+= table ;
            //  let area = document.getElementById('parent-form-area');
             area.innerHTML+= table2 ;

             function actualizar(){
                alert();
             }

            ";


            //  dd($porciones[sizeof($porciones) - 1]);
        }

        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getIndex") {
            $id = \crocodicstudio\crudbooster\helpers\CRUDBooster::myId();
            $idP = \crocodicstudio\crudbooster\helpers\CRUDBooster::myPrivilegeId();
            $this->script_js = "

          let list = document.querySelectorAll('td');
           let idP = " . $idP . ";
           let myId = " . $id . ";
                        console.log(list);
                        let index = 0;
                        let index2 = 0;
                        list.forEach(function (item) {
                            index2 = 0;
                            index++
                            if (item.innerText != 'Revendedor: '+myId && idP!=1) {
                                list.forEach(function (item2) {
                                    index2++;
//                                    for (let index3 = 0; index3 < 8; index3++) {
//                                        if (index2 == index - index3 && index2 != index) {
//                                        item2.remove();
//                                        }
//                                    }
//                                    for (let index3 = 0; index3 < 4; index3++) {
//                                        if (index2 == index + index3 && index2 != index) {
//                                         item2.remove();
//                                        }
//                                    }
                                })
//                                 item.remove();
                            }
                        });



             let addData = document.querySelector('#btn_add_new_data');
             addData.text = 'Agregar Clientes ';
             addData.innerHTML += `<i class='fa fa-plus-circle' style='margin-left: 5px;'></i> `;

             let showData = document.querySelector('#btn_show_data');
             showData.text = 'Ver todos ';
             showData.innerHTML += `<i class='fa fa-table' style='margin-left: 5px;'></i>`;


         ";
        }


        // $this->script_js = "";


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
        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::myId() != 1) {
            $query->where('revendedor_id', "=", \crocodicstudio\crudbooster\helpers\CRUDBooster::myId());
        }
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
        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::myPrivilegeId() == Customers::REVENDEDOR) {
            $postdata['revendedor_id'] = \crocodicstudio\crudbooster\helpers\CRUDBooster::myId();
        }
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


    //By the way, you can still create your own method in here... :)

    public function getSetStatus($id)
    {
        //        $asd = Accounts::where('id', '=', $id)->first();
        //        dd($asd);
    }
}
