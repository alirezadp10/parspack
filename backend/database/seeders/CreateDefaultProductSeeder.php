<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class CreateDefaultProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Product::firstOrCreate(['name' => 'A']);

        Product::firstOrCreate(['name' => 'B']);

        Product::firstOrCreate(['name' => 'C']);
    }
}
