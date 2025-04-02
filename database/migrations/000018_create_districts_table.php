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
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('district_id');
            //tên 
            $table->string('district_name')->nullable();
            //alias 
            $table->string('district_alias')->nullable();
            //alias 
            $table->integer('district_code')->default('0');
            //city_parents 
            $table->integer('city_parents')->default('0');
            // thứ tự
            $table->integer('district_order')->default('0');
            // thời gian
            $table->integer('district_created_at')->default('0');
            // cập nhật thời gian
            $table->integer('district_updated_at')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district');
    }
}
;
