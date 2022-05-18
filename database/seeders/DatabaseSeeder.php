<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
require_once "Modulos.php";

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait updating the data...');

        # cms_menus
        $data = [
            ["id" => "1", "name" => "Usuarios", "type" => "Route", "path" => "AdminCmsUsers1ControllerGetIndex", "color" => "normal", "icon" => "fa fa-user", "parent_id" => "0", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "4", "created_at" => "2021-10-18 18:35:25", "updated_at" => "2021-10-18 22:29:28"],
            ["id" => "2", "name" => "Cuentas", "type" => "Route", "path" => "AdminAccountsControllerGetIndex", "color" => "normal", "icon" => "fa fa-youtube-play", "parent_id" => "7", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "3", "created_at" => "2021-10-18 18:41:13", "updated_at" => "2021-10-18 22:30:19"],
            ["id" => "3", "name" => "Tipos de cuentas", "type" => "Route", "path" => "AdminTypeAccountControllerGetIndex", "color" => NULL, "icon" => "fa fa-key", "parent_id" => "7", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "5", "created_at" => "2021-10-18 18:43:09", "updated_at" => NULL],
            ["id" => "4", "name" => "Recargas", "type" => "Route", "path" => "AdminRecargasControllerGetIndex", "color" => "normal", "icon" => "fa fa-phone", "parent_id" => "0", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "2", "created_at" => "2021-10-24 21:38:56", "updated_at" => "2021-10-24 22:47:20"],
            ["id" => "5", "name" => "Tipo de recargas", "type" => "Route", "path" => "AdminTipoDeRecargasControllerGetIndex", "color" => NULL, "icon" => "fa fa-check", "parent_id" => "0", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "3", "created_at" => "2021-10-24 21:44:31", "updated_at" => NULL],
            ["id" => "7", "name" => "Streaming", "type" => "Module", "path" => "accounts", "color" => "normal", "icon" => "fa fa-youtube-play", "parent_id" => "0", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "1", "created_at" => "2021-12-08 15:32:31", "updated_at" => NULL],
            ["id" => "9", "name" => "Clientes", "type" => "Route", "path" => "AdminCustomersControllerGetIndex", "color" => NULL, "icon" => "fa fa-user", "parent_id" => "0", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "5", "created_at" => "2021-12-08 16:10:11", "updated_at" => NULL],
            ["id" => "10", "name" => "Ordenes", "type" => "Route", "path" => "AdminOrdersControllerGetIndex", "color" => NULL, "icon" => "fa fa-shopping-bag", "parent_id" => "7", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "2", "created_at" => "2021-12-08 16:26:05", "updated_at" => NULL],
            ["id" => "11", "name" => "Pantallas", "type" => "Route", "path" => "AdminScreensControllerGetIndex", "color" => NULL, "icon" => "fa fa-tv", "parent_id" => "7", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "4", "created_at" => "2021-12-27 04:13:41", "updated_at" => NULL],
            ["id" => "14", "name" => "Venta Individual", "type" => "Route", "path" => "AdminOrdersIndividualControllerGetIndex", "color" => NULL, "icon" => "fa fa-shopping-bag", "parent_id" => "7", "is_active" => "1", "is_dashboard" => "0", "id_cms_privileges" => "1", "sorting" => "1", "created_at" => "2022-05-15 00:45:57", "updated_at" => NULL]
        ];
        foreach ($data as $k => $d) {
            if (DB::table('cms_menus')->where('name', $d['name'])->count()) {unset($data[$k]);}
        }
        DB::table('cms_menus')->insert($data);
        $this->command->info("Create cms_menus completed");
        # cms_menus End

        //----------------------------------------------------------//

        # cms_menus_privileges
        $data = [
            ["id" => "3", "id_cms_menus" => "3", "id_cms_privileges" => "1"],
            ["id" => "4", "id_cms_menus" => "1", "id_cms_privileges" => "2"],
            ["id" => "5", "id_cms_menus" => "1", "id_cms_privileges" => "1"],
            ["id" => "6", "id_cms_menus" => "2", "id_cms_privileges" => "2"],
            ["id" => "7", "id_cms_menus" => "2", "id_cms_privileges" => "1"],
            ["id" => "9", "id_cms_menus" => "5", "id_cms_privileges" => "1"],
            ["id" => "10", "id_cms_menus" => "4", "id_cms_privileges" => "2"],
            ["id" => "11", "id_cms_menus" => "4", "id_cms_privileges" => "1"],
            ["id" => "12", "id_cms_menus" => "6", "id_cms_privileges" => "1"],
            ["id" => "13", "id_cms_menus" => "7", "id_cms_privileges" => "2"],
            ["id" => "14", "id_cms_menus" => "7", "id_cms_privileges" => "1"],
            ["id" => "15", "id_cms_menus" => "8", "id_cms_privileges" => "1"],
            ["id" => "16", "id_cms_menus" => "9", "id_cms_privileges" => "1"],
            ["id" => "17", "id_cms_menus" => "10", "id_cms_privileges" => "1"],
            ["id" => "18", "id_cms_menus" => "11", "id_cms_privileges" => "1"],
            ["id" => "19", "id_cms_menus" => "12", "id_cms_privileges" => "1"],
            ["id" => "20", "id_cms_menus" => "13", "id_cms_privileges" => "1"],
            ["id" => "21", "id_cms_menus" => "14", "id_cms_privileges" => "1"]
        ];
        foreach ($data as $k => $d) {
            if (DB::table('cms_menus_privileges')->where('id_cms_menus', $d['id_cms_menus'])->count()) {unset($data[$k]);}
        }
        DB::table('cms_menus_privileges')->insert($data);
        $this->command->info("Create cms_menus_privileges completed");
        # cms_menus_privileges End

        //----------------------------------------------------------//

        # cms_moduls
        $data = [

            ["id" => "13","name" => "Cuentas","icon" => "fa fa-youtube-play","path" => "accounts","table_name" => "accounts","controller" => "AdminAccountsController","is_protected" => "0","is_active" => "0","created_at" => "2021-10-18 18:41:13","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "14","name" => "Tipos de cuentas","icon" => "fa fa-key","path" => "type_account","table_name" => "type_account","controller" => "AdminTypeAccountController","is_protected" => "0","is_active" => "0","created_at" => "2021-10-18 18:43:09","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "15","name" => "Recargas","icon" => "fa fa-phone","path" => "recargas","table_name" => "recargas","controller" => "AdminRecargasController","is_protected" => "0","is_active" => "0","created_at" => "2021-10-24 21:38:56","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "16","name" => "Tipo de recargas","icon" => "fa fa-check","path" => "tipo_de_recargas","table_name" => "tipo_de_recargas","controller" => "AdminTipoDeRecargasController","is_protected" => "0","is_active" => "0","created_at" => "2021-10-24 21:44:31","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "19","name" => "Clientes","icon" => "fa fa-life-ring","path" => "customers","table_name" => "customers","controller" => "AdminCustomersController","is_protected" => "0","is_active" => "0","created_at" => "2021-12-08 16:10:11","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "20","name" => "Ordenes","icon" => "fa fa-shopping-bag","path" => "orders","table_name" => "orders","controller" => "AdminOrdersController","is_protected" => "0","is_active" => "0","created_at" => "2021-12-08 16:26:05","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "21","name" => "Pantallas","icon" => "fa fa-tv","path" => "screens","table_name" => "screens","controller" => "AdminScreensController","is_protected" => "0","is_active" => "0","created_at" => "2021-12-27 04:13:41","updated_at" => NULL,"deleted_at" => NULL],
            ["id" => "24","name" => "Venta Individual","icon" => "fa fa-shopping-bag","path" => "ordersIndividual","table_name" => "orders","controller" => "AdminOrdersIndividualController","is_protected" => "0","is_active" => "0","created_at" => "2022-05-15 00:45:57","updated_at" => NULL,"deleted_at" => NULL]
        ];

        foreach ($data as $k => $d) {
            if (DB::table('cms_moduls')->where('name', $d['name'])->count()) {unset($data[$k]);}
        }
        DB::table('cms_moduls')->insert($data);
        $this->command->info("Create cms_moduls completed");
        # cms_moduls End

        //---------------------------------------------------------------------------------//


        # cms_privileges_roles
        $data = [
            //Admin cms_privileges_roles
            ["id" => "1","is_visible" => "1","is_create" => "0","is_read" => "0","is_edit" => "0","is_delete" => "0","id_cms_privileges" => "1","id_cms_moduls" => "1","created_at" => NULL,"updated_at" => NULL],
            ["id" => "2","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "2","created_at" => NULL,"updated_at" => NULL],
            ["id" => "3","is_visible" => "0","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "3","created_at" => NULL,"updated_at" => NULL],
            ["id" => "4","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "4","created_at" => NULL,"updated_at" => NULL],
            ["id" => "5","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "5","created_at" => NULL,"updated_at" => NULL],
            ["id" => "6","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "6","created_at" => NULL,"updated_at" => NULL],
            ["id" => "7","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "7","created_at" => NULL,"updated_at" => NULL],
            ["id" => "8","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "8","created_at" => NULL,"updated_at" => NULL],
            ["id" => "9","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "9","created_at" => NULL,"updated_at" => NULL],
            ["id" => "10","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "10","created_at" => NULL,"updated_at" => NULL],
            ["id" => "11","is_visible" => "1","is_create" => "0","is_read" => "1","is_edit" => "0","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "11","created_at" => NULL,"updated_at" => NULL],
            ["id" => "12","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "12","created_at" => NULL,"updated_at" => NULL],
            ["id" => "13","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "13","created_at" => NULL,"updated_at" => NULL],
            ["id" => "14","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "14","created_at" => NULL,"updated_at" => NULL],
            ["id" => "27","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "15","created_at" => NULL,"updated_at" => NULL],
            ["id" => "28","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "16","created_at" => NULL,"updated_at" => NULL],
            ["id" => "29","is_visible" => "1","is_create" => "0","is_read" => "1","is_edit" => "0","is_delete" => "0","id_cms_privileges" => "2","id_cms_moduls" => "13","created_at" => NULL,"updated_at" => NULL],
            ["id" => "30","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "2","id_cms_moduls" => "15","created_at" => NULL,"updated_at" => NULL],
            ["id" => "31","is_visible" => "1","is_create" => "0","is_read" => "1","is_edit" => "0","is_delete" => "0","id_cms_privileges" => "2","id_cms_moduls" => "12","created_at" => NULL,"updated_at" => NULL],
            ["id" => "32","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "17","created_at" => NULL,"updated_at" => NULL],
            ["id" => "33","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "18","created_at" => NULL,"updated_at" => NULL],
            ["id" => "34","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "19","created_at" => NULL,"updated_at" => NULL],
            ["id" => "35","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "20","created_at" => NULL,"updated_at" => NULL],
            ["id" => "36","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "21","created_at" => NULL,"updated_at" => NULL],
            ["id" => "37","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "22","created_at" => NULL,"updated_at" => NULL],
            ["id" => "38","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "23","created_at" => NULL,"updated_at" => NULL],
            ["id" => "39","is_visible" => "1","is_create" => "1","is_read" => "1","is_edit" => "1","is_delete" => "1","id_cms_privileges" => "1","id_cms_moduls" => "24","created_at" => NULL,"updated_at" => NULL]
        ];

        foreach ($data as $k => $d) {
            if (DB::table('cms_privileges_roles')->where('id',"=", $d["id"])->count() == 1 ) {unset($data[$k]);}
        }
        \Illuminate\Support\Facades\DB::table('cms_privileges_roles')->insert($data);

        $this->command->info("Create cms_privileges_roles completed");
        # cms_moduls End

        //----------------------------------------------------------//

        # cms_menus_privileges
        $data = [
            ["id" => "3","id_cms_menus" => "3","id_cms_privileges" => "1"],
            ["id" => "4","id_cms_menus" => "1","id_cms_privileges" => "2"],
            ["id" => "5","id_cms_menus" => "1","id_cms_privileges" => "1"],
            ["id" => "6","id_cms_menus" => "2","id_cms_privileges" => "2"],
            ["id" => "7","id_cms_menus" => "2","id_cms_privileges" => "1"],
            ["id" => "9","id_cms_menus" => "5","id_cms_privileges" => "1"],
            ["id" => "10","id_cms_menus" => "4","id_cms_privileges" => "2"],
            ["id" => "11","id_cms_menus" => "4","id_cms_privileges" => "1"],
            ["id" => "12","id_cms_menus" => "6","id_cms_privileges" => "1"],
            ["id" => "13","id_cms_menus" => "7","id_cms_privileges" => "2"],
            ["id" => "14","id_cms_menus" => "7","id_cms_privileges" => "1"],
            ["id" => "15","id_cms_menus" => "8","id_cms_privileges" => "1"],
            ["id" => "16","id_cms_menus" => "9","id_cms_privileges" => "1"],
            ["id" => "17","id_cms_menus" => "10","id_cms_privileges" => "1"],
            ["id" => "18","id_cms_menus" => "11","id_cms_privileges" => "1"],
            ["id" => "19","id_cms_menus" => "12","id_cms_privileges" => "1"],
            ["id" => "20","id_cms_menus" => "13","id_cms_privileges" => "1"],
            ["id" => "21","id_cms_menus" => "14","id_cms_privileges" => "1"]
        ];

         foreach ($data as $k => $d) {
             if (DB::table('cms_menus_privileges')->where('id',"=", $d["id"])->count() == 1) {unset($data[$k]);}
         }
        DB::table('cms_menus_privileges')->insert($data);
        $this->command->info("Create cms_menus_privileges completed");
        # cms_moduls End

        //----------------------------------------------------------//


        # cms_privileges
          $data = [
              ["id" => "2","name" => "Coordinador","is_superadmin" => "0","theme_color" => "skin-green","created_at" => NULL,"updated_at" => NULL]
          ];

         foreach ($data as $k => $d) {
             if (DB::table('cms_privileges')->where('name', $d['name'])->count()) {unset($data[$k]);}
         }
        DB::table('cms_privileges')->insert($data);
         $this->command->info("Create cms_privileges completed");
        # cms_moduls End

        //----------------------------------------------------------//

        # cms_users
        $data = [
        ];
        // foreach ($data as $k => $d) {
        //      if (DB::table('cms_users')->where('name', $d['name'])->count()) {unset($data[$k]);}
        //  }
        // DB::table('cms_users')->insert($data);
        // $this->command->info("Create cms_users completed");
        # cms_users End

        //----------------------------------------------------------//

        #typesAccounts
        $data = [
            ["id" => "1","name" => "Netflix","total_screens" => "5","available_screens" => "4","extraordinary_available_screens" => "5","price_day" => "1000","picture" => "uploads/1/2021-10/netflix_n_logo_png.png","created_at" => "2021-10-18 18:44:18","updated_at" => NULL],
            ["id" => "2","name" => "Amazon Peime","total_screens" => "6","available_screens" => "3","extraordinary_available_screens" => "4","price_day" => "2500","picture" => "uploads/1/2021-10/l4get34d_400x400.jpg","created_at" => "2021-10-18 21:58:32","updated_at" => NULL],
            ["id" => "3","name" => "Disney plus","total_screens" => "7","available_screens" => "3","extraordinary_available_screens" => "4","price_day" => "5000","picture" => "uploads/1/2021-12/images.png","created_at" => "2021-12-08 20:34:11","updated_at" => NULL]
        ];
        foreach ($data as $k => $d) {
            if (DB::table('type_account')->where('name', $d['name'])->count()) {unset($data[$k]);}
        }
        DB::table('type_account')->insert($data);
        $this->command->info("Create types Completed completed");
        # typesAccounts End

        //----------------------------------------------------------//

        #cms_settings
//        $data = [
//
//        ];
//        foreach ($data as $k => $d) {
//            if (\Illuminate\Support\Facades\DB::table('cms_settings')->where('name', $d['name'])->count()) {unset($data[$k]);}
//        }
//
//        \Illuminate\Support\Facades\DB::table('cms_settings')->insert($data);
//        $this->command->info("Create cms_settings completed");
//        # cms_settings End
        $this->command->info('Updating the data completed !');
    }
}
