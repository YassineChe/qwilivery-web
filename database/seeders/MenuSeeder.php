<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurant  = DB::table('restaurants')->select('id')->get()->toArray();;

        DB::table('categories')->insert([
            "name" => Str::random(7),
            'restaurant_id' => $restaurant[array_rand($restaurant)]->id
        ]);

        $categories  = DB::table('categories')->select('id')->get()->toArray();

        $items = ["small", "big", "larg"];
        DB::table('variants')->insert([
            "name" => Str::random(7),
            'size' => $items[array_rand($items)],
            'price' => rand(10, 100),
            'category_id' => $categories[array_rand($categories)]->id,
        ]);
    }
}
