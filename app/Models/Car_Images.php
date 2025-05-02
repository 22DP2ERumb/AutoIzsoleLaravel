<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car_Images extends Model
{
    protected $fillable = ['car_id', 'image_path']; // Pieņemsim, ka šie ir tikai divi laukumi
    protected $table = 'car_images';

    /**
     * Car attiecība (viens pret daudziem).
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
