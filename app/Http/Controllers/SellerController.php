<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;

class SellerController extends Controller
{
    //

    public function getSeller($id) {
        /** @var Seller $seller */
        $seller = DB::table('sellers')
                    ->join('users', 'sellers.user_id', '=', 'users.id')
                    ->select('sellers.*', 'users.email')
                    ->where('sellers.id', '=', $id)
                    ->get()->first();
        return $seller;
    }

    public function getSellerData($id) {
        /** @var Seller $seller */
        $seller = DB::table('sellers')
                    ->where('user_id', '=', $id)
                    ->get()->first();
        return $seller;
    }

    public function updateSeller(Request $request) {
        $id = $request['id'];
        $name = $request['name'];
        $logo = $request['logo'];
        $description = $request['description'];
        
        $seller = Seller::find($id);
        if($seller) {
             DB::table('sellers')->where('id', $request['id'])->update([
            'name' => $name,
            'logo' => $logo,
            'description' => $description,
            'updated_at' => now(),
            ]);
            return response()->json(['seller' => $seller, 'message' => 'Seller update success']);
        } else {
            return response()->json(['message' => 'Seller not found', 401]);
        }
    }
}
