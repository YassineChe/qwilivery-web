<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'email' => "admin1" . '@gmail.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('admins')->insert([
            'email' => "admin2" . '@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
