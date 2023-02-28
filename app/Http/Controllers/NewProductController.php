<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\NewProduct;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewProductController extends Controller
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
            'pin_code'    => 'required|max:3|unique:new_products',
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

        // $videos = $request->videos->store('/public/newproducts/');

        $product = new NewProduct();
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
        $result = $product->save();

        $this->handleRequestMediaFiles($product, $request);

        // get the newly created product with media images and reviews count

        $product = NewProduct::with('media')
            ->withCount('reviews')
            ->findOrFail($product->id);

        return new ProductResource($product);
    }

    public function show($id)
    {
        // get the product with media images and reviews count
        $product = NewProduct::with('media')
            ->withCount('reviews')
            ->findOrFail($id);

        return new ProductResource($product);
    }
    public function index(){
        $products= ProductResource::collection(NewProduct::with('media')->withCount('reviews')->get());
        $productResource= [];
        foreach ($products as $product) {
            $productResource[]= new ProductResource($product);
        }
        return  $productResource;
    }
}
