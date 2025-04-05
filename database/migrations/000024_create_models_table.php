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
        Schema::create('models', function (Blueprint $table) {
            $table->bigIncrements('model_id');
            // Khóa ngoại ID order
            $table->unsignedBigInteger('model_brand_id')->nullable();
            $table->foreign('model_brand_id')->references('brand_id')->on('brands')->onDelete('set null');
            $table->string('model_name')->nullable();
            $table->string('model_alias')->nullable();
            $table->string('model_tags')->nullable();
            $table->string('model_title')->nullable();
            $table->string('model_description')->nullable();
            $table->string('model_keyword')->nullable();
            $table->integer('model_code')->default('0');
            $table->integer('model_parent_code')->default('0');
            $table->integer('model_count')->default('0');
            $table->integer('model_order')->default('0');
            $table->integer('model_active')->default('1');
            $table->integer('model_hot')->default('0');
            $table->string('model_img')->nullable();
            $table->string('model_301')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
