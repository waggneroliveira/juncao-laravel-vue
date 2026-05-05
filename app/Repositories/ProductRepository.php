<?php

namespace App\Repositories;

use App\Models\Product;
use App\Traits\ProductTransformer;

class ProductRepository
{
    use ProductTransformer;
    
    public function getAllActiveWithRelations()
    {
        $products = Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems'
        ])
        ->where('active', 1)
        ->get();
        
        // Transforma os produtos antes de retornar
        return $this->transformProducts($products);
    }
    
    // Se precisar de um produto específico
    public function findActiveWithRelations($id)
    {
        $product = Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems'
        ])
        ->where('active', 1)
        ->find($id);
        
        if (!$product) {
            return null;
        }
        
        return $this->transformProduct($product);
    }
}