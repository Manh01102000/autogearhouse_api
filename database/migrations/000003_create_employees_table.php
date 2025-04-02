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
        Schema::create('employees', function (Blueprint $table) {
            // Đặt post_id làm khóa chính
            $table->bigIncrements('employee_id');
            // Khóa ngoại liên kết với bảng users qua use_id
            $table->unsignedBigInteger('employee_user_id')->nullable();
            $table->foreign('employee_user_id')->references('user_id')->on('users')->onDelete('cascade');
            // Tên khách hàng
            $table->string('employee_name')->nullable();
            // Email
            $table->string('employee_email')->unique();
            // Số điện thoại
            $table->string('employee_phone')->nullable();
            // Chức vụ
            $table->string('employee_position')->nullable();
            // Phòng ban
            $table->string('employee_department')->nullable();
            // Mức lương
            $table->integer('employee_salary')->nullable();
            // Ảnh
            $table->string('employee_logo')->nullable();
            // Ngày sinh
            $table->string('employee_birthday')->nullable();
            // Ẩn hiện tài khoản
            $table->integer('employee_show')->default(0);
            // Thời gian nhân viên lâu chưa nhận khách hàng nhất (giúp chia đều khách hàng)
            $table->integer('last_assigned_at')->default(0);
            // Thời gian tạo
            $table->integer('employee_createAt')->default(0);
            // Thời gian cập nhật
            $table->integer('employee_updateAt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
