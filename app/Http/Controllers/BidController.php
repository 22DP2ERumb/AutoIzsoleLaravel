<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bid;
use App\Models\CarAuction; 

class BidController extends Controller
{
    public function CreateBid(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'auction_id' => 'required|exists:car_auction,id',
            'bid_amount' => 'required|numeric|min:0.01',
        ]);
        $auction = CarAuction::find($validated['auction_id']);

        if (!$auction || !$auction->is_active) {
            return response()->json(['message' => 'This auction is not active.'], 403);
        }

        $highestBid = Bid::where('auction_id', $validated['auction_id'])->max('bid_amount');

        if ($highestBid !== null && $validated['bid_amount'] <= $highestBid) {
        return response()->json([
            'message' => 'Your bid must be higher than the current highest bid (' . $highestBid . ')'
        ], 422);
    }

        $bid = Bid::create([
            'user_id' => $user->id,
            'auction_id' => $validated['auction_id'],
            'bid_amount' => $validated['bid_amount'],
        ]);

        return response()->json([
            'message' => 'Bid created successfully',
            'bid' => $bid
        ], 201);     
    } 

        
    public function GetUsersBids(Request $request)
    {
        $user = Auth::user();
        $auctionId = $request->query('auction_id');

        if (!$user) {
            return response()->json(['highest_bid' => 0]);
        }

        if (!$auctionId) {
            return response()->json(['error' => 'Missing auction_id'], 400);
        }

        $highestBid = Bid::where('user_id', $user->id)
            ->where('auction_id', $auctionId)
            ->orderByDesc('bid_amount')
            ->first();

        return response()->json([
            'highest_bid' => $highestBid?->bid_amount ?? 0
        ]);
    }
    public function GetBidData(Request $request)
    {
        $auctionId = $request->query('auction_id');

        $query = Bid::query();

        if ($auctionId) {
            $query->where('auction_id', $auctionId);
        }

        return response()->json([
            'total_bids' => $query->count(),
            'highest_bid' => $query->max('bid_amount'),
            'unique_bidders' => $query->distinct('user_id')->count('user_id'),
        ]);
    }
   
}
