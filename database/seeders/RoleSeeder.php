<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator_role = Role::on('mysql')->create(['name' => 'admin']);
        $permissions = Permission::on('mysql')->pluck('id','id')->all();
        $administrator_role->syncPermissions($permissions);
        
        $customer_role = Role::on('mysql')->create(['name' => 'customer']);
        $permissions = ['add-ticket','edit-ticket','view-ticket','delete-ticket','add-survey','edit-survey','view-survey','delete-survey','add-contact','edit-contact','view-contact','delete-contact'];
        $customer_role->syncPermissions($permissions);

    }
}
