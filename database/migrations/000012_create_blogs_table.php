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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('blog_id');
            // id admin
            $table->unsignedBigInteger('blog_employee_id')->nullable();
            $table->foreign('blog_employee_id')->references('employee_id')->on('employees')->onDelete('set null');
            // Tiêu đề bài viết
            $table->string('blog_title')->nullable();
            // danh mục bài viết
            $table->integer('blog_cate')->default('0');
            // Tiêu đề h1
            $table->string('blog_meta_h1')->nullable();
            // Nội dung bài viết
            $table->string('blog_content')->nullable();
            // seo title
            $table->string('blog_meta_title')->nullable();
            // seo des
            $table->string('blog_meta_description')->nullable();
            // Tiêu đề bài viết
            $table->string('blog_meta_keyword')->nullable();
            // Các từ khóa liên quan
            $table->string('blog_tags')->nullable();
            // Thời gian tạo bài viết
            $table->integer('blog_create_time')->default('0');
            // Thời gian cập nhật bài viết
            $table->integer('blog_update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
}
;
