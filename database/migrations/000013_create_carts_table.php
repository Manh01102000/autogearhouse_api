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
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('cart_id');
            // id người mua
            $table->unsignedBigInteger('cart_customer_id')->nullable();
            $table->foreign('cart_customer_id')->references('customer_id')->on('customers')->onDelete('set null');
            // mã sản phẩm
            $table->string('cart_product_code')->nullable();
            // mã phân loại sản phẩm
            $table->text('cart_product_classification')->nullable();
            // Số lượng sản phẩm
            $table->integer('cart_product_amount')->default('0');
            // Thời gian tạo giỏ hàng
            $table->integer('cart_create_time')->default('0');
            // Thời gian cập nhật giỏ hàng
            $table->integer('cart_update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
}
;
