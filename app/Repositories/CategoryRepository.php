<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAllActive()
    {
        // return Category::where('active', 1)->get();
        return Category::active()
        ->sorting()
        ->with([
            'products' => function ($query) {
                $query->where('active', 1)
                    ->orderBy('sorting', 'asc')
                    ->with([
                        'images',
                        'category',
                        'comboItems',
                        'optionGroups.options'
                    ]);
            }
        ])
        ->get();
    }
}
