<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the CarImage model
    public function images()
    {
        return $this->hasMany(Car_Images::class);
    }
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }
    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }
    public function auctions()
    {
        return $this->hasOne(CarAuction::class);
    }

    // Optional: You can also add fillable or guarded properties if needed
    protected $fillable = [
        'user_id', 'car_brand_id', 'car_model_id', 'year', 'mileage', 'fuel_type', 'transmission', 'engine_size', 'body_type', 'color'
    ];
}
