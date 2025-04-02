<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'district';
    //
    public $timestamps = false;
    // 
    protected $primaryKey = 'district_id';
    // 
    protected $fillable = [
        'district_name',
        'district_alias',
        'district_code',
        'city_parents',
        'district_order',
        'district_created_at',
        'district_updated_at'
    ];
}
