<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'employees';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'employee_id';
    // Fillable
    protected $fillable = [
        'employee_user_id',
        'employee_name',
        'employee_email',
        'employee_phone',
        'employee_position',
        'employee_department',
        'employee_salary',
        'employee_logo',
        'employee_birthday',
        'employee_show',
        'last_assigned_at',
        'employee_createAt',
        'employee_updateAt',
    ];

    // ==========Mối quan hệ============
    // Với User:
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_user_id', 'user_id');
    }
    // Với Order: 1 nhân viên xử lý nhiều sản phẩm
    public function orders()
    {
        return $this->hasMany(Orders::class, 'order_employee_id', 'employee_id');
    }
    // Một nhân viên có thể phụ trách nhiều khách hàng
    public function customers()
    {
        return $this->hasMany(Customers::class, 'customer_employee_id', 'employee_id');
    }
    // Một nhân viên có thể phụ trách nhiều sản phẩm
    public function product()
    {
        return $this->hasMany(Products::class, 'product_employee_id', 'employee_id');
    }
    // Với bảng Blog
    public function Blog()
    {
        return $this->hasMany(Blog::class, 'blog_employee_id', 'employee_id');
    }

    // Với Comment
    public function Comment()
    {
        return $this->hasMany(Comment::class, 'comment_employee_id', 'employee_id');
    }
}
