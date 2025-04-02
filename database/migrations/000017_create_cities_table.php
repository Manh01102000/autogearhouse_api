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
        Schema::create('city', function (Blueprint $table) {
            $table->bigIncrements('city_id');
            //tên 
            $table->string('cit_name')->nullable();
            //alias 
            $table->string('cit_alias')->nullable();
            // thứ tự
            $table->integer('cit_code')->default('0');
            // thứ tự
            $table->integer('cit_order')->default('0');
            // thời gian
            $table->integer('city_created_at')->default('0');
            // cập nhật thời gian
            $table->integer('city_updated_at')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};