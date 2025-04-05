<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\ModelsProduct\ModelProductsRepositoryInterface;
class ModelProductsController extends Controller
{
    protected $ModelProductsRepository;
    public function __construct(ModelProductsRepositoryInterface $ModelProductsRepository)
    {
        $this->ModelProductsRepository = $ModelProductsRepository;
    }

    public function getModelProducts()
    {
        try {
            $response = $this->ModelProductsRepository->getModelProducts();
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

    public function getModelProductsByID($id)
    {
        try {
            $response = $this->ModelProductsRepository->getModelProductsByID($id);
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
