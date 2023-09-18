<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
      
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // verify user's email
        $user = User::where('email', $request->email)->first();
        if(!$user){
            $response = [
                'response_code' => 401,
                'response_message' => "Invalid credentials",
            ];
            return response()->json($response, 401);
        }else{
            // Check if the password is correct
            if (Hash::check($request->password, $user->password)) {
                // if correct, then continue
                $token = $user->createToken('login_token')->plainTextToken;
                $response = [
                    'response_code' => 200,
                    'response_message' => 'Login successfully',
                    'data' => [
                        'token' => $token,
                        'user' => $user
                    ]
                ];
                return response()->json($response, 200);
            }else{
                // throw an error
                $response = [
                    'response_code' => 401,
                    'response_message' => "Invalid credentials",
                ];
                return response()->json($response, 401);
            }
        }
    }

    public function logout(){
        try {
            auth()->user()->tokens()->delete();
            $response = [
                'response_code' => 200,
                'response_message' => 'You have logged out successfully',
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th){
            $response = [
                'response_code' => 501,
                'response_message' => "And error occured!, make sure the token is been used - " . $th->getMessage(),
            ];
            return response()->json($response, 404);
        }
    }

}
