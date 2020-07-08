<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string'
        ])->validate();

        try{         

            $token = auth('api')->attempt($credentials);
            if(!$token) {
                throw new \Exception('Unauthorized.');
            }
            return response()->json([
                'token' => $token
            ], 200);

        }catch(\Exception $e) {       

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function logout()
    {
        try{

            auth('api')->logout();
            return response()->json([
                'message' => 'Successful logout!'
            ], 200);

        }catch(\Exception $e) {       

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function refresh()
    {
        try{

            $token = auth('api')->refresh();
            return response()->json([
                'token' => $token
            ], 200);

        }catch(\Exception $e) {       

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
