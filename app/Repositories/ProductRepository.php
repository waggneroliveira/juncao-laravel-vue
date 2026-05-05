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
        $products = Product::with([
            'category', 
            'images', 
            'comboItems'
        ])
        ->where('active', 1)
        ->get();
        
        // Carrega os optionGroups para cada produto manualmente
        foreach ($products as $product) {
            if ($product->is_combo) {
                // Busca grupos do produto (addons) + grupos dos comboItems
                $product->setRelation('optionGroups', ProductOptionGroup::with('options')
                    ->where(function($query) use ($product) {
                        $query->where('product_id', $product->id)
                            ->orWhereIn('combo_item_id', $product->comboItems->pluck('id'));
                    })
                    ->get());
            } else {
                $product->load('optionGroups.options');
            }
        }
        
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

    public function getHighlightsWithRelations()
    {
        $products = Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems'
        ])
        ->where('active', 1)
        ->where('highlights', 1)
        ->get();

        return $this->transformProducts($products);
    }
}