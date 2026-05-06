<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllActiveWithRelations()
    {
        return $this->productRepository->getAllActiveWithRelations();
    }

    public function getHighlights()
    {
        return $this->productRepository->getHighlightsWithRelations();
    }

    public function getById($id)
    {
        return $this->productRepository->findActiveWithRelations($id);
    }
}
