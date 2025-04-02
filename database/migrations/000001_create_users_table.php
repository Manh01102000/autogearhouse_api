<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Khóa chính
            $table->bigIncrements('user_id');
            // Check quyền 1: khách hàng, 2: nhân viên, 3: admin
            $table->integer('user_role')->default(0);
            // Tài khoản đăng nhập
            $table->string('user_email_account')->unique()->nullable();
            // Số điện thoại đăng nhập nếu có
            $table->string('user_phone_account')->unique()->nullable();
            // Mật khẩu
            $table->string('password');
            // Login
            $table->boolean('is_login')->default(false);
            // Đăng nhập lần cuối
            $table->string('last_login')->nullable();
            // 0:chưa xác thực, 1: đã xác thực
            $table->integer('user_authentic')->default(false);
            // Mã OTP
            $table->string('user_otp')->nullable();
            // thời gian hết hạn
            $table->integer('user_otp_expired')->default('0');
            // Địa chỉ IP
            $table->string('user_ip_address')->nullable();
            // Thời gian tạo tài khoản
            $table->integer('user_create_time')->default('0');
            // Cập nhật thời gian tài khoản
            $table->integer('user_update_time')->default('0');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};