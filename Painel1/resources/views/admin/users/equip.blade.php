@extends('layouts.app')

@section('title', 'Equipe')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">👮‍♂️ Membros da equipe</h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>

                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>

                    <li class="breadcrumb-item text-white opacity-75">Administração</li>

                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>

                    <li class="breadcrumb-item text-white opacity-75">Usuários</li>

                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>

                    <li class="breadcrumb-item text-white opacity-75">Equipe</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <a data-bs-toggle="modal" data-bs-target="#kt_modal_role_add_role" class="btn btn-light">Adicionar</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card" id="kt_block_ui_card_serverlist">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">User</th>
                                    <th class=""></th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-600 fw-bold" id="server_list"></tbody>
                        </table>
                    </div>

                    <div class="mb-5" id="serverlist_paginator"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="kt_modal_role_add_role" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content" id="kt_block_ui_modal_serverinfo">
                <form class="form" action="{{ url('api/admin/users/equip/role') }}" method="POST"
                    id="kt_modal_role_add_role_form">
                    <div class="modal-header">
                        <h4 class="fw-bolder">🎯 Dar cargo</h4>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                        rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="modal-body scroll-y">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span>Usuario</span>
                                </label>

                                <select data-control="select2" id="user-id" name="user"
                                    data-dropdown-parent="#kt_modal_role_add_role"
                                    data-placeholder="Selecione um estado para o servidor"
                                    class="form-select form-select-solid"> </select>
                            </div>

                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span>Cargo</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="O Cargo só é necesário para limitar as opçoes da conta."></i>
                                </label>

                                <select data-control="select2" id="user-roles" name="role"
                                    data-dropdown-parent="#kt_modal_role_add_role"
                                    data-placeholder="Selecione um estado para o servidor"
                                    class="form-select form-select-solid">
                                    <option value="1">💣 Jogador</option>
                                    <option value="0">🚧 Tester</option>
                                    <option value="2">👑 Administrador</option>
                                    <option value="3">👨‍💻 Desenvolvedor</option>
                                </select>
                            </div>
                            <div class="text-center pt-4">
                                <button type="submit" id="button_update_server" class="btn btn-primary w-100">
                                    <span class="indicator-label">Salvar alterações</span>
                                    <span class="indicator-progress">Processando aguarde... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_role_change" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content" id="kt_block_ui_modal_serverinfo">
                <form class="form" action="{{ url('api/admin/users/equip/role') }}" method="POST"
                    id="kt_modal_role_change_form">
                    <div class="modal-header">
                        <h4 class="fw-bolder">🎯 Trocar cargo</h4>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                        rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y">
                        <input type="hidden" name="user" id="user-id">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span>Cargo</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="O Cargo só é necesário para limitar as opçoes da conta."></i>
                                </label>
                                <select data-control="select2" id="user-role" name="role"
                                    data-dropdown-parent="#kt_modal_role_change"
                                    data-placeholder="Selecione um estado para o servidor"
                                    class="form-select form-select-solid">
                                    <option value="1">💣 Jogador</option>
                                    <option value="0">🚧 Tester</option>
                                    <option value="2">👑 Administrador</option>
                                    <option value="3">👨‍💻 Desenvolvedor</option>
                                </select>
                            </div>
                            <div class="text-center pt-4">
                                <button type="submit" id="button_update_server" class="btn btn-primary w-100">
                                    <span class="indicator-label">Salvar alterações</span>
                                    <span class="indicator-progress">Processando aguarde...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/users/equip/list.js"></script>
@endsection
