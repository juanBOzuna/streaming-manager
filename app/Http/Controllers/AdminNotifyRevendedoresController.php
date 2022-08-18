<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;
use App\Models\Revendedores;
use App\Models\OrderDetail;
use App\Models\TypeAccount;
use App\Models\Accounts;
use App\Models\Screens;

class AdminNotifyRevendedoresController extends \crocodicstudio\crudbooster\controllers\CBController
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
		$this->table = "revendedores";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Name", "name" => "name"];
		$this->col[] = ["label" => "Telefono", "name" => "telefono"];
		$this->col[] = ["label" => "No. Cuentas a expirar", "name" => "name", "callback" => function ($row) {
			$revendedor = Revendedores::where('id', '=', $row->id)->first();
			$details_of_exired = OrderDetail::where('customer_id', '=', $row->id)->where('is_renewed','=',0)->where('is_discarded','=',0)->where('is_notified', '=', '0')->where('is_venta_revendedor', '=', '1')->get();
			$number_accounts_expired = 0;
			// for ($i = 0; $i < 10; $i++) {
			// 	if ($i == 2) {
			// 		dd(\Carbon\Carbon::parse("")->month);
			// 	}
			// }

			// foreach ($details_of_exired as $key) {
			// 	date_default_timezone_set('America/Bogota');
			// 	$d = explode(" ", $key->finish_date);
			// 	$fv = \Carbon\Carbon::parse($d[0]);
			// 	$da = \Carbon\Carbon::parse("");
			// 	$da2 = explode(" ", $da);
			// 	$da = \Carbon\Carbon::parse($da2[0]);
			// 	$da->addDays(1);
			// 	//if ($fv->year == $da->year && $fv->month == $da->month  && ($fv->day - 1) == $da->day) {

			// 	//	dd("Vence manana" . " id=" . $key->id);
			// 	//	$number_screens_expired++;
			// 	//}
			// 	//echo $fv ."  ".$da;

			// 	if($fv == $da){
			// 		$number_screens_expired++;
			// 	}
			// }

			foreach ($details_of_exired as $key) {
				date_default_timezone_set('America/Bogota');
				$d = explode(" ", $key->finish_date);
				$fv = \Carbon\Carbon::parse($d[0]);
				$da = \Carbon\Carbon::parse("");
				$da2 = explode(" ", $da);
				$da = \Carbon\Carbon::parse($da2[0]);
				$da->addDays(1);
				//if ($fv->year == $da->year && $fv->month == $da->month  && ($fv->day - 1) == $da->day) {

				//	dd("Vence manana" . " id=" . $key->id);
				//	$number_screens_expired++;
				//}
				//echo $fv ."  ".$da;

				if ($fv == $da) {
					$number_accounts_expired++;
				}
			}

			$text = '';
			// if ($number_screens_expired == 1) {
			// 	$text = $number_screens_expired . " pantalla";
			// }

			// if($number_screens_expired > 1){
			// 	$text= $number_screens_expired . " pantallas";
			// }

			if ($number_accounts_expired == 1) {
				// if($number_screens_expired > 1){
				// 	$text .=' y '. $number_accounts_expired . " cuenta Completa";
				// }else{
				$text .= $number_accounts_expired . " cuenta Completa";
				// }

			}

			if ($number_accounts_expired > 1) {
				// if($number_screens_expired > 1){
				// 	$text .=' y '. $number_accounts_expired . " cuentas Completas";
				// }else{
				$text .= $number_accounts_expired . " cuentas Completas";
				// }

			}

			if ($number_accounts_expired == 0) {
				$text = "NINGUNA PANTALLA";
			}

			return $text;
		}];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		// $this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Name","name"=>"name","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"You can only enter the letter only"];
		//$this->form[] = ["label"=>"Telefono","name"=>"telefono","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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

        $this->addaction[] = [
			'label' => 'Avisado',
			'url' => CRUDBooster::mainpath('set-avisado/[id]'),
			'color' => 'success',
			'icon' => 'fa fa-refresh',
            // 'showIf' => "[is_expired]==1"
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

		if (null != $_REQUEST["isSuccess"]) {
			//session(['linkWp' => NULL	]);
			$telefono = session("telefonoRevendedor");
			$datos = session('linkWp');

			echo "
					<script>
					let datos = " . json_encode($datos) . "
					let telefono = " . json_encode($telefono) . "
					// alert();
					//window.localStorage.setItem('miGato2', 'Juan');
					//alert('https://wa.me/'+telefono+'?text='+'*COMUNICADO%20MOSERCON*%0A%0AEstimado%20REVENDEDOR,%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20*CADUCARA*%20esta%20noche.%0A%0A' + datos + 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia.%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20bloqueara%20la%20cuenta%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*');
					window.open('https://wa.me/'+telefono+'?text='+datos,'_blank');
					window.location.href = 'http://streaming-manager.test/admin/notify_revendedores'

					</script>
					";
		}

		// if (null != $_REQUEST["isSendSms"]) {
		// 	echo "
		// 		<script>
		// 		window.location.href = 'http://streaming-manager.test/admin/notify_revendedores?sendMessageSuccesfull=1';
		// 		</script>
		// 		";
		// }

		// if (null != $_REQUEST["sendMessageSuccesfull"]) {


		// }
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
	public function getSetAvisado($id)
	{

        $reve = Revendedores::where('id', '=', $id)->first();
		$details = OrderDetail::where('customer_id', '=', $id)->where('is_renewed','=',0)->where('is_discarded','=',0)->where('is_notified', '=', '0')->where('is_venta_revendedor', '=', 1)->get();
		$list_details_to_expired = [];
		$number_accounts = 1;

		foreach ($details as $detail) {
			date_default_timezone_set('America/Bogota');
			$d = explode(" ", $detail->finish_date);
			$fv = \Carbon\Carbon::parse($d[0]);
			$da = \Carbon\Carbon::parse("");
			$da2 = explode(" ", $da);
			$da = \Carbon\Carbon::parse($da2[0]);
			$da->addDays(1);

			if ($fv == $da) {
				array_push($list_details_to_expired, $detail);
				$number_accounts++;
			}
		}

        foreach ($list_details_to_expired as $detail) {
            $order_dt = OrderDetail::where('id', '=', $detail->id)->first();
            $order_dt->is_notified = 1;
            $order_dt->save();
        }
        \crocodicstudio\crudbooster\helpers\CRUDBooster::redirect("http://streaming-manager.test/admin/notify_revendedores", "El cliente fue avisado exitosamente", "success");

    }
    public function getSetWhatsapp($id)
	{
		$datos = "";
		$reve = Revendedores::where('id', '=', $id)->first();
		$details = OrderDetail::where('customer_id', '=', $id)->where('is_renewed','=',0)->where('is_discarded','=',0)->where('is_notified', '=', '0')->where('is_venta_revendedor', '=', 1)->get();
		$list_details_to_expired = [];
		$number_accounts = 1;

		foreach ($details as $detail) {
			date_default_timezone_set('America/Bogota');
			$d = explode(" ", $detail->finish_date);
			$fv = \Carbon\Carbon::parse($d[0]);
			$da = \Carbon\Carbon::parse("");
			$da2 = explode(" ", $da);
			$da = \Carbon\Carbon::parse($da2[0]);
			$da->addDays(1);

			if ($fv == $da) {
				array_push($list_details_to_expired, $detail);
				$number_accounts++;
			}
		}


		foreach ($list_details_to_expired as $detail) {
			$typeAccount = TypeAccount::where('id', '=', $detail->type_account_id)->first();
			$account = Accounts::where('id', '=', $detail->account_id)->first();
			// $porciones = explode(" ", $screen->name);
			// $nombre = $porciones[0] . "%20" . $porciones[1];

			$datos .= '*' . $typeAccount->name . '*%20' . $account->email . ' *_CUENTA COMPLETA_*. %20%0A%0A';
		}

		///\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'], "El cliente fue avisado exitosamente", "success");
		$telefono =  $reve->telefono;


		// echo "
		// <script>
		// let datos = " . json_encode($datos) . "
		// let telefono = " . json_encode($telefono) . "
		// // alert();
		// window.localStorage.setItem('miGato2', 'Juan');
		// //alert('https://wa.me/'+telefono+'?text='+'*COMUNICADO%20MOSERCON*%0A%0AEstimado%20cliente%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20caducara%20esta%20noche%0A%0A' + datos + 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20blokeara%20su%20pantalla%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*');
		// //window.open('https://wa.me/'+telefono+'?text='+'*COMUNICADO%20MOSERCON*%0A%0AEstimado%20cliente%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20caducara%20esta%20noche%0A%0A' + datos + 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20pantalla%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20blokeara%20su%20pantalla%20a%20partir%20de%20media%20noche%0A%20Att:%20*Admin*','_blank');
		// //window.location.href = 'http://streaming-manager.test/admin/customers_expired_tomorrow'

		// </script>
		// ";

		session(['linkWp' => '*COMUNICADO%20MOSERCON*%0A%0AEstimado%20REVENDEDOR,%20nuestro%20sistema%20le%20informa%20que%20el%20servicio%20adquirido%20con%20nosotros%20*~CADUCARA~*%20esta%20noche.%0A%0A' . $datos . 'Si%20desea%20seguir%20con%20nuestro%20servicio%20con%20la%20misma%20CUENTA%20debe%20mandarnos%20comprobante%20de%20pago%20en%20este%20dia.%0ADe%20lo%20contrario%20el%20sistema%20automaticamente%20*BLOQUEARA*%20la%20cuenta%20a%20partir%20de%20media%20noche.%0A%20Att:%20*Admin*.']);
		session(['telefonoRevendedor' => $telefono . ""]);
		session(['listDetail' => $list_details_to_expired]);
		\crocodicstudio\crudbooster\helpers\CRUDBooster::redirect($_SERVER['HTTP_REFERER'] . "?isSuccess=1", "El revendedor fue avisado exitosamente", "success");
	}
}
