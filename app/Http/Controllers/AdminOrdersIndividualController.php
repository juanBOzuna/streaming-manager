<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Screens;
use App\Models\TypeAccount;
use App\Models\TypeDevice;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster as HelpersCRUDBooster;
use Session;
use Request;
use DB;
use CRUDBooster;

class AdminOrdersIndividualController extends \crocodicstudio\crudbooster\controllers\CBController
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
		$this->col[] = ["label" => "Cliente", "name" => "customers_id", "join" => "customers,number_phone", "callback" => function ($row) {
			$cliente = Customers::where('id', '=', $row->customers_id)->first();
			return $cliente->name . ' | ' . $cliente->number_phone;
		}];
		$this->col[] = ["label" => "Pantalla", "name" => "customers_id", "join" => "customers,number_phone", "callback" => function ($row) {
			$order_detail = OrderDetail::where('orders_id', '=', $row->id)->first();
			$screen = Screens::where('id', '=', $order_detail->screen_id)->first();
			$type = TypeAccount::where('id', '=', $screen->type_account_id)->first();
			return $screen->id . ' | ' . $screen->email . ' | ' . $type->name;
		}];
		$this->col[] = ["label" => "Precio Total", "name" => "total_price"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		if (CRUDBooster::getCurrentMethod() == "getDetail") {
			$this->form[] = ['label' => 'ID cliente', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,id'];
			$this->form[] = ['label' => 'Nombre cliente', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,name'];
			$this->form[] = ['label' => 'Telefono', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,number_phone'];
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

			$this->form[] = ['label' => 'Venta', 'name' => 'order_details', 'type' => 'child', 'columns' => $columns, 'table' => 'order_details', 'foreign_key' => 'orders_id'];
		} else {
			$this->form[] = ['label' => 'Cliente', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,number_phone'];
			$this->form[] = ['label' => 'Pantalla', 'name' => 'pantalla_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'screens,id'];
			$this->form[] = ['label' => 'Dias Membresia', 'name' => 'dias_membersia', 'type' => 'number', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		}
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Customers Id","name"=>"customers_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"customers,name"];
		//$this->form[] = ["label"=>"Total Price","name"=>"total_price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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

		$screens = Screens::where('is_sold', '=', '0')->where('is_account_expired', '=', 0)->get();

		$customers = Customers::get();
		$i1 = 0;
		$text = '{';

		$text_dd = '';
		foreach ($screens as $key) {
			$i1 += 1;
			// $account =  Accounts::where('id', '=', $key->account_id)->first();
			// if ($accouFnt->is_expired == 0) {
			$other_screen = Screens::where('account_id', '=', $key->account_id)->where('is_sold', '=', 1)->first();
			if (isset($other_screen)) {
				if ($i1 == sizeof($screens)) {
					$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
					$text .= '"' . $i1 . '": {"id": ' . $key->id . ',"nombre": "' . $key->id  . " | " . $type . " | " . $key->email . '"}';
				} else {
					$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
					$text .= '"' . $i1 . '": {"id": ' . $key->id . ',"nombre": "' . $key->id . " | " . $type . " | " . $key->email . '"},';
				}
				// $1i++;
			} else {
				$order_detail = OrderDetail::where('screen_id', '=', $other_screen->id)->where('is_renewed', '=', '0')->where('account_id', '=', $other_screen->account_id)->where('is_discarded', '=', 0)->where('type_order', '=', Order::TYPE_FULL)->first();
				if (!isset($order_detail)) {
					if ($i1 == sizeof($screens)) {
						$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
						$text .= '"' . $i1 . '": {"id": ' . $key->id . ',"nombre": "' . $key->id  . " | " . $type . " | " . $key->email . '"}';
					} else {
						$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
						$text .= '"' . $i1 . '": {"id": ' . $key->id . ',"nombre": "' . $key->id . " | " . $type . " | " . $key->email . '"},';
					}
				}
				// $i++;
			}
		}
		$text .= '}';
		$text2 = '{';
		$i = 0;
		foreach ($customers as $cus) {
			if ($i + 1 == sizeof($customers)) {
				$text2 .= '"' . $i . '": {"id": ' . $cus->id . ',"nombre": "' . $cus->number_phone  . " | " . $cus->name . '"}';
			} else {
				$text2 .= '"' . $i . '": {"id": ' . $cus->id . ',"nombre": "' . $cus->number_phone . " | " . $cus->name . '"},';
			}
			$i++;
		}
		$text2 .= '}';
		$this->script_js = "
			let select2 = document.getElementById('pantalla_id');
			let list = " . json_encode($text) . ";
			let jsonList = JSON.parse(list); 
	

			var res = [];
            for(var i in jsonList){
                res.push(jsonList[i]);
			}

			var length = select2.options.length;
			for (i = length-1; i >= 1; i--) { 
				select2.options.remove(i);
			 }

			res.forEach(function (trs) {
				// console.log(trs);
				const option = document.createElement('option');
				// const valor = 1;
				option.value = trs.id;
				option.text = trs.nombre;
				select2.appendChild(option);
			});


			let select2Cus = document.getElementById('customers_id');
			let listCus = " . json_encode($text2) . ";
			let jsonListCus = JSON.parse(listCus); 
	
			// console.log(select2Cus.options);

			var resCus = [];
            for(var i in jsonListCus){
                resCus.push(jsonListCus[i]);
			}

			var length = select2Cus.options.length;
			for (i = length-1; i >= 1; i--) { 
				select2Cus.options.remove(i);
			 }
			 
			resCus.forEach(function (trsCus) {
				// console.log(trsCus);
				const option = document.createElement('option');
				// const valor = 1;
				option.value = trsCus.id;
				option.text = trsCus.nombre;
				select2Cus.appendChild(option);
			});

			let btns = document.querySelectorAll('.btn');
			// console.log(btns);
			btns[4].defaultValue = 'Guardar y Crear Otro';
			btns[5].defaultValue = 'Guardar';
			btns[3].innerText = 'Volver';
			btns[3].innerHTML = '<i class=\"fa fa-chevron-circle-left\"></i> Volver';
			
			  
           

			";


		/*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */

		if (CRUDBooster::getCurrentMethod() == "getDetail") {


			$urlPage = $_SERVER['REQUEST_URI'];
			$porciones = explode("?", $urlPage);
			$porciones = explode("/", $porciones[0]);
			$order = Order::where('id', '=', $porciones[sizeof($porciones) - 1])->first();
			$detail = OrderDetail::where('orders_id', '=', $order->id)->first();
			$account = Accounts::where('id', '=', $detail->account_id)->first();
			$type = TypeAccount::where('id', '=', $account->type_account_id)->first();
			$email = $account->email;
			$screen = Screens::where('id', '=', $detail->screen_id)->first();
			$screensText = 'Pantalla%20' . $screen->profile_number . '%20pin%20' . $screen->code_screen . '%0A';
			if (isset(explode(" ", $screen->name)[2])) {
				$screensText .= explode(" ", $screen->name)[2] . '%20%20%0A';
			}
			// dd($screen->type_device_id);
			if ($screen->type_device_id != null) {
				$typeDevice = TypeDevice::where('id', '=', $screen->type_device_id)->first();
				$screensText .= $typeDevice->name . '%20' . $typeDevice->emoji . '%20' . $screen->device . '%0A%0A';
			} else {
				$screensText .= '%0A%0A';
			}
			// dd(explode(" ", $screensText));
			$telefono_send_sms = Customers::where('id', '=', $detail->customer_id)->first()->number_phone;
			// $telefono_send_sms = $customer->number_phone;
			$url_send = '*' . $type->name . '*' . '%0A%0AOk%20listo%20Vendida%20por%2030%20dias%20mas%20de%20garanti,ba%0A%0A' . $email . '%20%0A%0A' . $screensText . 'Y%20recuerda%20cumplir%20las%20reglas%20para%20que%20la%20garantia%20sea%20efectiva%20por%2030%20dias';

			$host = env('LINK_SYSTEM');
			$link_customer_viewer = $host . "customers/detail/" . $detail->customer_id . "?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Fcustomers";

			// echo $url_send;	
			// dd($url_send);

			$this->script_js = "
				document.querySelector('#content_section').innerHTML= ` <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'>
				<a  href='https://api.whatsapp.com/send?phone=" . $telefono_send_sms . "&text=" . $url_send . "' class='float' target='_blank'>
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




				document.querySelector('#content_section').innerHTML= ` <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'>
				<a  href='" . $link_customer_viewer . "' class='float2' target='_blank'>
				<i class='fa fa-user my-float2'></i>
				</a>`+document.querySelector('#content_section').innerHTML;
				document.querySelector('#content_section').innerHTML+= `
			   <style type='text/css'>
					.float2{
						position:fixed;
						width:55px;
						height:55px;
						bottom:35px;
						right:100px;
						background-color:red;
						color:#FFF;
						border-radius:45px;
						text-align:center;
					font-size:25px;
						box-shadow: 2px 2px 3px #999;
					z-index:100;
					}
					.float2:hover {
						text-decoration: none;
						color: #25d366;
					background-color:#fff;
					}
					
					.my-float2{
						margin-top:16px;
					}
			   </style>
				`;
				";
		}

		if (HelpersCRUDBooster::getCurrentMethod() == 'getAdd') {
			if (session('is_success') != null) {
				$this->script_js = "
					 document.querySelector('#content_section').innerHTML= ` <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css'>
				<a  href='" . session('link') . "' class='float-search' target='_blank'>
				<i class='fa fa-search my-float-searc'></i>
				</a>`+document.querySelector('#content_section').innerHTML;
				document.querySelector('#content_section').innerHTML+= `
			   <style type='text/css'>
					.float-search{
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
					.float-search:hover {
						text-decoration: none;
						color: #25d366;
					background-color:#fff;
					}
					
					.my-float-searc{
						margin-top:16px;
					}
			   </style>
				`;
					";

				session(['is_success' => null]);
			}
		}
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
		$query->where('is_venta_revendedor', '=', '0')->where('type_order', '=', Order::ONLY_SCREEN);
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

		date_default_timezone_set('America/Bogota');
		$screen = Screens::where("id", "=", $postdata["pantalla_id"])->first();

		$type = TypeAccount::where("id", "=", $screen->type_account_id)->first();

		$total_price = doubleval($postdata["dias_membersia"]) * doubleval($type->price_day);
		$dateInstant = Carbon::parse('');
		// dd($dateInstant);


		$number_cliente =  strrev(substr(strrev(strval(Customers::where('id', '=', $postdata["customers_id"])->first()->number_phone)), 0, 4));
		// $c =  strrev(substr(strrev(strval($cliente)), 0, 4));

		$screen->is_sold = 1;
		$screen->code_screen = strval($number_cliente);
		$screen->client_id = $postdata["customers_id"];
		$screen->date_sold =  strval($dateInstant);
		$dateExpired = $dateInstant->addDays($postdata['dias_membersia']);
		$screen->date_expired = strval($dateExpired);
		$screen->price_of_membership = $total_price;
		$screen->save();

		$acc = Accounts::where('id', '=', $screen->account_id)->first();

		$type = TypeAccount::where('id', '=', $acc->type_account_id)->first();
		if (($acc->screens_sold + 1) >= $type->available_screens) {
			$acc->is_sold_ordinary = 1;
		} else {
			$acc->is_sold_ordinary = 0;
		}

		$acc->screens_sold = $acc->screens_sold + 1;
		$acc->is_sold_ordinary = 1;
		$acc->is_sold_extraordinary = 1;
		// $acc->screens_sold = $acc->screens_sold + 1;
		$acc->save();

		$order = Order::create([
			'customers_id' => $postdata["customers_id"],
			'total_price' => $total_price,
			'type_order' => Order::ONLY_SCREEN,
		]);

		OrderDetail::create([
			'orders_id' => $order->id,
			'type_account_id' => $screen->type_account_id,
			'customer_id' => $postdata["customers_id"],
			'screen_id' => $postdata["pantalla_id"],
			'account_id' => $screen->account_id,
			'type_order' => Order::ONLY_SCREEN,
			'membership_days' => $postdata["dias_membersia"],
			'price_of_membership_days' => $total_price,
			'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s')
		]);

		$host = env('LINK_SYSTEM');
		session(['is_success' => 1]);
		session(['link' => $host . "ordersIndividual/detail/" . $order->id . "?return_url=http%3A%2F%2Fstreaming-manager.test%2Fadmin%2Forders&parent_id=&parent_field="]);

		\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Se creo el pedido exitosamente", "success");
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
		// $order = Order::where('id', '=', $id)->first();

		// dd('asd');
	}



	//By the way, you can still create your own method in here... :)


}
