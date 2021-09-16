<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tokenStr = explode(" ", $request->header('Authorization'))[1];
        [$token, $id] = explode("::", $tokenStr);
        $user = User::find($id);
        $errorResponse = ['error' => 'Access Denied'];
        // return response()->json([$user, $token, $id]);
        if($user === null) {
            return response()->json($errorResponse, 400);
        } else {
            if(!Hash::check($token, $user->token)) {
                return response()->json($errorResponse, 400);
            } else {
                $request->user = $user;
                return $next($request);
            }
        }
        
    }
}
