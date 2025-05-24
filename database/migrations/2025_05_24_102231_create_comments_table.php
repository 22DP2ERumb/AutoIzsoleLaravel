<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('auction_id');
        $table->tinyInteger('rating')->unsigned()->comment('1-5 stars');
        $table->text('comment');
        $table->timestamps();

        $table->unique(['user_id', 'auction_id']); // one comment per user per auction
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('auction_id')->references('id')->on('car_auction')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
