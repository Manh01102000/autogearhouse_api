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

    // tìm kiếm sản phẩm trong admin
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

            // Sắp xếp
            $query->orderBy('product_id', 'desc')
                ->orderBy('product_create_time', 'desc');

            $query->with('productVariants');
            $products = $query->paginate($pageSize, ['*'], 'page', $page);

            foreach ($products->items() as $product) {
                $product->product_images_full = '';
                $product->product_videos_full = '';
                if (!empty($product->product_images)) {
                    $images = explode(',', $product->product_images);
                    $fullUrls = [];

                    foreach ($images as $image) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 1) . $image;
                    }

                    $product->product_images_full = implode(',', $fullUrls);
                }

                if (!empty($product->product_videos)) {
                    $videos = explode(',', $product->product_videos);
                    $fullUrls = [];

                    foreach ($videos as $video) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 2) . $video;
                    }

                    $product->product_videos_full = implode(',', $fullUrls);
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
    // Lấy sản phẩm mới nhất
    public function getProductNew(array $data)
    {
        try {

            $page = $data['page'] ?? 1;
            $pageSize = $data['pageSize'] ?? 8;

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
            // sắp xếp
            $query->where('product_active', 1)
                ->orderBy('product_id', 'desc')
                ->orderBy('product_create_time', 'desc');
            // relation với product variant
            $query->with('productVariants');
            $products = $query->paginate($pageSize, ['*'], 'page', $page);

            foreach ($products->items() as $product) {
                $product->product_images_full = '';
                $product->product_videos_full = '';
                if (!empty($product->product_images)) {
                    $images = explode(',', $product->product_images);
                    $fullUrls = [];

                    foreach ($images as $image) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 1) . $image;
                    }

                    $product->product_images_full = implode(',', $fullUrls);
                }

                if (!empty($product->product_videos)) {
                    $videos = explode(',', $product->product_videos);
                    $fullUrls = [];

                    foreach ($videos as $video) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 2) . $video;
                    }

                    $product->product_videos_full = implode(',', $fullUrls);
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

    public function getProductFeatured(array $data)
    {
        try {

            $page = $data['page'] ?? 1;
            $pageSize = $data['pageSize'] ?? 8;

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
            // sắp xếp
            $query->where('product_active', 1)
                ->orderBy('product_sold', 'desc')
                ->orderBy('product_create_time', 'desc');

            // relation với product variant
            $query->with('productVariants');
            $products = $query->paginate($pageSize, ['*'], 'page', $page);

            foreach ($products->items() as $product) {
                $product->product_images_full = '';
                $product->product_videos_full = '';
                if (!empty($product->product_images)) {
                    $images = explode(',', $product->product_images);
                    $fullUrls = [];

                    foreach ($images as $image) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 1) . $image;
                    }

                    $product->product_images_full = implode(',', $fullUrls);
                }

                if (!empty($product->product_videos)) {
                    $videos = explode(',', $product->product_videos);
                    $fullUrls = [];

                    foreach ($videos as $video) {
                        $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($product->product_create_time, 2) . $video;
                    }

                    $product->product_videos_full = implode(',', $fullUrls);
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
            $products = $this->Products->with('productVariants')->find($id); // Tìm sản phẩm theo ID

            if (!$products) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy sản phẩm",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            // Xử lý ảnh và video cho sản phẩm
            $products->product_images_full = '';
            $products->product_videos_full = '';

            if (!empty($products->product_images)) {
                $images = explode(',', $products->product_images);
                $fullUrls = [];

                foreach ($images as $image) {
                    $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($products->product_create_time, 1) . $image;
                }

                $products->product_images_full = implode(',', $fullUrls);
            }

            if (!empty($products->product_videos)) {
                $videos = explode(',', $products->product_videos);
                $fullUrls = [];

                foreach ($videos as $video) {
                    $fullUrls[] = env('DOMAIN_WEB') . getUrlImageVideoProduct($products->product_create_time, 2) . $video;
                }

                $products->product_videos_full = implode(',', $fullUrls);
            }

            return [
                'success' => true,
                'message' => "Lấy sản phẩm thành công",
                'httpCode' => 200,
                'data' => ['product' => $products],
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
            $dbemployee = $this->employees::where('employee_user_id', $data['user_id'])->first();
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
                'product_newold' => $data['product_newold'],
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
                        'variant_code' => $product->product_code,
                        'product_price' => str_replace(',', '', $variant['product_price']),
                        'product_stock' => $variant['product_stock'],
                        'product_color' => $variant['product_color'],
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
        try {
            $timestamp = time();
            // Bắt đầu transaction
            DB::beginTransaction();
            // Lấy dữ liệu nhân viên
            $dbemployee = $this->employees::where('employee_user_id', $data['user_id'])->first();
            if (!$dbemployee) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => "Không tìm thấy dữ liệu nhân viên",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }
            // Ảnh đã bị xóa bên font-end
            $product_images_del = $data["product_images_del"] ?? null;
            // Video đã bị xóa bên font-end
            $product_videos_del = $data["product_videos_del"] ?? null;
            // Ảnh cũ không bị xóa bên font-end
            $product_images_old = $data["product_images_old"] ?? null;
            // Video cũ không bị xóa bên font-end
            $product_videos_old = $data["product_videos_old"] ?? null;

            // Lấy dữ liệu của product
            $dataproduct = Products::where('product_id', $id)->first();

            if (!$dataproduct) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm',
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            $time = $dataproduct->toArray()['product_create_time'];
            // Xử lý unlink những ảnh bị xóa
            if (!empty($product_images_del)) {
                $this->deleteFiles($product_images_del, $time);
            }
            // Xử lý unlink những video bị xóa
            if (!empty($product_videos_del)) {
                $this->deleteFiles($product_videos_del, $time);
            }
            // Xử lý ảnh mới + ảnh cũ được giữ lại
            $product_images_old_list = isset($product_images_old) ? explode(',', $product_images_old) : [];
            $arr_img_new_list = isset($data['product_images']) ? explode(',', $this->handleImages($data['product_images'], $time)) : [];
            $result_img_array = array_unique(array_merge($product_images_old_list, $arr_img_new_list));
            $result_img_str = implode(',', $result_img_array);

            // Xử lý video + video cũ được giữ lại
            $product_videos_old_list = isset($product_videos_old) ? explode(',', $product_videos_old) : [];
            $arr_video_new_list = isset($data['product_videos']) ? explode(',', $this->handleVideos($data['product_videos'], $time)) : [];
            $result_video_array = array_unique(array_merge($product_videos_old_list, $arr_video_new_list));
            $result_video_str = implode(',', $result_video_array);

            // Cập nhật sản phẩm
            $product = $dataproduct->update([
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
                'category' => $data['category'],
                'category_code' => $data['category_code'],
                'product_newold' => $data['product_newold'],
                'product_brand' => $data['product_brand'],
                'product_images' => $result_img_str,
                'product_videos' => $result_video_str,
                'product_ship' => $data['product_ship'],
                'product_feeship' => $data['product_feeship'],
                'product_sold' => $data['product_sold'],
                'product_update_time' => $timestamp,
            ]);

            // Nếu có biến thể thì thêm vào `product_variants`
            $variants = [];
            if (!empty($data['product_variants'])) {
                $variants = collect($data['product_variants'])->map(function ($variant) use ($product, $timestamp) {
                    return $this->productVariants->where('variant_id', $variant['variant_id'])->update([
                        'product_price' => $variant['product_price'],
                        'product_stock' => $variant['product_stock'],
                        'product_color' => $variant['product_color'],
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

    public function delete($id)
    {
        try {
            // Bắt đầu transaction
            DB::beginTransaction();
            // Lấy dữ liệu của product
            $dataproduct = Products::where('product_id', $id)->first();

            if (!$dataproduct) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm',
                    'httpCode' => 404,
                    'data' => [],
                ];
            }
            // Xóa ảnh trước
            $time = $dataproduct->toArray()['product_create_time'];
            $product_images_del = $dataproduct->toArray()['product_images'];
            $product_videos_del = $dataproduct->toArray()['product_videos'];
            // Xử lý unlink những ảnh bị xóa
            if (!empty($product_images_del)) {
                $this->deleteFiles($product_images_del, $time);
            }
            // Xử lý unlink những video bị xóa
            if (!empty($product_videos_del)) {
                $this->deleteFiles($product_videos_del, $time);
            }

            $dataproduct->delete();
            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa sản phẩm thành công",
                'httpCode' => 201,
                'data' => [],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Lỗi khi xóa sản phẩm", [
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
