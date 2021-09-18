<?php

namespace App\Http\Controllers;

use App\Models\Authtoken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTokenController extends Controller
{
    public static function verifyAuthToken($request_token) {
        $tokenStr = explode(" ", $request_token)[1];
        [$token, $id] = explode("::", $tokenStr);
        $user = User::find($id);
        if(!$user) {
            return ['user' => null, 'token' => null];
        } 
        $tokens = Authtoken::where('user_id', $user->id)->get();
        if(count($tokens) > 0) {
            foreach($tokens as $tk) {
                if(Hash::check($token, $tk->token)) {
                    return ['user' => $user, 'token' => $token];
                }
            }
        }
        return ['user' => null, 'token' => null];
    }
    public static function replaceCurrentToken($user_id, $prev_token, $device_name) {
        $tokens = Authtoken::where('user_id', $user_id)->get();
        $token = null;
        foreach($tokens as $tk) {
            if(Hash::check($prev_token, $tk->token)) {
                $token = $tk;
                break;
            }
        }
        if($token !== null) {
            $token->delete();
        }       

        $new_token = Str::random(60);
        Authtoken::create([
            'user_id' => $user_id,
            'token' => Hash::make($new_token),
            'device_name' => $device_name 
        ]);
        return $new_token;
    }
    public static function createNewToken($user_id, $device_name) {
        $new_token = Str::random(60);
        Authtoken::create([
            'user_id' => $user_id,
            'token' => Hash::make($new_token),
            'device_name' => $device_name 
        ]);
        return $new_token;
    }
    public static function retreiveTokenFromBearer($str) {
        $tokenStr = explode(" ", $str)[1];
        [$token, $id] = explode("::", $tokenStr);
        return $token;
    }
}
