<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarAuction extends Model
{
    use HasFactory;

    protected $table = 'car_auction'; // Explicitly define table name if it's not plural

    protected $fillable = [
        'car_id',
        'starting_price',
        'buyout_price',
        'bid_increment',
        'reserve_price',
        'start_time',
        'end_time',
        'is_active',
        'Has_Ended',
    ];

    // If you want to treat start_time and end_time as Carbon instances (recommended)
    protected $dates = [
        'start_time',
        'end_time',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
