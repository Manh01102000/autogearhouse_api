<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $table = 'communes';
    //
    public $timestamps = false;
    // 
    protected $primaryKey = 'commune_id';
    // 
    protected $fillable = [
        'commune_name',
        'commune_alias',
        'commune_code',
        'city_parents',
        'district_parents',
        'commune_order',
        'commune_created_at',
        'commune_updated_at'
    ];
}
