<?php

namespace App\Repositories\ModelsProduct;

interface ModelProductsRepositoryInterface
{
    public function getModelProducts();
    public function getModelProductsByID($id);
}
