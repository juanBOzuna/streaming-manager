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
		$this->button_detail = false;
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
		$this->col[] = ["label" => "Correo", "name" => "accounts.email"];
		$this->col[] = ["label" => "Pantalla #", "name" => "screens.name"];
		$this->col[] = ["label" => "Fecha de COMPRA", "name" => "screens.date_sold"];
		$this->col[] = ["label" => "Fecha de VENCIMIENTO", "name" => "order_details.finish_date"];
		// $this->col[] = ["label"=>"Membership Days","name"=>"membership_days"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
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
			'icon' => 'fa fa-refresh'
		];
		$this->addaction[] = [
			'label' => 'Desechar',
			'url' => CRUDBooster::mainpath('set-desechar/[id]'),
			'color' => 'danger',
			'icon' => 'fa fa-ban'
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
		$this->script_js = NULL;


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
	public function hook_query_index(&$query) // este se ejecuta antes? si, no es el as, porque si se lo quito y uso el number_phone tampoco sale
	{
		date_default_timezone_set('America/Bogota');
		$da = \Carbon\Carbon::parse("");
		$month = $da->month > 9 ? '' . $da->month : '0' . $da->month;
		$day = $da->day > 9 ? '' . $da->day : '0' . $da->day;
		$dateSimpli = $da->year . '-' . $month . '-' . ($day + 2) . ' 01:00:00';
		// dd($dateSimpli);
		//Your code here
		// dd($dateSimpli);
		$query->where('order_details.is_renewed', '=', '0')->where('finish_date', '<', $dateSimpli)->join('customers', 'order_details.customer_id', '=', 'customers.id')
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
			// echo 'fin: ' . $date_finish_detail;
			// echo '  | acutal: ' . $dateInstant;
			// dd($date_finish_detail->isBefore($dateInstant));
			// dd($date_finish_detail >= $dateInstant);
			$dateExpired = null;

			if ($date_finish_detail >= $dateInstant) {
				// echo '</br>sirvio </br>  '; 
				$dateExpired = $date_finish_detail->addDays($detail->membership_days);
			} else {
				// echo '</br>NO sirvio </br>  ';
				$dateExpired = $dateInstant->addDays($detail->membership_days);
			}

			echo ' df = ' . $date_finish_detail;
			echo ' di = ' . $dateInstant;
			echo ' de = ' . $dateExpired;
			echo ' format = ' . (string)$dateExpired->format('Y-m-d H:i:s');

			dd($dateExpired);

			// if(){

			// }
			$s = OrderDetail::create([
				'orders_id' => $detail->orders_id,
				'type_account_id' => $detail->type_account_id,
				'customer_id' => $detail->customer_id,
				'screen_id' => $detail->screen_id,
				'account_id' => $detail->account_id,
				'membership_days' => $detail->membership_days,
				'price_of_membership_days' => $detail->price_of_membership_days,
				'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s'),
				'parent_order_detail' => $detail->parent_order_detail,
			]);

			// dd($s);
			// dd($s);

			$detail_parent = OrderDetail::where('id', '=', $detail->parent_order_detail)->first();
			$detail_parent->number_renovations = $detail->number_renovations + 1;
			$detail_parent->is_renewed = 1;
			$detail_parent->save();

			$detail->is_renewed = 1;
			$detail->save();

			$screen->date_sold = (string)$date_of_created_At->format('Y-m-d H:i:s');
			$screen->date_expired = (string)$dateExpired->format('Y-m-d H:i:s');
			$screen->save();
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
			OrderDetail::create([
				'orders_id' => $detail->orders_id,
				'type_account_id' => $detail->type_account_id,
				'customer_id' => $detail->customer_id,
				'screen_id' => $detail->screen_id,
				'account_id' => $detail->account_id,
				'membership_days' => $detail->membership_days,
				'price_of_membership_days' => $detail->price_of_membership_days,
				'finish_date' => (string)$dateExpired->format('Y-m-d H:i:s'),
				'parent_order_detail' => $detail->id
			]);

			// dd($s);

			$detail->number_renovations = $detail->number_renovations + 1;
			$detail->is_renewed = 1;
			$detail->save();

			$screen->date_sold = (string)$date_of_created_At->format('Y-m-d H:i:s');
			$screen->date_expired = (string)$dateExpired->format('Y-m-d H:i:s');
			$screen->save();
			HelpersCRUDBooster::redirect($_SERVER['HTTP_REFERER'], "Venta renovada exitosamente.", "success");
			// echo $detail->finish_date;
			// dd($detail_create);
		}
		// $detail->number_renovations = $detail->number_renovations + 1;

		// $screen = Screens::where('id', '=', $detail->screen_id)->first();
		// $order = Order::where('id', '=', $detail->orders_id);


		// $order->screens_sold = ($order->screens_sold + 1);
	}

	public function getSetDesechar($id)
	{
	}
}
