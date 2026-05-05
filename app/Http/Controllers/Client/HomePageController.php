<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('client.blades.app');
    }

    public function products()
    {
        $products = $this->productService->getAllActiveWithRelations();
        return response()->json($products);
    }

    public function categories()
    {
        $categories = $this->categoryService->getAllActive();
        return response()->json($categories);
    }

}
