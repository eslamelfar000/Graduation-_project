<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\RelatedProduct;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelatedProductController extends Controller
{
    use MediaTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'newPrice' => 'required',
            'oldPrice'    => 'required',
            // 'photo'    => 'required',
            'offer'    => 'required',
            'category'    => 'required',
            'color'    => 'required',
            'size'    => 'required',
            'abstract'    => 'required',
            'featuer'    => 'required',
            'pin_code'    => 'required|max:3|unique:related_products',
            'description'    => 'required',
            'videos'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = new RelatedProduct();
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
        $product->videos = $request->videos;

        $result = $product->save();

        $this->handleRequestMediaFiles($product, $request);

        // get the newly created product with media images and reviews count

        $product = RelatedProduct::with('media')
            ->withCount('reviews')
            ->findOrFail($product->id);

        return new ProductResource($product);
    }

    public function show($id)
    {
        // get the product with media images and reviews count
        $product = RelatedProduct::with('media')
            ->withCount('reviews')
            ->findOrFail($id);

        return new ProductResource($product);
    }

    public function index(){
        $products= ProductResource::collection(RelatedProduct::with('media')->withCount('reviews')->get());
        $productResource= [];
        foreach ($products as $product) {
            $productResource[]= new ProductResource($product);
        }
        return  $productResource;
    }
}
