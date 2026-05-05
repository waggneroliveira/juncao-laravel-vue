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
                [
                    'name' => 'Hambúrguer Clássico',
                    'description' => '4 smash burgers...',
                    'price' => 109.90,
                    'oldPrice' => 119.90,
                    'category' => 'hamburguers',
                    'productType' => 'food',
                    'customization' => [
                        'toppings' => [
                            ['name' => 'Queijo Extra', 'price' => 3.5],
                            ['name' => 'Bacon Extra', 'price' => 4]
                        ]
                    ]
                ],

                [
                    'name' => 'Combo Yakisoba',
                    'price' => 59.90,
                    'category' => 'combos',
                    'productType' => 'combo',
                    'comboItems' => [
                        [
                            'name' => 'Refrigerante',
                            'options' => [
                                'type' => 'select',
                                'choices' => [
                                    ['name' => 'Kuat'],
                                    ['name' => 'Pepsi']
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            foreach ($products as $p) {

                // 🧠 CATEGORY (com slug único)
                $categoryName = $p['category'] ?? 'geral';

                $categorySlug = $this->uniqueSlug(Category::class, $categoryName);

                $category = Category::firstOrCreate(
                    ['slug' => $categorySlug],
                    ['name' => $categoryName]
                );

                // 🧠 PRODUCT (com slug)
                $product = Product::create([
                    'name' => $p['name'],
                    'slug' => $this->uniqueSlug(Product::class, $p['name']),

                    'description' => $p['description'] ?? null,
                    'price' => $p['price'],
                    'old_price' => $p['oldPrice'] ?? null,

                    'category_id' => $category->id,
                    'product_type' => $p['productType'],

                    'is_combo' => $p['productType'] === 'combo'
                ]);

                // 📸 IMAGEM PADRÃO
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => '/images/default.png'
                ]);

                // 📦 STOCK
                ProductStock::create([
                    'product_id' => $product->id,
                    'available' => true,
                    'quantity' => 50
                ]);

                // 🔧 CUSTOMIZAÇÃO
                if (!empty($p['customization']['toppings'])) {

                    $group = ProductOptionGroup::create([
                        'product_id' => $product->id,
                        'name' => 'Adicionais',
                        'type' => 'checkbox',
                        'required' => false
                    ]);

                    foreach ($p['customization']['toppings'] as $t) {
                        ProductOption::create([
                            'option_group_id' => $group->id,
                            'name' => $t['name'],
                            'price' => $t['price']
                        ]);
                    }
                }

                // 🍱 COMBOS
                if (!empty($p['comboItems'])) {

                    foreach ($p['comboItems'] as $item) {

                        $comboItem = ComboItem::create([
                            'product_id' => $product->id,
                            'name' => $item['name'],
                            'item_key' => Str::slug($item['name'])
                        ]);

                        if (!empty($item['options'])) {

                            $group = ProductOptionGroup::create([
                                'combo_item_id' => $comboItem->id,
                                'name' => $item['options']['title'] ?? $item['name'],
                                'type' => $item['options']['type'],
                                'required' => true
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
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    /**
     * 🔥 SLUG ÚNICO (EVITA DUPLICAÇÃO)
     */
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