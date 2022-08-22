<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Screens;
use App\Models\Accounts;
use App\Models\TypeAccount;
use App\Models\TypeDevice;
use App\Models\Usuarios;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster as HelpersCRUDBooster;
use Illuminate\Support\Facades\DB as FacadesDB;

class AdminOrderDetailsRenovationsController extends \crocodicstudio\crudbooster\controllers\CBController
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
		$this->button_add = false;
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "order_details";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE

		$this->col = [];
		$this->col[] = ["label" => "Cliente", "name" => "customers.name"];
		$this->col[] = ["label" => "Telefono", "name" => "customers.number_phone"];
		$this->col[] = ["label" => "Orden", "name" => "order_details.orders_id"];
		$this->col[] = ["label" => "Correo", "name" => "accounts.email"];
		$this->col[] = ["label" => "Pantalla #", "name" => "screens.name"];
		$this->col[] = ["label" => "Fecha de COMPRA", "name" => "screens.date_sold"];
		$this->col[] = ["label" => "Fecha de VENCIMIENTO", "name" => "order_details.finish_date"];
		$this->col[] = ["label" => "Aviso vencimiento", "name" => "order_details.is_notified", "callback" => function ($row) {
        if($row->is_notified==1){
            return "AVISADO";
        }else{
            return "NO AVISADO";
        }
        }];

        $this->col[] = ["label" => "Estado", "name" => "order_details.is_renewed", "callback" => function ($row) {
            $val =0;
            if($row->is_renewed==1){
                return "RENOVADO";
                $val =1;
            }
            if($row->is_discarded==1){
                return "DESECHADA";
                $val =1;
            }
            if( $val !=1){
                return "SIN DETERMINAR";
            }
            }];
		// $this->col[] = ["label"=>"Membership Days","name"=>"membership_days"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {
            $this->form[] = ['label'=>'ID del Detalle:','name'=>'id','type'=>'number','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'ID de la venta:','name'=>'orders_id','type'=>'number','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Cliente:','name'=>'customer_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'customers,name'];
            $this->form[] = ['label'=>'Telefono:','name'=>'customer_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'customers,number_phone'];
            $this->form[] = ['label'=>'Cuenta:','name'=>'account_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'accounts,email'];
            $this->form[] = ['label'=>'Pantalla:','name'=>'screen_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'screens,name'];
            $this->form[] = ['label'=>'Fecha de Compra:','name'=>'created_at','type'=>'date','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Fecha de Vencimiento','name'=>'finish_date','type'=>'date','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=>'Estado (avisado:1, no avisado:0)','name'=>'is_notified','type'=>'number','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];

        }
		// $this->form[] = ['label'=>'Orders Id','name'=>'orders_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'orders,id'];
		// $this->form[] = ['label'=>'Type Account Id','name'=>'type_account_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'type_account,name'];
		// $this->form[] = ['label'=>'Customer Id','name'=>'customer_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'customer,id'];
		// $this->form[] = ['label'=>'Number Screens','name'=>'number_screens','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Screen Id','name'=>'screen_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'screen,id'];
		// $this->form[] = ['label'=>'Account Id','name'=>'account_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'account,id'];
		// $this->form[] = ['label'=>'Membership Days','name'=>'membership_days','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Price Of Membership Days','name'=>'price_of_membership_days','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Finish Date','name'=>'finish_date','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Is Notified','name'=>'is_notified','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-10','dataenum'=>'Array'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Orders Id","name"=>"orders_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"orders,id"];
		//$this->form[] = ["label"=>"Type Account Id","name"=>"type_account_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"type_account,name"];
		//$this->form[] = ["label"=>"Customer Id","name"=>"customer_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"customer,id"];
		//$this->form[] = ["label"=>"Number Screens","name"=>"number_screens","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
		//$this->form[] = ["label"=>"Screen Id","name"=>"screen_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"screen,id"];
		//$this->form[] = ["label"=>"Account Id","name"=>"account_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"account,id"];
		//$this->form[] = ["label"=>"Membership Days","name"=>"membership_days","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Price Of Membership Days","name"=>"price_of_membership_days","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Finish Date","name"=>"finish_date","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
		//$this->form[] = ["label"=>"Is Notified","name"=>"is_notified","type"=>"radio","required"=>TRUE,"validation"=>"required|integer","dataenum"=>"Array"];
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
		$this->addaction[] = [
			'label' => 'Renovar',
			'url' => CRUDBooster::mainpath('set-renovar/[id]'),
			'color' => 'success',
			'icon' => 'fa fa-refresh',
            'showIf' => "[is_renewed]==0 && [is_discarded]==0  "
		];
		$this->addaction[] = [
			'label' => 'Desechar',
			'url' => CRUDBooster::mainpath('set-desechar/[id]'),
			'color' => 'danger',
			'icon' => 'fa fa-ban',
            'showIf' => "[is_renewed]==0 && [is_discarded]==0 "
		];

        $this->addaction[] = [
			'label' => 'Notificar',
			'url' => CRUDBooster::mainpath('set-notificar/[id]'),
			'color' => 'success',
			'icon' => 'fa fa-bell',
            'showIf' => "[is_renewed]==1"
		];

        $this->addaction[] = [
			'label' => 'NOTIFICADO',
			'url' => CRUDBooster::mainpath('set-notificado/[id]'),
			'color' => 'warning',
			'icon' => 'fa fa-refresh',
            'showIf' => "[is_renewed]==1"
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
		$this->alert        = array();



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
        $this->table_row_color[] = ['condition' => "[is_notified] == '1'", "color" => "success"];


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
		$this->script_js = NULL;
        $telefono_send_sms = 3044155592;
        $link_sms = "Hola";
        if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {

            $this->script_js = "
            document.querySelector('#content_section').innerHTML= ` <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'>
            <a  href='https://api.whatsapp.com/send?phone=" . $telefono_send_sms . "&text=" . $link_sms . "' class='float' target='_blank'>
            <i class='fa fa-whatsapp my-float'></i>
            </a>`+document.querySelector('#content_section').innerHTML;
            document.querySelector('#content_section').innerHTML+= `
           <style type='text/css'>
                .float{
                    position:fixed;
                    width:55px;
                    height:55px;
                    bottom:35px;
                    right:35px;
                    background-color:#25d366;
                    color:#FFF;
                    border-radius:45px;
                    text-align:center;
                font-size:25px;
                    box-shadow: 2px 2px 3px #999;
                z-index:100;
                }
                .float:hover {
                    text-decoration: none;
                    color: #25d366;
                background-color:#fff;
                }

                .my-float{
                    margin-top:16px;
                }
           </style>
            `;

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
	public function hook_query_index(&$query) // este se ejecuta antes? si, no es el as, porque si se lo quito y uso el number_phone tampoco sale
	{
		date_default_timezone_set('America/Bogota');
		$da = \Carbon\Carbon::parse("");
		$da->addDays(2);
		$month = $da->month > 9 ? '' . $da->month : '0' . $da->month;
		$day = $da->day > 9 ? '' . $da->day : '0' . $da->day;
		$dateSimpli = $da->year . '-' . $month . '-' . $day . ' 00:00:00';
		//dd($dateSimpli);
		//Your code here
		// dd($dateSimpli);
		$query->where('order_details.is_discarded', '=', '0')->where('order_details.is_notified_renovation', '=', '0')->where('type_order', '!=', Order::TYPE_FULL)->where('is_venta_revendedor', '=', '0')->where('finish_date', '<', $dateSimpli)->where('screen_id', '!=', null)->join('customers', 'order_details.customer_id', '=', 'customers.id')
			->join('accounts', 'order_details.account_id', '=', 'accounts.id')
			->join('screens', 'order_details.screen_id', 'screens.id')
			->select('order_details.*',  'customers.name', 'customers.number_phone', 'accounts.email', 'screens.name', 'screens.date_sold', 'screens.date_expired')
			->get();

		// dd($query); // pues si y cual es el error
	}

	/*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		// echo $column_index. " ". $column_value;
		// dd("");
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
	public function getSetRenovar($id)
	{
		$detail = OrderDetail::where('id', '=', $id)->first();
		date_default_timezone_set('America/Bogota');
		$dateInstant = Carbon::parse('');
		$date_of_created_At = Carbon::parse('');
		$screen = Screens::where('id', '=', $detail->screen_id)->first();

		if (isset($detail->parent_order_detail)) {
			$date_finish_detail = Carbon::parse($detail->finish_date);
			$dateExpired = null;
			if ($date_finish_detail >= $dateInstant) {
				// echo '</br>sirvio </br>  ';
				$dateExpired = $date_finish_detail->addDays($detail->membership_days);
			} else {
				// echo '</br>NO sirvio </br>  ';
				$dateExpired = $dateInstant->addDays($detail->membership_days);
			}
			// if($detail->type_order == Order::TYPE_INDIVIDUAL){
			$s = OrderDetail::create([
				'orders_id' => $detail->orders_id,
				'type_account_id' => $detail->type_account_id,
				'customer_id' => $detail->customer_id,
				'screen_id' => $detail->screen_id,
				'account_id' => $detail->account_id,
				'is_venta_revendedor' => 0,
				'type_order' => Order::TYPE_INDIVIDUAL,
				'membership_days' => $detail->membership_days,
				'price_of_membership_days' => $detail->price_of_membership_days,
				'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s'),
				'parent_order_detail' => $detail->parent_order_detail,
			]);

			$detail_parent = OrderDetail::where('id', '=', $detail->parent_order_detail)->first();
			$detail_parent->number_renovations = $detail->number_renovations + 1;
			$detail_parent->is_renewed = 1;
			$detail_parent->save();
			$detail->is_renewed = 1;
			$detail->save();

			$screen->date_sold = (string)$date_of_created_At->format('Y-m-d H:i:s');
			$screen->date_expired = (string)$dateExpired->format('Y-m-d H:i:s');
			$screen->save();
			// }

			HelpersCRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Venta renovada exitosamente.", "success");
		} else {
			$date_finish_detail = Carbon::parse($detail->finish_date);
			if ($date_finish_detail >= $dateInstant) {
				// echo '</br>sirvio </br>  ';
				$dateExpired = $date_finish_detail->addDays($detail->membership_days);
			} else {
				// echo '</br>NO sirvio </br>  ';
				$dateExpired = $dateInstant->addDays($detail->membership_days);
			}

			// $dateExpired = $dateInstant->addDays($detail->membership_days);



			// if($detail->type_order == Order::TYPE_INDIVIDUAL){
			OrderDetail::create([
				'orders_id' => $detail->orders_id,
				'type_account_id' => $detail->type_account_id,
				'customer_id' => $detail->customer_id,
				'screen_id' => $detail->screen_id,
				'account_id' => $detail->account_id,
				'is_venta_revendedor' => 0,
				'type_order' => Order::TYPE_INDIVIDUAL,
				'membership_days' => $detail->membership_days,
				'price_of_membership_days' => $detail->price_of_membership_days,
				'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s'),
				'parent_order_detail' => $detail->id
			]);

			// dd($s);

			$detail->number_renovations = $detail->number_renovations + 1;
			$detail->is_renewed = 1;
			$detail->save();

			$screensAux = Screens::where('account_id', '=', $detail->account_id)->get();

			foreach ($screensAux as $screenAux) {
				# code...
				$screenAux->date_sold = (string)$date_of_created_At->format('Y-m-d H:i:s');
				$screenAux->date_expired = (string)$dateExpired->format('Y-m-d H:i:s');
				$screenAux->save();
			}
			// }

			HelpersCRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Venta renovada exitosamente.", "success");
		}
	}

	public function getSetDesechar($id)
	{


		$order_detail = OrderDetail::where('id', '=', $id)->update([
			'is_discarded' => 1
		]);
		$order_detail = OrderDetail::where('id', '=', $id)->first();
		$orders = Order::where('id', '=', $order_detail->orders_id)->first();
		$orders->is_discarded = 1;
		$orders->number_screens_discarded = ($orders->number_screens_discarded + 1);
		$orders->save();
		// $screen = Screens::where('id', '=', $order_detail->screen_id);
		$screen = Screens::where('id',  $order_detail->screen_id)->update([
			'client_id' => null,
			'code_screen' => null,
			'date_expired' => null,
			'price_of_membership' => 0,
			'date_sold' => null,
			'is_sold' => 0,
			'device' => null,
			'ip' => null

		]);
		$screen = Screens::where('id',  $order_detail->screen_id)->first();
		$account_of_screen =  Accounts::where('id', '=', $screen->account_id)->first();
		$account_of_screen->screens_sold = ($account_of_screen->screens_sold - 1);
		$account_of_screen->save();

		$order_details = OrderDetail::where('orders_id', '=', $order_detail->orders_id)->where('is_discarded', '=', 0)->get();
		if (!sizeof($order_details)) {
			$orders = Order::where('id', '=', $order_detail->orders_id)->update([
				'is_discarded_all' => 1
			]);
		}
		HelpersCRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Venta desechada exitosamente.", "success");
		// $order_detail->is_discarded=1;
		// $order

		// dd($id);
	}

    public function getSetNotificar($id)
	{
		///dd("asd");
		$order_id = OrderDetail::where('id','=',$id)->first();
		//s$order_id=null;
		if($order_id->parent_order_detail!=null){
			$order_id = OrderDetail::where('parent_order_detail', '=',$order_id->parent_order_detail)->where('is_renewed','=',0)->where('is_discarded','=',0)->first();
		}

		$email = Accounts::where('id','=',$order_id->account_id)->first()->email;
        $screen = Screens::where('id','=',$order_id->screen_id)->first();
        $customer= Customers::where('id','=',$order_id->customer_id)->first();
        $type_account = TypeAccount::where('id','=',$order_id->type_account_id)->first();
        $nombre ='';

        $nombre =  explode(" ", $screen->name)[0].'%20'. explode(" ", $screen->name)[1] .'%20pin%20'.$screen->code_screen;
        if (isset(explode(" ", $screen->name)[2])) {
            $nombre .= "%0A".explode(" ", $screen->name)[2] . '%20%20%0A';
        }else{
            $nombre .=  '%20%20%0A';
        }

        if ($screen->type_device_id != null) {
            $typeDevice = TypeDevice::where('id', '=', $screen->type_device_id)->first();
            $nombre .= $typeDevice->name . '%20' . $typeDevice->emoji . '%20' . $screen->device . '%0A%0A';
        } else {
            $nombre .= '%0A%0A';
        }

        $message= '*'. str_replace(' ', '%20', $type_account->name).'*%0A%0A'.'Ok%20listo%20renovada%20por%20'.$order_id->membership_days.'%20dias%20mas%20de%20garantia%0A%0A'.$email."%0A%0A".$nombre.'Y%20recuerda%20cumplir%20las%20reglas%20para%20que%20la%20garantia%20sea%20efectiva%20por%20'.$order_id->membership_days.'%20dias';
    	$number_phone = $customer->number_phone;

		if($customer->revendedor_id !=null){
			$number_phone = Usuarios::where('id','=',$customer->revendedor_id)->first()->number_phone;
		}else{
			$number_phone = $customer->number_phone;
		}
        $host = env('LINK_SYSTEM');
        echo "
					<script>
					let datos = " . json_encode($message) . "
					let telefono = " . json_encode($number_phone ) . "
                    let host =  ".json_encode($host)."
					// alert();
					//window.localStorage.setItem('miGato2', 'Juan');
					//alert('https://api.whatsapp.com/send?phone='+telefono+'&text='+'*COMUNICADO%20MOSERCON*%0A%0AEstimado%20REVENDEDOR,%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20*CADUCARA*%20esta%20noche.%0A%0A' + datos + 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia.%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20bloqueara%20la%20cuenta%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*');
					window.open('https://api.whatsapp.com/send?phone='+telefono+'&text='+datos,'_blank');
					window.location.href = host+'order_details_renovations'

					</script>
					";
    }

    public function getSetNotificado($id)
	{

        $order_id = OrderDetail::where('id','=',$id)->first();
        $order_id->is_notified_renovation  =1;
        $order_id->save();

        HelpersCRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Renovacion Notificada exitosamente.", "success");
    }
}
