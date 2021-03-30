<?php

namespace Database\Seeders;

use Carbon\Carbon as CarbonCarbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class LivreurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        for ($i = 0; $i <= 5; $i++) {

            DB::table('deliveries')->insert([
                'first_name' => Str::random(6),
                'last_name' => Str::random(6),
                'status' => true,
                'experience' => 1,
                'permit' => "permit.pdf",
                'blocked_at' => null,
                'phone' =>  "+1" . rand(1000000000, 1999999999),
                'email' => "L-" . Str::random(5) . '@gmail.com',
                'password' => Hash::make('password'),
            ]);

            DB::table('deliveries')->insert([
                'first_name' => Str::random(6),
                'last_name' => Str::random(6),
                'status' => false,
                'experience' => 0,
                'permit' => "permit.pdf",
                'blocked_at' => null,
                'phone' =>  "+1" . rand(1000000000, 1999999999),
                'email' => "L-" . Str::random(5) . '@gmail.com',
                'password' => Hash::make('password'),
            ]);

            DB::table('deliveries')->insert([
                'first_name' => Str::random(6),
                'last_name' => Str::random(6),
                'status' => false,
                'experience' => 10,
                'permit' => "permit.pdf",
                'blocked_at' => Carbon::now(),
                'phone' =>  "+1" . rand(1000000000, 1999999999),
                'email' => "L-" . Str::random(5) . '@gmail.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
