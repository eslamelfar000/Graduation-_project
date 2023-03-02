<?php

namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\SpecialPiece;
use App\Traits\MediaTrait;
use Illuminate\Support\Facades\Validator;


class SpecialPieceController extends Controller
{
    use MediaTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required',
            'newPrice'    => 'required',
            'oldPrice'    => 'required',
            'offer'       => 'required',           
            'color'       => 'required',
            'size'        => 'required',
            'abstract'    => 'required',
            'featuer'     => 'required',
            'pin_code'    => 'required|max:3|unique:special_pieces',
            'description' => 'required',
            'videos'      => 'required',

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
        $product= new SpecialPiece;
        $product->title = $request->title;
        $product->newPrice = $request->newPrice;
        $product->oldPrice = $request->oldPrice;
        $product->photo = '--';
        $product->offer = $request->offer;
        $product->category = 'special';
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

        $product = SpecialPiece::with('media')
        ->withCount('reviews')
        ->findOrFail($product->id);

    return new ProductResource($product);
    }
    
    public function show($id)
    {
        // get the product with media images and reviews count
        $product = SpecialPiece::with('media')
            ->withCount('reviews')
            ->findOrFail($id);

        return new ProductResource($product);
    }

    public function index(){
        $products= ProductResource::collection(SpecialPiece::with('media')->withCount('reviews')->get());
        $productResource= [];
        foreach ($products as $product) {
            $productResource[]= new ProductResource($product);
        }
        return  $productResource;
    }
}