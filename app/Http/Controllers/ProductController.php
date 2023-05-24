<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;


class ProductController extends Controller
{
    //
    public function getProducts(Request $request) {
        $seller = $request['seller'];
        $category = $request['category'];
        $min = $request['min'];
        $max = $request['max'];
        $rating = $request['rating'];
        $order = $request['order'];
         
        $query = DB::table('products')
        ->join('sellers', 'products.seller_id', '=', 'sellers.id')
        ->select('products.id', 'products.name', 'products.image', 'products.price', 'products.category', 'products.rating',
                    'products.num_reviews','products.seller_id', 'sellers.name as seller_name');
        if(!empty($name)){
            $query->where('name', 'like', '%'.$name.'%');
        }
        if(!empty($category)) {
            $query->where('category', '=', $category);
        }
        if(!empty($seller)) {
            $query->where('products.seller_id', '=', $seller);
        }
        if (!empty($min) & !empty($max)) {
            $query->where('products.price', '>', $min);
            $query->where('products.price', '<', $max);

        }
        if(!empty($rating)) {
            $query->where('products.rating', '>=', $rating);
        }
        if (!empty($order)) {
            if(strcmp($order, 'newest') == 0) {
                $query->orderBy('products.id', 'desc');
            } else if (strcmp($order, 'lowest') == 0) {
                $query->orderBy('products.price', 'asc');
            } else if (strcmp($order, 'highest') == 0) {
                $query->orderBy('products.price', 'desc');
            } else if (strcmp($order, 'toprated') == 0) {
                $query->orderBy('products.rating', 'desc');
            }
        }
      
        $products = $query->get();
       
        return $products;
    }

    public function getProductList(Request $request) {
        $products;
        if($request['is_admin']) {
            $products = DB::table('products')->select('products.*')->get();
        } else {
            $products = DB::table('products')->select('products.*')->where('products.seller_id', '=', $request['id'])->get();
        }
        return $products;
    }

    public function getProduct($id) {
        $product = DB::table('products')
        ->join('sellers', 'products.seller_id', '=', 'sellers.id')
        ->select('products.*', 'sellers.name as seller_name', 'sellers.rating as seller_rating', 'sellers.num_reviews as seller_numReviews')
        ->where('products.id', '=', $id)->get()->first();
        return $product;
    }

    public function getCategories() {
        $categories = DB::table('products')->select('category')->distinct()->get();
        return $categories;
    }

    public function createProduct(Request $request) {
        $seller = $request['seller'];
        $name = "Sample Product";
        $image = "/images/p1.jpg";
        $price = 0.00;
        $category = 'Sample Category';
        $brand ='Sample Brand';
        $count_in_stock = 0;
        $rating = 0;
        $num_reviews = 0;
        $description = 'Sample description';

        $product = Product::create([
            'name' => $name,
            'image' => $image,
            'price' => $price, 
            'category' => $category,
            'description' => $description,
            'brand' => $brand,
            'count_in_stock' => $count_in_stock,
            'rating' => $rating,
            'num_reviews' => $num_reviews,
            'seller_id' => $seller,
        ]);
        return $product;
    }

    public function updateProduct(Request $request) {
        $id = $request['id'];
        $seller = $request['seller'];
        $name = $request['name'];
        $image = $request['image'];
        $price = $request['price'];
        $category = $request['category'];
        $brand = $request['brand'];
        $count_in_stock = $request['count_in_stock'];
        $description = $request['description'];

        $product = Product::find($id);
        if($product) {
             DB::table('products')->where('id', $request['id'])->update([
            'name' => $name,
            'image' => $image,
            'price' => $price, 
            'category' => $category,
            'description' => $description,
            'brand' => $brand,
            'count_in_stock' => $count_in_stock,
            'updated_at' => now(),
            ]);
            return response()->json(['product' => $product, 'message' => 'Product update success']);
        } else {
            return response()->json(['message' => 'Product not found', 401]);
        }
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        if($product) {
             DB::table('products')->where('id', $id)->delete();
            return response()->json(['product' => $product, 'message' => 'Product delete success']);
        } else {
            return response()->json(['message' => 'Product not found', 401]);
        }
    }
}
