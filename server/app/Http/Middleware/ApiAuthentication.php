<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthTokenController;
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

        $errorResponse = ['error' => 'Access Denied'];
        $user = AuthTokenController::verifyAuthToken($request->header('Authorization'))['user'];
        if($user === null) {
            return response()->json($errorResponse, 400);
        } else {
            $request->user = $user;
            return $next($request);
        }        
    }
}
