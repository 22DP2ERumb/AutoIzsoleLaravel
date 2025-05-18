<?php

namespace App\Http\Controllers;

use App\Models\CarAuction; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuctionController extends Controller
{
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
