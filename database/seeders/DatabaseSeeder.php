<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Responsibility;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//        DB::table('users')->insert([
//            'name' => 'Aldi Nugraha',
//            'email' => 'aldngrha@gmail.com',
//            'email_verified_at' => now(),
//            'password' => Hash::make('password'),
//        ]);

//        Company::factory(10)->create();
//        Team::factory(10)->create();
//        Role::factory(40)->create();
//        Responsibility::factory(200)->create();
        Employee::factory(1000)->create();
    }
}
