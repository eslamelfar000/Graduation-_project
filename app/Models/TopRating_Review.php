<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopRating_Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'review','star'  ,'product_id'
    ];
    public function product() 
{ 
    return $this->belongsTo(TopRating::class); 
}
}
