<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    protected $fillable = [
    'auction_id',
    'user_id',
    'rating',
    'comment',
];
public function user()
{
    return $this->belongsTo(User::class);
}
}
