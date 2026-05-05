<?php
namespace App\Repositories;

use App\Models\Category;
use App\Traits\ProductTransformer;

class CategoryRepository
{
    use ProductTransformer;
    
    public function getAllActive()
    {
        $categories = Category::active()
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
        
        // Transforma os produtos dentro de cada categoria
        return $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'path_image' => $category->path_image,
                'active' => (bool) $category->active,
                'sorting' => $category->sorting,
                'products' => $this->transformProducts($category->products)
            ];
        });
    }
}