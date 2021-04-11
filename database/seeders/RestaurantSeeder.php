<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('restaurants')->insert([
            'name' => Str::random(6),
            'email' => "R-" . Str::random(5) . '@gmail.com',
            'password' => Hash::make('password'),
            'phone_number' =>  "+1" . rand(1000000000, 1999999999),
            'address' => "casablanca zarktoni",
            'rate' => 3,
        ]);
    }
}
