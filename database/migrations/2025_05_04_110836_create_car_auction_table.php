<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('car_auction', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')
                  ->references(columns: 'id')->on('cars')
                  ->onDelete(action: 'cascade');

            $table->float('starting_price');
            $table->float('buyout_price');
            $table->float('bid_increment');
            $table->float('reserve_price');

            $table->boolean('is_active');
            $table->boolean('Has_Ended')->default(false);
            
            $table->date('start_time');
            $table->date(column: 'end_time');


            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('car_acution');
    }
};
