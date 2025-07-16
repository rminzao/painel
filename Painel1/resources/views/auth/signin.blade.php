@extends('layouts.auth')

@section('title', 'Autenticação')

@section('content')
    <div class="d-flex flex-column flex-lg-row-fluid py-10">
        <div class="d-flex flex-center flex-column flex-column-fluid">
            <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                <form class="form w-100" method="POST" id="kt_sign_in_form"
                    action="{{ url(!is_mobile() ? 'api/auth/signin' : 'api/auth/mobile/signin') }}">
                    <div class="text-center mb-10">
                        <h3 class="text-dark mb-3">
                            Faça login em <span class="text-warning">{{ $_ENV['APP_NAME'] }}</span>
                        </h3>
                        <div class="fw-bold fs-7">
                            Novo por aqui?
                            <a href="{{ url('auth/cadastro') }}" class="link-primary fw-bolder">Crie sua conta agora</a>
                        </div>
                    </div>
                    {!! flash() !!}
                    <div class="fv-row mb-10">
                        <label class="form-label fs-8 text-dark">📫 Email</label>
                        <input class="form-control form-control-sm form-control-solid" type="email"
                            placeholder="Digite seu e-mail" name="email" value="<?= $cookie ?? null ?>" />
                    </div>
                    <div class="fv-row mb-4">
                        <div class="d-flex flex-stack mb-2">
                            <label class="form-label text-dark fs-8 mb-0">🔑 Senha</label>
                            <a class="link-primary fs-8" href="{{ url('auth/recuperar-senha') }}">
                                ❓ Esqueceu a senha ?
                            </a>
                        </div>
                        <input class="form-control form-control-sm form-control-solid" type="password" name="password"
                            placeholder="Digite sua senha" />
                    </div>
                    <div class="fv-row mb-10">
                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                            <input class="form-check-input w-18px h-18px" type="checkbox" name="save" value="1" checked>
                            <span class="form-check-label fs-8">Lembrar dados ?</span>
                        </label>

                    </div>

                    @if ($_ENV['APP_CAPTCHA'] == 'true')
					{{-- <div class="g-recaptcha mb-10" data-sitekey="{{ $_ENV['CAPTCHA_KEY'] }}"></div> --}}
                    @endif

                    <div class="text-center">
                        <button type="submit" id="auth_form_submit" class="btn btn-sm btn-light-primary w-100 mb-5">
                            <span class="indicator-label">Fazer Login</span>
                            <span class="indicator-progress">
                                Entrando...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
						<!--
						@if(!isset($_SESSION['clientUser']))
							<div class="d-flex align-items-center mb-5">
								<div class="border-bottom border-gray-300 mw-50 w-50"></div>
								<span class="fw-bold text-gray-400 fs-7 mx-2 text-uppercase">ou entre com</span>
								<div class="border-bottom border-gray-300 mw-50 w-50"></div>
							</div>
							<div id="g_id_onload" data-client_id="{{ $_ENV['GOOGLE_CLIENT_ID'] }}"
								data-login_uri="{{ url('api/auth/google') }}" data-ux_mode="redirect" data-auto_prompt="true"
								data-cancel_on_tap_outside="false" data-itp_support="true">
							</div>
							<div class="d-flex justify-content-center">
								<a href="{{ url('api/auth/discord') }}" class="btn btn-icon btn-discord me-1"></a>
								<div class="g_id_signin mb-10" data-type="standard" data-size="large" data-theme="outline"
									data-text="signup_in_with" data-shape="rectangular"></div>
							</div>
						@endif                        
						!-->
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('custom-js')
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    @if (!is_mobile())
        <script src="{{ url() }}/assets/js/custom/auth4.js"></script>
    @endif
@endsection
