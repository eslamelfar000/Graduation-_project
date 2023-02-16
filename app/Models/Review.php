<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{

    protected $fillable = [
        'review','star'  ,'product_id'
    ];
    public function product() 
{ 
    return $this->belongsTo(Shop::class); 
}

    
}
