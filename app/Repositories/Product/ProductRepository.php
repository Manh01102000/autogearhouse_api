<?php

namespace App\Repositories\Product;
// Interface
use App\Repositories\Product\ProductRepositoryInterface;
// Model
use App\Models\Employees;
use App\Models\Products;
use App\Models\ProductVariants;
// Import db transaction
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    protected $employees;
    protected $Products;
    protected $productVariants;

    public function __construct(Employees $employees, Products $Products, ProductVariants $productVariants)
    {
        $this->employees = $employees;
        $this->Products = $Products;
        $this->productVariants = $productVariants;
    }

    public function getAll()
    {

        try {
            $product = $this->Products->with('productVariants')->get();
            return [
                'success' => true,
                'message' => "Lấy sản phẩm thành công",
                'httpCode' => 201,
                'data' => ['product' => $product],
            ];
        } catch (\Exception $e) {

            \Log::error("Lỗi khi tạo sản phẩm", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function searchProduct(array $data)
    {
        try {

            $page = $data['page'] ?? 1;
            $pageSize = $data['pageSize'] ?? 20;

            $query = $this->Products->query();
            // Lọc theo điều kiện nếu có
            if (!empty($data['product_name'])) {
                $query->where('product_name', 'LIKE', "%{$data['product_name']}%");
            }

            if (!empty($data['product_id'])) {
                $query->where('product_id', $data['product_id']);
            }

            if (!empty(!empty($data['product_time_start']) && !empty($data['product_time_end']))) {
                $query->whereBetween("product_create_time", [$data['product_time_start'], $data['product_time_end']]);
            }

            $query->with('productVariants');
            $products = $query->paginate($pageSize, ['*'], 'page', $page);

            foreach ($products->items() as $product) {
                $product->product_images_full = '';

                if (!empty($product->product_images)) {
                    $images = explode(',', $product->product_images);
                    $fullUrls = [];

                    foreach ($images as $image) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 1) . $image;
                    }

                    $product->product_images_full = implode(',', $fullUrls);
                }
            }

            return [
                'success' => true,
                'message' => "Lấy sản phẩm thành công",
                'httpCode' => 200,
                'data' => $products,
            ];

        } catch (\Exception $e) {

            \Log::error("Lỗi khi tạo sản phẩm", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function getById($id)
    {
        try {
            $product = $this->Products->with('productVariants')->find($id); // Tìm sản phẩm theo ID

            if (!$product) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy sản phẩm",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy sản phẩm thành công",
                'httpCode' => 200,
                'data' => ['product' => $product],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy sản phẩm", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }


    public function create(array $data)
    {
        try {
            DB::beginTransaction(); // Bắt đầu transaction

            $timestamp = time();

            // Lấy dữ liệu nhân viên
            $dbemployee = $this->employees::find($data['user_id']);
            if (!$dbemployee) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => "Không tìm thấy dữ liệu nhân viên",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }
            $employee_id = $dbemployee->employee_id;

            // Xử lý ảnh và video
            $str_new_img = isset($data['product_images']) ? $this->handleImages($data['product_images'], $timestamp) : '';
            $str_new_video = isset($data['product_videos']) ? $this->handleVideos($data['product_videos'], $timestamp) : '';

            // Tạo sản phẩm
            $product = $this->Products->create([
                'product_employee_id' => $employee_id,
                'product_code' => $data['product_code'],
                'product_name' => $data['product_name'],
                'product_alias' => replaceTitle($data['product_name']),
                'product_description' => $data['product_description'],
                'product_year' => $data['product_year'],
                'product_model' => $data['product_model'],
                'product_mileage' => $data['product_mileage'],
                'product_fuel_type' => $data['product_fuel_type'],
                'product_transmission' => $data['product_transmission'],
                'product_engine_capacity' => $data['product_engine_capacity'],
                'product_horsepower' => $data['product_horsepower'],
                'product_torque' => $data['product_torque'],
                'product_drive_type' => $data['product_drive_type'],
                'product_body_type' => $data['product_body_type'],
                'product_seats' => $data['product_seats'],
                'product_doors' => $data['product_doors'],
                'product_airbags' => $data['product_airbags'],
                'product_safety_features' => $data['product_safety_features'],
                'product_infotainment' => $data['product_infotainment'],
                'product_parking_assist' => $data['product_parking_assist'],
                'product_cruise_control' => $data['product_cruise_control'],
                'product_active' => $data['product_active'] ?? 1,
                'category' => $data['category'],
                'category_code' => $data['category_code'],
                'category_children_code' => $data['category_children_code'],
                'product_brand' => $data['product_brand'],
                'product_images' => $str_new_img,
                'product_videos' => $str_new_video,
                'product_ship' => $data['product_ship'],
                'product_feeship' => $data['product_feeship'],
                'product_sold' => $data['product_sold'],
                'product_create_time' => $timestamp,
                'product_update_time' => $timestamp,
            ]);

            // Nếu có biến thể thì thêm vào `product_variants`
            $variants = [];
            if (!empty($data['product_variants'])) {
                $variants = collect($data['product_variants'])->map(function ($variant) use ($product, $timestamp) {
                    return $this->productVariants->create([
                        'product_id' => $product->product_id,
                        'variant_code' => $variant['variant_code'],
                        'product_price' => $variant['product_price'],
                        'product_stock' => $variant['product_stock'],
                        'product_size' => $variant['product_size'],
                        'product_color' => $variant['product_color'],
                        'product_code_color' => $variant['product_code_color'],
                        'variant_create_time' => $timestamp,
                        'variant_update_time' => $timestamp,
                    ]);
                })->toArray();
            }

            // Commit transaction nếu không có lỗi
            DB::commit();

            return [
                'success' => true,
                'message' => "Thêm mới sản phẩm thành công",
                'httpCode' => 201,
                'data' => [
                    'product' => $product,
                    'variants' => $variants,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction nếu có lỗi

            \Log::error("Lỗi khi tạo sản phẩm", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }


    public function update($id, array $data)
    {
        $record = $this->Products->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->Products->destroy($id);
    }

    // Hàm xử lý ảnh
    private function handleImages($images, $time)
    {
        if (!$images)
            return '';

        $list_arr_image = [];
        foreach ($images as $uploadedFile) {
            if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                $name = 'image_prod_' . md5($uploadedFile->getClientOriginalName() . time()) . '.' . $uploadedFile->getClientOriginalExtension();
                $dir = getUrlImageVideoProduct($time, 1);
                $uploadedFile->move($dir, $name);
                $list_arr_image[] = $name;
            }
        }
        return implode(",", $list_arr_image);
    }

    // Hàm xử lý video
    private function handleVideos($videos, $time)
    {
        if (!$videos)
            return '';

        $list_arr_video = [];
        foreach ($videos as $uploadedFile) {
            if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                $name = 'video_prod_' . md5($uploadedFile->getClientOriginalName() . time()) . '.' . $uploadedFile->getClientOriginalExtension();
                $dir = getUrlImageVideoProduct($time, 2);
                $uploadedFile->move($dir, $name);
                $list_arr_video[] = $name;
            }
        }
        return implode(",", $list_arr_video);
    }

    // hàm xử lý xóa file
    private function deleteFiles($fileList, $time)
    {
        if (empty($fileList))
            return;

        $fileArray = explode(',', $fileList);
        foreach ($fileArray as $file) {
            $url = getUrlImageVideoProduct($time, 1) . $file;
            if (is_file($url)) {
                unlink($url);
            } else {
                error_log("File không tồn tại: " . $url);
            }
        }
    }
}
