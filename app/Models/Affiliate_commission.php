<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate_commission extends Model
{
    use HasFactory;
    protected $table = 'affiliate_commissions'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'commission_id';
    protected $fillable = [
        'commission_affiliate_id',
        'commission_order_id',
        'commission_product_id',
        'commission_amount',
        'commission_create_time',
        'commission_update_time',
    ];
    // =============Mối quan hệ==========
    // Với bảng tiếp thị liên kết (Affiliate)
    public function Affiliate()
    {
        $this->belongsTo(Affiliate::class, 'commission_affiliate_id', 'affiliate_id');
    }
}
