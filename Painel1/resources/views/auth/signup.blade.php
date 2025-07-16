@php
$refer = $_GET['refer'] ?? '';
@endphp

@extends('layouts.auth')

@section('title', 'Cadastro')

@section('content')
    <div class="d-flex flex-column flex-lg-row-fluid py-10">
        <div class="d-flex flex-center flex-column flex-column-fluid">
            <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                <form class="form w-100" novalidate="novalidate" method="post" type="signup" id="kt_sign_in_form"
                    action="{{ url(!is_mobile() ? 'api/auth/signup' : 'api/auth/mobile/signup') }}">
                    <div class="text-center mb-10">
                        <h3 class="text-dark mb-3">Crie sua conta no <span
                                class="text-warning">{{ $_ENV['APP_NAME'] }}</span></h3>
                        <div class="fw-bold fs-7">
                            Ja tem conta?
                            <a href="{{ url('auth/entrar') }}" class="link-primary fw-bolder">Fazer login</a>
                        </div>
                    </div>
                    {!! flash() !!}
                    <div class="row fv-row ">
                        <div class="col-6 mb-7">
                            <label class="form-label fs-8 text-dark">🏷️ Nome</label>
                            <input class="form-control form-control-sm form-control-solid" type="text" name="fname"
                                placeholder="Digite seu nome" autocomplete="off" />
                        </div>

                        <div class="col-6">
                            <label class="form-label fs-8 text-dark">🏷️ Sobrenome</label>
                            <input class="form-control form-control-sm form-control-solid" type="text" name="lname"
                                placeholder="Digite seu sobrenome" autocomplete="off" />
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="form-label fs-8 text-dark">📫 Email</label>
                        <input class="form-control form-control-sm form-control-solid" type="email" name="email"
                            placeholder="Digite seu email" />
                        <div class="text-muted fs-8">
                            Utilize um email válido para seu login.
                        </div>
                    </div>

                    <div class="row fv-row mb-7">
                        <div data-kt-password-meter="true">
                            <div class="mb-1">
                                <label class="form-label fs-8 text-dark">🔑 Senha</label>

                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-sm form-control-solid" type="password"
                                        name="password" placeholder="Digite sua senha" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted fs-8">
                            Use 8 ou mais caracteres com uma mistura de letras, números e símbolos.
                        </div>
                    </div>

                    {{-- 
					
					<div class="row fv-row mb-7">
                        <div data-kt-password-meter="true">
                            <div class="mb-1">
                                <label class="form-label fs-8 text-dark">❓ possui um código de referência ?</label>
                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-sm form-control-solid" type="text"
                                        placeholder="Opicional..." name="referenced" value="{{ $refer }}"
                                        {{ !$refer ?: 'readonly' }} />
                                </div>
                            </div>
                        </div>
                    </div>
					--}}

                    <div class="fv-row mb-10">
                        <label class="form-check form-check-custom form-check-solid form-check-inline">
                            <input class="form-check-input w-20px h-20px" type="checkbox" name="terms" value="1"
                                checked />
                            <span class="form-check-label fw-bold text-gray-700 fs-8">
                                Eu aceito os <a href="#" class="ms-1 link-primary">Termos e condições</a>.
                            </span>
                        </label>
                    </div>

                    @if ($_ENV['APP_CAPTCHA'] == 'true')
                        <div class="g-recaptcha mb-10" data-sitekey="{{ $_ENV['CAPTCHA_KEY'] }}"></div>
                    @endif

                    <div class="text-center">
                        <button type="submit" id="auth_form_submit" class="btn btn-sm btn-light-primary w-100 mb-5">
                            <span class="indicator-label">Finalizar cadastro</span>
                            <span class="indicator-progress">
                                Cadastrando...
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
								data-login_uri="{{ url('api/auth/google') }}" data-ux_mode="redirect" data-auto_prompt="true">
							</div>
							<div class="d-flex justify-content-center">
								<a href="{{ url('api/auth/discord') }}" class="btn btn-icon btn-discord me-1"></a>
								<div class="g_id_signin mb-10" data-type="standard" data-size="large" data-theme="outline"
									data-text="signup_in_with" data-shape="rectangular"></div>
							</div>
						@endif
						-->
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
