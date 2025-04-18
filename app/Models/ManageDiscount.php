<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageDiscount extends Model
{
    /** @use HasFactory<\Database\Factories\ManageDiscountFactory> */
    use HasFactory;
    protected $table = 'manage_discounts';
    public $timestamps = false;
    protected $primaryKey = 'discount_id';
    protected $fillable = [
        'discount_employee_id',
        'discount_product_id',
        'discount_name',
        'discount_description',
        'discount_active',
        'discount_type',
        'discount_price',
        'discount_start_time',
        'discount_end_time',
        'discount_create_time',
        'discount_update_time',
    ];

    // Quan hệ với product
    public function ManageDiscount()
    {
        return $this->belongsTo(Products::class, 'discount_product_id', 'product_id');
    }

    // Với Employees
    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'discount_employee_id', 'employee_id');
    }
}
