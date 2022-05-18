<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Modulos extends Seeder
{
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
            if (\Illuminate\Support\Facades\DB::table('cms_menus')->where('name', $d['name'])->count()) {
                unset($data[$k]);
            }
        }
        \Illuminate\Support\Facades\DB::table('cms_menus')->insert($data);
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
            if (\Illuminate\Support\Facades\DB::table('cms_menus_privileges')->where('id_cms_menus', $d['id_cms_menus'])->count()) {
                unset($data[$k]);
            }
        }
        \Illuminate\Support\Facades\DB::table('cms_menus_privileges')->insert($data);
        $this->command->info("Create cms_menus_privileges completed");
        # cms_menus_privileges End
        //----------------------------------------------------------//


    }
}
