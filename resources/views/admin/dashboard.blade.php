@extends('admin.core.admin')
@section('content')
<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
            
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{__('dashboard.title_dashboard')}}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{__('dashboard.title_dashboard')}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        @if (Auth::user()->hasRole('Super') || 
            Auth::user()->hasPermissionTo('usuario.tornar usuario master') || 
            Auth::user()->hasPermissionTo('produtos.visualizar'))
            <div class="col-md-5 col-xl-3">
                <div class="card borda-cx ratio ratio-4x3 border-whi">
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="{{route('admin.dashboard.product.index')}}">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="avatar-xl bg-hoom rounded-circle text-center">
                                        <i class="avatar-md mdi mdi-package-variant-closed font-48 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 col-12 text-center">
                                <h5 class="text-uppercase text-muted">Produtos</h5>
                            </div>
                        </a>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        @endif
    </div>

    @if (Auth::user()->hasRole('Super') || 
    Auth::user()->hasPermissionTo('usuario.tornar usuario master') || 
    Auth::user()->hasPermissionTo('newsletter.visualizar') || 
    Auth::user()->hasPermissionTo('contato.visualizar')) 
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title "><i class="mdi mdi-card-account-mail-outline"></i> Contato</h4>
                </div>
            </div>
            @if (Auth::user()->hasRole('Super') || 
            Auth::user()->hasPermissionTo('usuario.tornar usuario master') || 
            Auth::user()->hasPermissionTo('contato.visualizar'))
                <div class="col-md-5 col-xl-3">
                    <div class="card borda-cx ratio ratio-4x3 border-whi">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{route('admin.dashboard.contact.index')}}">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="avatar-xl bg-hoom rounded-circle text-center">
                                            <i class="avatar-md mdi mdi-card-account-mail-outline font-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-12 text-center">
                                    <h5 class="text-uppercase text-muted">Contato</h5>
                                </div>
                            </a>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endif

            @if (Auth::user()->hasRole('Super') || 
            Auth::user()->hasPermissionTo('usuario.tornar usuario master') || 
            Auth::user()->hasPermissionTo('newsletter.visualizar'))
                <div class="col-md-5 col-xl-3">
                    <div class="card borda-cx ratio ratio-4x3 border-whi">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{route('admin.dashboard.newsletter.index')}}">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="avatar-xl bg-hoom rounded-circle text-center">
                                            <i class="avatar-md mdi mdi-email-outline font-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-12 text-center">
                                    <h5 class="text-uppercase text-muted">Newsletter</h5>
                                </div>
                            </a>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endif
        </div>
    @endif

        @if (Auth::user()->hasRole('Super') || 
        Auth::user()->can('usuario.tornar usuario master') || 
        Auth::user()->can('email.visualizar'))
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title "><i class="mdi mdi-email-edit"></i> {{__('dashboard.setting_smtp')}}</h4>
                </div>
            </div>
            <div class="col-md-5 col-xl-3">
                <div class="card borda-cx ratio ratio-4x3 border-whi">
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="{{route('admin.dashboard.settingEmail.index')}}">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="avatar-xl bg-hoom rounded-circle text-center">
                                        <i class="avatar-md mdi mdi-email font-48 text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 col-12 text-center">
                                <h5 class="text-uppercase text-muted">{{__('dashboard.setting_email')}}</h5>
                            </div>
                        </a>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        @endif
    </div>
    <div class="row">
        @if (Auth::user()->hasRole('Super') || 
        Auth::user()->can('usuario.tornar usuario master') || 
        Auth::user()->can('auditoria.visualizar') || 
        Auth::user()->can('usuario.visualizar')|| 
        Auth::user()->can('grupo.visualizar'))
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title "><i class="mdi mdi-security"></i> {{__('dashboard.security_and_access_control')}}</h4>
                </div>
            </div>
            @if (Auth::user()->hasRole('Super') || Auth::user()->can('usuario.tornar usuario master') || Auth::user()->can('auditoria.visualizar'))
                <div class="col-md-5 col-xl-3">
                    <div class="card borda-cx ratio ratio-4x3 border-whi">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{route('admin.dashboard.audit.index')}}">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="avatar-xl bg-hoom rounded-circle text-center">
                                            <i class="avatar-md mdi mdi-clipboard-text font-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-12 text-center">
                                    <h5 class="text-uppercase text-muted">{{__('dashboard.audit')}}</h5>
                                </div>
                            </a>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endif
            
            @if (Auth::user()->hasRole('Super') || Auth::user()->can('usuario.tornar usuario master') || Auth::user()->can('grupo.visualizar'))
                <div class="col-md-5 col-xl-3">
                    <div class="card borda-cx ratio ratio-4x3 border-whi">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{route('admin.dashboard.group.index')}}">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="avatar-xl bg-hoom rounded-circle text-center">
                                            <i class="mdi mdi-account-group font-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-12 text-center">
                                    <h5 class="text-uppercase text-muted">{{__('dashboard.group_and_permission')}}</h5>
                                </div>
                            </a>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endif
    
            @if (Auth::user()->hasRole('Super') || Auth::user()->can('usuario.tornar usuario master') || Auth::user()->can('usuario.visualizar'))
                <div class="col-md-5 col-xl-3">
                    <div class="card borda-cx ratio ratio-4x3 border-whi">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="{{route('admin.dashboard.user.index')}}">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="avatar-xl bg-hoom rounded-circle text-center">
                                            <i class="avatar-md mdi mdi-account-multiple font-48 text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 col-12 text-center">
                                    <h5 class="text-uppercase text-muted">{{__('dashboard.users')}}</h5>
                                </div>
                            </a>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endif
        @endif
    </div>
    <!-- end row -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div><a href="" target="_blank" style="color:#94a0ad;"><script>document.write(new Date().getFullYear())</script> © WHI - Web de Alta Inspiração</a></div>
                </div>
                <div class="col-md-6">
                    <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                        <a href="https://www.whi.dev.br/" target="_blank" rel="noopener noreferrer">Sobre a WHI</a>
                        <a href="https://wa.me/5571996483853" target="_blank" rel="noopener noreferrer">Fale conosco</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @include('admin.loadPage.loading')
    <!-- end Footer -->
@endsection