<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function store(Request $request){
        $photo= $request->photo->store('/public/shops/');
        
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'newPrice' => 'required',
            'oldPrice'    => 'required',
            'photo'    => 'required',
            'offer'    => 'required',
            'category'    => 'required',
            'color'    => 'required',
            'size'    => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }
        $product= new Shop;
        $product->title = $request->title;
        $product->newPrice = $request->newPrice;
        $product->oldPrice = $request->oldPrice;
        $product->photo = $request->photo->hashName();
        $product->offer = $request->offer;
        $product->category = $request->category;
        $product->color = $request->color;
        $product->size = $request->size;
        $result= $product->save();

        return response($product,200,["add successfully"]);
    }
    public function show($id){
        $product = Shop::find($id);
         return response()->json([ 
            $product,$product->reviews->count(), 
        
         ]);
        }
}

