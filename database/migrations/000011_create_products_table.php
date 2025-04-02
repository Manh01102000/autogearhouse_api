<?php

use App\Models\Employees;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id'); // ID sản phẩm (Khóa chính)
            // Liên kết với bảng Employees
            $table->unsignedBigInteger('product_employee_id')->nullable();
            $table->foreign('product_employee_id')->references('employee_id')->on('employees')->onDelete('set null');

            // Thông tin cơ bản
            $table->string('product_code')->nullable(); // mã sản phẩm
            $table->string('product_name')->nullable(); // tên sản phẩm
            $table->string('product_alias')->nullable(); // alias
            $table->text('product_description')->nullable(); // mô tả

            // Thông tin chung
            $table->integer('product_year')->nullable(); // Năm sản xuất
            $table->string('product_model')->nullable(); // Mẫu xe
            $table->integer('product_mileage')->nullable(); // Số km đã đi (cho xe cũ)
            $table->string('product_fuel_type')->nullable(); // Loại nhiên liệu (Xăng, Dầu, Điện,...)
            $table->string('product_transmission')->nullable(); // Hộp số (Số sàn, Tự động)
            $table->decimal('product_engine_capacity', 5, 2)->nullable(); // Dung tích động cơ
            $table->integer('product_horsepower')->nullable(); // Công suất (HP)
            $table->string('product_torque')->nullable(); // Mô-men xoắn (Nm)
            $table->string('product_drive_type')->nullable(); // Dẫn động (FWD, RWD, AWD, ...)
            $table->string('product_color')->nullable(); // màu sắc
            $table->integer('product_newold')->nullable(); // cũ mới (1:mới, 2:cũ)

            // Thông tin ngoại thất - nội thất
            $table->string('product_body_type')->nullable(); // Loại thân xe
            $table->integer('product_seats')->nullable(); // Số chỗ ngồi
            $table->integer('product_doors')->nullable(); // Số cửa
            $table->integer('product_airbags')->nullable(); // Số túi khí
            $table->text('product_safety_features')->nullable(); // Các tính năng an toàn (JSON)

            // Tính năng hỗ trợ
            $table->text('product_infotainment')->nullable(); // Hệ thống giải trí (JSON)
            $table->integer('product_parking_assist')->default(0); // Hỗ trợ đỗ xe
            $table->integer('product_cruise_control')->default(0); // Kiểm soát hành trình

            // Thông tin thêm
            // Active
            $table->integer('product_active')->default(0);
            // Danh mục cấp 1
            $table->integer('category')->default(0);
            // Danh mục cấp 2
            $table->integer('category_code')->default(0);
            // Danh mục cấp 3
            $table->integer('category_children_code')->default(0);
            // Thương hiệu
            $table->string('product_brand')->nullable();

            // Ảnh giới thiệu
            $table->text('product_images')->nullable();
            // Video giới thiệu
            $table->text('product_videos')->nullable();

            // Phí vận chuyển 1:miễn phí, 2 giá tiền
            $table->integer('product_ship')->default(0);
            // Giá vận chuyển
            $table->string('product_feeship')->nullable();

            // Số lượng bán
            $table->integer('product_sold')->default(0);

            // Thời gian tạo và cập nhật
            $table->integer('product_create_time')->default(0);
            $table->integer('product_update_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};