<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content_emojis extends Model
{
    use HasFactory;
    protected $table = 'content_emojis';
    //
    public $timestamps = false;
    // 
    protected $primaryKey = 'id';
    // 
    protected $fillable = [
        'content_customer_id',
        'content_comment_id',
        'content_type',
        'emoji',
        'create_time',
        'update_time',
    ];

    // Với customer
    public function Customers()
    {
        return $this->hasMany(Customers::class, 'content_customer_id', 'customer_id');
    }

    // Với Comment
    public function Comment()
    {
        return $this->belongsTo(Comment::class, 'content_comment_id', 'comment_id');
    }
}
