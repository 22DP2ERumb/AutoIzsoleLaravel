<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\AdminController;
use App\Models\Car; 
use App\Models\CarModel; 
use App\Models\CarAuction;
use App\Models\Comment; 
use Illuminate\Http\Request;

Route::get('/', function () {
    return view(view: 'welcome');
});
Route::get('/contactus', function () {
    return view(view: 'welcome');
});
Route::get('/aboutus', function () {
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

Route::get('/getAuctionCar/{carId}', function ($carId) {

    $car = Car::whereHas('auctions')
              ->with(['images', 'brand', 'model', 'auctions'])
              ->find($carId);

    if (!$car) {
        return response()->json(['message' => 'Car not found or has no auctions.'], 404);
    }

    $car->images->each(function ($image) {
        $image->url = $image->image_path;
    });

    $seller = $car->user;

    if (!$seller) {
        Log::warning('Seller not found for car', ['car_id' => $car->id]);
    } else {
        Log::info('Seller found', ['seller_id' => $seller->id]);
    }

    $averageRating = Comment::join('car_auction', 'comments.auction_id', '=', 'car_auction.id')
        ->join('cars', 'car_auction.car_id', '=', 'cars.id')
        ->where('cars.user_id', $seller->id)
        ->avg('comments.rating');

    $averageRating = is_null($averageRating) ? 0 : round($averageRating, 1);

    Log::info('Average rating calculated', ['average_rating' => $averageRating]);
    Log::info('Sellers Comments', ['Comments' => $averageRating]);

    return response()->json([
        'car' => $car,
        'auctions' => $car->auctions,
        'seller' => $seller,
        'averageRating' => $averageRating,
    ]);
    
});
Route::get('/auction/{carid}', function () {
    return view('welcome');
});

Route::post('/createBid', [BidController::class, 'CreateBid']);

Route::get('/getUserBid', [BidController::class, 'GetUsersBids']);

Route::get('/getBidData', [BidController::class, 'GetBidData']);

Route::post('/auction/comment', [AuctionController::class, 'storeComment']);


Route::get('/comments/{auction_id}', function ($auction_id) {
    $comments = Comment::where('auction_id', $auction_id)->latest()->get();

    $hasCommented = false;
    if (auth()->check()) {
        $hasCommented = Comment::where('auction_id', $auction_id)
            ->where('user_id', auth()->id())
            ->exists();
    }

    return response()->json([
        'comments' => $comments,
        'hasCommented' => $hasCommented,
    ]);
});
Route::get('/filteredCars', function (Request $request) {
    $query = Car::whereHas('auctions')
                ->with(['images', 'brand', 'model', 'auctions']);

    // Apply brand filter if provided
    if ($request->has('brand_id') && $request->brand_id != 'all') {
        $query->where('car_brand_id', $request->brand_id);
        Log::info('Fetching car with brand_id:', ['brand_id' => $request->brand_id]);
    }

    // Apply model filter if provided
    if ($request->has('model_id') && $request->model_id != 'all') {
        $query->where('car_model_id', $request->model_id);
    }

    // Apply search query if provided
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('brand', function($q) use ($search) {
                $q->where('manufacturer', 'like', "%{$search}%");
            })->orWhereHas('model', function($q) use ($search) {
                $q->where('model', 'like', "%{$search}%");
            });
        });
    }

    // Apply sorting if provided
    if ($request->has('sort_by')) {
        switch ($request->sort_by) {
            case 'year_asc':
                $query->orderBy('year', direction: 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            case 'mileage_asc':
                $query->orderBy('mileage', 'asc');
                break;
            case 'mileage_desc':
                $query->orderBy('mileage', 'desc');
                break;
            default:
                // Default sorting (if needed)
                $query->orderBy('created_at', 'desc');
        }
    } else {
        // Default sorting if no sort parameter is provided
        $query->orderBy('created_at', 'desc');
    }

    $cars = $query->get();

    return response()->json($cars);
});
Route::put('/updateUser', [UserSettingsController::class, 'update']);

Route::put('/updateUserPassword', [UserSettingsController::class, 'updatePassword']);


Route::get('/canListForAuction', function (Request $request) {
    // Check if the car is already in any auction
    $hasAuction = CarAuction::where('car_id', $request->carId)->exists();

    if (!$hasAuction) {
        return response()->json(['can_list' => true]);
    }

    // Check if the car is in an active auction
    $hasActiveAuction = CarAuction::where('car_id', $request->carId)
        ->where('Has_Ended', false)
        ->exists();

    return response()->json([
        'can_list' => !$hasActiveAuction,
        'message' => $hasActiveAuction ? 'This car is already in an active auction' : ''
    ]);
});

Route::get('/adminPanel', function () {
    $user = Auth::user();

    if ($user && $user->isAdmin) {
        return view('welcome');
    } else {
        return response('Forbidden', 403);
    }
});
Route::get('/getDashboardAdmin', [AdminController::class, 'getDashboard']);
Route::get('/getUsersAdmin', [AdminController::class, 'getUsers']);
Route::put('/updateUserRole/{user}', [AdminController::class, 'updateUserRole']);
Route::delete('/deleteUserAdmin/{user}', [AdminController::class, 'deleteUser']);
