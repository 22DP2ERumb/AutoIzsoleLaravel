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
        // Honda
        CarModel::create(["model"=> "Accord", "brand_id"=> 1]);
        CarModel::create(["model"=> "Civic", "brand_id"=> 1]);
        CarModel::create(["model"=> "CR-V", "brand_id"=> 1]);
        CarModel::create(["model"=> "Jazz", "brand_id"=> 1]);
        CarModel::create(["model"=> "HR-V", "brand_id"=> 1]);
        CarModel::create(["model"=> "Pilot", "brand_id"=> 1]);

        // BMW
        CarModel::create(["model"=> "1 Series", "brand_id"=> 2]);
        CarModel::create(["model"=> "2 Series", "brand_id"=> 2]);
        CarModel::create(["model"=> "3 Series", "brand_id"=> 2]);
        CarModel::create(["model"=> "X1", "brand_id"=> 2]);
        CarModel::create(["model"=> "X3", "brand_id"=> 2]);
        CarModel::create(["model"=> "X5", "brand_id"=> 2]);

        // Audi
        CarModel::create(["model"=> "A1", "brand_id"=> 3]);
        CarModel::create(["model"=> "A2", "brand_id"=> 3]);
        CarModel::create(["model"=> "A3", "brand_id"=> 3]);
        CarModel::create(["model"=> "A4", "brand_id"=> 3]);
        CarModel::create(["model"=> "Q3", "brand_id"=> 3]);
        CarModel::create(["model"=> "Q5", "brand_id"=> 3]);

        // Mercedes
        CarModel::create(["model"=> "A-Class", "brand_id"=> 4]);
        CarModel::create(["model"=> "B-Class", "brand_id"=> 4]);
        CarModel::create(["model"=> "C-Class", "brand_id"=> 4]);
        CarModel::create(["model"=> "E-Class", "brand_id"=> 4]);
        CarModel::create(["model"=> "GLA", "brand_id"=> 4]);
        CarModel::create(["model"=> "GLC", "brand_id"=> 4]);

        // Nissan
        CarModel::create(["model"=> "X-Trail", "brand_id"=> 5]);
        CarModel::create(["model"=> "Pathfinder", "brand_id"=> 5]);
        CarModel::create(["model"=> "Juke", "brand_id"=> 5]);
        CarModel::create(["model"=> "Qashqai", "brand_id"=> 5]);
        CarModel::create(["model"=> "Leaf", "brand_id"=> 5]);
        CarModel::create(["model"=> "Micra", "brand_id"=> 5]);

        // Toyota
        CarModel::create(["model"=> "Auris", "brand_id"=> 6]);
        CarModel::create(["model"=> "Celica", "brand_id"=> 6]);
        CarModel::create(["model"=> "Corolla", "brand_id"=> 6]);
        CarModel::create(["model"=> "Yaris", "brand_id"=> 6]);
        CarModel::create(["model"=> "Camry", "brand_id"=> 6]);
        CarModel::create(["model"=> "RAV4", "brand_id"=> 6]);
    }
}
