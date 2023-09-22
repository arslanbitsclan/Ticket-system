<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::on('mysql')->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@e.com',
            'phone' => '123456789',
            'password' => Hash::make('admin123')
        ]);
        $user->assignRole(1);

    }
}
