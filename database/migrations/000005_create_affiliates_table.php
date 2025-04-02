<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            // ID của bảng
            $table->bigIncrements('affiliate_id');
            // Liên kết với users
            $table->unsignedBigInteger('affiliate_user_id')->nullable();
            $table->foreign('affiliate_user_id')->references('user_id')->on('users')->onDelete('set null');
            // Mã giới thiệu
            $table->string('affiliate_code')->nullable();
            // name
            $table->string('affiliate_name')->nullable();
            // email
            $table->string('affiliate_email')->nullable();
            // phone
            $table->string('affiliate_phone')->nullable();
            // % hoa hồng (sử dụng DECIMAL để hỗ trợ số thập phân)
            $table->decimal('affiliate_commission_rate', 5, 2)->default(0.00);
            // Tổng tiền hoa hồng kiếm được từ trước đến nay (dùng DECIMAL để tránh làm tròn)
            $table->decimal('affiliate_total_earnings', 15, 2)->default(0.00);
            // Số tiền có thể rút (Tổng hoa hồng - tiền đã rút)
            $table->decimal('affiliate_balance', 15, 2)->default(0.00);
            // Thông tin tài khoản rút tiền
            // Phương thức rút tiền (1:Bank,2:momo, ...)
            $table->string('payment_method')->nullable();
            // Tên chủ tài khoản ngân hàng hoặc tên tài khoản rút tiền
            $table->string('account_name')->nullable();
            // Số tài khoản ngân hàng hoặc ví điện tử
            $table->string('account_number')->nullable();
            // Tên ngân hàng (nếu chọn phương thức ngân hàng)
            $table->string('bank_name')->nullable();
            // Chi nhánh ngân hàng (nếu có)
            $table->string('bank_branch')->nullable();
            // Thời gian tạo
            $table->integer('affiliate_create_time')->default('0');
            // Thời gian cập nhật
            $table->integer('affiliate_update_time')->default('0');

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
        Schema::dropIfExists('affiliates');
    }
};