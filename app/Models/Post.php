<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // Tên bảng trong database
    protected $table = 'posts';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'post_id';
    // Fillable
    protected $fillable = [
        'post_title',
        'post_content',
        'post_user_id',
        'post_status', // '1: unpublished: chưa xuất bản', '2: published:đã xuất bản',
        'post_createAt',
        'post_updateAt',
    ];

    // ==========Mối quan hệ============
    // Với User: 1 bài viết thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'use_id');
    }
}
