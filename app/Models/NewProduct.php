<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewProduct extends Model
{
    protected $fillable = [
        'title','newPrice','oldPrice','photo','offer','category','color','size'  
    ];
    public function reviews()
    {
        return $this->hasMany(NewProduct_Review::class,'product_id');
    }
}
