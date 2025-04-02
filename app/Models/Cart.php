<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'cart_id ';
    protected $fillable = [
        'cart_customer_id',
        'cart_product_code',
        'cart_product_amount',
        'cart_product_classification',
        'cart_create_time',
        'cart_update_time',
    ];
    // ==============Mối quan hệ================
    // Với khách hàng
    public function Customers()
    {
        return $this->belongsTo(Customers::class, 'cart_customer_id', 'customer_id');
    }

    // Với xác nhận đơn hàng
    public function OrderConfirm()
    {
        return $this->hasMany(order_confirm::class, 'conf_cart_id', 'cart_id');
    }
}
