<?php

namespace Database\Seeders;

use App\Models\Type;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StatusSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CountrySeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PrioritySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\EvaluationQuestionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([PermissionSeeder::class,RoleSeeder::class,UserSeeder::class,CategorySeeder::class, PrioritySeeder::class,StatusSeeder::class,CountrySeeder::class, EvaluationQuestionSeeder::class]);
        DB::table('departments')->truncate();
        Department::factory()->createMany([
            ['name' => 'Technical'], ['name' => 'Management'], ['name' => 'Hardware'], ['name' => 'Software'], ['name' => 'Development'], ['name' => 'Admin']
        ]);

        DB::table('types')->truncate();
        Type::factory()->createMany([
            ['name' => 'Service'], ['name' => 'Hardware'], ['name' => 'Software'], ['name' => 'Event'], ['name' => 'New type']
        ]);
    }
}
