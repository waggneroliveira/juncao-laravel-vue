<?php

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PopUpController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\StackController;
use App\Http\Controllers\TopicController;
use App\Repositories\AuditCountRepository;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NoticiesController;
use App\Repositories\SettingThemeRepository;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormIndexController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\SettingEmailController;
use App\Http\Controllers\SettingThemeController;
use App\Http\Controllers\AuditActivityController;
use App\Http\Controllers\StackSessionTitleController;
use App\Http\Controllers\Auth\PasswordEmailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DepoimentController;
use App\Http\Controllers\DepoimentSessionController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PlanCategoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanSectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSectionController;
use App\Models\PlanCategory;
use App\Models\PlanSection;

Route::get('control/', function () {
    return redirect()->route('admin.dashboard.painel');
});

Route::prefix('control/')->group(function () {
    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('admin.dashboard.painel');

    Route::get('/success-logout', function () {
        return view('admin.success.success-logout');
    })->name('success-logout');

    Route::post('login.do', [AuthController::class, 'authenticate'])
    ->name('admin.user.authenticate');

    /*=====================REDEFINICAO DE SENHA=========================*/

    // Rota para exibir o formulário "Esqueci a senha"
    Route::get('password/reset', function(){
        return view('admin.auth.recover-password');
    })->name('password.request');

    // Rota para processar o formulário "Esqueci a senha"
    Route::post('/password/email', [PasswordEmailController::class, 'passwordEmail'])
    ->name('password.email');

    // Rota para exibir o formulário de redefinição de senha
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
    
    // Rota para processar a redefinição de senha
    Route::post('/password/reset', [ResetPasswordController::class, 'processPasswordReset'])
    ->name('password.update');
    
    Route::get('/send-success', [PasswordEmailController::class, 'showSuccess'])
    ->name('send-success');

    Route::get('/password-success-reset', function () {
        return view('emails.password-success-reset');
    })->name('success-reset-password');

    /*=====================FINAL REDEFINICAO DE SENHA=========================*/

    Route::middleware([Authenticate::class])->group(function(){ 
        Route::get('documentation', function () {
            return view('admin.documentation.introduction');
        })->name('admin.dashboard.documentation.introduction');

        Route::get('/loading', function () {
            return view('admin.loadPage.loading');
        })->name('loading');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        //AUDITORIA
        Route::resource('auditorias', AuditActivityController::class)
        ->names('admin.dashboard.audit')
        ->parameters(['auditorias'=>'activitie']);
        Route::post('auditorias/{id}/mark-as-read', [AuditActivityController::class, 'markAsRead']);
        Route::post('/auditorias/mark-all-as-read', [AuditActivityController::class, 'markAllAsRead']);
        //CONTATO
        Route::resource('contato', ContactController::class)
        ->names('admin.dashboard.contact')
        ->parameters(['contato'=>'contact']);
        //NEWSLTTER
        Route::resource('newsletter', NewsletterController::class)
        ->names('admin.dashboard.newsletter')
        ->parameters(['newsletter'=>'newsletter']);
        Route::post('newsletter/delete', [NewsletterController::class, 'destroySelected'])
        ->name('admin.dashboard.newsletter.destroySelected');
        //SLIDES
        Route::resource('slides', SlideController::class)
        ->names('admin.dashboard.slide')
        ->parameters(['slides'=>'slide']);
        Route::post('slides/delete', [SlideController::class, 'destroySelected'])
        ->name('admin.dashboard.slide.destroySelected');
        Route::post('slides/sorting', [SlideController::class, 'sorting'])
        ->name('admin.dashboard.slide.sorting');
        //E-MAIL CONFIG
        Route::resource('configuracao-de-email', SettingEmailController::class)
        ->names('admin.dashboard.settingEmail')
        ->parameters(['configuracao-de-email' => 'settingEmail']);
        Route::post('configuracoes/smtp/verify', [SettingEmailController::class, 'smtpVerify'])->name('admin.dashboard.settingEmail.smtpVerify');
        //GRUPOS
        Route::resource('grupos', RoleController::class)
        ->names('admin.dashboard.group')
        ->parameters(['grupos' => 'role']);
        Route::post('grupos/delete', [RoleController::class, 'destroySelected'])
        ->name('admin.dashboard.group.destroySelected');
        //USUARIOS
        Route::resource('usuario', UserController::class)
        ->names('admin.dashboard.user')
        ->parameters(['usuario'=>'user']);
        Route::post('usuario/delete', [UserController::class, 'destroySelected'])
        ->name('admin.dashboard.user.destroySelected');
        Route::post('usuario/sorting', [UserController::class, 'sorting'])
        ->name('admin.dashboard.user.sorting');
        //PLAN CATEGORY
        Route::resource('categoria-de-plano', PlanCategoryController::class)
        ->names('admin.dashboard.planCategory')
        ->parameters(['categoria-de-plano'=>'planCategory']);
        Route::post('categoria-de-plano/delete', [PlanCategoryController::class, 'destroySelected'])
        ->name('admin.dashboard.planCategory.destroySelected');
        Route::post('categoria-de-plano/sorting', [PlanCategoryController::class, 'sorting'])
        ->name('admin.dashboard.planCategory.sorting');
        //PLAN SECTION
        Route::resource('sessao-planos', PlanSectionController::class)
        ->parameters(['sessao-planos' => 'planSection'])
        ->names('admin.dashboard.planSection');
        //PLAN
        Route::resource('planos', PlanController::class)
        ->parameters(['planos' => 'plan'])
        ->names('admin.dashboard.plan');
        Route::post('planos/delete', [PlanController::class, 'destroySelected'])
        ->name('admin.dashboard.plan.destroySelected');
        Route::post('planos/sorting', [PlanController::class, 'sorting'])
        ->name('admin.dashboard.plan.sorting');   
        //PRODUCT SECTION
        Route::resource('sessao-produtos', ProductSectionController::class)
        ->parameters(['sessao-produtos' => 'productSection'])
        ->names('admin.dashboard.productSection');     
        //PRODUCT
        Route::resource('produtos', ProductController::class)
        ->parameters(['produtos' => 'product'])
        ->names('admin.dashboard.product');
        Route::post('produtos/delete', [ProductController::class, 'destroySelected'])
        ->name('admin.dashboard.product.destroySelected');
        Route::post('produtos/sorting', [ProductController::class, 'sorting'])
        ->name('admin.dashboard.product.sorting');  
        //PARTNER
        Route::resource('parceiros', PartnerController::class)
        ->names('admin.dashboard.partner')
        ->parameters(['parceiros'=>'partner']);
        Route::post('parceiros/delete', [PartnerController::class, 'destroySelected'])
        ->name('admin.dashboard.partner.destroySelected');
        Route::post('parceiros/sorting', [PartnerController::class, 'sorting'])
        ->name('admin.dashboard.partner.sorting');
        //ABOUT
        Route::resource('sobre', AboutController::class)
        ->names('admin.dashboard.about')
        ->parameters(['sobre'=>'about']);  
        //TOPICOS
        Route::resource('topico', TopicController::class)
        ->names('admin.dashboard.topic')
        ->parameters(['topico'=>'topic']);
        Route::post('topico/delete', [TopicController::class, 'destroySelected'])
        ->name('admin.dashboard.topic.destroySelected');
        Route::post('topico/sorting', [TopicController::class, 'sorting'])
        ->name('admin.dashboard.topic.sorting');
        //DEPOIMENT SECTION
        Route::resource('sessao-depoimentos', DepoimentSessionController::class)
        ->parameters(['sessao-depoimentos' => 'depoimentSession'])
        ->names('admin.dashboard.depoimentSession'); 
        //DEPOIMENT
        Route::resource('depoimentos', DepoimentController::class)
        ->parameters(['depoimentos' => 'depoiment'])
        ->names('admin.dashboard.depoiment');
        Route::post('depoimentos/delete', [DepoimentController::class, 'destroySelected'])
        ->name('admin.dashboard.depoiment.destroySelected');
        Route::post('depoimentos/sorting', [DepoimentController::class, 'sorting'])
        ->name('admin.dashboard.depoiment.sorting');

        // SETTINGS THEME
        Route::post('setting', [SettingThemeController::class, 'setting'])->name('admin.dashboard.settingTheme'); 
        Route::post('setting/update', [SettingThemeController::class, 'settingUpdate'])->name('admin.dashboard.settingThemeUpdate'); 
    });

    // LANGUAGES
    Route::get('/lang/{locale}', function (string $locale) {
        if (! in_array($locale, ['en', 'es', 'pt'])) {
            abort(400);
        }
        session(['locale' => $locale]);
        App::setLocale($locale);

        return redirect()->back();
    })->name('change.language');
    // LOGOUT
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.dashboard.user.logout');
});

View::composer('admin.core.admin', function ($view) {
    $currentUser = Auth::user();
    $user = User::where('id', $currentUser->id)->active()->first();
    
    $notifications = (new AuditCountRepository());
    $auditorias = $notifications->allAudit();
    $auditCount = $notifications->auditCount();
    $settingTheme = (new SettingThemeRepository())->settingTheme();

    return $view->with('settingTheme', $settingTheme)->with('user', $user)->with('auditorias', $auditorias)->with('auditCount', $auditCount);
});
