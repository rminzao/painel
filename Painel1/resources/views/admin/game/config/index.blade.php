@extends('layouts.app')

@section('title', 'Configurações do servidor')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">Configurações do servidor</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Configurações do servidor</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-3">
                    <select id="sid" class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary" id="button_update_serverConfig"
                    onclick="serverConfig.updateOnGame()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z"
                                    fill="currentColor" />
                                <path
                                    d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z"
                                    fill="currentColor" />
                            </svg>
                        </span>atualizar
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                        transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>

                            <input type="text" name="search" class="form-control form-control-solid w-250px ps-14"
                                placeholder="Buscar (id or reference)" />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                            data-bs-target="#md_setting_create">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3"
                                        d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7 13.4 7 14C7 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 19 12 19C12.6 19 12.5 18.6 12.5 18V14.5L16 14C16.6 14 17 14.6 17 14C17 13.4 16.6 13.5 16 13.5Z"
                                        fill="currentColor"></path>
                                    <rect x="11" y="19" width="10" height="2" rx="1" transform="rotate(-90 11 19)"
                                        fill="currentColor">
                                    </rect>
                                    <rect x="7" y="13" width="10" height="2" rx="1" fill="currentColor"></rect>
                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
                                </svg>
                            </span>Adicionar parametro
                        </button>

                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="no-results">
                        @include('components.default.notfound', [
                            'title' => 'Sem dados',
                            'message' => 'não tem nada por aqui',
                        ])
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3" id="table-settings-list"
                            style="display:none;">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Prefixo</th>
                                    <th>Valor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600" id="settings-list"></tbody>
                        </table>
                    </div>
                    <div class="mt-5" id="item_paginator"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_setting_create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adicionar novo parâmetro</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row">
                                    <label class="fs-6 fw-bold form-label">🎫 Prefixo</label>
                                    <input type="text" class="form-control form-control-solid" name="name" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">📝 Valor</label>
                                    <input type="text" class="form-control form-control-solid" name="value"
                                        placeholder="Valor">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="serverConfig.create()" id="invoice-update-form-submit"
                                    class="btn btn-primary w-100">
                                    <span class="indicator-label">Criar parametro</span>
                                    <span class="indicator-progress">
                                        criando...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_setting_edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editando parâmetro</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y">
                    <form>
                        <input type="hidden" name="id">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row">
                                    <label class="fs-6 fw-bold form-label">🎫 Prefixo</label>
                                    <input type="text" class="form-control form-control-solid" name="name" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">📝 Valor</label>
                                    <input type="text" class="form-control form-control-solid" name="value"
                                        placeholder="Valor">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="serverConfig.update()" id="invoice-update-form-submit"
                                    class="btn btn-primary w-100">
                                    <span class="indicator-label">Aplicar alterações</span>
                                    <span class="indicator-progress">
                                        aplicando...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/game/server/list.js "></script>
@endsection
