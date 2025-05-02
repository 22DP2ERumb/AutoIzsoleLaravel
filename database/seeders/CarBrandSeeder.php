<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarBrand;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarBrand::create([
            'manufacturer' => 'BMW',
            'image_path' => '/storage/car_brands/bmw.png',   
        ]);
        CarBrand::create([
            'manufacturer' => 'Audi',
            'image_path' => '/storage/car_brands/Audi.png',   
        ]);
        CarBrand::create([
            'manufacturer' => 'Mercedes',
            'image_path' => '/storage/car_brands/Mercedes.png',   
        ]);
    }
}
