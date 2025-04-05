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
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('brand_id');
            $table->string('brand_name')->nullable();
            $table->string('brand_alias')->nullable();
            $table->string('brand_tags')->nullable();
            $table->string('brand_title')->nullable();
            $table->string('brand_description')->nullable();
            $table->string('brand_keyword')->nullable();
            $table->integer('brand_code')->default('0');
            $table->integer('brand_parent_code')->default('0');
            $table->integer('brand_count')->default('0');
            $table->integer('brand_order')->default('0');
            $table->integer('brand_active')->default('1');
            $table->integer('brand_hot')->default('0');
            $table->string('brand_img')->nullable();
            $table->string('brand_301')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
