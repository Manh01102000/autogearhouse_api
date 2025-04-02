<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'customers';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'customer_id';
    // Fillable
    protected $fillable = [
        'customer_user_id',
        'customer_employee_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_city',
        'customer_district',
        'customer_address',
        'customer_birthday',
        'customer_gender',
        'customer_marital_status',
        'customer_view_count',
        'customer_show',
        'customer_lat',
        'customer_long',
        'customer_create_time',
        'customer_update_time',
    ];

    // ==========Mối quan hệ============
    // Với User:
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_user_id', 'user_id');
    }
    // Với Order: Một khách hàng có thể đặt nhiều đơn hàng.
    public function orders()
    {
        return $this->hasMany(Orders::class, 'order_customer_id', 'customer_id');
    }
    // Quan hệ với bảng Employee (Nhân viên phụ trách)
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'customer_employee_id', 'employee_id');
    }
    // Với giỏ hàng
    public function Carts()
    {
        return $this->hasMany(Cart::class, 'cart_customer_id', 'customer_id');
    }
    // Với Content Emoji
    public function ContentEmoji()
    {
        return $this->hasMany(content_emojis::class, 'content_customer_id', 'customer_id');
    }

    // Với Comment
    public function Comment()
    {
        return $this->hasMany(Comment::class, 'comment_customer_id', 'customer_id');
    }
}
