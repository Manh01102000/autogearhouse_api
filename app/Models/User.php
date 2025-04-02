<?php

namespace App\Models;

// HasFactory	    Hỗ trợ tạo dữ liệu giả bằng Factory.
// Authenticatable	Cho phép Model User hoạt động với hệ thống đăng nhập Laravel.
// Notifiable	    Cho phép Model User nhận thông báo qua email, Slack, hoặc database.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// JWT
use Tymon\JWTAuth\Contracts\JWTSubject;

// Thêm HasApiTokens vào model User
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens;

    // Tên bảng trong database
    protected $table = 'users';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính user_id
    protected $primaryKey = 'user_id';
    // Fillable
    protected $fillable = [
        'user_role',
        'user_email_account',
        'user_phone_account',
        'password',
        'is_login',
        'last_login',
        'user_authentic',
        'user_otp',
        'user_otp_expired',
        'user_ip_address',
        'user_create_time',
        'user_update_time',
    ];

    // Ẩn các trường nhạy cảm
    protected $hidden = ['password', 'use_otp', 'use_ip_address'];

    // Thêm hai hàm để sử dụng JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // ==========Mối quan hệ============
    // Với Bài viết: 1 User có nhiều bài viết
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'use_id');
    }

    // Với bảng Affiliate
    public function Affiliate()
    {
        $this->hasMany(Affiliate::class, 'affiliate_user_id', 'user_id');
    }

    // Với bảng Employees
    public function Employees()
    {
        return $this->hasMany(Employees::class, 'employee_user_id', 'user_id');
    }

    // Với bảng Customers
    public function Customers()
    {
        return $this->hasMany(Customers::class, 'customer_user_id', 'user_id');
    }
}
