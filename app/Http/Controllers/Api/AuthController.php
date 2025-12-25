<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request){
       $validator = Validator::make($request->all(),[
           'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
       if($validator->fails()){
           return response()->json([
               'success' =>false,
               'errors' => $validator->errors(),
           ], 422);
       }
        $user = User::create([
            'name' =>$request ->name,
            'email' =>$request->email,
            'password'=>Hash::make($request->password),
            'role' => 'customer',
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'message' => ' Foydalanuvchi muvaffaqiyatli ro\'yxatdan o\'tdi',
            'data' =>[
            'user'=>$user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in'=>auth('api')->factory()->getTTL() * 60,
                ]
        ], 201);

    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' =>false,
                'errors' => $validator->errors(),
            ]);
        }

        $creds = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($creds)) {
            return response()->json(['message' => 'Email yoki parol xato'], 401);
        }

        return response()->json([
            'success' => true,
            'message' =>'Muvaffaqiyatli ro\'yxatdan o\'tish',
            'data' =>[
            'user'=>auth('api')->user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
                ]
        ]);
    }
    public function me()
    {
        return response()->json([
            'success' => true,
            auth('api')->user()]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'success' => true,
            'message' => 'Logged out']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json([
            'access_token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
