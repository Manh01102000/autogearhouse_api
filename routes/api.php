<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

// ==============================Luồng danh mục=============================
Route::prefix("category")->group(function () {
    Route::get('/tree', [CategoryController::class, 'getCategoryTree']);
    Route::get('/{id}', [CategoryController::class, 'getCategoryByID']);
    Route::get('/', [CategoryController::class, 'getCategory']);
});
// =========================================================================
// =============================Luồng User==================================
Route::prefix("user")->group(function () {
    // ==========Luồng API Không yêu cầu xác thực JWT=================
    // API đăng ký
    Route::post('/register', [AuthController::class, 'register']);
    // API đăng nhập
    Route::post('/login', [AuthController::class, 'login']);
    // API lấy thông tin người dùng (có theo tìm kiếm => dùng post vì get có thể hạn chế string trên params)
    Route::post('/search', [UserController::class, 'searchUser']);
    // API lấy thông tin người dùng theo admin (có theo tìm kiếm => dùng post vì get có thể hạn chế string trên params)
    Route::post('/search-user-admin', [UserController::class, 'searchUserAdmin']);
    // API lấy thông tin người dùng theo id
    Route::get('/{id}', [UserController::class, 'getUserById']);
    // API cập nhật thông tin người dùng
    Route::put('/{id}', [UserController::class, 'updateUser']);
    // API xóa thông tin người dùng
    Route::delete('/{id}', [UserController::class, 'deleteUser']);
    // =========Luồng API yêu cầu JWT authentication viết ở đây=========
    // + jwt.auth.custom: kiểm tra tính hợp lệ của token
    // + throttle:60,1: giới hạn số lần request (60 lần/phút) tránh DDOS hoặc spam request.
    Route::middleware(['jwt.auth.custom', 'throttle:60,1'])->group(function () {
        // API đăng xuất tài khoản
        Route::post('/logout', [AuthController::class, 'logout']);
        // API làm mới token
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // Hàm gửi mã OTP
    Route::post('/send-otp', [OtpController::class, 'sendOtp']);
});
// ===========================Luồng admin====================================
Route::prefix("admin")->group(function () {
    Route::middleware(['jwt.auth.custom', 'role:2'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::put('/post/{post}', [PostController::class, 'update'])->middleware('can:update,post');
    });
});
// =========================================================================
// ===========================Luồng sản phẩm================================
Route::prefix("products")->group(function () {
    // Lấy danh sách sản phẩm
    Route::get('/', [ProductController::class, 'getAll']);
    // Lấy danh sách sản phẩm kèm tìm kiếm
    Route::post('/search', [ProductController::class, 'searchProduct']);
    // Lấy 1 sản phẩm
    Route::get('/{id}', [ProductController::class, 'getById']);
    // ======================== API yêu cầu Xác thực ========================
    Route::middleware(['jwt.auth.custom', 'role:2'])->group(function () {
        Route::post('/', [ProductController::class, 'createProduct']); // Tạo sản phẩm
        Route::put('/{id}', [ProductController::class, 'updateProduct']); // Cập nhật sản phẩm
        Route::delete('/{id}', [ProductController::class, 'deleteProduct']); // Xóa sản phẩm
    });
});
