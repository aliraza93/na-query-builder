<?php

namespace App\Http\Middleware;

use App\Admin\User;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;
use Auth;

class ApiAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        
        $apiToken =   $request->header('X-API-Key');
        $token = $request->bearerToken();
        if ($apiToken !== 'bcfa6b80-3245-4789-88db-eea45143b7f5') {

            return response()->json([
                'error' => 'Access denied',
                'token' => $apiToken
            ], 403);
        } else {

            
         /*
         Auth::viaRequest('custom-jwt', function ($request) { $token = $request->bearerToken();
             $secret = config('auth.auth_jwt_secret_key');
              if ($token && strlen($token) > 0)
               { try { $user = JWT::decode($token, $secret, array("HS256"));
                 if (!$user) throw new \Exception; } catch (\Exception $e) { return null; 
                } return DB::table("users")->where("id", $user->user->id)->first(); } return null; });

                 Auth::user();



             try {
                $credentials = ['Name' => 'admin', 'password' => '1234'];
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'error' => 'Invalid credentials'
                    ], 401);
                }
            } catch (JWTException $e) {
                return response()->json([
                    'error' => 'Something went wrong'
                ], 500);
            }
         */
        


            return $next($request);
        }
    }
}
