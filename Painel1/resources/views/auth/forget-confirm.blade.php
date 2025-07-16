@extends('layouts.auth')

@section('title', 'Recuperar senha')

@section('custom-js')
    <script>
        const auth = {
            forget() {
                const data = $("#kt_new_password_form").serialize();
                var button = document.querySelector("#kt_new_password_submit");
                changeButtonState(button, true);
                axios.post(
                    `${baseUrl}/api/auth/forget/confirm`, data
                ).then((response) => {
                    var su = response.data;
                    if (su.state) {
                        window.location.href = `${baseUrl}/auth/entrar`;
                        return;
                    }
                    swMessage(su.state ? "success" : "warning", su.message);
                    changeButtonState(button, false);
                    grecaptcha.reset();
                }).catch((error) => {
                    swMessage("error", "erro interno, verifique o console.");
                    changeButtonState(button, false);
                    console.error(error);
                    grecaptcha.reset();
                });
            }
        }
    </script>
@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row-fluid py-10">
        <div class="d-flex flex-center flex-column flex-column-fluid">
            <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                <form class="form w-100" novalidate="novalidate" id="kt_new_password_form">
                    <div class="text-center mb-10">
                        <h3 class="text-dark mb-3">Defina sua nova senha no <span class="text-warning">{{ $_ENV['APP_NAME'] }}</span></h3>

                        <div class=" fw-bold fs-7">
                            Já redefiniu sua senha ?
                            <a href="{{ url('auth/entrar') }}" class="link-primary fw-bolder">Fazer login</a>
                        </div>
                    </div>

                    <input type="hidden" name="hash" value="{{ $user->forget }}" />
                    <div class="row mb-10">
                        <div class="fv-row col-6">
                            <div class="mb-1">
                                <label class="form-label text-dark fs-7">🔑 Senha</label>

                                <div class="position-relative mb-3">
                                    <input class="form-control form-control-sm form-control-solid" type="password"
                                        placeholder="" name="password" autocomplete="off" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="fv-row col-6">
                            <label class="form-label  text-dark fs-7">🔑 Confirme a senha</label>
                            <input class="form-control form-control-sm form-control-solid" type="password" placeholder=""
                                name="confirm-password" autocomplete="off" />
                        </div>

                        <div class="text-muted fs-7">
                            Use 8 ou mais caracteres com uma mistura de letras, números e símbolos.
                        </div>
                    </div>

                    @if ($_ENV['APP_CAPTCHA'] == 'true')
                        <div class="g-recaptcha mb-10" data-sitekey="{{ $_ENV['CAPTCHA_KEY'] }}"></div>
                    @endif

                    <div class="text-center">
                        <button type="button" id="kt_new_password_submit" onclick="auth.forget()"
                            class="btn btn-sm btn-light-primary w-100">
                            <span class="indicator-label">Salvar nova senha</span>
                            <span class="indicator-progress">Salvando... <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
