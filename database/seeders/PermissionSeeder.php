<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::on('mysql')->create(['name'  => 'add-ticket']);
        Permission::on('mysql')->create(['name'  => 'edit-ticket']);
        Permission::on('mysql')->create(['name'  => 'view-ticket']);
        Permission::on('mysql')->create(['name'  => 'delete-ticket']);
        
        Permission::on('mysql')->create(['name'  => 'add-survey']);
        Permission::on('mysql')->create(['name'  => 'edit-survey']);
        Permission::on('mysql')->create(['name'  => 'view-survey']);
        Permission::on('mysql')->create(['name'  => 'delete-survey']);
        
        Permission::on('mysql')->create(['name'  => 'add-customer']);
        Permission::on('mysql')->create(['name'  => 'edit-customer']);
        Permission::on('mysql')->create(['name'  => 'view-customer']);
        Permission::on('mysql')->create(['name'  => 'delete-customer']);
        
        Permission::on('mysql')->create(['name'  => 'add-note']);
        Permission::on('mysql')->create(['name'  => 'edit-note']);
        Permission::on('mysql')->create(['name'  => 'view-note']);
        Permission::on('mysql')->create(['name'  => 'delete-note']);
        
        Permission::on('mysql')->create(['name'  => 'add-contact']);
        Permission::on('mysql')->create(['name'  => 'edit-contact']);
        Permission::on('mysql')->create(['name'  => 'view-contact']);
        Permission::on('mysql')->create(['name'  => 'delete-contact']);
        
        Permission::on('mysql')->create(['name'  => 'add-organization']);
        Permission::on('mysql')->create(['name'  => 'edit-organization']);
        Permission::on('mysql')->create(['name'  => 'view-organization']);
        Permission::on('mysql')->create(['name'  => 'delete-organization']);
        
        Permission::on('mysql')->create(['name'  => 'add-user']);
        Permission::on('mysql')->create(['name'  => 'edit-user']);
        Permission::on('mysql')->create(['name'  => 'view-user']);
        Permission::on('mysql')->create(['name'  => 'delete-user']);
        
        Permission::on('mysql')->create(['name'  => 'add-role']);
        Permission::on('mysql')->create(['name'  => 'edit-role']);
        Permission::on('mysql')->create(['name'  => 'view-role']);
        Permission::on('mysql')->create(['name'  => 'delete-role']);
        
        
        Permission::on('mysql')->create(['name'  => 'add-permission']);
        Permission::on('mysql')->create(['name'  => 'edit-permission']);
        Permission::on('mysql')->create(['name'  => 'view-permission']);
        Permission::on('mysql')->create(['name'  => 'delete-permission']);
        
        Permission::on('mysql')->create(['name'  => 'add-category']);
        Permission::on('mysql')->create(['name'  => 'edit-category']);
        Permission::on('mysql')->create(['name'  => 'view-category']);
        Permission::on('mysql')->create(['name'  => 'delete-category']);
        
        Permission::on('mysql')->create(['name'  => 'add-status']);
        Permission::on('mysql')->create(['name'  => 'edit-status']);
        Permission::on('mysql')->create(['name'  => 'view-status']);
        Permission::on('mysql')->create(['name'  => 'delete-status']);
        
        
        Permission::on('mysql')->create(['name'  => 'add-department']);
        Permission::on('mysql')->create(['name'  => 'edit-department']);
        Permission::on('mysql')->create(['name'  => 'view-department']);
        Permission::on('mysql')->create(['name'  => 'delete-department']);
        
        Permission::on('mysql')->create(['name'  => 'add-type']);
        Permission::on('mysql')->create(['name'  => 'edit-type']);
        Permission::on('mysql')->create(['name'  => 'view-type']);
        Permission::on('mysql')->create(['name'  => 'delete-type']);
        
        Permission::on('mysql')->create(['name'  => 'add-priority']);
        Permission::on('mysql')->create(['name'  => 'edit-priority']);
        Permission::on('mysql')->create(['name'  => 'view-priority']);
        Permission::on('mysql')->create(['name'  => 'delete-priority']);
        
        
        Permission::on('mysql')->create(['name'  => 'add-evaluation']);
        Permission::on('mysql')->create(['name'  => 'edit-evaluation']);
        Permission::on('mysql')->create(['name'  => 'view-evaluation']);
        Permission::on('mysql')->create(['name'  => 'delete-evaluation']); 

    }
}
