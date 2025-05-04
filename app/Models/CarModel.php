<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'car_models';

    // The attributes that are mass assignable
    protected $fillable = [
        'model',
        'brand_id',
    ];

    // Define the relationship with the CarBrand model
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    // Optionally, define the relationship with the Car model
    public function cars()
    {
        return $this->hasMany(Car::class, 'car_model_id');
    }
}