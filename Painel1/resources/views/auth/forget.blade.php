@extends('layouts.auth')

@section('title', 'Recuperar senha')

@section('custom-js')
    <script>
        const auth = {
            forget() {
                const data = $("#kt_password_reset_form").serialize();
                var button = document.querySelector("#kt_password_reset_submit");
                changeButtonState(button, true);
                axios.post(
                    `${baseUrl}/api/auth/forget`, data
                ).then((res) => {
                    var su = res.data;
                    swMessage(su.state ? "success" : "warning", su.message);
                    changeButtonState(button, false);
                    if ($("div.g-recaptcha").length > 0)
                        grecaptcha.reset();

                }).catch((err) => {
                    swMessage("error",
                        "Ops, não conseguimos processar sua solicitação, entre em contato com administrador.",
                        true, false);
                    changeButtonState(button, false);
                    console.error(err);
                    if ($("div.g-recaptcha").length > 0)
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
                <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form">
                    <div class="text-center mb-10">
                        <h3 class="text-dark mb-3">Esqueceu sua senha do <span class="text-warning">{{ $_ENV['APP_NAME'] }}</span>?</h3>

                        <div class="fw-bold fs-7">Insira seu e-mail para recuperar sua conta.</div>
                    </div>

                    <div class="fv-row mb-10">
                        <label class="form-label fw-bolder text-gray-900 fs-7">📫 Email</label>
                        <input class="form-control form-control-sm form-control-solid" type="email" placeholder=""
                            name="email" autocomplete="off" />
                    </div>

                    @if ($_ENV['APP_CAPTCHA'] == 'true')
                        <div class="g-recaptcha mb-10" data-sitekey="{{ $_ENV['CAPTCHA_KEY'] }}"></div>
                    @endif

                    <div class="d-flex">
                        <button type="button" id="kt_password_reset_submit" onclick="auth.forget()"
                            class="btn btn-sm btn-light-primary w-100 me-3">
                            <span class="indicator-label">Solicitar nova senha</span>
                            <span class="indicator-progress">Solicitando... <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <a href="{{ url('auth/entrar') }}" class="btn btn-sm btn-light-warning w-50">voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
