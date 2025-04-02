<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $table = 'category';
    //
    public $timestamps = false;
    protected $primaryKey = 'cat_id';
    // 
    protected $fillable = [
        'cat_name',
        'cat_alias',
        'cat_tags',
        'cat_title',
        'cat_description',
        'cat_keyword',
        'cat_code',
        'cat_parent_code',
        'cat_count',
        'cat_active',
        'cat_hot',
        'cat_img',
        'cat_301',
    ];
}
