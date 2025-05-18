<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuctionController;
use App\Models\Car; 
use App\Models\CarModel; 
use App\Models\CarAuction;

Route::get('/', function () {
    return view(view: 'welcome');
});

//LOGIN
Route::get('/login', function () {
    return view('welcome');
});
Route::post("/login", [AuthController::class, "loginPost"]);

// REGISTER
Route::get('/register', function () {
    return view('welcome');
});

Route::post("/register", [AuthController::class, "registerPost"]);

Route::get('/auth-user', function () {
    return response()->json([
        'isAuthenticated' => Auth::check(),
        'user' => Auth::user(),
    ]);
});

Route::post('/logout', function () {
    Auth::logout();
    return response()->json(['message' => 'Logged out']);
});


Route::get('/profile', function () {
    return view('welcome');
});

Route::get("/profile/cars", [CarController::class, "GetProfileCars"]);

Route::get('/addCar', function () {
    return view('welcome');
});

Route::post("/addCar", [CarController::class, "addCarPost"]);
Route::post("/AuctionCar", [AuctionController::class, "StartAuctionPost"]);

Route::delete('/deleteCar/{carId}', function ($carId) {
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $car = Car::where('user_id', $user->id)->find($carId);

    if (!$car) {
        return response()->json(['message' => 'Car not found or not owned by user'], 404);
    }
    
    if ($car->images) {
        foreach ($car->images as $image) {
            // Assuming each $image->url is a path to the file in storage
            $filePath = 'public/' . $image->url;

            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }
    }

    $car->delete();

    return response()->json(['message' => 'Car deleted successfully']);
});

Route::get('/getCar/{carId}', function ($carId) {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $car = Car::with(['images', 'brand', 'model', 'auctions'])
             ->where('user_id', $user->id)
             ->find($carId);

    if (!$car) {
        return response()->json(['message' => 'Car not found or not owned by user'], 404);
    }

    // Add `url` attribute to each image
    $car->images->each(function ($image) {
        $image->url = $image->image_path;
    });

    return response()->json($car);
});
Route::get('/allCarBrands', [CarController::class, 'getCarBrands']);

Route::get('/profile/{carid}', function () {
    return view('welcome');
});

Route::get('/getModelsByBrand/{brandid}', function ($brandid) {

    $carModels = CarModel::where('brand_id', $brandid)->get();
    return response()->json($carModels);
});
Route::post('/updateCar', [CarController::class, 'update']);

Route::delete('/deleteAuction/{auctionId}', function ($auctionId) {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $auction = CarAuction::where('user_id', $user->id)->find($auctionId);

    if (!$auction) {
        return response()->json(['message' => 'Auction not found or not owned by user'], 404);
    }

    $auction->delete();

    return response()->json(['message' => 'Auction deleted successfully']);
});

Route::get('/carAuctions', [CarController::class, 'GetAuctionCars']);