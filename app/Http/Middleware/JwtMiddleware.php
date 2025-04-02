<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Lấy token từ Header hoặc Cookie
            $token = $request->bearerToken(); // Ưu tiên lấy từ Header

            if (!$token) {
                $token = $request->cookie('jwt_token'); // Nếu không có thì lấy từ Cookie
            }

            if (!$token) {
                return response()->json(['message' => 'Token không được cung cấp!'], 401);
            }

            // Set token cho JWTAuth trước khi authenticate
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();
            // Lưu user vào request
            $request->merge(['user' => $user]);

        } catch (TokenExpiredException $e) {
            try {
                // Nếu token hết hạn, tự động refresh
                $newToken = JWTAuth::refresh();
                // Set lại token mới cho JWTAuth
                JWTAuth::setToken($newToken);

                // Trả về response kèm token mới trong Header & Cookie
                return $next($request)
                    ->withCookie(cookie('jwt_token', $newToken, config('jwt.ttl') * 60, '/', null, true, true))
                    ->header('Authorization', 'Bearer ' . $newToken);

            } catch (JWTException $e) {
                return response()->json(['message' => 'Phiên đăng nhập hết hạn, vui lòng đăng nhập lại!'], 401);
            }
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token không hợp lệ!'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Có lỗi với token!'], 401);
        }

        return $next($request);
    }
}
