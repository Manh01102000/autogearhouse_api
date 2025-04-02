<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// USER
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
// PRODUCT
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;
// CATEGORY
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }
}
