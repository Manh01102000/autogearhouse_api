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
        Schema::create('communes', function (Blueprint $table) {
            $table->bigIncrements('commune_id');
            //tên 
            $table->string('commune_name')->nullable();
            //alias 
            $table->string('commune_alias')->nullable();
            //commune code
            $table->integer('commune_code')->default('0');
            //city cha 
            $table->integer('city_parents')->default('0');
            //district cha 
            $table->integer('district_parents')->default('0');
            // thứ tự
            $table->integer('commune_order')->default('0');
            // thời gian
            $table->integer('commune_created_at')->default('0');
            // cập nhật thời gian
            $table->integer('commune_updated_at')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};
