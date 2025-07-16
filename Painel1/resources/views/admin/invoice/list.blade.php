@extends('layouts.app')

@section('title', 'Lista de faturas')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📮 Lista de faturas</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Lista de faturas</li>
                </ul>
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
                        <div class="w-100 mw-200px">
                            <select class="form-select form-select-solid" name="sid" data-control="select2"
                                data-hide-search="true" data-placeholder="Servidor">
                                @foreach ($servers as $server)
                                    <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                        {{ $server->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100 mw-200px">
                            <select class="form-select form-select-solid" name="uid" data-control="select2"
                                data-placeholder="Jogador" id="user-list">
                                <option></option>
                                <option value="0" selected>Todos jogadores</option>
                            </select>
                        </div>

                        <div class="w-100 mw-150px">
                            <select class="form-select form-select-solid" id="state-filter" data-control="select2"
                                data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                <option></option>
                                <option value="0" selected>Todos</option>
                                <option value="approved">Aprovado</option>
                                <option value="pending">Pendente</option>
                                <option value="rejected">Recusado</option>
                                <option value="refound">Reembolsado</option>
                                <option value="cancelled">Cancelado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="no-results">
                        @include('components.default.notfound', [
                            'title' => 'Sem faturas',
                            'message' => 'não tem nada por aqui',
                        ])
                    </div>
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="table-invoice-list"
                        style="display:none;">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>Usuário</th>
                                <th>Fatura</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Atualizado em</th>
                                <th>Pago em</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="invoice-list"></tbody>
                    </table>
                    <div class="mt-10" id="item_paginator"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_invoice_edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-edit-method-pic" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-edit-user-name"></span>
                            <a href="javascript:;" id="md-edit-reference" class="text-gray-500 text-hover-primary mb-1"></a>
                        </div>
                    </div>
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
                    <form id="form-invoice-update">
                        <input type="hidden" name="id">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6" id="md-edit-reward-annex-level-area">
                                    <label for="kt_ecommerce_add_category_store_template"
                                        class="form-label">Status</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" id="md-edit-reward-annex-in-level" data-placeholder="Nível"
                                        name="state">
                                        <option value="approved">Aprovado</option>
                                        <option value="pending">Pendente</option>
                                        <option value="rejected">Recusado</option>
                                        <option value="refound">Reembolsado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                    <div class="text-muted fs-7">Selecione o status da fatura.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-6" id="md-edit-reward-annex-amount-area">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">Enviado</label>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid mb-5 mt-3">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" id="meurabodisgrca"
                                            name="sent" value="1" checked="checked" />

                                    </label>
                                    <div class="fs-7 fw-bold text-muted">Será enviado os cupons ao jogador caso não tenha
                                        sido enviado antes.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">📝 Nota</label>
                                    <textarea name="note" class="form-control form-control-solid" rows="3"
                                        placeholder="Ex: Pagamento realizado fora do site via pix."></textarea>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="invoice.update()" id="invoice-update-form-submit"
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
    <script src="{{ url() }}/assets/js/admin/invoice/list.js"></script>
@endsection
