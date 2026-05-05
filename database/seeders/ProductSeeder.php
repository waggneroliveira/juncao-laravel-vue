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
                    'category' => 'hamburguers',
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
                            ['name' => 'Sem Pimenta', 'level' => 0],
                            ['name' => 'Leve', 'level' => 1],
                            ['name' => 'Médio', 'level' => 2],
                            ['name' => 'Picante', 'level' => 3],
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
                    'category' => 'pizzas',
                    'productType' => 'food',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => 'P', 'price' => 45.90],
                            ['name' => 'M', 'price' => 59.90],
                            ['name' => 'G', 'price' => 79.90],
                        ],
                        'flavors' => [
                            ['name' => 'Portuguesa', 'price' => 0],
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
                    'category' => 'combos',
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
                                    ['name' => 'Kuat'],
                                    ['name' => 'Fanta Laranja'],
                                    ['name' => 'Fanta Uva'],
                                    ['name' => 'Pepsi'],
                                    ['name' => 'Guaraná'],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Rolinhos',
                            'quantity' => 4,
                            'options' => [
                                'type' => 'checkbox',
                                'choices' => [
                                    ['name' => 'Queijo Misto'],
                                    ['name' => 'Romeu e Julieta'],
                                    ['name' => 'Carne'],
                                    ['name' => 'Frango'],
                                    ['name' => 'Legumes'],
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
                    'category' => 'combos',
                    'productType' => 'combo',
                    'isCombo' => true,

                    'comboItems' => [
                        [
                            'name' => 'Pizza',
                            'options' => [
                                'type' => 'multicheckbox',
                                'choices' => [
                                    ['name' => 'Portuguesa'],
                                    ['name' => 'Calabresa'],
                                    ['name' => 'Frango com Catupiry'],
                                    ['name' => 'Margherita'],
                                    ['name' => 'Quatro Queijos'],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Refrigerante',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Coca-Cola'],
                                    ['name' => 'Zero'],
                                    ['name' => 'Guaraná'],
                                    ['name' => 'Pepsi'],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Sobremesa',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Pudim'],
                                    ['name' => 'Torta'],
                                    ['name' => 'Brownie'],
                                    ['name' => 'Sorvete'],
                                ]
                            ]
                        ]
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
                    'category' => 'combos',
                    'productType' => 'combo',
                    'isCombo' => true,

                    'comboItems' => [
                        ['name' => 'Hambúrguer Artesanal'],
                        [
                            'name' => 'Acompanhamento',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Batata Frita'],
                                    ['name' => 'Batata Rústica'],
                                    ['name' => 'Onion Rings'],
                                    ['name' => 'Salada'],
                                ]
                            ]
                        ],
                        [
                            'name' => 'Bebida',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Coca-Cola'],
                                    ['name' => 'Guaraná'],
                                    ['name' => 'Suco Natural'],
                                    ['name' => 'Água'],
                                ]
                            ]
                        ]
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
                    'category' => 'acai',
                    'productType' => 'dessert',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => '300ml', 'price' => 19.90],
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
                // 🥤 BEBIDAS
                // =========================
                [
                    'name' => 'Coca-Cola 2L',
                    'description' => 'Refrigerante gelado',
                    'price' => 12.90,
                    'category' => 'bebidas',
                    'productType' => 'beverage',
                    'isCombo' => false,

                    'customization' => [
                        'sizes' => [
                            ['name' => '350ml', 'price' => 5.90],
                            ['name' => '600ml', 'price' => 8.90],
                            ['name' => '2L', 'price' => 12.90],
                        ]
                    ],

                    'stock' => ['quantity' => 200]
                ],

                [
                    'name' => 'Coca-Cola 1L',
                    'description' => 'Refrigerante gelado',
                    'price' => 9.90,
                    'category' => 'bebidas',
                    'productType' => 'beverage',
                    'isCombo' => false,

                    'stock' => ['quantity' => 200]
                ],
            ];

            foreach ($products as $p) {

                $category = Category::updateOrCreate(
                    ['slug' => Str::slug($p['category'])],
                    ['name' => $p['category'], 'active' => 1]
                );

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
                    'active' => 1
                ]);

                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => '/images/default.png'
                ]);

                ProductStock::create([
                    'product_id' => $product->id,
                    'quantity' => $p['stock']['quantity'] ?? 50,
                    'available' => true
                ]);

                // =========================
                // CUSTOMIZATION
                // =========================
                if (!empty($p['customization'])) {

                    foreach ($p['customization'] as $type => $items) {

                        $group = ProductOptionGroup::create([
                            'product_id' => $product->id,
                            'name' => ucfirst($type),
                            'type' => 'checkbox',
                            'required' => false
                        ]);

                        foreach ($items as $item) {
                            ProductOption::create([
                                'option_group_id' => $group->id,
                                'name' => $item['name'],
                                'price' => $item['price'] ?? 0
                            ]);
                        }
                    }
                }

                // =========================
                // COMBOS
                // =========================
                if (!empty($p['comboItems'])) {

                    foreach ($p['comboItems'] as $item) {

                        $comboItem = ComboItem::create([
                            'product_id' => $product->id,
                            'name' => $item['name'],
                            'item_key' => Str::slug($item['name']),
                            'quantity' => $item['quantity'] ?? 1,
                            'required' => true
                        ]);

                        if (!empty($item['options'])) {

                            $group = ProductOptionGroup::create([
                                'combo_item_id' => $comboItem->id,
                                'name' => $item['name'],
                                'type' => $item['options']['type'],
                                'required' => true,
                                'max_selections' => $item['options']['maxSelections'] ?? 1
                            ]);

                            foreach ($item['options']['choices'] as $choice) {

                                ProductOption::create([
                                    'option_group_id' => $group->id,
                                    'name' => $choice['name'],
                                    'price' => $choice['price'] ?? 0
                                ]);
                            }
                        }
                    }
                }

                // =========================
                // ADDONS COMBO
                // =========================
                if (!empty($p['comboAddons'])) {

                    $group = ProductOptionGroup::create([
                        'product_id' => $product->id,
                        'name' => 'Addons',
                        'type' => 'checkbox',
                        'required' => false
                    ]);

                    foreach ($p['comboAddons'] as $addon) {
                        ProductOption::create([
                            'option_group_id' => $group->id,
                            'name' => $addon['name'],
                            'price' => $addon['price']
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}