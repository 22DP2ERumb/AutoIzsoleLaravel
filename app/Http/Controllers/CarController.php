<?php

namespace App\Http\Controllers;

use App\Models\Car; 
use App\Models\CarBrand; 
use App\Models\CarAuction; 
use App\Models\Car_Images; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CarModel;  // Make sure this is at the top of the file

class CarController extends Controller
{
    public function addCarPost(Request $request)
    {
        Log::info('Car data:', $request->all());
        // Validate first
        $validator = Validator::make($request->all(), [
        'brand' => 'required|string|exists:car_brands,manufacturer',
        'model' => 'required|string|exists:car_models,model',
        'year' => 'required|integer',
        'mileage' => 'required|integer',
        'fuel_type' => 'required|string',
        'transmission' => 'required|string',
        'engine_size' => 'required|numeric',
        'body_type' => 'required|string',
        'color' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 400);
    }

    // Find car brand ID
    $carBrand = CarBrand::where('manufacturer', $request->brand)->first();
    if (!$carBrand) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid car brand.'
        ], 400);
    }

    $carModel = CarModel::where('model', $request->model)->first();

    if (!$carModel) {
        return response()->json([
            'status' => 'error',
            'message' => 'Model not found.',
        ], 404);
    }

    // Create car
    $car = Car::create([
        'user_id' => Auth::id(),
        'car_brand_id' => $carBrand->id,
        'car_model_id' => $carModel->id,
        'year' => $request->year,
        'mileage' => $request->mileage,
        'fuel_type' => $request->fuel_type,
        'transmission' => $request->transmission,
        'engine_size' => $request->engine_size,
        'body_type' => $request->body_type,
        'color' => $request->color,
    ]);

    // Save images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('car_images', 'public');
            Car_Images::create([
                'car_id' => $car->id,
                'image_path' => Storage::url($path),
            ]);
        }
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
                    ->with(['images', 'brand', 'model', 'auctions'])
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
    public function update(Request $request)
{
    // Check if the user is authenticated
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Validate the request data
    $validated = $request->validate([
        'car_id' => 'required|exists:cars,id',
        'car_brand' => 'required|string|exists:car_brands,manufacturer', // Validate by brand manufacturer name
        'car_model' => 'required|string|exists:car_models,model', // Validate by model name
        'year' => 'required|integer',
        'mileage' => 'required|integer',
        'fuel_type' => 'required|string',
        'transmission' => 'required|string',
        'engine_size' => 'required|string',
        'body_type' => 'required|string',
        'color' => 'required|string',
    ]);

    // Find the car by its ID
    $car = Car::find($validated['car_id']);

    // Check if the authenticated user owns the car
    if ($car->user_id !== $user->id) {
        return response()->json(['error' => 'You do not own this car'], 403);
    }

    // Find the car brand and model by their names
    $carBrand = CarBrand::where('manufacturer', $validated['car_brand'])->first();
    $carModel = CarModel::where('model', $validated['car_model'])->first();

    // If no brand or model found, return an error
    if (!$carBrand) {
        return response()->json(['error' => 'Brand not found'], 404);
    }

    if (!$carModel) {
        return response()->json(['error' => 'Model not found'], 404);
    }

    // Update the car's information with the found IDs
    $car->update([
        'car_brand_id' => $carBrand->id,
        'car_model_id' => $carModel->id,
        'year' => $validated['year'],
        'mileage' => $validated['mileage'],
        'fuel_type' => $validated['fuel_type'],
        'transmission' => $validated['transmission'],
        'engine_size' => $validated['engine_size'],
        'body_type' => $validated['body_type'],
        'color' => $validated['color'],
    ]);

    return response()->json(['message' => 'Car updated successfully', 'car' => $car], 200);
}
    public function GetAuctionCars()
    {
        $cars = Car::whereHas('auctions') // just checks if there are any auctions
                    ->with(['images', 'brand', 'model', 'auctions'])
                    ->get();

        return response()->json($cars);
    }
    public function GetAuctionCarInfo(Request $request)
    {
        $cars = Car::whereHas('auctions') // just checks if there are any auctions
                    ->with(['images', 'brand', 'model', 'auctions'])
                    ->get();

        return response()->json($cars);
    }
    
}
