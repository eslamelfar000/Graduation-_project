<?php

namespace App\Http\Controllers;

use App\Models\TopRating;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Traits\MediaTrait;
use Illuminate\Support\Facades\Validator;

class TopRatingController extends Controller
{
    use MediaTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'newPrice' => 'required',
            'oldPrice'    => 'required',
            'offer'    => 'required',
            'category'    => 'required',
            'color'    => 'required',
            'size'    => 'required',
            'abstract'    => 'required',
            'featuer'    => 'required',
            'pin_code'    => 'required|max:3|unique:top_ratings',
            'description'    => 'required',
            'videos'    => 'required',

            'image_1'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_2'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_3'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_4'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_5'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_6'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }
        $product= new TopRating;
        $product->title = $request->title;
        $product->newPrice = $request->newPrice;
        $product->oldPrice = $request->oldPrice;
        $product->photo = '--';
        $product->offer = $request->offer;
        $product->category = $request->category;
        $product->color = $request->color;
        $product->size = $request->size;
        $product->abstract = $request->abstract;
        $product->featuer = $request->featuer;
        $product->pin_code = $request->pin_code;
        $product->description = $request->description;
        // $product->videos = $request->videos->hashName();
        $product->videos = $request->videos;
        $result= $product->save();

        $this->handleRequestMediaFiles($product, $request);
        $product = TopRating::with('media')
        ->withCount('reviews')
        ->findOrFail($product->id);

    return new ProductResource($product);
    }

    public function show($id)
    {
        // get the product with media images and reviews count
        $product = TopRating::with('media')
            ->withCount('reviews')
            ->findOrFail($id);

        //return response()->json( new ProductResource($product));
        return new ProductResource($product);
    }
    public function index(){
        $products= ProductResource::collection(TopRating::with('media')->withCount('reviews')->get());
        $productResource= [];
        foreach ($products as $product) {
            $productResource[]= new ProductResource($product);
        }
        return  $productResource;
    }
    
}
