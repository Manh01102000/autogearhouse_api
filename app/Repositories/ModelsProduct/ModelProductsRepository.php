<?php

namespace App\Repositories\ModelsProduct;
// Interface
use App\Repositories\ModelsProduct\ModelProductsRepositoryInterface;
// Model
use App\Models\Brands;
use App\Models\ModelProducts;
// Import db transaction
use Illuminate\Support\Facades\DB;

class ModelProductsRepository implements ModelProductsRepositoryInterface
{
    protected $brands;
    protected $modelProducts;

    public function __construct(Brands $brands, ModelProducts $modelProducts)
    {
        $this->brands = $brands;
        $this->modelProducts = $modelProducts;
    }

    public function getModelProducts()
    {
        try {
            $modelProduct = $this->modelProducts::where([
                ['model_active', '=', 1]
            ])->get();

            if (!$modelProduct) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy dòng xe",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy dòng xe thành công",
                'httpCode' => 200,
                'data' => ['modelProduct' => $modelProduct],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy dòng xe", [
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

    public function getModelProductsByID($id)
    {
        try {
            $modelProduct = $this->modelProducts::where([
                ['model_brand_id', '=', $id],
                ['model_active', '=', 1]
            ])->get();

            if (!$modelProduct) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy dòng xe",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy dòng xe thành công",
                'httpCode' => 200,
                'data' => ['modelProduct' => $modelProduct],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy dòng xe", [
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
}
