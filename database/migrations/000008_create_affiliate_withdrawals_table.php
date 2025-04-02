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
        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
            // ID của bảng lịch sử rút tiền
            $table->bigIncrements('withdrawal_id');
            // Liên kết với bảng affiliates
            $table->unsignedBigInteger('withdrawal_affiliate_id')->nullable();
            $table->foreign('withdrawal_affiliate_id')->references('affiliate_id')->on('affiliates')->onDelete('set null');
            // Số tiền rút
            $table->decimal('withdrawal_amount', 10, 2)->default(0.00);
            // Trạng thái rút tiền: pending, approved, rejected
            $table->string('withdrawal_status')->default('pending');
            // Phương thức rút tiền (1:Bank,2:momo, ...)
            $table->string('withdrawal_method')->nullable();
            // Số tài khoản ngân hàng hoặc số tài khoản momo,...
            $table->string('withdrawal_account')->nullable();
            // Tên chủ tài khoản ngân hàng hoặc tên tài khoản rút tiền
            $table->string('withdrawal_account_name')->nullable();
            // Ngày yêu cầu rút
            $table->integer('withdrawal_requested_at')->default('0');
            // Ngày xử lý (nếu có)
            $table->integer('withdrawal_processed_at')->default('0');
            // Thời gian tạo
            $table->integer('withdrawal_create_time')->default('0');
            // Thời gian cập nhật
            $table->integer('withdrawal_update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
    }
};
