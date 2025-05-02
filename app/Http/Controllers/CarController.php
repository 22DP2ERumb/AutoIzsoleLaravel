<?php

namespace App\Http\Controllers;

use App\Models\Car; 
use App\Models\CarBrand; 
use App\Models\Car_Images; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function addCarPost(Request $request)
    {
        Log::info('Car data:', $request->all());

        $car_brand_id = CarBrand::where('manufacturer', $request->brand)->first()->id;

        $car = Car::create([
            'user_id' => Auth::user()->id, // Pievieno lietotāja ID
            'car_brand_id' => $car_brand_id,
            'model' => $request->model,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'engine_size' => $request->engine_size,
            'body_type' => $request->body_type,
            'color' => $request->color,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('car_images', 'public');
                Car_Images::create([
                    'car_id' => $car->id,
                    'image_path' => Storage::url($path), // generates URL like /storage/...
                ]);
            }
        }
        




        $validator = Validator::make($request->all(), []);
                
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful.',
            'car' => $car
        ], 201);
    }
    public function GetProfileCars()
    {
        $cars = Car::where('user_id', Auth::id())
                    ->with(['images', 'brand'])
                    ->get();


        return response()->json($cars);
    }
    public function getCarBrands()
    {
        // Retrieve all car brands from the database
        $carBrands = CarBrand::all();  // Assuming you have a CarBrand model

        // Return the car brands as JSON
        return response()->json($carBrands);
    }
    
    
}
