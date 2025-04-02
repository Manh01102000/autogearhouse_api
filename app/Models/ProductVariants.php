<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'product_variants';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'variant_id';
    // Fillable
    protected $fillable = [
        'product_id',
        'variant_code',
        'product_price',
        'product_stock',
        'variant_images',
        'product_size',
        'product_color',
        'product_code_color',
        'variant_create_time',
        'variant_update_time',
    ];

    // ==========Mối quan hệ============
    // Quan hệ với product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}
