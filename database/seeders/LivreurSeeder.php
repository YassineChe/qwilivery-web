<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        DB::table('deliveries')->insert([
            'first_name' => Str::random(6),
            'last_name' => Str::random(6),
            'status' => true,
            'phone' => "+12323433433",
            'email' => "L-" . Str::random(5) . '@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
