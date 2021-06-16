<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Response;
use Socialite;

# JWT
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

# Models
use App\Models\User;

class AuthController extends Controller
{
    public function login (Request $request) {
        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        # Making an attempt to login
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response(null, 401);
        }
        
        # Response with the token
        return response()->json(compact('token'));
    }

    public function logout () {
        # Log out user
        auth()->logout();
    }
}
