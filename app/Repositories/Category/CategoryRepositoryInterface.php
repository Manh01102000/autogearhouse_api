<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface
{
    public function getCategory();
    public function getCategoryByID($id);
    public function getCategoryTree();
}
