<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    //
    public function register(SignupRequest $request) {
        $data = $request->validated();

        /** @var User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('main')->plainTextToken;
        return response()->json([
            '_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'is_seller' => $user['is_seller'],
            'token' => $token
        ]);
    }

    public function signin(LoginRequest $request) {
        $credentials = $request->validated();
        if(!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provided email address or password is incorrect'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response()->json([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'is_seller' => $user['is_seller'],
            'token' => $token
        ]);
    }

    public function signout(Request $request) {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response('', 204);
    }

    public function topSeller() {
        /** @var Seller $top_sellers */
        $top_sellers = DB::table('sellers')->select('sellers.*')
                        ->limit(3)->get();
       
        return response()->json(['topSellers' => $top_sellers]);
    }

    public function getUser($id) {
        /** @var User $user */
        $user = User::find($id);
        //$token = $user->createToken('main')->plainTextToken;
        return response()->json([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'is_seller' => $user['is_seller'],
        ]);
    }

    public function updateProfile(UserRequest $request) {
        
        $data = $request->validated();
        \Log::error($request);
        try {
            $id = $request['id'];
            
            /** @var User $user */
            $user = User::find($id);
            if(!empty($request['password'])) {
                $user->update([
                    'name' => $data['name'] ,
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'updated_at' => now(),
                ]);
            } else {
                $user->update([
                    'name' => $data['name'] ,
                    'email' => $data['email'],
                    'updated_at' => now(),
                ]);
            }

            $token = $user->createToken('main')->plainTextToken;
            return response()->json([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_admin' => $user['is_admin'],
                'is_seller' => $user['is_seller'],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something went wrong while updating user!'
            ],500);
        }
    }

    public function getUsers() {
         /** @var User $users */
         $users = User::all();
         return response()->json(['users' => $users]);
    }

    public function getUserById($id) {
        /** @var User $users */
        $user = User::find($id);
        return $user;
    }

   public function updateUser(Request $request) {
    $data = $request->validated();
    
    try {
        $id = $data['id'];
        
        /** @var User $user */
        $user = User::find($id);
        if($request['password'] !== '') {
            $user->update([
                'name' => $data['name'] || $user['name'],
                'email' => $data['email'] || $user['email'],
                'password' => bcrypt($data['password']),
                'is_seller' => $data['is_seller'] || $user['is_seller'],
            ]);
        } else {
            $user->update([
                'name' => $data['name'] || $user['name'],
                'email' => $data['email'] || $user['email'],
                'is_seller' => $data['is_seller'] || $user['is_seller'],
            ]);
        }

        $token = $user->createToken('main')->plainTextToken;
        return response()->json([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'is_seller' => $user['is_seller'],
            'token' => $token
        ]);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json([
            'message'=>'Something goes wrong while updating user!'
        ],500);
    }
}

    public function updateUserByAdmin(Request $request, $id) {
        $user = User::find($id);
        if($user) {
             DB::table('users')->where('id', $id)->update([
            'is_seller' => $request['is_seller'],
            'is_admin' => $request['is_admin'],
            'updated_at' => now(),
            ]);
            return response()->json(['user' => $user, 'message' => 'User update success']);
        } else {
            return response()->json(['message' => 'User not found', 401]);
        }
    }

    public function deleteUser($id) {
        $user = User::find($id);
        
        if($user) {
            if($user['is_admin']) {
                return response()->json(['message' => 'Order not found', 401]);
            } else {
                DB::table('users')->where('id', $id)->delete();
                return response()->json(['user' => $user, 'message' => 'User delete success']);
            }
             
        } else {
            return response()->json(['message' => 'User not found', 401]);
        }
    }
}
