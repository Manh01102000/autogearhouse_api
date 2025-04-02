<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 
use App\Repositories\Product\ProductRepositoryInterface;
// 
class ProductController extends Controller
{

    protected $ProductRepository;
    public function __construct(ProductRepositoryInterface $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function createProduct(Request $request)
    {
        try {
            $dataUser = $request->user;
            if (!$dataUser) {
                return apiResponse('error', "Không tìm thấy người dùng", [], false, 404);
            }
            $user_id = $dataUser->user_id;
            // Convert JSON string "product_variants" thành array
            $productVariants = json_decode($request->input('product_variants'), true);

            // Kiểm tra nếu decode thất bại
            if (!is_array($productVariants)) {
                return apiResponse('error', "Dữ liệu product_variants không hợp lệ", [], false, 400);
            }

            // Gán lại product_variants vào request để validate
            $request->merge(['product_variants' => $productVariants]);

            // Validate request
            $request->validate([
                // Thông tin cơ bản
                'product_code' => 'required',
                'product_name' => 'required',
                'product_description' => 'required',
                // Thông tin chung
                'product_year' => 'required',
                'product_model' => 'required',
                'product_fuel_type' => 'required',
                'product_transmission' => 'required',
                'product_engine_capacity' => 'required',
                'product_horsepower' => 'required',
                'product_torque' => 'required',
                'product_drive_type' => 'required',
                // Thông tin ngoại thất - nội thất
                'product_body_type' => 'required',
                'product_seats' => 'required',
                'product_doors' => 'required',
                'product_airbags' => 'required',
                'product_safety_features' => 'required',
                // Tính năng hỗ trợ
                'product_infotainment' => 'required',
                'product_parking_assist' => 'required',
                'product_cruise_control' => 'required',
                // Thông tin thêm
                'product_active' => 'required',
                'category' => 'required',
                'category_code' => 'required',
                'category_children_code' => 'required',
                'product_brand' => 'required',
                // Vận chuyển
                'product_ship' => 'required',
                // Số lượng bán
                'product_sold' => 'required',
                // Validate danh sách biến thể sản phẩm
                'product_variants' => 'required|array|min:1', // Phải có ít nhất 1 biến thể
                'product_variants.*.variant_code' => 'required',
                'product_variants.*.product_price' => 'required|numeric',
                'product_variants.*.product_stock' => 'required|integer',
                'product_variants.*.product_size' => 'required|string',
                'product_variants.*.product_color' => 'required|string',
                'product_variants.*.product_code_color' => 'required|string',
            ]);

            $data = [
                'user_id' => $user_id,
                'product_code' => $request->get('product_code'),
                'product_name' => $request->get('product_name'),
                'product_description' => $request->get('product_description'),
                'product_year' => $request->get('product_year'),
                'product_model' => $request->get('product_model'),
                'product_mileage' => $request->get('product_mileage'),
                'product_fuel_type' => $request->get('product_fuel_type'),
                'product_transmission' => $request->get('product_transmission'),
                'product_engine_capacity' => $request->get('product_engine_capacity'),
                'product_horsepower' => $request->get('product_horsepower'),
                'product_torque' => $request->get('product_torque'),
                'product_drive_type' => $request->get('product_drive_type'),
                'product_body_type' => $request->get('product_body_type'),
                'product_seats' => $request->get('product_seats'),
                'product_doors' => $request->get('product_doors'),
                'product_airbags' => $request->get('product_airbags'),
                'product_safety_features' => $request->get('product_safety_features'),
                'product_infotainment' => $request->get('product_infotainment'),
                'product_parking_assist' => $request->get('product_parking_assist'),
                'product_cruise_control' => $request->get('product_cruise_control'),
                'product_active' => $request->get('product_active'),
                'category' => $request->get('category'),
                'category_code' => $request->get('category_code'),
                'category_children_code' => $request->get('category_children_code'),
                'product_brand' => $request->get('product_brand'),
                'product_images' => $request->file('product_images'),
                'product_videos' => $request->file('product_videos'),
                'product_ship' => $request->get('product_ship'),
                'product_feeship' => $request->get('product_feeship'),
                'product_sold' => $request->get('product_sold'),
                'product_variants' => $request->get('product_variants'),
            ];

            /** === Lấy dữ liệu từ repository === **/
            $response = $this->ProductRepository->create($data);
            if ($response['success']) {
                return apiResponse("success", $response['message'], $response['data'], true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            \Log::error('có lối xảy ra khi tạo sản phẩm' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $dataUser = $request->user();
            if (!$dataUser) {
                return apiResponse('error', "Không tìm thấy người dùng", [], false, 404);
            }
            $user_id = $dataUser->id;

            // Convert JSON string "product_variants" thành array
            $productVariants = json_decode($request->input('product_variants'), true);

            if (!is_array($productVariants)) {
                return apiResponse('error', "Dữ liệu product_variants không hợp lệ", [], false, 400);
            }

            // Gán lại product_variants vào request để validate
            $request->merge(['product_variants' => $productVariants]);

            // Validate request
            $request->validate([
                'product_code' => 'required',
                'product_name' => 'required',
                'product_description' => 'required',
                'product_year' => 'required',
                'product_model' => 'required',
                'product_fuel_type' => 'required',
                'product_transmission' => 'required',
                'product_engine_capacity' => 'required',
                'product_horsepower' => 'required',
                'product_torque' => 'required',
                'product_drive_type' => 'required',
                'product_body_type' => 'required',
                'product_seats' => 'required',
                'product_doors' => 'required',
                'product_airbags' => 'required',
                'product_safety_features' => 'required',
                'product_infotainment' => 'required',
                'product_parking_assist' => 'required',
                'product_cruise_control' => 'required',
                'product_active' => 'required',
                'category' => 'required',
                'category_code' => 'required',
                'category_children_code' => 'required',
                'product_brand' => 'required',
                'product_ship' => 'required',
                'product_sold' => 'required',
                'product_variants' => 'required|array|min:1',
                'product_variants.*.variant_code' => 'required',
                'product_variants.*.product_price' => 'required|numeric',
                'product_variants.*.product_stock' => 'required|integer',
                'product_variants.*.product_size' => 'required|string',
                'product_variants.*.product_color' => 'required|string',
                'product_variants.*.product_code_color' => 'required|string',
            ]);

            // Lấy dữ liệu từ request
            $data = $request->only([
                'product_code',
                'product_name',
                'product_description',
                'product_year',
                'product_model',
                'product_mileage',
                'product_fuel_type',
                'product_transmission',
                'product_engine_capacity',
                'product_horsepower',
                'product_torque',
                'product_drive_type',
                'product_body_type',
                'product_seats',
                'product_doors',
                'product_airbags',
                'product_safety_features',
                'product_infotainment',
                'product_parking_assist',
                'product_cruise_control',
                'product_active',
                'category',
                'category_code',
                'category_children_code',
                'product_brand',
                'product_ship',
                'product_feeship',
                'product_sold',
                'product_variants'
            ]);

            $data['user_id'] = $user_id;

            // Xử lý ảnh & video (nếu có)
            if ($request->hasFile('product_images')) {
                $data['product_images'] = $request->file('product_images');
            }
            if ($request->hasFile('product_videos')) {
                $data['product_videos'] = $request->file('product_videos');
            }

            // Gọi repository update
            $response = $this->ProductRepository->update($id, $data);

            return apiResponse(
                $response['success'] ? "success" : "error",
                $response['message'],
                $response['data'],
                $response['success'],
                $response['httpCode']
            );
        } catch (\Exception $e) {
            \Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }


    public function getAll()
    {
        try {
            /** === Lấy danh sách sản phẩm từ repository === **/
            $response = $this->ProductRepository->getAll();

            return apiResponse(
                $response['success'] ? "success" : "error",
                $response['message'],
                $response['data'],
                $response['success'],
                $response['httpCode']
            );
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy danh sách sản phẩm: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function searchProduct(Request $request)
    {
        try {
            /** === Lấy danh sách sản phẩm từ repository === **/
            $response = $this->ProductRepository->searchProduct($request->all());

            return apiResponse(
                $response['success'] ? "success" : "error",
                $response['message'],
                $response['data'],
                $response['success'],
                $response['httpCode']
            );
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy danh sách sản phẩm: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            /** === Lấy sản phẩm theo ID từ repository === **/
            $response = $this->ProductRepository->getById($id);

            return apiResponse(
                $response['success'] ? "success" : "error",
                $response['message'],
                $response['data'],
                $response['success'],
                $response['httpCode']
            );
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy sản phẩm: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            /** === Xóa sản phẩm theo ID từ repository === **/
            $response = $this->ProductRepository->delete($id);

            return apiResponse(
                $response['success'] ? "success" : "error",
                $response['message'],
                $response['data'],
                $response['success'],
                $response['httpCode']
            );
        } catch (\Exception $e) {
            \Log::error('Lỗi khi xóa sản phẩm: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

}