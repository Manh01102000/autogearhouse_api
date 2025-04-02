<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;
    protected $table = 'affiliates'; // Tên bảng trong database
    public $timestamps = false;
    protected $primaryKey = 'affiliate_id';
    protected $fillable = [
        'affiliate_user_id',
        'affiliate_code',
        'affiliate_name',
        'affiliate_email',
        'affiliate_phone',
        'affiliate_commission_rate',
        'affiliate_total_earnings',
        'affiliate_balance',
        'payment_method',
        'account_name',
        'account_number',
        'bank_name',
        'bank_branch',
        'affiliate_create_time',
        'affiliate_update_time',
    ];

    // =============Mối quan hệ==========
    // Với bảng user
    public function User()
    {
        $this->belongsTo(User::class, 'affiliate_user_id', 'user_id');
    }
    // Với bảng lịch sử rút tiền (withDrawals)
    public function AffiliateWithdrawal()
    {
        $this->hasMany(Affiliate_withdrawal::class, 'withdrawal_affiliate_id', 'affiliate_id');
    }
    // Với bảng hợp đồng (Contract)
    public function AffiliateContract()
    {
        $this->hasMany(Affiliate_contract::class, 'contract_affiliate_id', 'affiliate_id');
    }
    // Với bảng Hoa hồng (Commission)
    public function AffiliateCommission()
    {
        $this->hasMany(Affiliate_commission::class, 'commission_affiliate_id', 'affiliate_id');
    }
}
