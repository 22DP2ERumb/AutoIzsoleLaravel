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
        $cars = [
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
                'user_id' => 1,
                'images' => [
                    '/storage/car_images/3hfJHylMPZ6X3LjYeDOKpxOJShs7oTXIUqUxxunu.jpg',
                    '/storage/car_images/4z2E6FfRlBkdZ2KtcticLOC8KD9UO3p3TP3N0nxf.jpg'
                ]
            ],
            [
                'car_brand_id' => 1,
                'car_model_id' => 2,
                'year' => 2020,
                'mileage' => 30000,
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'engine_size' => '2.5',
                'body_type' => 'SUV',
                'color' => 'White',
                'user_id' => 1,
                'images' => [
                    '/storage/car_images/4z2E6FfRlBkdZ2KtcticLOC8KD9UO3p3TP3N0nxf.jpg',
                    '/storage/car_images/3hfJHylMPZ6X3LjYeDOKpxOJShs7oTXIUqUxxunu.jpg'
                ]
            ],
            [
                'car_brand_id' => 2,
                'car_model_id' => 4,
                'year' => 2017,
                'mileage' => 75000,
                'fuel_type' => 'Hybrid',
                'transmission' => 'Automatic',
                'engine_size' => '1.8',
                'body_type' => 'Hatchback',
                'color' => 'Blue',
                'user_id' => 1,
                'images' => [
                    '/storage/car_images/3hfJHylMPZ6X3LjYeDOKpxOJShs7oTXIUqUxxunu.jpg',
                    '/storage/car_images/4z2E6FfRlBkdZ2KtcticLOC8KD9UO3p3TP3N0nxf.jpg'
                ]
            ],
            [
                'car_brand_id' => 2,
                'car_model_id' => 5,
                'year' => 2019,
                'mileage' => 45000,
                'fuel_type' => 'Petrol',
                'transmission' => 'Manual',
                'engine_size' => '2.0',
                'body_type' => 'Sedan',
                'color' => 'Grey',
                'user_id' => 1,
                'images' => [
                    '/storage/car_images/3hfJHylMPZ6X3LjYeDOKpxOJShs7oTXIUqUxxunu.jpg',
                    '/storage/car_images/4z2E6FfRlBkdZ2KtcticLOC8KD9UO3p3TP3N0nxf.jpg'
                ]
            ],
            [
                'car_brand_id' => 3,
                'car_model_id' => 7,
                'year' => 2021,
                'mileage' => 15000,
                'fuel_type' => 'Electric',
                'transmission' => 'Automatic',
                'engine_size' => '0.0',
                'body_type' => 'Sedan',
                'color' => 'Red',
                'user_id' => 1,
                'images' => [
                    '/storage/car_images/4z2E6FfRlBkdZ2KtcticLOC8KD9UO3p3TP3N0nxf.jpg',
                    '/storage/car_images/3hfJHylMPZ6X3LjYeDOKpxOJShs7oTXIUqUxxunu.jpg'
                ]
            ],
        ];

        foreach ($cars as $carData) {
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
