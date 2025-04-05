<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\Brand\BrandRepositoryInterface;
class BrandsController extends Controller
{
    protected $BrandRepository;
    public function __construct(BrandRepositoryInterface $BrandRepository)
    {
        $this->BrandRepository = $BrandRepository;
    }

    public function getBrands()
    {
        try {
            $response = $this->BrandRepository->getBrands();
            if ($response['success']) {
                return apiResponse("success", $response['message'], $response['data'], true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy danh mục: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function getBrandsByID($id)
    {
        try {
            $response = $this->BrandRepository->getBrandsByID($id);
            if ($response['success']) {
                return apiResponse("success", $response['message'], $response['data'], true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy danh mục: ' . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }
}
