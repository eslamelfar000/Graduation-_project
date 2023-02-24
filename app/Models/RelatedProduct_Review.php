<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedProduct_Review extends Model
{
    protected $fillable = [
        'review','star'  ,'product_id'
    ];
    public function product() 
{ 
    return $this->belongsTo(RelatedProduct::class); 
}
}
