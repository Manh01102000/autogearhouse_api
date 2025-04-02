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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('variant_id'); // ID biến thể (Khóa chính)

            $table->unsignedBigInteger('product_id'); // ID sản phẩm (Khóa ngoại)
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

            // Mã biến thể duy nhất (Ví dụ: AUTOA_XANH)
            $table->string('variant_code')->nullable();

            // Giá của biến thể
            $table->decimal('product_price', 10, 2);
            // Số lượng tồn kho của biến thể
            $table->integer('product_stock')->default(0);

            // Ảnh của từng loại xe
            $table->text('variant_images')->nullable();
            // Kích thước sản phẩm (nếu có)
            $table->string('product_size')->nullable();
            // Màu sắc sản phẩm
            $table->string('product_color')->nullable();
            // Mã màu HEX (VD: #FF0000)
            $table->string('product_code_color')->nullable();

            $table->timestamps(); // Lưu ngày tạo & cập nhật
            // Thời gian tạo sản phẩm
            $table->integer('variant_create_time')->default(0);
            // Thời gian cập nhật sản phẩm
            $table->integer('variant_update_time')->default(0);
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