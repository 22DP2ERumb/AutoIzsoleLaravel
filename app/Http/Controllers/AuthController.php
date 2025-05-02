<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    
    public function registerPost(Request $request)
    {
        // larvale.log for debugging
        Log::info('User registration data:', $request->all());


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful.',
            'user' => $user
        ], 201);

    }
    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Log::info('User logged in successfully.', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'time' => now()->toDateTimeString()
            ]);

        }

        Log::warning('Failed login attempt.', [
            'email' => $request->email,
            'time' => now()->toDateTimeString()
        ]);

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
        
    }
}
