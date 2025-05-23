<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('auction_id');

            $table->float('bid_amount');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('auction_id')->references('id')->on('car_auction')->onDelete('cascade');

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
