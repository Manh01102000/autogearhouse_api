<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Kiểm tra nếu user chưa đăng nhập hoặc không có role phù hợp
        if (!Auth::check() || $request->user()->user_role != $role) {
            return response()->json(['message' => 'Bạn không có quyền truy cập!'], 403);
        }

        return $next($request);
    }
}
