<div class="tab-pane" id="tab_user_person_change_nick">
    <div class="card">
        <div class="card-header">
            <!--begin::Title-->
            <div class="card-title">
                <span style="color: #a1a5b7;font-size:13.975px;">Trocar nickname</span>
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                 <!--begin::Menu-->
                 <button type="button" class="btn btn-light-primary btn-sm" id="kt_user_game_nick_update_submit">
                    <span class="indicator-label">Salvar novo nick</span>
                    <span class="indicator-progress">processando aguarde...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Menu-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body">
            <form id="kt_user_game_nick_update_form">
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row">
                        <div class="d-flex flex-column mb-7 fv-row col-6">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">🎫 Novo
                                nick</label>
                            <input type="text" class="form-control form-control-sm form-control-solid" name="nickname">
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row col-6">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">🎫 Confirme o novo
                                nick</label>
                            <input type="text" class="form-control form-control-sm form-control-solid" name="nickname-confirm">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
