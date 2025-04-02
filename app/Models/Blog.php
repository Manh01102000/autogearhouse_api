<?php

namespace App\Models;

// HasFactory	    Hỗ trợ tạo dữ liệu giả bằng Factory.
// Authenticatable	Cho phép Model User hoạt động với hệ thống đăng nhập Laravel.
// Notifiable	    Cho phép Model User nhận thông báo qua email, Slack, hoặc database.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Blog extends Authenticatable
{
    use HasFactory;

    protected $table = 'blogs'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'blog_id';
    protected $fillable = [
        'blog_employee_id',
        'blog_title',
        'blog_cate',
        'blog_content',
        'blog_meta_h1',
        'blog_meta_title',
        'blog_meta_description',
        'blog_meta_keyword',
        'blog_tags',
        'blog_create_time',
        'blog_update_time',
    ];
    // Mối quan hệ
    // Với bảng Employees
    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'blog_employee_id', 'employee_id');
    }
    // Với Comment
    public function Comment()
    {
        return $this->hasMany(Comment::class, 'comment_blog_id', 'blog_id');
    }
}
