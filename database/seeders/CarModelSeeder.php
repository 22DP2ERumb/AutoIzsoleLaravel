<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarModel;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarModel::create([
            "model"=> "Accord",
            "brand_id"=> 1,
        ]);
        CarModel::create([
            "model"=> "Civic",
            "brand_id"=> 1,
        ]);
        CarModel::create([
            "model"=> "Cr-v",
            "brand_id"=> 1,
        ]);



        CarModel::create([
            "model"=> "1. series",
            "brand_id"=> 2,
        ]);
        CarModel::create([
            "model"=> "2. series",
            "brand_id"=> 2,
        ]);
        CarModel::create([
            "model"=> "3. series",
            "brand_id"=> 2,
        ]);


        CarModel::create([
            "model"=> "A1",
            "brand_id"=> 3,
        ]);
        CarModel::create([
            "model"=> "A2",
            "brand_id"=> 3,
        ]);
        CarModel::create([
            "model"=> "A3",
            "brand_id"=> 3,
        ]);



        CarModel::create([
            "model"=> "A-class",
            "brand_id"=> 4,
        ]);
        CarModel::create([
            "model"=> "B-class",
            "brand_id"=> 4,
        ]);
        CarModel::create([
            "model"=> "C-class",
            "brand_id"=> 4,
        ]);
    }
}
