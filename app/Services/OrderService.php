<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CartCalculationService $calculationService;

    public function __construct(CartCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Cria um novo pedido a partir dos dados do carrinho
     * 
     * @param Client $client Cliente fazendo o pedido
     * @param array $orderData Dados do pedido (items, payment_method, delivery_method, etc)
     * @return Order Pedido criado
     */
    public function createOrder(Client $client, array $orderData): Order
    {
        return DB::transaction(function () use ($client, $orderData) {
            // Calcular totalizações
            $cartCalculation = $this->calculationService->calculateCart(
                $orderData['items'] ?? [],
                $orderData['coupon_code'] ?? null
            );

            // Preparar dados do pedido
            $orderPayload = [
                'client_id' => $client->id,
                'status' => 'pending',
                'delivery_method' => $orderData['delivery_method'],
                'payment_method' => $orderData['payment_method'],
                'subtotal' => $cartCalculation['subtotal'],
                'discount' => $cartCalculation['discount'],
                'cashback' => $cartCalculation['cashback'],
                'delivery_fee' => $orderData['delivery_fee'] ?? 0,
                'total' => $cartCalculation['total'] + ($orderData['delivery_fee'] ?? 0),
                'notes' => $orderData['notes'] ?? null,
                'coupon_id' => $cartCalculation['coupon']['id'] ?? null,
                'delivery_location_id' => $orderData['delivery_location_id'] ?? null,
                'delivery_address' => $orderData['delivery_address'] ?? null,
            ];

            // Criar pedido
            $order = Order::create($orderPayload);

            // Criar itens do pedido
            foreach (($orderData['items'] ?? []) as $cartItem) {
                $itemCalculation = $this->calculationService->calculateItemPrice($cartItem);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'product_name' => $cartItem['product_name'] ?? Product::find($cartItem['product_id'])->name,
                    'base_price' => $itemCalculation['base_price'],
                    'unit_price' => $itemCalculation['unit_price'],
                    'quantity' => $itemCalculation['quantity'],
                    'subtotal' => $itemCalculation['subtotal'],
                    'customizations' => json_encode($cartItem['customizations'] ?? []),
                    'combo_data' => json_encode($cartItem['combo_data'] ?? null),
                    'is_combo' => $cartItem['is_combo'] ?? false,
                ]);
            }

            // Incrementar contador de uso do cupom
            if ($cartCalculation['coupon']) {
                $cartCalculation['coupon']->increment('usage_count');
            }

            return $order;
        });
    }

    /**
     * Busca pedidos de um cliente
     * 
     * @param Client $client
     * @param array $filters Filtros opcionais (status, limit, etc)
     * @return mixed Query resultado
     */
    public function getClientOrders(Client $client, array $filters = [])
    {
        $query = $client->orders()
            ->with(['items', 'coupon', 'deliveryLocation'])
            ->orderByDesc('created_at');

        // Filtrar por status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Paginação
        $limit = $filters['limit'] ?? 10;
        return $query->paginate($limit);
    }

    /**
     * Busca um pedido específico
     * 
     * @param int $orderId ID do pedido
     * @param Client|null $client Cliente (para validação)
     * @return Order|null
     */
    public function getOrder(int $orderId, ?Client $client = null): ?Order
    {
        $query = Order::with(['items', 'coupon', 'deliveryLocation']);

        if ($client) {
            $query->where('client_id', $client->id);
        }

        return $query->find($orderId);
    }

    /**
     * Atualiza o status de um pedido
     * 
     * @param Order $order
     * @param string $status Novo status
     * @return bool
     */
    public function updateOrderStatus(Order $order, string $status): bool
    {
        $validStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Status inválido: {$status}");
        }

        $order->status = $status;
        
        if ($status === 'delivered') {
            $order->completed_at = now();
        }

        return $order->save();
    }

    /**
     * Cancela um pedido
     * 
     * @param Order $order
     * @param string $reason Motivo do cancelamento
     * @return bool
     */
    public function cancelOrder(Order $order, string $reason = ''): bool
    {
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            throw new \InvalidArgumentException("Não é possível cancelar um pedido com status: {$order->status}");
        }

        $order->status = 'cancelled';
        $order->notes = ($order->notes ?? '') . "\n[Cancelado] {$reason}";

        return $order->save();
    }

    /**
     * Recria um pedido anterior (reorder)
     * 
     * @param Client $client
     * @param Order $originalOrder Pedido anterior a ser replicado
     * @return Order Novo pedido criado
     */
    public function reorderFromPrevious(Client $client, Order $originalOrder): Order
    {
        // Preparar dados para novo pedido
        $items = $originalOrder->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'customizations' => json_decode($item->customizations, true),
                'combo_data' => json_decode($item->combo_data, true),
                'is_combo' => $item->is_combo,
            ];
        })->toArray();

        $orderData = [
            'items' => $items,
            'delivery_method' => $originalOrder->delivery_method,
            'payment_method' => $originalOrder->payment_method,
            'delivery_location_id' => $originalOrder->delivery_location_id,
            'delivery_address' => $originalOrder->delivery_address,
        ];

        return $this->createOrder($client, $orderData);
    }
}
