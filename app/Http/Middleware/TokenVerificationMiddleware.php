<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    $token = $request->cookie('token');
    $result = JWTToken::VerifyToken($token);

    if($result == "unauthorized"){
        return redirect('/userLogin');
    }

    $user = User::find($result->userID);

    // 🔥 SINGLE SESSION CHECK
    if(!$user || $user->login_token !== $token){
        return redirect('/userLogin'); // force logout old browser
    }

    $request->merge([
        'auth_user_id' => $user->id,
        'auth_shop_id' => $user->shop_id,
        'auth_email' => $user->email,
    ]);

    return $next($request);
}
}
