<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function upload(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->image->move('./uploads', $imageName);

        return response()->json(['image' => $imageName, 'message' => "Success"]);
    }
}
