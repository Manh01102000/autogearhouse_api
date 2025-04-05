<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;
    protected $table = 'brands';
    //
    public $timestamps = false;
    protected $primaryKey = 'brand_id';
    // 
    protected $fillable = [
        'brand_name',
        'brand_alias',
        'brand_tags',
        'brand_title',
        'brand_description',
        'brand_keyword',
        'brand_code',
        'brand_parent_code',
        'brand_count',
        'brand_active',
        'brand_hot',
        'brand_img',
        'brand_301',
    ];

    // Mối quan hệ
    // với bảng models
    public function Models()
    {
        return $this->hasMany(ModelProducts::class, 'model_brand_id', 'brand_id');
    }
}
