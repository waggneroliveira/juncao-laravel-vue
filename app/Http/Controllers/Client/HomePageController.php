<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\CategoryService;
use App\Services\CompanyStatusService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $companyService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService,
        CompanyStatusService $companyService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->companyService = $companyService;
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

    public function highlights()
    {
        $products = $this->productService->getHighlights();
        return response()->json($products);
    }

    public function company()
    {
        $company = $this->companyService->getPublicData();

        return response()->json($company);
    }
}
