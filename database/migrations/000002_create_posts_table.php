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
        Schema::create('posts', function (Blueprint $table) {
            // Đặt post_id làm khóa chính
            $table->bigIncrements('post_id');
            // Tiêu đề
            $table->string('post_title');
            // Nội dung
            $table->text('post_content');
            // Trạng thái bài viết (1: unpublished, 2: published)
            $table->tinyInteger('post_status')->default(1);
            // Khóa ngoại liên kết với bảng users qua use_id
            $table->unsignedBigInteger('post_user_id');
            $table->foreign('post_user_id')->references('user_id')->on('users')->onDelete('cascade');
            // Thời gian tạo
            $table->integer('post_createAt')->default(0);
            // Thời gian cập nhật
            $table->integer('post_updateAt')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
