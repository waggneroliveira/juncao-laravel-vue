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

                // 🍔 HAMBÚRGUER
                [
                    'name' => 'Hambúrguer Clássico',
                    'description' => '4 smash burgers com 400g de fritas, creme de cheddar com bacon + Refrigerante',
                    'price' => 109.90,
                    'oldPrice' => 119.90,
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
                            'Sem Pimenta',
                            'Leve',
                            'Médio',
                            'Picante'
                        ]
                    ]
                ],

                // 🍕 PIZZA
                [
                    'name' => 'Pizza Portuguesa',
                    'description' => 'Molho especial, presunto, ovos, cebola, azeitona e queijo mussarela',
                    'price' => 45.90,
                    'category' => 'pizzas',
                    'productType' => 'food',
                    'customization' => [
                        'sizes' => [
                            ['name' => 'P', 'price' => 45.90],
                            ['name' => 'M', 'price' => 59.90],
                            ['name' => 'G', 'price' => 79.90],
                        ],
                        'flavors' => [
                            ['name' => 'Portuguesa', 'price' => 0],
                            ['name' => 'Calabresa', 'price' => 5],
                            ['name' => 'Frango com Catupiry', 'price' => 8],
                            ['name' => 'Margherita', 'price' => 3],
                        ],
                        'toppings' => [
                            ['name' => 'Queijo Extra', 'price' => 4],
                            ['name' => 'Orégano', 'price' => 0],
                            ['name' => 'Azeitona Extra', 'price' => 2],
                        ]
                    ]
                ],

                // 🍱 COMBO YAKISOBA
                [
                    'name' => 'Combo Yakisoba Completo',
                    'description' => 'Yakisoba + Refrigerante + 4 Rolinhos',
                    'price' => 59.90,
                    'oldPrice' => 89.90,
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
                            'name' => 'Rolinhos Primavera',
                            'quantity' => 4,
                            'options' => [
                                'type' => 'checkbox',
                                'maxSelections' => 4,
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

                    'addons' => [
                        ['name' => 'Hashi', 'price' => 1],
                        ['name' => 'Molho Especial', 'price' => 2],
                    ]
                ],

                // 🍨 AÇAÍ
                [
                    'name' => 'Açaí Tradicional',
                    'description' => 'Açaí puro da Amazônia, sem xarope, acompanha granola',
                    'price' => 19.90,
                    'category' => 'acai',
                    'productType' => 'dessert',
                    'customization' => [
                        'sizes' => [
                            ['name' => '300ml', 'price' => 19.90],
                            ['name' => '500ml', 'price' => 27.90],
                            ['name' => '700ml', 'price' => 34.90],
                        ],
                        'toppings' => [
                            ['name' => 'Granola', 'price' => 2],
                            ['name' => 'Banana', 'price' => 1.5],
                            ['name' => 'Leite Condensado', 'price' => 2.5],
                            ['name' => 'Morango', 'price' => 2],
                            ['name' => 'Paçoca', 'price' => 2],
                        ]
                    ]
                ],

                // 🥤 BEBIDAS
                [
                    'name' => 'Coca-Cola 2L',
                    'description' => 'Refrigerante gelado',
                    'price' => 12.90,
                    'category' => 'bebidas',
                    'productType' => 'beverage',
                    'customization' => [
                        'sizes' => [
                            ['name' => '350ml', 'price' => 5.90],
                            ['name' => '600ml', 'price' => 8.90],
                            ['name' => '2L', 'price' => 12.90],
                        ]
                    ]
                ],

                [
                    'name' => 'Coca-Cola 1L',
                    'description' => 'Refrigerante gelado',
                    'price' => 9.90,
                    'category' => 'bebidas',
                    'productType' => 'beverage',
                ],

            ];

            foreach ($products as $p) {

                // CATEGORY
                $category = Category::updateOrCreate(
                    ['slug' => Str::slug($p['category'])],
                    ['name' => $p['category'], 'active' => 1]
                );

                // PRODUCT
                $product = Product::create([
                    'name' => $p['name'],
                    'slug' => $this->uniqueSlug(Product::class, $p['name']),
                    'description' => $p['description'] ?? null,
                    'price' => $p['price'],
                    'old_price' => $p['oldPrice'] ?? null,
                    'category_id' => $category->id,
                    'product_type' => $p['productType'],
                    'is_combo' => $p['isCombo'] ?? false,
                    'active' => 1
                ]);

                // IMAGE
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => '/images/default.png'
                ]);

                // STOCK
                ProductStock::create([
                    'product_id' => $product->id,
                    'available' => true,
                    'quantity' => 50
                ]);

                // CUSTOMIZATION
                if (!empty($p['customization'])) {

                    foreach ($p['customization'] as $type => $items) {

                        $group = ProductOptionGroup::create([
                            'product_id' => $product->id,
                            'name' => ucfirst($type),
                            'type' => in_array($type, ['sizes']) ? 'radio' : 'checkbox',
                            'required' => false
                        ]);

                        foreach ($items as $item) {
                            ProductOption::create([
                                'option_group_id' => $group->id,
                                'name' => $item['name'] ?? $item,
                                'price' => $item['price'] ?? 0
                            ]);
                        }
                    }
                }

                // COMBO
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

                // ADDONS (tratado como option group separado)
                if (!empty($p['addons'])) {

                    $group = ProductOptionGroup::create([
                        'product_id' => $product->id,
                        'name' => 'Extras',
                        'type' => 'checkbox',
                        'required' => false
                    ]);

                    foreach ($p['addons'] as $addon) {
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

    private function uniqueSlug($model, $text)
    {
        $slug = Str::slug($text);
        $original = $slug;
        $count = 1;

        while ($model::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}