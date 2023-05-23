<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    //
    public function createReview(Request $request) {
        $product = Product::find($request['id']);
        if($product) {
            /** @var Review $review */
            $review = Review::create([
                                'name' => $request['name'],
                                'comment' => $request['comment'],
                                'rating' => $request['rating'], 
                                'product_id' => $request['id']]);
            $num_reviews = DB::table('reviews')->where('product_id', '=', $request['id'])->count();
            $rating = DB::table('reviews')->where('product_id', '=', $request['id'])->avg('rating');
            DB::table('products')->where('id', '=', $request['id'])
                        ->update(['num_reviews' => $num_reviews, 'rating' => $rating]);
            return response()->json(['message' => 'review added successfully', 'review' => $review]);

        } else {
            return response()->json(['message' => 'Failed adding Review', 401]);
        }
    }

    public function getReviews($id) {
        $reviews = DB::table('reviews')->select('reviews.*')->where('reviews.product_id', '=', $id)->get();
        return $reviews;
    }
}
