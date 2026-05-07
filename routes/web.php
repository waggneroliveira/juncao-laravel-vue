<?php

use App\Http\Controllers\Auth\AuthClientController;
use App\Http\Controllers\Auth\PasswordEmailClientController;
use App\Http\Controllers\Auth\ResetPasswordClientController;
use App\Http\Controllers\Client\BlogPageController;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Client\ContactPageController;
use App\Http\Controllers\Client\HomePageController;
use App\Http\Controllers\Client\NoticiesPageController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FormIndexController;
use App\Http\Controllers\NewsletterController;
use App\Http\Middleware\AuthClientMiddleware;
use App\Models\Announcement;
use App\Models\BlogCategory;
use App\Models\Contact;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

require __DIR__ . '/dashboard.php';

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('index');
});

// Rotas de autenticação
Route::post('login.do', [AuthClientController::class, 'authenticate'])->name('client.user.authenticate');

// Rotas de recuperação de senha
Route::post('/password/email', [PasswordEmailClientController::class, 'passwordEmail'])->name('client.password.email');
Route::get('/email-enviado-com-sucesso', [PasswordEmailClientController::class, 'showSuccess'])->name('send-success-client');
Route::post('/password/reset', [ResetPasswordClientController::class, 'processPasswordReset'])->name('client-password.update');
Route::get('password/reset/{token}', [ResetPasswordClientController::class, 'showResetForm'])->name('client.password.reset');
Route::get('/senha-alterada-com-sucesso', function () {
    return view('emails.password-success-client-reset');
})->name('client-success-reset-password');

// Rotas públicas do Identify
Route::prefix('identify')->group(function () {
    Route::post('/check', [AuthClientController::class, 'checkUser']);
    Route::post('/register', [AuthClientController::class, 'identifyRegister']);
    Route::post('/validate-user', [AuthClientController::class, 'validateUser']);
});

// Rotas autenticadas com AuthClientMiddleware (apenas logout e authenticate)
Route::middleware([AuthClientMiddleware::class])->group(function () {
    Route::post('/authenticate', [AuthClientController::class, 'identifyAuthenticate']);
    Route::get('logout', [AuthClientController::class, 'logout'])->name('client.user.logout');
});

// Rotas públicas da home
Route::get('/index', [HomePageController::class, 'index'])->name('index');

// Rotas API para produtos e categorias (públicas)
Route::get('/api/products', [HomePageController::class, 'products']);
Route::get('/api/categories', [HomePageController::class, 'categories']);
Route::get('/api/products/highlights', [HomePageController::class, 'highlights']);

// Rotas API para Pedidos e Cliente (autenticação necessária com guard client)
Route::middleware('auth:client')->group(function () {
    
    // Endereços do cliente
    Route::prefix('client')->group(function () {
        Route::get('/addresses', [ClientAddressController::class, 'index']);
        Route::post('/addresses', [ClientAddressController::class, 'store']);
        Route::put('/addresses/{id}', [ClientAddressController::class, 'update']);
        Route::delete('/addresses/{id}', [ClientAddressController::class, 'destroy']);
        Route::put('/addresses/{id}/primary', [ClientAddressController::class, 'setPrimary']);
    });
    
    // Dados do cliente autenticado
    Route::get('/client/data', [AuthClientController::class, 'getClientData']);
    
    // Cálculo do carrinho
    Route::post('/api/cart/calculate', function (\Illuminate\Http\Request $request, \App\Services\CartCalculationService $service) {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'coupon_code' => 'nullable|string',
            ]);
            
            $calculation = $service->calculateCart($validated['items'], $validated['coupon_code'] ?? null);
            
            return response()->json([
                'success' => true,
                'data' => $calculation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    });
    
    // Rotas de pedidos
    Route::apiResource('api/orders', \App\Http\Controllers\OrderController::class);
    Route::post('/api/orders/{id}/reorder', [\App\Http\Controllers\OrderController::class, 'reorder']);
    
    // Rotas de cupons
    Route::post('/api/coupons/validate', [\App\Http\Controllers\CouponController::class, 'validate']);
});

// Rotas de cupons (públicas para consulta)
Route::apiResource('api/coupons', \App\Http\Controllers\CouponController::class);