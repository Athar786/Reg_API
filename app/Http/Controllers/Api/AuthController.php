<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'name' =>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'

        ]);
        $validate['password'] = bcrypt($request->password);
        $user = User::create($validate);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user'=>$user,'access_token'=>$accessToken]);
    }

    public function login(Request $request)
    {
        $login_data = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(!auth()->attempt($login_data))
        {
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user'=>auth()->user(),'access_token'=>$accessToken]);

    }
}
