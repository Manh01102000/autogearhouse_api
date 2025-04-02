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
        Schema::create('affiliate_contracts', function (Blueprint $table) {
            $table->bigIncrements('contracts_id');
            // Liên kết với bảng affiliates
            $table->unsignedBigInteger('contract_affiliate_id')->nullable();
            $table->foreign('contract_affiliate_id')->references('affiliate_id')->on('affiliates')->onDelete('set null');
            // Tên công ty
            $table->string('contract_company_name')->nullable();
            // Tên đối tác
            $table->string('contract_partner_name')->nullable();
            // Tên công ty ký
            $table->string('company_sign_name')->nullable();
            // Tên đối tác ký
            $table->string('partner_sign_name')->nullable();
            // Ngày company ký
            $table->integer('company_sign_date')->nullable();
            // Ngày đối tác dùng ký
            $table->integer('partner_sign_date')->nullable();
            // Thời gian thanh toán
            $table->integer('contract_payment_date')->nullable();
            // Phương thức thanh toán
            $table->string('contract_payment_method')->nullable();
            // Số tiền thanh toán tối thiểu
            $table->string('contract_payment_minimum')->nullable();
            // Ngày tối thiểu chấm dứt hợp đồng
            $table->integer('terminate_date_min')->nullable();
            // Ngày chấm dứt hợp đồng
            $table->integer('contract_terminate_date')->nullable();
            // Có thể lưu nội dung hợp đồng
            $table->text('contract_details')->nullable();
            // Thời gian tạo
            $table->integer('contract_create_time')->default('0');
            // Thời gian cập nhật
            $table->integer('contract_update_time')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_contracts');
    }
};
