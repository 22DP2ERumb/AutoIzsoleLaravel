<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Car_Images;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Base car templates (without user_id)
        $baseCars = [
            [
                'car_brand_id' => 1,
                'car_model_id' => 1,
                'year' => 2018,
                'mileage' => 60000,
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_size' => '2.0',
                'body_type' => 'Sedan',
                'color' => 'Black',
                'images' => [
                    '/storage/car_images/car1.jpg',
                    '/storage/car_images/car2.jpg',
                ],
            ],
            [
                'car_brand_id' => 5,
                'car_model_id' => 28,
                'year' => 2020,
                'mileage' => 30000,
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'engine_size' => '2.5',
                'body_type' => 'SUV',
                'color' => 'White',
                'images' => [
                    '/storage/car_images/car2.jpg',
                    '/storage/car_images/car1.jpg',
                ],
            ],
            [
                'car_brand_id' => 6,
                'car_model_id' => 35,
                'year' => 2017,
                'mileage' => 75000,
                'fuel_type' => 'Hybrid',
                'transmission' => 'Automatic',
                'engine_size' => '1.8',
                'body_type' => 'Hatchback',
                'color' => 'Blue',
                'images' => [
                    '/storage/car_images/car1.jpg',
                    '/storage/car_images/car2.jpg',
                ],
            ],
            [
                'car_brand_id' => 2,
                'car_model_id' => 7,
                'year' => 2019,
                'mileage' => 45000,
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'engine_size' => '2.0',
                'body_type' => 'Sedan',
                'color' => 'Grey',
                'images' => [
                    '/storage/car_images/car1.jpg',
                    '/storage/car_images/car2.jpg',
                ],
            ],
            [
                'car_brand_id' => 3,
                'car_model_id' => 17,
                'year' => 2021,
                'mileage' => 15000,
                'fuel_type' => 'Electric',
                'transmission' => 'Automatic',
                'engine_size' => '0.0',
                'body_type' => 'Sedan',
                'color' => 'Red',
                'images' => [
                    '/storage/car_images/car2.jpg',
                    '/storage/car_images/car1.jpg',
                ],
            ],
            [
                'car_brand_id' => 4,
                'car_model_id' => 22,
                'year' => 2016,
                'mileage' => 80000,
                'fuel_type' => 'Diesel',
                'transmission' => 'Automatic',
                'engine_size' => '2.2',
                'body_type' => 'SUV',
                'color' => 'Silver',
                'images' => [
                    '/storage/car_images/car1.jpg',
                    '/storage/car_images/car2.jpg',
                ],
            ],
        ];

        // Generate cars for users 1 to 6
        for ($userId = 1; $userId <= 6; $userId++) {
            foreach ($baseCars as $carData) {
                $carData['user_id'] = $userId;

                // Slight variation: add userId to mileage and year to diversify
                $carData['mileage'] += ($userId - 1) * 5000;
                $carData['year'] = $carData['year'] - ($userId - 1);

                $images = $carData['images'];
                unset($carData['images']);

                $car = Car::create($carData);

                foreach ($images as $path) {
                    Car_Images::create([
                        'car_id' => $car->id,
                        'image_path' => $path,
                    ]);
                }
            }
        }
    }
}
