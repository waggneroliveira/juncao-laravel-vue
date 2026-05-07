<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Coupon;

class CartCalculationService
{
    /**
     * Calcula o preço final de um item do carrinho com todas as customizações
     * 
     * @param array $item Dados do item (product_id, quantity, customizations, combo_data)
     * @return array Cálculo detalhado do item
     */
    public function calculateItemPrice(array $item): array
    {
        $product = Product::findOrFail($item['product_id']);
        
        $basePrice = $product->price;
        $unitPrice = $basePrice;
        
        // Se for combo, usar lógica especial
        if ($item['is_combo'] ?? false) {
            $unitPrice = $this->calculateComboPrice($product, $item['combo_data'] ?? []);
        } else {
            // Adicionar customizações (tamanho, sabores, adicionais)
            $customizations = $item['customizations'] ?? [];
            $unitPrice += $this->sumCustomizationPrices($customizations);
        }
        
        $quantity = $item['quantity'] ?? 1;
        $subtotal = $unitPrice * $quantity;
        
        return [
            'base_price' => $basePrice,
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }

    /**
     * Calcula o preço de um combo baseado nas seleções do usuário
     * 
     * @param Product $combo O produto tipo combo
     * @param array $comboData Dados das seleções do combo
     * @return float Preço final do combo
     */
    public function calculateComboPrice(Product $combo, array $comboData): float
    {
        $price = $combo->price; // Preço base do combo
        
        // Adiciona adicionais do combo
        if (isset($comboData['addons'])) {
            foreach ($comboData['addons'] as $addonId => $quantity) {
                // Buscar preço do addon
                $addon = $combo->comboAddons()->find($addonId);
                if ($addon) {
                    $price += ($addon->pivot->price ?? 0) * $quantity;
                }
            }
        }
        
        // Adiciona customizações dos itens do combo
        if (isset($comboData['item_customizations'])) {
            foreach ($comboData['item_customizations'] as $itemId => $customization) {
                $price += $this->sumCustomizationPrices($customization);
            }
        }
        
        return $price;
    }

    /**
     * Soma os preços das customizações (tamanho, sabores, adicionais)
     * 
     * @param array $customizations Array com as customizações
     * @return float Valor total das customizações
     */
    private function sumCustomizationPrices(array $customizations): float
    {
        $total = 0;
        
        // Adiciona tamanho
        if (isset($customizations['size']) && is_array($customizations['size'])) {
            $total += (float) ($customizations['size']['price'] ?? 0);
        }
        
        // Adiciona sabores
        if (isset($customizations['flavors']) && is_array($customizations['flavors'])) {
            foreach ($customizations['flavors'] as $flavor) {
                $total += (float) ($flavor['price'] ?? 0);
            }
        }
        
        // Adiciona adicionais
        if (isset($customizations['additionals']) && is_array($customizations['additionals'])) {
            foreach ($customizations['additionals'] as $additional) {
                $quantity = $additional['quantity'] ?? 1;
                $total += ((float) ($additional['price'] ?? 0)) * $quantity;
            }
        }
        
        return $total;
    }

    /**
     * Calcula o resumo completo do carrinho
     * 
     * @param array $items Todos os itens do carrinho
     * @param string|null $couponCode Código do cupom (opcional)
     * @return array Resumo com subtotal, desconto, total, etc
     */
    public function calculateCart(array $items, ?string $couponCode = null): array
    {
        $subtotal = 0;
        $itemsDetails = [];
        
        // Calcular preço de cada item
        foreach ($items as $item) {
            $itemCalculation = $this->calculateItemPrice($item);
            $itemsDetails[] = [
                'product_id' => $item['product_id'],
                'quantity' => $itemCalculation['quantity'],
                'unit_price' => $itemCalculation['unit_price'],
                'subtotal' => $itemCalculation['subtotal'],
            ];
            $subtotal += $itemCalculation['subtotal'];
        }
        
        // Calcular desconto (cupom)
        $discount = 0;
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->valid()->first();
            if ($coupon) {
                $discount = $this->calculateDiscount($subtotal, $coupon);
            }
        }
        
        // Calcular cashback
        $cashback = $this->calculateCashback($subtotal);
        
        // Total final
        $total = $subtotal - $discount;
        
        return [
            'items' => $itemsDetails,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'cashback' => round($cashback, 2),
            'delivery_fee' => 0, // Será calculado separadamente
            'total' => round($total, 2),
            'coupon' => $coupon ? [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $discount,
            ] : null,
        ];
    }

    /**
     * Calcula o desconto baseado em um cupom
     * 
     * @param float $subtotal Subtotal antes do desconto
     * @param Coupon $coupon O cupom
     * @return float Valor do desconto
     */
    public function calculateDiscount(float $subtotal, Coupon $coupon): float
    {
        // Verificar se atende o valor mínimo
        if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
            return 0;
        }
        
        $discount = 0;
        
        if ($coupon->type === 'percentage') {
            $discount = ($subtotal * $coupon->value) / 100;
            
            // Aplicar limite máximo se existir
            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        } else if ($coupon->type === 'fixed') {
            $discount = min($coupon->value, $subtotal);
        }
        
        return max(0, $discount); // Garantir que nunca seja negativo
    }

    /**
     * Calcula cashback (recompensa em dinheiro)
     * 
     * @param float $subtotal Subtotal
     * @return float Valor do cashback (percentual configurável)
     */
    public function calculateCashback(float $subtotal): float
    {
        // 2% de cashback padrão
        $cashbackPercentage = config('orders.cashback_percentage', 2);
        return ($subtotal * $cashbackPercentage) / 100;
    }

    /**
     * Valida se um cupom é válido
     * 
     * @param string $code Código do cupom
     * @return array ['valid' => bool, 'message' => string, 'coupon' => Coupon|null]
     */
    public function validateCoupon(string $code): array
    {
        $coupon = Coupon::where('code', $code)->first();
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Cupom não encontrado',
                'coupon' => null,
            ];
        }
        
        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'Cupom desativado',
                'coupon' => null,
            ];
        }
        
        if ($coupon->valid_from && $coupon->valid_from > now()) {
            return [
                'valid' => false,
                'message' => 'Cupom ainda não é válido',
                'coupon' => null,
            ];
        }
        
        if ($coupon->valid_until && $coupon->valid_until < now()) {
            return [
                'valid' => false,
                'message' => 'Cupom expirou',
                'coupon' => null,
            ];
        }
        
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Cupom atingiu o limite de usos',
                'coupon' => null,
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Cupom válido',
            'coupon' => $coupon,
            'discount_type' => $coupon->type,
            'discount_value' => $coupon->value,
        ];
    }
}
