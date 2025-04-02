<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'orders';
    // Không dùng timestamps
    public $timestamps = false;
    // Khóa chính use_id
    protected $primaryKey = 'order_id';
    // Fillable
    protected $fillable = [
        'order_customer_id',
        'order_employee_id',
        'order_user_name',
        'order_user_email',
        'order_user_phone',
        'order_user_note',
        'order_code',
        'order_affiliate_id',
        'order_address_ship',
        'order_total_price',
        'order_status',
        'order_admin_accept',
        'order_admin_accept_time',
        'order_money_received',
        'order_bill_pdf',
        'order_create_time',
        'order_update_time',
        'order_paymentMethod',
        'order_name_bank',
        'order_branch_bank',
        'order_account_bank',
        'order_account_holder',
        'order_content_bank',
    ];

    // ==========Mối quan hệ============
    // Quan hệ với Customer (Mỗi đơn hàng thuộc về một khách hàng)
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'order_customer_id', 'customer_id');
    }

    // Quan hệ với Employee (Mỗi đơn hàng có thể do một nhân viên xử lý)
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'order_employee_id', 'employee_id');
    }

    // Quan hệ với Chi tiết đơn hàng (Mỗi đơn hàng có thể do một nhân viên xử lý)
    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class, 'ordetail_order_id', 'order_id');
    }
}
