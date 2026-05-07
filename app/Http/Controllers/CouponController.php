<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CartCalculationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    protected CartCalculationService $calculationService;

    public function __construct(CartCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Validar um cupom de desconto
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $validation = $this->calculationService->validateCoupon($request->input('code'));

        $statusCode = $validation['valid'] ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY;

        return response()->json([
            'success' => $validation['valid'],
            'message' => $validation['message'],
            'coupon' => $validation['coupon'] ? [
                'code' => $validation['coupon']->code,
                'type' => $validation['coupon']->type,
                'value' => (float) $validation['coupon']->value,
                'min_order_value' => $validation['coupon']->min_order_value ? (float) $validation['coupon']->min_order_value : null,
            ] : null,
        ], $statusCode);
    }

    /**
     * Listar cupons disponíveis (admin)
     */
    public function index()
    {
        // Implementar verificação de admin
        $coupons = Coupon::active()->get();

        return response()->json([
            'success' => true,
            'data' => $coupons,
        ]);
    }

    /**
     * Criar novo cupom (admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $coupon = Coupon::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cupom criado com sucesso',
            'data' => $coupon,
        ], Response::HTTP_CREATED);
    }

    /**
     * Exibir cupom específico
     */
    public function show(string $id)
    {
        $coupon = Coupon::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $coupon,
        ]);
    }

    /**
     * Atualizar cupom
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $coupon->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cupom atualizado com sucesso',
            'data' => $coupon,
        ]);
    }

    /**
     * Deletar cupom
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cupom deletado com sucesso',
        ]);
    }
}
