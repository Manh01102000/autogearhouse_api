<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function searchProduct(array $data);
    // Lấy sản phẩm mới nhất
    public function getProductNew(array $data);
    // Lấy sản phẩm nổi bật
    public function getProductFeatured(array $data);
}
