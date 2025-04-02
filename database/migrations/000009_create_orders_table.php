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
        Schema::create('orders', function (Blueprint $table) {
            // Đặt post_id làm khóa chính
            $table->bigIncrements('order_id');
            // Khóa ngoại ID khách hàng
            $table->unsignedBigInteger('order_customer_id');
            $table->foreign('order_customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            // Khóa ngoại nhân viên chăm sóc
            $table->unsignedBigInteger('order_employee_id')->nullable();
            $table->foreign('order_employee_id')->references('employee_id')->on('employees')->onDelete('set null');
            // Liên kết với bảng affiliates khóa ngoại order_affiliate_id
            $table->unsignedBigInteger('order_affiliate_id')->nullable();
            $table->foreign('order_affiliate_id')->references('affiliate_id')->on('affiliates')->onDelete('set null');
            // Tên khách hàng
            $table->string('order_user_name')->nullable();
            // Email
            $table->string('order_user_email')->unique();
            // Số điện thoại
            $table->string('order_user_phone')->nullable();
            // ghi chú
            $table->text('order_user_note')->nullable();
            // Mã đơn hàng
            $table->string('order_code')->nullable();
            // địa điểm giao hàng
            $table->string('order_address_ship')->nullable();
            // Tổng số tiền đơn hàng
            $table->decimal('order_total_price', 15, 2);
            // Trạng thái duyệt đơn hàng (1: đơn chờ duyệt, 2: đơn đang hoạt động, 3: đơn hoàn thành, 4: đơn hết hạn, 5: đơn bị hủy)
            $table->integer('order_status')->default('0');
            // 0: chưa gửi admin, 1: admin đã nhận và đang chờ duyệt, 2: admin đã duyệt, 3 admin từ chối
            $table->integer('order_admin_accept')->default('0');
            // Thời gian admin duyệt đơn
            $table->integer('order_admin_accept_time')->default('0');
            // số tiền chuyên viên nhận
            $table->integer('order_money_received')->default('0');
            // Hóa đơn (dạng PDF)
            $table->string('order_bill_pdf')->nullable();
            // Thời gian tạo đơn hàng
            $table->integer('order_create_time')->default('0');
            // Thời gian cập nhật đơn hàng
            $table->integer('order_update_time')->default('0');
            // (1: thanh toán toàn bộ, 2: thanh toán 10%, 3: momo)
            $table->integer('order_paymentMethod')->default('0');
            // Tên ngân hàng
            $table->string('order_name_bank')->nullable();
            // Chi nhánh ngân hàng
            $table->string('order_branch_bank')->nullable();
            // Tài khoản ngân hàng
            $table->string('order_account_bank')->nullable();
            // Chủ sở hữu
            $table->string('order_account_holder')->nullable();
            // Nội dung chuyển khoản
            $table->string('order_content_bank')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
