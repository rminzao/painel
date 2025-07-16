<div class="tab-pane" id="tab_user_info">
    <div class="card">
        <div class="card-header">
            <!--begin::Title-->
            <div class="card-title">
                <span style="color: #a1a5b7;font-size:13.975px;">Informações do jogador</span>
            </div>
            <!--end::Title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Menu-->
                <button type="button" class="btn btn-light-primary btn-sm"
                    id="kt_user_info_update_submit">
                    <span class="indicator-label">Salvar alterações</span>
                    <span class="indicator-progress">processando aguarde...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Menu-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <div class="card-body">
            <form id="kt_user_info_update_form">
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row">
                        <div class="d-flex flex-column mb-5 fv-row col-6">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                Nome
                            </label>

                            <input type="text" class="form-control form-control-sm form-control-solid" name="first_name"
                                value="{{ $userSelected->first_name }}">
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row col-6">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                Sobrenome
                            </label>

                            <input type="text" name="last_name" class="form-control form-control-sm form-control-solid"
                                value="{{ $userSelected->last_name }}">

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex flex-column mb-5 fv-row col-6">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span>📙 referencia</span>
                            </label>

                            <input type="text" name="reference" class="form-control form-control-sm form-control-solid"
                                value="{{ $userSelected->reference }}" disabled readonly>
                            <div class="text-muted fs-7">Código ultilizado para referencia entre os
                                jogadores.</div>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row col-6">
                            <label for="kt_ecommerce_add_category_store_template" class="form-label">
                                👷 Cargo
                            </label>
                            <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                                data-hide-search="true" name="role" data-placeholder="Validade" tabindex="0"
                                aria-hidden="false" {{ $user->id == $userSelected->id ? 'disabled' : '' }}>
                                <option value="1" {{ $userSelected->role == 1 ? 'selected' : '' }}>💣
                                    Jogador</option>
                                <option value="0" {{ $userSelected->role == 0 ? 'selected' : '' }}>🚧
                                    Tester</option>
                                <option value="2" {{ $userSelected->role == 2 ? 'selected' : '' }}>👑
                                    Administrador</option>
                                <option value="3" {{ $userSelected->role == 3 ? 'selected' : '' }}>👨‍💻
                                    Desenvolvedor</option>
                            </select>
                            {!! $user->id == $userSelected->id ? '<div class="text-danger fs-7">Você não pode alterar o próprio cargo.</div>' : '<div class="text-muted fs-7">Selecione o cargo do jogador.</div>'  !!}

                        </div>
                    </div>
                    <div class="d-flex flex-stack mb-7">
                        <div class="me-5">
                            <label class="fs-6 fw-bold form-label">Ativo</label>
                            <div class="fs-7 fw-bold text-muted">se desmarcado o jogador <span
                                    class="text-danger">não terá</span> mais acesso ao site.</div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input h-20px w-30px" type="checkbox" name="active"
                                value="1" {{ $userSelected->active ? 'checked' : '' }} {{ $user->id == $userSelected->id ? 'disabled' : '' }}>
                            <span class="form-check-label fw-bold text-muted"></span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
