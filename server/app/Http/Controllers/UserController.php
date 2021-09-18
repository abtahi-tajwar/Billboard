<?php

namespace App\Http\Controllers;

use App\Models\Authtoken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{
    public function create(Request $req) {
        if(User::where('Email', $req->input('Email'))->first()) {
            return response()->json(['error' => 'This email is already in use'], 400);
        }
        if(User::where('Username', $req->input('Username'))->first()) {
            return response()->json(['error' => 'This username is already in use'], 400);
        }
        $req->validate([
            'Username' => 'required|min:2',
            'Email' => 'required|email:rfc,dns',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'TypeId' => 'required'
        ]);
        $imgFile = $req->file('Image');
        $imgName = time() . $imgFile->getClientOriginalExtension();
        $imgFile->move(public_path().'/images/users', $imgName);
        
        $user = User::create([
            'Username' => $req->input('Username'),
            'Email' => $req->input('Email'),
            'Password' => Hash::make($req->input('password')),
            'Image' => $imgName,
            'About' => $req->input('About'),
            'TypeId' => $req->input('TypeId'),           

        ]);
        $token = AuthTokenController::createNewToken($user->id, $req->header('DeviceName'));
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
                $header_token = $req->header('Authorization') ? AuthTokenController::retreiveTokenFromBearer($req->header('Authorization')) : null;
                $token = AuthTokenController::replaceCurrentToken($user->id, $header_token,  $req->header('DeviceName'));
                return [
                    'user' => $user,
                    'token' => $token
                ];
            }
        }
    }
}
