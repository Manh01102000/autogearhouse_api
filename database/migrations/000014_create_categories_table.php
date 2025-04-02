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
        Schema::create('category', function (Blueprint $table) {
            $table->bigIncrements('cat_id');
            $table->string('cat_name')->nullable();
            $table->string('cat_alias')->nullable();
            $table->string('cat_tags')->nullable();
            $table->string('cat_title')->nullable();
            $table->string('cat_description')->nullable();
            $table->string('cat_keyword')->nullable();
            $table->integer('cat_code')->default('0');
            $table->integer('cat_parent_code')->default('0');
            $table->integer('cat_count')->default('0');
            $table->integer('cat_order')->default('0');
            $table->integer('cat_active')->default('1');
            $table->integer('cat_hot')->default('0');
            $table->string('cat_img')->nullable();
            $table->string('cat_301')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
}
;
