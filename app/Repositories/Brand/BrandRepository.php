<?php

namespace App\Repositories\Brand;
// Interface
use App\Repositories\Brand\BrandRepositoryInterface;
// Model
use App\Models\Brands;
use App\Models\ModelProducts;
// Import db transaction
use Illuminate\Support\Facades\DB;

class BrandRepository implements BrandRepositoryInterface
{
    protected $brands;
    protected $models;

    public function __construct(Brands $brands, ModelProducts $models)
    {
        $this->brands = $brands;
        $this->models = $models;
    }

    public function getBrands()
    {
        try {
            $brand = $this->brands::where([
                ['brand_active', '=', 1]
            ])->get();

            if (!$brand) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy hãng xe",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy hãng xe thành công",
                'httpCode' => 200,
                'data' => ['brand' => $brand],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy hãng xe", [
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

    public function getBrandsByID($id)
    {
        try {
            $brand = $this->brands::where([
                ['brand_code', '=', $id],
                ['brand_active', '=', 1]
            ])->get();

            if (!$brand) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy hãng xe",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy hãng xe thành công",
                'httpCode' => 200,
                'data' => ['brand' => $brand],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy hãng xe", [
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
