<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthenticateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $extractedToken = explode(' ',$request->header('Authorization'))[1];
            $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
            $response = User::where('id', $decoded->uid)->first();
            if($response){
                return $next($request);
            }
        }catch(\Throwable $th){
            return response()->json([
                'message' => 'Invalid Token'
            ], 401);
        }

    }
}
