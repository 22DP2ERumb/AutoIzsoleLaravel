<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarAuction;
use Carbon\Carbon;
class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auctions = [
            [
                'car_id' => 1,
                'starting_price' => 5000,
                'buyout_price' => 12000,
                'bid_increment' => 250,
                'reserve_price' => 7000,
                'start_time' => Carbon::now()->subDays(3),
                'end_time' => Carbon::now()->addDays(4),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 2,
                'starting_price' => 8000,
                'buyout_price' => 15000,
                'bid_increment' => 300,
                'reserve_price' => 10000,
                'start_time' => Carbon::now()->subDays(1),
                'end_time' => Carbon::now()->addDays(7),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 3,
                'starting_price' => 4000,
                'buyout_price' => 9000,
                'bid_increment' => 200,
                'reserve_price' => 6000,
                'start_time' => Carbon::now()->subDays(6),
                'end_time' => Carbon::now()->subDays(1),
                'is_active' => false,
                'Has_Ended' => true,
            ],
            [
                'car_id' => 4,
                'starting_price' => 7500,
                'buyout_price' => 14000,
                'bid_increment' => 350,
                'reserve_price' => 9000,
                'start_time' => Carbon::now()->subHours(2),
                'end_time' => Carbon::now()->addDays(9),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 5,
                'starting_price' => 6000,
                'buyout_price' => 11000,
                'bid_increment' => 250,
                'reserve_price' => 8000,
                'start_time' => Carbon::now()->addHours(1),
                'end_time' => Carbon::now()->addDays(8),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 6,
                'starting_price' => 9000,
                'buyout_price' => 16000,
                'bid_increment' => 400,
                'reserve_price' => 11000,
                'start_time' => Carbon::now()->subDays(4),
                'end_time' => Carbon::now()->addDays(5),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 7,
                'starting_price' => 5500,
                'buyout_price' => 13000,
                'bid_increment' => 275,
                'reserve_price' => 7500,
                'start_time' => Carbon::now()->subDays(3),
                'end_time' => Carbon::now()->addDays(6),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 8,
                'starting_price' => 7000,
                'buyout_price' => 14000,
                'bid_increment' => 350,
                'reserve_price' => 9000,
                'start_time' => Carbon::now()->subDays(2),
                'end_time' => Carbon::now()->addDays(7),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 9,
                'starting_price' => 6200,
                'buyout_price' => 12500,
                'bid_increment' => 300,
                'reserve_price' => 8500,
                'start_time' => Carbon::now()->subDays(1),
                'end_time' => Carbon::now()->addDays(8),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 10,
                'starting_price' => 4800,
                'buyout_price' => 10000,
                'bid_increment' => 200,
                'reserve_price' => 6500,
                'start_time' => Carbon::now()->subDays(7),
                'end_time' => Carbon::now()->subDays(2),
                'is_active' => false,
                'Has_Ended' => true,
            ],
            [
                'car_id' => 11,
                'starting_price' => 5300,
                'buyout_price' => 11000,
                'bid_increment' => 250,
                'reserve_price' => 7000,
                'start_time' => Carbon::now()->subHours(12),
                'end_time' => Carbon::now()->addDays(5),
                'is_active' => true,
                'Has_Ended' => false,
            ],
            [
                'car_id' => 12,
                'starting_price' => 7200,
                'buyout_price' => 15000,
                'bid_increment' => 300,
                'reserve_price' => 9500,
                'start_time' => Carbon::now(),
                'end_time' => Carbon::now()->addDays(10),
                'is_active' => true,
                'Has_Ended' => false,
            ],
        ];

        foreach ($auctions as $auction) {
            CarAuction::create($auction);
        }
    }
}
