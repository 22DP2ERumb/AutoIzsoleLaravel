<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CarAuction;
use App\Models\Bid;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function getDashboard()
    {
        $query = Bid::query();

        $userCount = User::count();
        $auctionCount = CarAuction::count();
        $uniqueBidsCount = $query->distinct('user_id')->count('user_id');
        $bidsCount = Bid::count();

        // TEST
        // Get latest 5 orders with timestamp and description

    // Get latest 5 users with timestamp and description
    $users = \DB::table('users')
        ->select('created_at', \DB::raw('CONCAT("User \\"", name, "\\" registered") as description'))
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    $bids = \DB::table('bids')
        ->join('users', 'bids.user_id', '=', 'users.id')
        ->select('bids.created_at', \DB::raw('CONCAT("User \\"", users.name, "\\" placed a bid of ", bids.bid_amount, "$") as description'))
        ->orderByDesc('bids.created_at')
        ->limit(5)
        ->get();


    // Merge collections and sort by created_at descending
    $activities = $users->merge($bids)
        ->sortByDesc('created_at')
        ->take(10)
        ->values();


    return response()->json([
        'user_count' => $userCount,
        'auction_count' => $auctionCount,
        'unique_bid_count' => $uniqueBidsCount,
        'bid_count' => $bidsCount,
        'activities' => $activities
    ]);
    }
    public function getUsers()
    {
        $users = \DB::table('users')
            ->select('id', 'name', 'email', 'isAdmin')
            ->get();

        return response()->json($users);
    }

    public function updateUserRole(Request $request, User $user)
    {
        \Log::debug('Authorization check', [
            'auth_user_id' => auth()->id(),
            'auth_user_is_admin' => auth()->user()->isAdmin(),
            'target_user_id' => $user->id
        ]);
        // Check if current user is admin (using the new method)
        if (!auth()->user()->isAdmin()) {
            Log::warning('Unauthorized role update attempt', [
                'attempted_by' => auth()->id(),
                'target_user' => $user->id
            ]);
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        // Convert role to boolean
        $isAdmin = $validated['role'] === 'admin';

        // Update the user
        $user->update([
            'isAdmin' => $isAdmin
        ]);

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'user' => [
                'id' => $user->id,
                'is_admin' => $isAdmin,
                'role' => $validated['role'] // string representation
            ]
        ]);
    }
    public function deleteUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            Log::warning('Unauthorized deletion attempt', [
                'attempted_by' => auth()->id(),
                'target_user' => $user->id
            ]);
            abort(403, 'Unauthorized action.');
        }


        $user->delete();
    }

    
}
