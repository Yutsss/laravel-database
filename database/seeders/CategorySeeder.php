<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("categories")->insert([
            'id' => 'GADGET',
            'name' => 'GADGET',
            'description' => 'GADGET Category',
            'created_at' => "2021-01-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'FOOD',
            'name' => 'FOOD',
            'description' => 'FOOD Category',
            'created_at' => "2021-02-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'CLOTH',
            'name' => 'CLOTH',
            'description' => 'CLOTH Category',
            'created_at' => "2021-03-01 00:00:00"
        ]);

        DB::table("categories")->insert([
            'id' => 'TOY',
            'name' => 'TOY',
            'description' => 'TOY Category',
            'created_at' => "2021-04-01 00:00:00"
        ]);
    }
}
