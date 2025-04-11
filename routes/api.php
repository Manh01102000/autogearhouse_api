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
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\ModelProductsController;

// ==============================Luồng danh mục=============================
Route::prefix("category")->group(function () {
    Route::get('/tree', [CategoryController::class, 'getCategoryTree']);
    Route::get('/all', [CategoryController::class, 'getCategoryAll']);
    Route::get('/{id}', [CategoryController::class, 'getCategoryByID'])->where('id', '[0-9]+');
    Route::get('/', [CategoryController::class, 'getCategory']);
});
// ==============================Luồng hãng xe=============================
Route::prefix("brands")->group(function () {
    Route::get('/{id}', [BrandsController::class, 'getBrandsByID'])->where('id', '[0-9]+');
    Route::get('/', [BrandsController::class, 'getBrands']);
});
// ==============================Luồng dòng xe=============================
Route::prefix("models")->group(function () {
    Route::get('/{id}', [ModelProductsController::class, 'getModelProductsByID'])->where('id', '[0-9]+');
    Route::get('/', [ModelProductsController::class, 'getModelProducts']);
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
    Route::get('/{id}', [UserController::class, 'getUserById'])->where('id', '[0-9]+');

    // =========Luồng API yêu cầu JWT authentication viết ở đây=========
    // + jwt.auth.custom: kiểm tra tính hợp lệ của token
    // + throttle:60,1: giới hạn số lần request (60 lần/phút) tránh DDOS hoặc spam request.
    Route::middleware(['jwt.auth.custom', 'throttle:60,1'])->group(function () {
        // API đăng xuất tài khoản
        Route::post('/logout', [AuthController::class, 'logout']);
        // API cập nhật thông tin người dùng
        Route::put('/{id}', [UserController::class, 'updateUser'])->where('id', '[0-9]+');
        // API xóa thông tin người dùng
        Route::delete('/{id}', [UserController::class, 'deleteUser'])->where('id', '[0-9]+');
    });

    Route::middleware(['throttle:60,1'])->group(function () {
        // API checktoken
        Route::post('/checkToken', [AuthController::class, 'checkToken']);
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
        Route::put('/post/{post}', [PostController::class, 'update']);
    });
});
// =========================================================================
// ===========================Luồng sản phẩm================================
Route::prefix("products")->group(function () {
    // Lấy danh sách sản phẩm
    Route::get('/', [ProductController::class, 'getAll']);
    // Lấy danh sách sản phẩm kèm tìm kiếm
    Route::post('/search', [ProductController::class, 'searchProduct']);
    // Lấy danh sách sản phẩm mới nhất
    Route::get('/new', [ProductController::class, 'getProductNew']);
    // Lấy danh sách sản phẩm nổi bật
    Route::get('/featured', [ProductController::class, 'getProductFeatured']);
    // Lấy 1 sản phẩm
    Route::get('/{id}', [ProductController::class, 'getById'])->where('id', '[0-9]+');
    // ======================== API yêu cầu Xác thực ========================
    Route::middleware(['jwt.auth.custom', 'role:2'])->group(function () {
        Route::post('/', [ProductController::class, 'createProduct']); // Tạo sản phẩm
        Route::put('/{id}', [ProductController::class, 'updateProduct']); // Cập nhật sản phẩm
        Route::delete('/{id}', [ProductController::class, 'deleteProduct']); // Xóa sản phẩm
    });
});
