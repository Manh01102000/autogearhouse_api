<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_emojis', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Khóa ngoại liên kết với bảng customer qua use_id
            $table->unsignedBigInteger('content_customer_id');
            $table->foreign('content_customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            // id comment
            // Khóa ngoại liên kết với bảng customer qua use_id
            $table->unsignedBigInteger('content_comment_id');
            $table->foreign('content_comment_id')->references('comment_id')->on('comments')->onDelete('cascade');
            // 1 là sản phẩm, 2:tin tức
            $table->integer('content_type')->default('0');
            // 1:like, 2:yêu thích, 3:Haha, 4:Wow, 5: Buồn, 6:Phẫn nộ
            $table->integer('emoji')->default('0');
            // Thời gian gửi emoji
            $table->integer('create_time')->default('0');
            // Thời gian cập gửi emoji
            $table->integer('update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_emojis');
    }
}
;
