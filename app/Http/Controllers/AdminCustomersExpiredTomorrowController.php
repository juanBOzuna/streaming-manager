<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

use App\Models\Customers;
use App\Models\OrderDetail;
use App\Models\TypeAccount;
use App\Models\Screens;
use DateTime;

class AdminCustomersExpiredTomorrowController extends \crocodicstudio\crudbooster\controllers\CBController
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
		$this->button_edit = false;
		$this->button_delete = false;
		$this->button_detail = false;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "customers";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Cliente", "name" => "name"];
		$this->col[] = ["label" => "Telefono", "name" => "number_phone"];
		$this->col[] = ["label" => "No. Pantallas a expirar", "name" => "date_sold", "callback" => function ($row) {
			$customer = Customers::where('id', '=', $row->id)->first();
			$details_of_exired = OrderDetail::where('customer_id', '=', $row->id)->where('is_notified', '=', '0')->get();
			$number_expired = 0;
			// for ($i = 0; $i < 10; $i++) {
			// 	if ($i == 2) {
			// 		dd(\Carbon\Carbon::parse("")->month);
			// 	}
			// }

			foreach ($details_of_exired as $key) {
				date_default_timezone_set('America/Bogota');
				$d = explode(" ", $key->finish_date);
				$fv = \Carbon\Carbon::parse($d[0]);
				$da = \Carbon\Carbon::parse("");

				if ($fv->year == $da->year && $fv->month == $da->month  && ($fv->day - 1) == $da->day) {

					// dd("Vence manana" . " id=" . $key->id);
					$number_expired++;
				}
			}
			if ($number_expired == 1) {
				return "" . $number_expired . " pantalla";
			} elseif ($number_expired > 1) {
				return "" . $number_expired . " pantallas";
			} else {
				return "NINGUNA PANTALLA";
			}
		}];
		// $this->col[] = ["label"=>"Date Expired Sold","name"=>"date_expired_sold"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		// $this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
		// $this->form[] = ['label'=>'Number Phone','name'=>'number_phone','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Date Sold','name'=>'date_sold','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Date Expired Sold','name'=>'date_expired_sold','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
		// $this->form[] = ['label'=>'Revendedor Id','name'=>'revendedor_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-10','datatable'=>'revendedor,id'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Name","name"=>"name","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"You can only enter the letter only"];
		//$this->form[] = ["label"=>"Number Phone","name"=>"number_phone","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Date Sold","name"=>"date_sold","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
		//$this->form[] = ["label"=>"Date Expired Sold","name"=>"date_expired_sold","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
		//$this->form[] = ["label"=>"Revendedor Id","name"=>"revendedor_id","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"revendedor,id"];
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
			'label' => 'Avisar por Whatsapp',
			'url' => CRUDBooster::mainpath('set-whatsapp/[id]'),
			'color' => 'success',
			'icon' => 'fa fa-whatsapp'
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
		$this->script_js = "
		let list = document.querySelectorAll('tr');
		list.forEach(function (trs) {
				 trs.childNodes.forEach(function (tds) {
					if (tds.innerText == 'NINGUNA PANTALLA') {
						trs.remove();
					}
				 });
		 });

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
		$this->load_css = array();
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
	public function getSetWhatsapp($id)
	{
		$datos = "";
		$customer = Customers::where('id', '=', $id)->first();
		$details = OrderDetail::where('customer_id', '=', $id)->where('is_notified', '=', '0')->get();
		$list_details_to_expired = [];
		$number_screens = 1;

		foreach ($details as $detail) {
			date_default_timezone_set('America/Bogota');
			$d = explode(" ", $detail->finish_date);
			$fv = \Carbon\Carbon::parse($d[0]);
			$da = \Carbon\Carbon::parse("");

			if ($fv->year == $da->year && $fv->month == $da->month  && ($fv->day - 1) == $da->day) {
				array_push($list_details_to_expired, $detail);
				$number_screens++;
			}
		}

		foreach ($list_details_to_expired as $detail) {
			$typeAccount = TypeAccount::where('id', '=', $detail->type_account_id)->first();
			$screen = Screens::where('id', '=', $detail->screen_id)->first();
			$porciones = explode(" ", $screen->name);
			$nombre = $porciones[0] . "%20" . $porciones[1];

			$datos .= '*' . $typeAccount->name . '*%20' . $screen->email . '%20*' . $nombre . '*%20%0A%0A';
		}

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://wp-bot-automatic.herokuapp.com/auto?tell=57' . $customer->number_phone . '&message=' . '*COMUNICADO%20MOSERCON*%0A%0AEstimado%20cliente%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20caducara%20esta%20noche%0A%0A' . $datos . 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20blokeara%20su%20pantalla%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*',
			// CURLOPT_URL => 'https://wp-bot-automatic.herokuapp.com/auto?tell=573044155592&message=asd',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;

		if ($response == "Success") {
			foreach ($list_details_to_expired as $detail) {
				$order_dt = OrderDetail::where('id', '=', $detail->id)->first();
				$order_dt->is_notified = 1;
				$order_dt->save();
			}
			\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "El cliente fue avisado exitosamente", "success");
		}else{
			\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Hubo un error al enviar el mensaje, revise el internet, y que el bot  de whatsapp sirva", "danger");
		}

		dd($response);
	}
}
