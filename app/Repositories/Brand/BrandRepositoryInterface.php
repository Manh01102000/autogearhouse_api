<?php

namespace App\Repositories\Brand;

interface BrandRepositoryInterface
{
    public function getBrands();
    public function getBrandsByID($id);
}
