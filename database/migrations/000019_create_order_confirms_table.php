<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_confirms', function (Blueprint $table) {
            $table->bigIncrements('order_confirm_id');
            // Mã đơn hàng
            $table->string('conf_code_order')->nullable();
            // id người mua
            $table->unsignedBigInteger('conf_customer_id')->nullable();
            $table->foreign('conf_customer_id')->references('customer_id')->on('customers')->onDelete('set null');
            // id giỏ hàng
            $table->unsignedBigInteger('conf_cart_id')->nullable();
            $table->foreign('conf_cart_id')->references('cart_id')->on('carts')->onDelete('set null');
            // mã sản phẩm
            $table->string('conf_product_code')->nullable();
            // Số lượng sản phẩm
            $table->integer('conf_product_amount')->default('0');
            // mã phân loại sản phẩm
            $table->string('conf_product_classification')->nullable();
            // tổng tiền của từng loại sản phẩm
            $table->integer('conf_total_price')->default('0');
            // đơn giá của từng loại sản phẩm
            $table->integer('conf_unitprice')->default('0');
            // tổng phí ship
            $table->integer('conf_feeship')->default('0');
            // Thời gian xác nhận đơn
            $table->integer('conf_create_time')->default('0');
            // Thời gian cập xác nhận đơn
            $table->integer('conf_update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_confirms');
    }
}
;
