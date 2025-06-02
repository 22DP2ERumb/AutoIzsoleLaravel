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
use App\Models\CarModel;

class UserSettingsController extends Controller
{
    public function update(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $validated = $request->validate([
        
        ]);

        
        return response()->json(['message' => 'User updated successfully', 'car' => $user], 200);
    }
}
