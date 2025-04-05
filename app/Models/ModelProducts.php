<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProducts extends Model
{
    use HasFactory;
    protected $table = 'models';
    //
    public $timestamps = false;
    protected $primaryKey = 'model_id';
    // 
    protected $fillable = [
        'model_brand_id',
        'model_name',
        'model_alias',
        'model_tags',
        'model_title',
        'model_description',
        'model_keyword',
        'model_code',
        'model_parent_code',
        'model_count',
        'model_active',
        'model_hot',
        'model_img',
        'model_301',
    ];

    // Mối quan hệ
    // với bảng brands
    public function Brands()
    {
        return $this->belongsTo(Brands::class, 'model_brand_id', 'brand_id');
    }
}
