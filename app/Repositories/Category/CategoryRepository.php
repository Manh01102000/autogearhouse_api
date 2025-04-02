<?php

namespace App\Repositories\Category;
// Interface
use App\Repositories\Category\CategoryRepositoryInterface;
// Model
use App\Models\category;
// Import db transaction
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        try {
            $category = $this->category::where([
                ['cat_parent_code', '=', 0],
                ['cat_active', '=', 1]
            ])->get();

            if (!$category) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy danh mục",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy danh mục thành công",
                'httpCode' => 200,
                'data' => ['category' => $category],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy danh mục", [
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

    public function getCategoryByID($id)
    {
        try {
            $category = $this->category::where([
                ['cat_parent_code', '=', $id],
                ['cat_active', '=', 1]
            ])->get();

            if (!$category) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy danh mục",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy danh mục thành công",
                'httpCode' => 200,
                'data' => ['category' => $category],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy danh mục", [
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

    public function getCategoryTree()
    {
        try {
            $datacategory = $this->category::where([
                ['cat_active', '=', 1]
            ])->get();

            // Chuyển danh sách danh mục thành cây
            $categoryTree = $this->buildCategoryTree($datacategory);

            if (empty($categoryTree)) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy danh mục",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => "Lấy danh mục thành công",
                'httpCode' => 200,
                'data' => ['category' => $categoryTree],
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy danh mục", [
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

    private function buildCategoryTree($categories, $parentCode = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['cat_parent_code'] == $parentCode) {
                $children = $this->buildCategoryTree($categories, $category['cat_code']);
                if (!empty($children)) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }
}
