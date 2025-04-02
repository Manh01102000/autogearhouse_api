<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate_withdrawal extends Model
{
    use HasFactory;
    protected $table = 'affiliate_withdrawals'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'withdrawal_id';
    protected $fillable = [
        'withdrawal_affiliate_id',
        'withdrawal_amount',
        'withdrawal_status',
        'withdrawal_method',
        'withdrawal_account',
        'withdrawal_account_name',
        'withdrawal_requested_at',
        'withdrawal_processed_at',
        'withdrawal_create_time',
        'withdrawal_update_time',
    ];
    // =============Mối quan hệ==========
    // Với bảng tiếp thị liên kết (Affiliate)
    public function Affiliate()
    {
        $this->belongsTo(Affiliate::class, 'withdrawal_affiliate_id', 'affiliate_id');
    }
}
