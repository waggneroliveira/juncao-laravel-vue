<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAllActiveWithRelations()
    {
        return Product::with([
            'category', 
            'images', 
            'optionGroups', 
            'comboItems'
            ])
            ->where('active', 1)
            ->get();
    }
}
