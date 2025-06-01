<?php

namespace App\Http\Controllers;

use App\Models\CarAuction; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Comment; 

class AuctionController extends Controller
{
    public function storeComment(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['message' => 'Not authenticated'], 401);
    }

    // Log incoming request data
    \Log::info('Incoming comment data', $request->all());

    // Temporarily dump auction_id to check its value
    // dd($request->auction_id);

    $request->validate([
        'auction_id' => 'required|exists:car_auction,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
    ]);

    $userId = auth()->id();

    // Log user ID and auction ID before checking for existing comment
    \Log::info('Checking for existing comment', [
        'user_id' => $userId,
        'auction_id' => $request->auction_id
    ]);

    $existing = Comment::where('user_id', $userId)
        ->where('auction_id', $request->auction_id)
        ->first();

    if ($existing) {
        return response()->json(['message' => 'You already left a comment for this auction.'], 400);
    }

    $comment = Comment::create([
        'user_id' => $userId,
        'auction_id' => $request->auction_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    \Log::info('Comment created successfully', $comment->toArray());

    return response()->json($comment);
}
    public function StartAuctionPost(Request $request)
    {
        // larvale.log for debugging
        Log::info('Auction data:', $request->all());


        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'startingPrice' => 'required|numeric|min:0',
            'buyOutPrice' => 'required|numeric|min:0|gte:startingPrice',
            'bidIncrement' => 'required|numeric|min:0',
            'reservePrice' => 'required|numeric|min:0|gte:startingPrice|lte:buyOutPrice',
            'startTime' => 'required|date|after_or_equal:today',
            'endTime' => 'required|date|after:startTime',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        $isActive = Carbon::now()->between(
            Carbon::parse($request->startTime),
            Carbon::parse($request->endTime)
        );

        $CarAuction = CarAuction::create([
            'car_id' => $request->car_id,
            'starting_price'=> $request->startingPrice,
            'buyout_price' => $request->buyOutPrice,
            'bid_increment'=> $request->bidIncrement,
            'reserve_price' => $request->reservePrice,

            'start_time'=> $request->startTime,
            'end_time' => $request->endTime,
            
            'is_active'=> $isActive,
            
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CarAuctioned',
            'CarAuction' => $CarAuction
        ], 201);

    }
}
