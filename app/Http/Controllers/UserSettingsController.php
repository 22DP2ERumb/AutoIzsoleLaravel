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
use Illuminate\Validation\Rules\Password;

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
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
    ]);

    try {
        // Update user data
        $user->name = $validated['name'];
        
        // Only update email if it's changed
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null; // Require email verification if email changed
            // You might want to send a verification email here
        }
        
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update profile',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function updatePassword(Request $request)
{
    // Get authenticated user
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Validate request data
    $validated = $request->validate([
        'current_password' => [
            'required',
            function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }
        ],
        'new_password' => [
            'required',
            'string',
            'confirmed',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ],
    ]);

    try {
        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to update password',
            'error' => $e->getMessage()
        ], 500);
    }
}
}

