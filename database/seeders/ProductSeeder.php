<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOptionGroup;
use App\Models\ProductOption;
use App\Models\ProductStock;
use App\Models\ComboItem;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {

            $products = [

                // =========================
                // 🍔 HAMBÚRGUER
                // =========================
                [
                    'name' => 'Hambúrguer Clássico',
                    'description' => '4 smash burgers com 400g de fritas, creme de cheddar com bacon + Refrigerante',
                    'price' => 109.90,
                    'oldPrice' => 119.90,
                    'cashback' => 5,
                    'category' => 'Hamburguers',
                    'productType' => 'food',
                    'isCombo' => false,

                    'customization' => [
                        'toppings' => [
                            ['name' => 'Queijo Extra', 'price' => 3.50],
                            ['name' => 'Bacon Extra', 'price' => 4.00],
                            ['name' => 'Cheddar', 'price' => 3.00],
                            ['name' => 'Molho Especial', 'price' => 2.00],
                        ],
                        'spiciness' => [
                            ['name' => 'Sem Pimenta', 'price' => 0],
                            ['name' => 'Leve', 'price' => 0],
                            ['name' => 'Médio', 'price' => 0],
                            ['name' => 'Picante', 'price' => 0],
                        ]
                    ],

                    'stock' => ['quantity' => 50]
                ],

                // =========================
                // 🍕 PIZZA
                // =========================
                [
                    'name' => 'Pizza Portuguesa',
                    'description' => 'Molho especial, presunto, ovos, cebola, azeitona e queijo mussarela',
                    'price' => 45.90,
                    'oldPrice' => 0,
                    'cashback' => 8,
                    'category' => 'Pizzas',
                    'productType' => 'food',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => 'Pequena', 'price' => 45.90],
                            ['name' => 'Média', 'price' => 59.90],
                            ['name' => 'Grande', 'price' => 79.90],
                        ],
                        'flavors' => [
                            ['name' => 'Portuguesa', 'price' => 0, 'is_default' => true],
                            ['name' => 'Calabresa', 'price' => 5.00],
                            ['name' => 'Frango com Catupiry', 'price' => 8.00],
                            ['name' => 'Margherita', 'price' => 3.00],
                        ],
                        'toppings' => [
                            ['name' => 'Queijo Extra', 'price' => 4.00],
                            ['name' => 'Orégano', 'price' => 0],
                            ['name' => 'Azeitona Extra', 'price' => 2.00],
                        ]
                    ],

                    'stock' => ['quantity' => 30]
                ],

                // =========================
                // 🍱 COMBO YAKISOBA
                // =========================
                [
                    'name' => 'Combo Yakisoba Completo',
                    'description' => 'Yakisoba + Refrigerante + 4 Rolinhos',
                    'price' => 59.90,
                    'oldPrice' => 89.90,
                    'cashback' => 10,
                    'category' => 'Combos',
                    'productType' => 'combo',
                    'isCombo' => true,

                    'comboItems' => [
                        [
                            'name' => 'Yakisoba',
                            'quantity' => 1,
                        ],
                        [
                            'name' => 'Refrigerante',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Kuat', 'price' => 0],
                                    ['name' => 'Fanta Laranja', 'price' => 0],
                                    ['name' => 'Fanta Uva', 'price' => 0],
                                    ['name' => 'Pepsi', 'price' => 0],
                                    ['name' => 'Guaraná', 'price' => 0],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Rolinhos',
                            'quantity' => 4,
                            'options' => [
                                'type' => 'multicheckbox',
                                'maxSelections' => 4,
                                'choices' => [
                                    ['name' => 'Queijo Misto', 'price' => 0],
                                    ['name' => 'Romeu e Julieta', 'price' => 0],
                                    ['name' => 'Carne', 'price' => 0],
                                    ['name' => 'Frango', 'price' => 0],
                                    ['name' => 'Legumes', 'price' => 0],
                                ]
                            ]
                        ]
                    ],

                    'comboAddons' => [
                        ['name' => 'Hashi', 'price' => 1.00],
                        ['name' => 'Molho Especial', 'price' => 2.00],
                    ],

                    'stock' => ['quantity' => 20]
                ],

                // =========================
                // 🍕 COMBO PIZZA
                // =========================
                [
                    'name' => 'Combo Pizza Especial',
                    'description' => 'Pizza Média + Refrigerante 1L + Sobremesa',
                    'price' => 69.90,
                    'oldPrice' => 99.90,
                    'cashback' => 8,
                    'category' => 'Combos',
                    'productType' => 'combo',
                    'isCombo' => true,

                    'comboItems' => [
                        [
                            'name' => 'Pizza',
                            'options' => [
                                'type' => 'multicheckbox',
                                'maxSelections' => 2,
                                'choices' => [
                                    ['name' => 'Portuguesa', 'price' => 0, 'is_default' => true],
                                    ['name' => 'Calabresa', 'price' => 0],
                                    ['name' => 'Frango com Catupiry', 'price' => 0],
                                    ['name' => 'Margherita', 'price' => 0],
                                    ['name' => 'Quatro Queijos', 'price' => 5.00],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Refrigerante',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Coca-Cola', 'price' => 0, 'is_default' => true],
                                    ['name' => 'Coca-Cola Zero', 'price' => 0],
                                    ['name' => 'Guaraná', 'price' => 0],
                                    ['name' => 'Pepsi', 'price' => 0],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Sobremesa',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Pudim', 'price' => 0, 'is_default' => true],
                                    ['name' => 'Torta de Limão', 'price' => 3.00],
                                    ['name' => 'Brownie', 'price' => 4.00],
                                    ['name' => 'Sorvete', 'price' => 2.00],
                                ]
                            ]
                        ]
                    ],

                    'comboAddons' => [
                        ['name' => 'Molho Especial', 'price' => 2.00],
                        ['name' => 'Queijo Extra', 'price' => 3.00],
                        ['name' => 'Borda Recheada', 'price' => 5.00],
                    ],

                    'stock' => ['quantity' => 15]
                ],

                // =========================
                // 🍔 COMBO BURGER
                // =========================
                [
                    'name' => 'Combo Burger',
                    'description' => 'Hambúrguer + Acompanhamento + Bebida',
                    'price' => 39.90,
                    'oldPrice' => 59.90,
                    'cashback' => 5,
                    'category' => 'Combos',
                    'productType' => 'combo',
                    'isCombo' => true,

                    'comboItems' => [
                        [
                            'name' => 'Hambúrguer Artesanal',
                            'quantity' => 1,
                        ],
                        [
                            'name' => 'Acompanhamento',
                            'options' => [
                                'type' => 'radio',
                                'choices' => [
                                    ['name' => 'Batata Frita', 'price' => 0, 'is_default' => true],
                                    ['name' => 'Batata Rústica', 'price' => 2.00],
                                    ['name' => 'Onion Rings', 'price' => 3.00],
                                    ['name' => 'Salada', 'price' => 1.00],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Bebida',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Coca-Cola', 'price' => 0, 'is_default' => true],
                                    ['name' => 'Guaraná', 'price' => 0],
                                    ['name' => 'Suco Natural', 'price' => 2.00],
                                    ['name' => 'Água', 'price' => 0],
                                ]
                            ]
                        ]
                    ],

                    'comboAddons' => [
                        ['name' => 'Bacon Extra', 'price' => 3.00],
                        ['name' => 'Queijo Extra', 'price' => 2.00],
                        ['name' => 'Ovo', 'price' => 2.00],
                    ],

                    'stock' => ['quantity' => 30]
                ],

                // =========================
                // 🍧 AÇAÍ
                // =========================
                [
                    'name' => 'Açaí Tradicional',
                    'description' => 'Açaí puro da Amazônia, sem xarope, acompanha granola',
                    'price' => 19.90,
                    'cashback' => 3,
                    'category' => 'Açaí',
                    'productType' => 'dessert',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => '300ml', 'price' => 19.90, 'is_default' => true],
                            ['name' => '500ml', 'price' => 27.90],
                            ['name' => '700ml', 'price' => 34.90],
                        ],
                        'toppings' => [
                            ['name' => 'Granola', 'price' => 2.00],
                            ['name' => 'Banana', 'price' => 1.50],
                            ['name' => 'Leite Condensado', 'price' => 2.50],
                            ['name' => 'Morango', 'price' => 2.00],
                            ['name' => 'Paçoca', 'price' => 2.00],
                        ]
                    ],

                    'stock' => ['quantity' => 100]
                ],

                // =========================
                // 🥤 Bedidas
                // =========================
                [
                    'name' => 'Coca-Cola 2L',
                    'description' => 'Refrigerante gelado',
                    'price' => 12.90,
                    'oldPrice' => 0,
                    'cashback' => 2,
                    'category' => 'Bedidas',
                    'productType' => 'beverage',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => '350ml', 'price' => 5.90],
                            ['name' => '600ml', 'price' => 8.90],
                            ['name' => '2L', 'price' => 12.90, 'is_default' => true],
                        ]
                    ],

                    'stock' => ['quantity' => 200]
                ],

                [
                    'name' => 'Coca-Cola 1L',
                    'description' => 'Refrigerante gelado',
                    'price' => 9.90,
                    'oldPrice' => 0,
                    'cashback' => 2,
                    'category' => 'Bedidas',
                    'productType' => 'beverage',
                    'isCombo' => false,

                    'stock' => ['quantity' => 200]
                ],

                // =========================
                // 🍨 SOBREMESA
                // =========================
                [
                    'name' => 'Petit Gateau',
                    'description' => 'Brownie quente com sorvete de creme e calda de chocolate',
                    'price' => 18.90,
                    'oldPrice' => 0,
                    'cashback' => 3,
                    'category' => 'Sobremesas',
                    'productType' => 'dessert',
                    'isCombo' => false,

                    'customization' => [
                        'toppings' => [
                            ['name' => 'Calda de Morango', 'price' => 2.00],
                            ['name' => 'Calda de Caramelo', 'price' => 2.00],
                            ['name' => 'Granulado', 'price' => 1.00],
                        ]
                    ],

                    'stock' => ['quantity' => 40]
                ],
            ];

            foreach ($products as $p) {

                // Cria ou busca a categoria
                $category = Category::updateOrCreate(
                    ['slug' => Str::slug($p['category'])],
                    ['name' => $p['category'], 'active' => 1, 'sorting' => 0]
                );

                // Cria o produto
                $product = Product::create([
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']),
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'old_price' => $p['oldPrice'] ?? null,
                    'cashback' => $p['cashback'] ?? 0,
                    'category_id' => $category->id,
                    'product_type' => $p['productType'],
                    'is_combo' => $p['isCombo'],
                    'active' => 1,
                    'featured' => $p['featured'] ?? false,
                    'highlights' => $p['highlights'] ?? false,
                    'sorting' => $p['sorting'] ?? 0
                ]);

                // Adiciona imagem padrão
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => ''
                ]);

                // Adiciona stock
                ProductStock::create([
                    'product_id' => $product->id,
                    'quantity' => $p['stock']['quantity'] ?? 50,
                    'available' => true
                ]);

                // ==========================================
                // CUSTOMIZATION - Produtos normais
                // ==========================================
                if (!empty($p['customization']) && !$p['isCombo']) {
                    foreach ($p['customization'] as $type => $items) {
                        // Define o tipo baseado no nome do grupo
                        $groupType = 'checkbox';
                        $maxSelections = null;
                        $required = false;
                        
                        $typeLower = strtolower($type);
                        
                        // Identifica o tipo correto
                        if (str_contains($typeLower, 'size') || str_contains($typeLower, 'tamanho')) {
                            $groupType = 'radio';
                            $required = true;
                            $maxSelections = 1;
                        } elseif (str_contains($typeLower, 'flavor') || str_contains($typeLower, 'sabor')) {
                            $groupType = 'checkbox';
                            $required = false;
                            $maxSelections = 2; // máximo de sabores
                        } elseif (str_contains($typeLower, 'topping') || str_contains($typeLower, 'adicional')) {
                            $groupType = 'checkbox';
                            $required = false;
                            $maxSelections = null;
                        }
                        
                        $group = ProductOptionGroup::create([
                            'product_id' => $product->id,
                            'combo_item_id' => null,
                            'name' => ucfirst($type),
                            'type' => $groupType,
                            'required' => $required,
                            'max_selections' => $maxSelections,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        foreach ($items as $item) {
                            ProductOption::create([
                                'option_group_id' => $group->id,
                                'name' => $item['name'],
                                'price' => $item['price'] ?? 0,
                                'is_default' => $item['is_default'] ?? false,
                                'max_quantity' => $item['max_quantity'] ?? null,
                                'description' => $item['description'] ?? null,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }

                // ==========================================
                // COMBOS - Itens do combo
                // ==========================================
                if (!empty($p['comboItems']) && $p['isCombo']) {
                    foreach ($p['comboItems'] as $item) {
                        $comboItem = ComboItem::create([
                            'product_id' => $product->id,
                            'name' => $item['name'],
                            'item_key' => Str::slug($item['name']),
                            'quantity' => $item['quantity'] ?? 1,
                            'required' => true,
                            // REMOVIDA A LINHA 'price' 
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        // Se o item tem opções (sabores, tamanhos, etc)
                        if (!empty($item['options'])) {
                            $optionType = $item['options']['type'];
                            $maxSelections = null;
                            
                            if ($optionType === 'multicheckbox') {
                                $optionType = 'checkbox';
                                $maxSelections = $item['options']['maxSelections'] ?? 4;
                            } elseif ($optionType === 'select') {
                                $maxSelections = 1;
                            } elseif ($optionType === 'radio') {
                                $maxSelections = 1;
                            }
                            
                            $group = ProductOptionGroup::create([
                                'product_id' => null,
                                'combo_item_id' => $comboItem->id,
                                'name' => $item['name'],
                                'type' => $optionType,
                                'required' => true,
                                'max_selections' => $maxSelections,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            
                            foreach ($item['options']['choices'] as $choice) {
                                ProductOption::create([
                                    'option_group_id' => $group->id,
                                    'name' => $choice['name'],
                                    'price' => $choice['price'] ?? 0,
                                    'is_default' => $choice['is_default'] ?? false,
                                    'max_quantity' => $choice['max_quantity'] ?? null,
                                    'description' => $choice['description'] ?? null,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }

                // ==========================================
                // COMBOS - Addons extras
                // ==========================================
                if (!empty($p['comboAddons']) && $p['isCombo']) {
                    $group = ProductOptionGroup::create([
                        'product_id' => $product->id,
                        'combo_item_id' => null,
                        'name' => 'Adicionais Extras',
                        'type' => 'checkbox',
                        'required' => false,
                        'max_selections' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    foreach ($p['comboAddons'] as $addon) {
                        ProductOption::create([
                            'option_group_id' => $group->id,
                            'name' => $addon['name'],
                            'price' => $addon['price'],
                            'is_default' => false,
                            'max_quantity' => $addon['max_quantity'] ?? 5,
                            'description' => $addon['description'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            DB::commit();
            $this->command->info('✅ Produtos criados com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erro ao criar produtos: ' . $e->getMessage());
            throw $e;
        }
    }
}