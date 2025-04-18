<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'products';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'product_id';
    // Fillable
    protected $fillable = [
        // Liên kết với bảng Employees
        'product_employee_id',
        // Thông tin cơ bản
        'product_code',
        'product_name',
        'product_alias',
        'product_description',
        // Thông tin chung
        'product_year',
        'product_model',
        'product_mileage',
        'product_fuel_type',
        'product_transmission',
        'product_engine_capacity',
        'product_horsepower',
        'product_torque',
        'product_drive_type',
        'product_color',
        'product_newold',
        // Thông tin ngoại thất - nội thất
        'product_body_type',
        'product_seats',
        'product_doors',
        'product_airbags',
        'product_safety_features',
        // Tính năng hỗ trợ
        'product_infotainment',
        'product_parking_assist',
        'product_cruise_control',
        // Thông tin thêm
        'product_active',
        'category',
        'category_code',
        'category_children_code',
        'product_brand',
        // Ảnh & Video
        'product_images',
        'product_videos',
        // Vận chuyển
        'product_ship',
        'product_feeship',
        // Số lượng bán
        'product_sold',
        // Thời gian tạo & cập nhật
        'product_create_time',
        'product_update_time',
    ];

    // ==========Mối quan hệ============
    // Quan hệ với employee (Mỗi đơn hàng thuộc về một khách hàng)
    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'product_employee_id', 'employee_id');
    }

    // Với Comment
    public function Comment()
    {
        return $this->hasMany(Comment::class, 'comment_product_id', 'product_id');
    }

    // Quan hệ với product variants
    public function ProductVariants()
    {
        return $this->hasMany(ProductVariants::class, 'product_id', 'product_id');
    }

    // Quan hệ với ManageDiscount
    public function ManageDiscount()
    {
        return $this->hasMany(ManageDiscount::class, 'discount_product_id', 'product_id');
    }
}
