<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\BestSeller_Review;
use Illuminate\Support\Facades\Validator;

class BestSellerReviewController extends Controller
{
    public function store(Request $request ){
        
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required',
            'review' => 'required',
            'star'    => 'required',
    
        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }
        $review= new BestSeller_Review;
        $review->product_id = $request->product_id;
        $review->review = $request->review;
        $review->star = $request->star;
        
        $result= $review->save();

        return response($review,200,["add successfully"]);
    }
}
