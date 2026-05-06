<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductOptionGroup;
use App\Traits\ProductTransformer;

class ProductRepository
{
    use ProductTransformer;
    
    public function getAllActiveWithRelations()
    {
        return Product::with([
            'category', 
            'images', 
            'optionGroups.options',
            'comboItems',
            'stock'
        ])
        ->where('active', 1)
        ->get()
        ->map(function($product) {
            return $this->transformProduct($product);
        });
    }
    
    // Se precisar de um produto específico
    public function findActiveWithRelations($id)
    {
        $product = Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems',
            'stock'
        ])
        ->where('active', 1)
        ->find($id);
        
        if (!$product) {
            return null;
        }
        
        return $this->transformProduct($product);
    }

    public function getHighlightsWithRelations()
    {
        return Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems',
            'stock'
        ])
        ->where('active', 1)
        ->where('highlights', 1)
        ->get()
        ->map(function($product) {
            return $this->transformProduct($product);
        });
    }
}