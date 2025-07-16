<div class="tab-pane" id="tab_user_email">
    <div class="card">
        <div class="card-header">
            <!--begin::Title-->
            <div class="card-title">
                <span style="color: #a1a5b7;font-size:13.975px;">Trocar email</span>
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                @if ($userSelected->discord_id == '' and $userSelected->google_id == '')
                <!--begin::Menu-->
                <button type="button" class="btn btn-light-primary btn-sm" id="kt_user_email_update_submit">
                    <span class="indicator-label">Salvar novo email</span>
                    <span class="indicator-progress">processando aguarde...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Menu-->
                @endif
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body">
            @if ($userSelected->discord_id != '' or $userSelected->google_id != '')
                @include('components.default.notfound', [
                    'title' => 'Opss',
                    'message' => 'esse usuario está conectado a um serviço de autenticação <br>externo, não é possível alterar o email',
                ])
            @else
                <form id="kt_user_email_update_form">
                    <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">📪 Nova
                                    email</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="email">
                            </div>
                            <div class="d-flex flex-column mb-7 fv-row col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">📪 Confirme o novo
                                    email</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="email-confirm">
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
