<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarBrand extends Model
{
    use HasFactory;
    protected $fillable = [
        'manufacturer',
        'image_path',
    ];
    protected $table = 'car_brands';

    
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
