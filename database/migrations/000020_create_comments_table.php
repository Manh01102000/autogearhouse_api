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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('comment_id');
            // id bình luận cha
            $table->integer('comment_parents_id')->default('0');
            // Khóa ngoại liên kết với bảng customer qua customer_id
            $table->unsignedBigInteger('comment_customer_id');
            $table->foreign('comment_customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            // Khóa ngoại liên kết với bảng employees qua employee_id
            $table->unsignedBigInteger('comment_employee_id')->nullable();
            $table->foreign('comment_employee_id')->references('employee_id')->on('employees')->onDelete('set null');
            // id sản phẩm đánh giá
            // Khóa ngoại liên kết với bảng product qua product_id
            $table->unsignedBigInteger('comment_product_id');
            $table->foreign('comment_product_id')->references('product_id')->on('products')->onDelete('cascade');
            // id blog đánh giá
            // Khóa ngoại liên kết với bảng product qua product_id
            $table->unsignedBigInteger('comment_blog_id');
            $table->foreign('comment_blog_id')->references('blog_id')->on('blogs')->onDelete('cascade');
            // 1: bình luận sản phẩm, 2: bình luận bài viết
            $table->integer('comment_type')->default('0');
            // Nội dung bình luận
            $table->string('comment_content')->nullable();
            // ảnh comment
            $table->string('comment_image')->nullable();
            // 1: facebook, 2: zalo, 3:mess, 4 sao chép lk
            $table->integer('comment_share')->default('0');
            // lượt xem
            $table->integer('comment_views')->default('0');
            // Thời gian đánh giá
            $table->integer('createdAt')->default('0');
            // Cập nhật thời gian đánh giá
            $table->integer('updatedAt')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
}
;
