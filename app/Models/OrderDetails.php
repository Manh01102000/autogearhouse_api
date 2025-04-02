<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'order_details'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'ordetail_id';
    protected $fillable = [
        'ordetail_order_id',
        'ordetail_product_code',
        'ordetail_product_amount',
        'ordetail_product_classification',
        'ordetail_product_totalprice',
        'ordetail_product_unitprice',
        'ordetail_product_feeship',
        'ordetail_created_at',
        'ordetail_updated_at',
    ];

    // Quan hệ với đơn hàng (Nhiều chi tiết đơn hàng chỉ ứng với 1 đơn hàng)
    public function orders()
    {
        return $this->belongsTo(Orders::class, 'ordetail_order_id', 'order_id');
    }
}
