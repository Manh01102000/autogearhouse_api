<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            // ID của bảng
            $table->bigIncrements('commission_id');
            // Liên kết với bảng affiliates
            $table->unsignedBigInteger('commission_affiliate_id')->nullable();
            $table->foreign('commission_affiliate_id')->references('affiliate_id')->on('affiliates')->onDelete('set null');
            // ID đơn hàng
            $table->integer('commission_order_id')->nullable();
            // ID sản phẩm
            $table->integer('commission_product_id')->nullable();
            // Số tiền hoa hồng (sử dụng DECIMAL để đảm bảo chính xác)
            $table->decimal('commission_amount', 15, 2)->default(0.00);
            // Thời gian tạo
            $table->integer('commission_create_time')->default('0');
            // Thời gian cập nhật
            $table->integer('commission_update_time')->default('0');

            // Giải thích về decimal
            // DECIMAL(3,2)->0.00-9.99
            // DECIMAL(4,2)->0.00-99.99
            // DECIMAL(5,2)->0.00-999.99
            // DECIMAL(6,2)->0.00-9999.99
            // DECIMAL(10,2)->0.00-9999999999.99
            // DECIMAL(15,2)->0.00-999999999999999.99
            // Giải thích về $table->foreignId('commission_affiliate_id')->references('affiliate_id')->on('affiliates');
            // foreignId('commission_affiliate_id'): Tạo cột commission_affiliate_id trong bảng affiliates.
            // references('affiliate_id'): Khóa ngoại tham chiếu đến cột affiliate_id trong bảng affiliates.
            // on('affiliates'): Chỉ định bảng affiliates chứa khóa chính affiliate_id.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};