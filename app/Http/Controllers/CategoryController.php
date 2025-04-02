<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\Category\CategoryRepositoryInterface;
class CategoryController extends Controller
{
    protected $CategoryRepository;
    public function __construct(CategoryRepositoryInterface $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }

    public function getCategory()
    {
        try {
            $response = $this->CategoryRepository->getCategory();
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

    public function getCategoryByID($id)
    {
        try {
            $response = $this->CategoryRepository->getCategoryByID($id);
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

    public function getCategoryTree()
    {
        try {
            $response = $this->CategoryRepository->getCategoryTree();
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
