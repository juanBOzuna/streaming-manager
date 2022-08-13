<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;
use App\Models\Revendedores;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Accounts;
use App\Models\Screens;
use App\Models\Customers;
// use App\Models\Revendedores;

use Carbon\Carbon;

use App\Models\TypeAccount;

class AdminOrdersRevendedoresController extends \crocodicstudio\crudbooster\controllers\CBController
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
		$this->button_delete = false;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "orders";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Vendido a", "name" => "is_venta_revendedor", "callback" => function ($row) {

			$customer = null;
			$type = '';
			//	var_dump($row->is_venta_revendedor==0 );
			if ($row->is_venta_revendedor == 0) {
				$customer = Customers::where('id', '=', $row->customers_id)->first();
				$type = 'CLIENTE';
			} else {
				$customer = Revendedores::where('id', '=', $row->customers_id)->first();
				$type = 'REVENDEDOR';
			}
			return $type;
		}];
		$this->col[] = ["label" => "Nombre comprador", "name" => "customers_id", "callback" => function ($row) {

			$customer = null;
			$type = '';
			if ($row->is_venta_revendedor == 0) {
				$customer = Customers::where('id', '=', $row->customers_id)->first();
				$type = $customer->name;
			} else {
				$customer = Revendedores::where('id', '=', $row->customers_id)->first();
				$type = $customer->name;
			}
			return $type;
		}];
		$this->col[] = ["label" => "Telefono comprador", "name" => "customers_id", "callback" => function ($row) {

			$customer = null;
			$telefono = 0;
			if ($row->is_venta_revendedor == 0) {
				$customer = Customers::where('id', '=', $row->customers_id)->first();
				$telefono = $customer->number_phone;
			} else {
				$customer = Revendedores::where('id', '=', $row->customers_id)->first();
				$telefono = $customer->telefono;
			}
			return $telefono;
		}];
		$this->col[] = ["label" => "Precio Total", "name" => "total_price"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		// $this->form[] = ['label'=>'Venta','name'=>'order_details','type'=>'child','width'=>'col-sm-10','table'=>'order_details','foreign_key'=>'orders_id'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getDetail") {
			$urlPage = $_SERVER['REQUEST_URI'];
			$porciones = explode("?", $urlPage);
			$porciones = explode("/", $porciones[0]);

			$order = Order::where('id', '=', $porciones[sizeof($porciones) - 1])->first();

			if ($order->is_venta_revendedor == 0) {
				$this->form[] = ['label' => 'Cliente', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,name', 'datatable_format' => 'name,\'  -  \',number_phone'];
			} else {
				$this->form[] = ['label' => 'Revendedor', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'revendedores,name', 'datatable_format' => 'name,\'  -  \',telefono'];
			}
			if ($order->is_venta_revendedor == 0) {
				$this->form[] = ['label' => 'Telefono', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,number_phone'];
			} else {
				$this->form[] = ['label' => 'Telefono', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'revendedores,telefono'];
			}
			if (CRUDBooster::getCurrentMethod() == "getDetail") {

				//$this->form[] = ["label" => "Telefono", "name" => "customers_id", 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,number_phone'];
				$this->form[] = ['label' => 'Precio Total', 'name' => 'total_price', 'type' => 'money', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];


				$columns[] = ['label' => 'Dias', 'name' => 'membership_days', 'type' => 'number', 'required' => true];
				// $columns[] = ['label' => 'Pantalla', 'name' => 'screen_id', 'type' => 'number', 'required' => true];
				$columns[] = ['label' => 'Cuenta', 'name' => 'account_id', 'type' => 'number', 'required' => true];
				// $columns[] = ['label' => 'Precio', 'name' => 'price_of_membership_days', 'type' => 'money', 'required' => true];
				$columns[] = ['label' => 'Vendida', 'name' => 'created_at', 'type' => 'text', 'required' => true];
				$columns[] = ['label' => 'Vence', 'name' => 'finish_date', 'type' => 'text', 'required' => true];
				$columns[] = ['label' => 'Esta renovada', 'name' => 'is_renewed', 'type' => 'number', 'required' => true];
				$columns[] = ['label' => 'Numero de renovaciones', 'name' => 'number_renovations', 'type' => 'number', 'required' => true];
				$columns[] = ['label' => 'Venta padre', 'name' => 'parent_order_detail', 'type' => 'number', 'required' => true];
			}

			$this->form[] = ['label' => 'Venta', 'name' => 'order_details', 'type' => 'child', 'columns' => $columns, 'table' => 'order_details', 'foreign_key' => 'orders_id'];

			//

			//}
			//// dd(\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod());
			//
			//$this->form[] = ['label' => 'Comprador', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'revendedores,id'];
			//$this->form[] = ['label' => 'Cliente', 'name' => 'customers_id2', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,id'];
			//$this->form[] = ['label' => 'Cuenta', 'name' => 'account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'accounts,id'];
			//$this->form[] = ['label' => 'Dias Membresia', 'name' => 'dias_membersia', 'type' => 'number', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		}

		if (\crocodicstudio\crudbooster\helpers\CRUDBooster::getCurrentMethod() == "getAdd") {
			$this->form[] = ['label' => 'Revendedor', 'name' => 'customers_id', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'revendedores,id'];
			$this->form[] = ['label' => 'Cliente', 'name' => 'customers_id2', 'type' => 'select2', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'customers,id'];
			$this->form[] = ['label' => 'Cuenta', 'name' => 'account_id', 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'accounts,id'];
			$this->form[] = ['label' => 'Dias Membresia', 'name' => 'dias_membersia', 'type' => 'number', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		}
		//
		//if (CRUDBooster::getCurrentMethod() == "getDetail") {
		//
		//$this->form[] = ["label" => "Telefono", "name" => "customers_id", 'type' => 'select2', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10', 'datatable' => 'revendedores,telefono'];
		//$this->form[] = ['label' => 'Precio Total', 'name' => 'total_price', 'type' => 'money', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
		//
		//
		//$columns[] = ['label' => 'Dias', 'name' => 'membership_days', 'type' => 'number', 'required' => true];
		//$columns[] = ['label' => 'Pantalla', 'name' => 'screen_id', 'type' => 'number', 'required' => true];
		//$columns[] = ['label' => 'Cuenta', 'name' => 'account_id', 'type' => 'number', 'required' => true];
		//// $columns[] = ['label' => 'Precio', 'name' => 'price_of_membership_days', 'type' => 'money', 'required' => true];
		//$columns[] = ['label' => 'Vendida', 'name' => 'created_at', 'type' => 'text', 'required' => true];
		//$columns[] = ['label' => 'Vence', 'name' => 'finish_date', 'type' => 'text', 'required' => true];
		//$columns[] = ['label' => 'Esta renovada', 'name' => 'is_renewed', 'type' => 'number', 'required' => true];
		//$columns[] = ['label' => 'Numero de renovaciones', 'name' => 'number_renovations', 'type' => 'number', 'required' => true];
		//$columns[] = ['label' => 'Venta padre', 'name' => 'parent_order_detail', 'type' => 'number', 'required' => true];
		//} else {
		//$columns[] = ['label' => 'Numero de pantallas', 'name' => 'number_screens', 'type' => 'number', 'required' => true];
		//$columns[] = ['label' => 'Dias de membresia', 'name' => 'membership_days', 'type' => 'number', 'required' => true];
		//}
		//$this->form[] = ['label' => 'Venta', 'name' => 'order_details', 'type' => 'child', 'columns' => $columns, 'table' => 'order_details', 'foreign_key' => 'orders_id'];
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

		$d = array();
		$accounts = Accounts::where('screens_sold', '=', '0')->where('is_expired', '=', 0)->get();
		$revendedores = Revendedores::get();
		$clientes = Customers::get();

		$text = '{';
		$i = 0;
		foreach ($accounts as $key) {
			if ($i + 1 == sizeof($accounts)) {
				$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
				$text .= '"' . $i . '": {"id": ' . $key->id . ', "nombre": "' . $key->id  . " | " . $type . " | " . $key->email . '"}';
			} else {
				$type = TypeAccount::where('id', '=', $key->type_account_id)->first()->name;
				$text .= '"' . $i . '": {"id": ' . $key->id . ', "nombre": "' . $key->id . " | " . $type . " | "  . $key->email . '"},';
			}
			$i++;
		}
		$text .= '}';

		$text3 = '{';
		$i = 0;
		foreach ($clientes as $key2) {
			if ($i + 1 == sizeof($clientes)) {

				$text3 .= '"' . $i . '": {"id": ' . $key2->id . ',"nombre": "' . $key2->number_phone  . " | " . $key2->name . '"}';
			} else {
				$text3 .= '"' . $i . '": {"id": ' . $key2->id . ',"nombre": "' . $key2->number_phone . " | " . $key2->name . '"},';
			}
			$i++;
		}
		$text3 .= '}';


		$text2 = '{';
		$i = 0;
		foreach ($revendedores as $cus) {
			if ($i + 1 == sizeof($revendedores)) {
				$text2 .= '"' . $i . '": {"id": ' . $cus->id . ',"nombre": "' . $cus->telefono  . " | " . $cus->name . '"}';
			} else {
				$text2 .= '"' . $i . '": {"id": ' . $cus->id . ',"nombre": "' . $cus->telefono . " | " . $cus->name . '"},';
			}
			$i++;
		}
		$text2 .= '}';

		if (CRUDBooster::getCurrentMethod() == "getDetail") {
			$this->script_js = "
            let tabla = document.querySelector('#table-order_details');

            // console.log(tabla.childNodes[3].childNodes);

            // let trs=  ;

            // for(let i =0 ; i<tabla.childNodes[3].childNodes.length){
            //     console.log(item[i]);
            // }
            let i=0;
            tabla.childNodes[3].childNodes.forEach(function (item) {
               let=i++;
               if(i%2==0){
                // console.log(item.children[4].innerText);
                if(item.children[4].innerText=='0'){
                    item.children[4].innerText = 'No';
                    // item.children[4].style.color = '#DD4B39';
                    item.children[4].style.fontWeight = 'bold';
                }else{
                    item.children[1].childElementCount=1;
                    item.children[4].innerText = 'Si';
                    item.children[4].style.color = '#04AA6D';
                    item.children[4].style.fontWeight = 'bold';
                }
                
               }
               
             });
            ";
		} else {

			$this->script_js = "
				let select2 = document.getElementById('account_id');
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
	
				let select2Cus2 = document.getElementById('customers_id2');
				// console.log(select2Cus2);
				let listCus2 = " . json_encode($text3) . ";
				let jsonListCus2 = JSON.parse(listCus2); 
				console.log(jsonListCus2);
				// console.log(select2Cus2.options);
	
				var resCus2 = [];
				for(var i in jsonListCus2){
					resCus2.push(jsonListCus2[i]);
				}
	
				var length = select2Cus2.options.length;
				for (i = length-1; i >= 1; i--) { 
					select2Cus2.options.remove(i);
				 }
				 
				resCus2.forEach(function (trsCus) {
					// console.log(trsCus);
					const option = document.createElement('option');
					// const valor = 1;
					option.value = trsCus.id;
					option.text = trsCus.nombre;
					select2Cus2.appendChild(option);
				});
	
	
	
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
		$query->where('type_order', '=', Order::TYPE_FULL);
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
		//	dd($_REQUEST['customers_id2']);
		//dd(Customers::where('id', '=', intval($_REQUEST['customers_id2']))->first()->number_phone);
		//dd($telefono =strrev(substr(strrev(strval(Customers::where('id', '=', $_REQUEST['customers_id2'])->first()->telefono)), 0, 4)));
		//Your code here
		// echo $_REQUEST['customers_id2'];
		// echo $_REQUEST['customers_id'];
		// dd($_REQUEST['customers_id']!=0 && $_REQUEST['customers_id2']==0);

		$validationIf = 0;
		// echo $postdata['customers_id2'];
		// dd($_REQUEST['customers_id'] != 0 && $_REQUEST['customers_id2'] != 0);
		// dd($_REQUEST);

		if ($_REQUEST['customers_id'] == 0 && $_REQUEST['customers_id2'] == 0) {
			$validationIf = 1;
			\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "OJO, DEBE SELECCIONAR UN CLIENTE O UN REVENDEDOR ALMENOS", "warning");
		}
		if ($_REQUEST['customers_id'] != 0 && $_REQUEST['customers_id2'] != 0) {
			$validationIf = 1;
			\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "OJO, SI ESCOGE REVENDEDOR NO PUEDE ESCOGER CLIENTE, IGUAL EN VICEVERSA", "warning");
		}

		if ($validationIf == 0) {
			date_default_timezone_set('America/Bogota');
			$accounts = Accounts::where("id", "=", $_REQUEST["account_id"])->first();
			$type = TypeAccount::where("id", "=", $accounts->type_account_id)->first();
			$total_price = doubleval($type->price_full);
			$telefono = '';
			$screens = Screens::where('account_id', '=', $accounts->id)->get();
			if (!$_REQUEST['customers_id'] == 0) {
				$telefono = strrev(substr(strrev(strval(Revendedores::where('id', '=', $_REQUEST['customers_id'])->first()->telefono)), 0, 4));
			} else {
				$telefono = strrev(substr(strrev(strval(Customers::where('id', '=', $_REQUEST['customers_id2'])->first()->number_phone)), 0, 4));
			}
			foreach ($screens as $screen) {
				# code...\
				$dateInstant = Carbon::parse('');
				$screen->is_sold = 1;
				// if (!$_REQUEST['customers_id'] == 0) {
				// }
				$screen->date_sold =  strval($dateInstant);
				$dateExpired = $dateInstant->addDays($_REQUEST['dias_membersia']);
				$screen->date_expired = strval($dateExpired);
				$screen->price_of_membership = $total_price;
				$screen->code_screen = intval($telefono);
				if ($_REQUEST['customers_id'] != 0) {
					$screen->client_id = $_REQUEST['customers_id'];
					$screen->is_sold_revendedor = 1;
					$screen->revendedor_id = $_REQUEST["customers_id"];
				} else {
					$screen->client_id = $_REQUEST['customers_id2'];
				}
				$screen->save();
			}

			//$acc = Accounts::where('id', '=', $screen->account_id)->first();

			$accounts->screens_sold = $type->available_screens;
			if (!$_REQUEST['customers_id'] == 0) {
				$accounts->revendedor_id = $_REQUEST["customers_id"];
			}
			$accounts->is_sold_ordinary = 1;
			$accounts->save();

			if ($_REQUEST['customers_id'] != 0) {
				$order = Order::create([
					'customers_id' => $_REQUEST["customers_id"],
					'total_price' => $total_price,
					'type_order' => 'Cuenta Completa',
					'is_venta_revendedor' => 1
				]);
			} else {
				$order = Order::create([
					'customers_id' => $_REQUEST["customers_id2"],
					'total_price' => $total_price,
					'type_order' => 'Cuenta Completa',
					'is_venta_revendedor' => 0
				]);
			}
			$dateInstant = Carbon::parse('');
			$dateInstant->addDays($_REQUEST["dias_membersia"]);
			if ($_REQUEST['customers_id'] != 0) {
				$d = OrderDetail::create([
					'orders_id' => $order->id,
					'type_account_id' => $accounts->type_account_id,
					'customer_id' => $_REQUEST["customers_id"],
					'is_venta_revendedor' => 1,
					'type_order' => Order::TYPE_FULL,
					'account_id' => $accounts->id,
					'membership_days' => $_REQUEST["dias_membersia"],
					'price_of_membership_days' => $total_price,
					'finish_date' => (string)$dateInstant->format('Y-m-d H:i:s')
				]);
			} else {
				$d = OrderDetail::create([
					'orders_id' => $order->id,
					'type_account_id' => $accounts->type_account_id,
					'customer_id' => $_REQUEST["customers_id2"],
					'is_venta_revendedor' => 0,
					'type_order' => Order::TYPE_FULL,
					'account_id' => $accounts->id,
					'membership_days' => $_REQUEST["dias_membersia"],
					'price_of_membership_days' => $total_price,
					'finish_date' => (string)$dateInstant->format('Y-m-d H:i:s')
				]);
			}

			// $screensAux = Screens::where('account_id','=', $detail->account_id)->get();
			// foreach ($screensAux as $screenAux) {
			// 	# code...
			// 	$screenAux->date_sold = (string)$date_of_created_At->format('Y-m-d H:i:s');
			// $screenAux->date_expired = (string)$dateExpired->format('Y-m-d H:i:s');
			// $screenAux->save();
			// }
			// dd($d);

			\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Se creo el pedido exitosamente", "success");
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


}
