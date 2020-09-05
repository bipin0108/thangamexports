<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware as Middleware;

class JwtMiddleware extends Middleware
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'status' => false,
                    'message' => 'Token is Invalid'
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'status' => false,
                    'message' => 'Token is Expired'
                ]); 
            }else if ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Auth failed'
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                 return response()->json([
                    'status' => false,
                    'message' => 'Token has been blacklisted'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Authorization Token not found'
                ]);
            }
        }
        return $next($request);
    }
}
