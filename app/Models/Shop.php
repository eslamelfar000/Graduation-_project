<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','newPrice','oldPrice','photo','offer','category','color','size'  
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class,'product_id');
    }
    
}
