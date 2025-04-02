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
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('ordetail_id');
            // Khóa ngoại ID order
            $table->unsignedBigInteger('ordetail_order_id')->nullable();
            $table->foreign('ordetail_order_id')->references('order_id')->on('orders')->onDelete('set null');
            // mã sản phẩm
            $table->integer('ordetail_product_code')->default(0);
            // tổng sản phẩm
            $table->string('ordetail_product_amount')->nullable();
            // loại sản phẩm
            $table->string('ordetail_product_classification')->nullable();
            // tổng số tiền của riêng sản phẩm
            $table->integer('ordetail_product_totalprice')->default(0);
            // giá gốc sản phẩm hoặc giảm giá sp
            $table->integer('ordetail_product_unitprice')->default(0);
            // phí ship của sản phẩm đó
            $table->decimal('ordetail_product_feeship', 15, 2)->default(0.00);
            // Ngày tạo cho tiết đơn hàng
            $table->integer('ordetail_created_at')->default(0);
            // Ngày cập nhật cho tiết đơn hàng
            $table->integer('ordetail_updated_at')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
