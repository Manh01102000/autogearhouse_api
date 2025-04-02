<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;
    protected $table = 'comments';
    //
    public $timestamps = false;
    // 
    protected $primaryKey = 'comment_id';
    // 
    protected $fillable = [
        'comment_customer_id',
        'comment_employee_id',
        'comment_parents_id',
        'comment_product_id',
        'comment_blog_id',
        'comment_type',
        'comment_content',
        'comment_share',
        'comment_views',
        'comment_image',
        'createdAt',
        'updatedAt',
    ];

    // Mối quan hệ

    // Với Customer
    public function Customer()
    {
        return $this->belongsTo(Customers::class, 'comment_customer_id', 'customer_id');
    }

    // Với Employee
    public function Employee()
    {
        return $this->belongsTo(Employees::class, 'comment_employee_id', 'employee_id');
    }

    // Với Product
    public function Product()
    {
        return $this->belongsTo(Products::class, 'comment_product_id', 'product_id');
    }

    // Với Blog
    public function Blog()
    {
        return $this->belongsTo(Blog::class, 'comment_blog_id', 'blog_id');
    }

    // Với ContentEmojis
    public function ContentEmojis()
    {
        return $this->belongsTo(Content_emojis::class, 'content_comment_id', 'comment_id');
    }
}
