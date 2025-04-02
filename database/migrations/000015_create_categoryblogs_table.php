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
        Schema::create('category_blog', function (Blueprint $table) {
            $table->bigIncrements('cat_blog_id');
            $table->string('cat_blog_name')->nullable();
            $table->string('cat_blog_alias')->nullable();
            $table->string('cat_blog_tags')->nullable();
            $table->string('cat_blog_title')->nullable();
            $table->string('cat_blog_description')->nullable();
            $table->string('cat_blog_keyword')->nullable();
            $table->integer('cat_blog_parent_id')->default('0');
            $table->integer('cat_blog_count')->default('0');
            $table->integer('cat_blog_order')->default('0');
            $table->integer('cat_blog_active')->default('1');
            $table->integer('cat_blog_hot')->default('0');
            $table->string('cat_blog_img')->nullable();
            $table->string('cat_blog_301')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_blog');
    }
};
