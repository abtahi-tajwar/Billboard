<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function create(Request $req) {
        if(User::where('Email', $req->input('Email'))) {
            return ['error' => 'This email is already in use'];
        }
        if(User::where('Username', $req->input('Username'))) {
            return ['error' => 'This username is already in use'];
        }
        $req->validate([
            'Username' => 'required|min:2',
            'Email' => 'required|email:rfc,dns',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'TypeId' => 'required'
        ]);
        $token = Str::random(60);
        $imgFile = $req->file('Image');
        $imgName = time() . $imgFile->getClientOriginalExtension();
        $imgFile->move(public_path().'/images/users', $imgName);
        
        $user = User::create([
            'Username' => $req->input('Username'),
            'Email' => $req->input('Email'),
            'Password' => Hash::make($req->input('password')),
            'token' => Hash::make($token),
            'Image' => $imgName,
            'About' => $req->input('About'),
            'TypeId' => $req->input('TypeId'),           

        ]);
        
        return [
            'user' => $user,
            'token' => $token
        ];
    }
    public function login(Request $req) {
        $user = User::where('Email', $req->input('username'))->orWhere('Username', $req->input('username'))->first();
        $errorMsg = ['error' => 'Wrong username or password'];
        if($user === null) {
            return response()->json($errorMsg, 404);
        } else {
            if(!Hash::check($req->input('password'), $user->Password)) {
                return response()->json($errorMsg, 404);
            } else {
                $token = Str::random(60);
                $user->token = Hash::make($token);
                $user->update();
                return [
                    'user' => $user,
                    'token' => $token
                ];
            }
        }
    }
}
