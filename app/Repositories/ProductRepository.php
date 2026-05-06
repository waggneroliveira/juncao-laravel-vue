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
            'optionGroups.options',
            'comboItems',
            'stock'
        ])
        ->where('active', 1)
        ->get();
        
        // Carrega opções dos combo items
        foreach ($products as $product) {
            if ($product->is_combo && $product->comboItems->count() > 0) {
                $comboItemIds = $product->comboItems->pluck('id')->toArray();
                $comboOptionGroups = ProductOptionGroup::with('options')
                    ->whereIn('combo_item_id', $comboItemIds)
                    ->get();
                
                // Adiciona aos optionGroups do produto
                $product->setRelation('optionGroups', $product->optionGroups->merge($comboOptionGroups));
            }
        }
        
        return $products->map(function($product) {
            return $this->transformProduct($product);
        });
    }
    
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
        
        // Carrega opções dos combo items
        if ($product->is_combo && $product->comboItems->count() > 0) {
            $comboItemIds = $product->comboItems->pluck('id')->toArray();
            $comboOptionGroups = ProductOptionGroup::with('options')
                ->whereIn('combo_item_id', $comboItemIds)
                ->get();
            
            $product->setRelation('optionGroups', $product->optionGroups->merge($comboOptionGroups));
        }
        
        return $this->transformProduct($product);
    }

    public function getHighlightsWithRelations()
    {
        $products = Product::with([
            'category', 
            'images', 
            'optionGroups.options', 
            'comboItems',
            'stock'
        ])
        ->where('active', 1)
        ->where('highlights', 1)
        ->get();
        
        // Carrega opções dos combo items
        foreach ($products as $product) {
            if ($product->is_combo && $product->comboItems->count() > 0) {
                $comboItemIds = $product->comboItems->pluck('id')->toArray();
                $comboOptionGroups = ProductOptionGroup::with('options')
                    ->whereIn('combo_item_id', $comboItemIds)
                    ->get();
                
                $product->setRelation('optionGroups', $product->optionGroups->merge($comboOptionGroups));
            }
        }
        
        return $products->map(function($product) {
            return $this->transformProduct($product);
        });
    }
}