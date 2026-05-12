<?php
// app/Traits/ProductTransformer.php

namespace App\Traits;

trait ProductTransformer
{
    /**
     * Transforma um produto para o formato esperado pelo frontend
     */
    protected function transformProduct($product)
    {
        if ($product->is_combo) {
            return $this->transformComboProduct($product);
        }
        
        return $this->transformNormalProduct($product);
    }
    
    /**
     * Transforma produtos normais (não combo)
     */
    protected function transformNormalProduct($product)
    {
        // Busca os grupos do banco
        $sizeGroup = null;
        $flavorGroup = null;
        $toppingGroups = [];
        $otherGroups = [];
        
        foreach ($product->optionGroups as $group) {
            $groupName = strtolower($group->name);
            
            // Força o tipo correto baseado no nome
            if (str_contains($groupName, 'size') || str_contains($groupName, 'tamanho')) {
                $group->type = 'radio';
                $sizeGroup = $group;
            } elseif (str_contains($groupName, 'flavor') || str_contains($groupName, 'sabor')) {
                $flavorGroup = $group;
            } elseif (str_contains($groupName, 'topping') || str_contains($groupName, 'adicional')) {
                $toppingGroups[] = $group;
            } else {
                $otherGroups[] = $group;
            }
        }
        
        // ⭐ MONTA O option_groups PARA O MODAL
        $optionGroups = [];
        
        // Adiciona grupo de tamanhos (como radio)
        if ($sizeGroup) {
            $optionGroups[] = [
                'id' => $sizeGroup->id,
                'name' => $sizeGroup->name,
                'type' => 'radio',
                'required' => true,
                'max_selections' => 1,
                'options' => $sizeGroup->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'price' => (float) $option->price,
                        'description' => $option->description,
                        'is_default' => (bool) $option->is_default,
                        'max_quantity' => $option->max_quantity ?? 99
                    ];
                })
            ];
        }
        
        // Adiciona grupo de sabores (como checkbox)
        if ($flavorGroup) {
            $optionGroups[] = [
                'id' => $flavorGroup->id,
                'name' => $flavorGroup->name,
                'type' => 'checkbox',
                'required' => false,
                'max_selections' => $flavorGroup->max_selections ?? 2,
                'options' => $flavorGroup->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'price' => (float) $option->price,
                        'description' => $option->description,
                        'is_default' => (bool) $option->is_default,
                        'max_quantity' => $option->max_quantity ?? 99
                    ];
                })
            ];
        }
        
        // Adiciona grupos de adicionais (como checkbox)
        foreach ($toppingGroups as $group) {
            $optionGroups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => 'checkbox',
                'required' => false,
                'max_selections' => null,
                'options' => $group->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'price' => (float) $option->price,
                        'description' => $option->description,
                        'is_default' => (bool) $option->is_default,
                        'max_quantity' => $option->max_quantity ?? 99
                    ];
                })
            ];
        }
        
        // Adiciona outros grupos
        foreach ($otherGroups as $group) {
            $optionGroups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => $group->type,
                'required' => (bool) $group->required,
                'max_selections' => $group->max_selections,
                'options' => $group->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'price' => (float) $option->price,
                        'description' => $option->description,
                        'is_default' => (bool) $option->is_default,
                        'max_quantity' => $option->max_quantity ?? 99
                    ];
                })
            ];
        }
        
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'old_price' => $product->old_price ? (float) $product->old_price : null,
            'cashback' => (float) $product->cashback,
            'is_combo' => false,
            'savings' => null,
            'product_type' => $product->product_type,
            'cuisine_type' => $product->cuisine_type,
            'featured' => (bool) $product->featured,
            'active' => (bool) $product->active,
            'highlights' => (bool) $product->highlights,
            'sorting' => $product->sorting,
            'tags' => $product->tags,
            'specifications' => $product->specifications,
            
            'path_image' => $product->path_image ? $product->path_image : asset('build/admin/images/products/product-1.png'),
            'images' => $product->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('build/admin/images/products/product-1.png')
                ];
            }),
            
            'stock' => $product->stock ? [
                'id' => $product->stock->id,
                'quantity' => (int) $product->stock->quantity,
                'min_quantity' => (int) $product->stock->min_quantity,
                'max_quantity' => (int) $product->stock->max_quantity,
            ] : null,
            
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug
            ] : null,
            
            // ⭐ AGORA POPULADO!
            'option_groups' => $optionGroups,
            
            // Mantém customization para compatibilidade
            'customization' => [
                'hasSize' => !is_null($sizeGroup),
                'sizes' => $sizeGroup ? $this->transformOptionsToArray($sizeGroup->options) : [],
                'hasFlavors' => !is_null($flavorGroup),
                'flavors' => $flavorGroup ? $this->transformOptionsToArray($flavorGroup->options) : [],
                'maxFlavors' => $flavorGroup ? ($flavorGroup->max_selections ?? 2) : 0,
                'hasToppings' => count($toppingGroups) > 0,
                'toppings' => collect($toppingGroups)->flatMap(function($group) {
                    return $this->transformOptionsToArray($group->options);
                })->values()->all()
            ],
            
            'combo_items' => [],
            'combo_addons' => [],
            'isEditing' => false,
            'cartItemId' => null,
        ];
    }
    
    /**
     * Transforma produtos combo
     */
    protected function transformComboProduct($product)
    {
        $comboAddonGroups = [];
        foreach ($product->optionGroups as $group) {
            if (!$group->combo_item_id) {
                $comboAddonGroups[] = $group;
            }
        }
        
        $optionGroups = [];
        foreach ($comboAddonGroups as $group) {
            $optionGroups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => $group->type,
                'required' => (bool) $group->required,
                'max_selections' => $group->max_selections,
                'options' => $group->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'price' => (float) $option->price,
                        'description' => $option->description,
                        'is_default' => (bool) $option->is_default,
                        'max_quantity' => $option->max_quantity ?? 99,
                        'stock' => $option->max_quantity ?? 99
                    ];
                })
            ];
        }
        
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'old_price' => $product->old_price ? (float) $product->old_price : null,
            'cashback' => (float) $product->cashback,
            'is_combo' => true,
            'savings' => $product->old_price ? (float) ($product->old_price - $product->price) : null,
            'product_type' => $product->product_type,
            'cuisine_type' => $product->cuisine_type,
            'featured' => (bool) $product->featured,
            'active' => (bool) $product->active,
            'highlights' => (bool) $product->highlights,
            'sorting' => $product->sorting,
            'tags' => $product->tags,
            'specifications' => $product->specifications,
            
            'path_image' => $product->path_image ? $product->path_image : asset('build/admin/images/products/product-1.png'),
            'images' => $product->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('build/admin/images/products/product-1.png')
                ];
            }),
            
            'stock' => $product->stock ? [
                'id' => $product->stock->id,
                'quantity' => (int) $product->stock->quantity,
                'min_quantity' => (int) $product->stock->min_quantity,
                'max_quantity' => (int) $product->stock->max_quantity,
            ] : null,
            
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug
            ] : null,
            
            'option_groups' => $optionGroups,
            'customization' => null,
            'combo_items' => $this->transformComboItems($product),
            'combo_addons' => $this->transformComboAddonsToArray($comboAddonGroups),
            'isEditing' => false,
            'cartItemId' => null,
        ];
    }
    
    protected function transformComboItems($product)
    {
        $comboItems = [];
        
        foreach ($product->comboItems as $item) {
            $comboItem = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'required' => (bool) $item->required,
                'price' => $item->price ? (float) $item->price : null,
                'item_key' => $item->item_key ?? null,
                'options' => null // Se tiver múltiplos, pode ser um array
            ];
            
            // Se tiver múltiplos grupos de opções
            if ($item->optionGroups->count() > 0) {
                // Pega o primeiro grupo (ou pode adaptar para múltiplos)
                $firstGroup = $item->optionGroups->first();
                
                if ($firstGroup && $firstGroup->options->count() > 0) {
                    $comboItem['options'] = [
                        'id' => $firstGroup->id,
                        'type' => $firstGroup->type,
                        'title' => $firstGroup->name,
                        'required' => (bool) $firstGroup->required,
                        'maxSelections' => $firstGroup->max_selections,
                        'choices' => $firstGroup->options->map(function($option) {
                            return [
                                'id' => $option->id,
                                'name' => $option->name,
                                'price' => (float) $option->price,
                                'description' => $option->description,
                                'default' => (bool) $option->is_default,
                                'maxPerOption' => $option->max_quantity
                            ];
                        })
                    ];
                }
            }
            
            $comboItems[] = $comboItem;
        }
        
        return $comboItems;
    }
    
    protected function transformOptionsToArray($options)
    {
        return $options->map(function($option) {
            return [
                'id' => $option->id,
                'name' => $option->name,
                'price' => (float) $option->price,
                'description' => $option->description,
                'is_default' => (bool) $option->is_default,
                'isRecommended' => (bool) $option->is_default,
                'stock' => $option->max_quantity ?? 99,
                'max_quantity' => $option->max_quantity ?? 99
            ];
        });
    }
    
    protected function transformComboAddonsToArray($groups)
    {
        $addons = [];
        foreach ($groups as $group) {
            foreach ($group->options as $option) {
                $addons[] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => (float) $option->price,
                    'max_quantity' => $option->max_quantity ?? 99
                ];
            }
        }
        return $addons;
    }
    
    protected function transformProducts($products)
    {
        return $products->map(function($product) {
            return $this->transformProduct($product);
        });
    }
}