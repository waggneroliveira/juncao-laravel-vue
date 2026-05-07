<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\CartCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected CartCalculationService $calculationService;

    public function __construct(
        OrderService $orderService,
        CartCalculationService $calculationService
    ) {
        $this->orderService = $orderService;
        $this->calculationService = $calculationService;
        $this->middleware('auth:client');
    }

    /**
     * Listar pedidos do cliente autenticado
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $filters = [
            'status' => $request->query('status'),
            'limit' => $request->query('limit', 10),
        ];

        $orders = $this->orderService->getClientOrders($client, $filters);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Criar um novo pedido
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();

        // Validar dados do pedido
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.customizations' => 'nullable|array',
            'items.*.combo_data' => 'nullable|array',
            'items.*.is_combo' => 'nullable|boolean',
            
            'delivery_method' => 'required|in:delivery,pickup,local',
            'payment_method' => 'required|in:credit_card,debit_card,pix,cash',
            'delivery_location_id' => 'nullable|integer|exists:delivery_locations,id',
            'delivery_address' => 'nullable|array',
            'coupon_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            // Validar que o carrinho não foi alterado (reconverter preços)
            $cartCalculation = $this->calculationService->calculateCart(
                $validated['items'],
                $validated['coupon_code'] ?? null
            );

            // Criar o pedido
            $order = $this->orderService->createOrder($client, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Pedido criado com sucesso!',
                'data' => [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'total' => $order->total,
                    'estimated_time' => '30-45 minutos',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Exibir um pedido específico
     */
    public function show(string $id)
    {
        $client = Auth::guard('client')->user();
        
        $order = $this->orderService->getOrder((int)$id, $client);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatOrder($order),
        ]);
    }

    /**
     * Atualizar status do pedido (admin apenas)
     */
    public function update(Request $request, string $id)
    {
        // Verificar se é admin (você pode implementar essa lógica)
        // if (!Auth::user()->isAdmin()) return response()->json(['error' => 'Unauthorized'], 403);

        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        try {
            $this->orderService->updateOrderStatus($order, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Status do pedido atualizado',
                'data' => $this->formatOrder($order),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Cancelar um pedido
     */
    public function destroy(string $id)
    {
        $client = Auth::guard('client')->user();
        
        $order = $this->orderService->getOrder((int)$id, $client);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->orderService->cancelOrder($order, 'Cancelado pelo cliente');

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Reorder - Recriar um pedido anterior
     */
    public function reorder(Request $request, string $orderId)
    {
        $client = Auth::guard('client')->user();
        
        $originalOrder = $this->orderService->getOrder((int)$orderId, $client);

        if (!$originalOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido anterior não encontrado',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $newOrder = $this->orderService->reorderFromPrevious($client, $originalOrder);

            return response()->json([
                'success' => true,
                'message' => 'Pedido criado a partir do anterior!',
                'data' => [
                    'order_id' => $newOrder->id,
                    'status' => $newOrder->status,
                    'total' => $newOrder->total,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Formatar pedido para resposta
     */
    private function formatOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'delivery_method' => $order->delivery_method,
            'payment_method' => $order->payment_method,
            'subtotal' => (float) $order->subtotal,
            'discount' => (float) $order->discount,
            'cashback' => (float) $order->cashback,
            'delivery_fee' => (float) $order->delivery_fee,
            'total' => (float) $order->total,
            'estimated_time' => $order->estimated_time,
            'notes' => $order->notes,
            'created_at' => $order->created_at,
            'completed_at' => $order->completed_at,
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'subtotal' => (float) $item->subtotal,
                    'is_combo' => $item->is_combo,
                    'customizations' => json_decode($item->customizations, true),
                ];
            })->toArray(),
            'coupon' => $order->coupon ? [
                'code' => $order->coupon->code,
                'discount_amount' => (float) $order->discount,
            ] : null,
        ];
    }
}
