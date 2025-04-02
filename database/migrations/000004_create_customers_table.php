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
        Schema::create('customers', function (Blueprint $table) {
            // Đặt post_id làm khóa chính
            $table->bigIncrements('customer_id');
            // Khóa ngoại liên kết với bảng users qua use_id
            $table->unsignedBigInteger('customer_user_id');
            $table->foreign('customer_user_id')->references('user_id')->on('users')->onDelete('cascade');
            // Thêm khóa ngoại employee_id (Nhân viên phụ trách khách hàng)
            $table->unsignedBigInteger('customer_employee_id')->nullable();
            $table->foreign('customer_employee_id')->references('employee_id')->on('employees')->onDelete('set null');
            // Tên khách hàng
            $table->string('customer_name')->nullable();
            // Email
            $table->string('customer_email')->unique();
            // Số điện thoại
            $table->string('customer_phone')->nullable();
            // Tỉnh thành 
            $table->string('customer_city')->nullable();
            // Quận huyện
            $table->string('customer_district')->nullable();
            // Địa chỉ
            $table->text('customer_address')->nullable();
            // Ngày sinh
            $table->string('customer_birthday')->nullable();
            // Ảnh
            $table->string('customer_logo')->nullable();
            // Giới tính 0:khác, 1:nam, 2:nữ
            $table->integer('customer_gender')->default(0);
            // Hôn nhân: 1: độc thân, 2: đã kết hôn
            $table->integer('customer_marital_status')->default(0);
            // Số lượt xem
            $table->integer('customer_view_count')->default(0);
            // ẩn,hiện tài khoản
            $table->boolean('customer_show')->default(true);
            // Lat
            $table->decimal('customer_lat', 10, 6)->nullable();
            // Long
            $table->decimal('customer_long', 10, 6)->nullable();
            // Thời gian tạo
            $table->integer('customer_create_time')->default(0);
            // Thời gian cập nhật
            $table->integer('customer_update_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
