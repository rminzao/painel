@extends('layouts.app')

@section('title', 'Lista de Produtos')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🛒 Lista de produtos</h1>

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

                    <li class="breadcrumb-item text-white opacity-75">Produtos</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="product_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Nome/valor do produto" name="search" />
                                        </div>
                                    </div>
                                    <select name="sid" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-hide-search="true" data-placeholder="Servidor">
                                        <option value="0" selected>Todos</option>
                                        @foreach ($servers as $server)
                                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_product_new">
                                    novo produto
                                </button>
                            </div>
                        </div>

                        <div class="card-body pt-4 ps-7" id="product_body_list">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Nada por aqui',
                                    'message' => 'nenhum produto encontrado',
                                ])
                            </div>

                            <div id="product_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>

                            <div id="product_footer">
                              <div class="d-flex justify-content-between mt-5">
                                <div>
                                    <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-hide-search="true">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div id="product_paginator"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um produto para continuar',
                            ])
                        </div>
                        <div id="product_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <span class="text-muted fs-6">Editar produto</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <form>
                                    <input type="hidden" name="id">
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="name" value="Recarga" />
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">🌍 Servidor</label>
                                            <select name="sid" class="form-select form-select-sm form-select-solid w-60"
                                                data-control="select2" data-hide-search="true"
                                                data-placeholder="Selecione um servidor">
                                                <option selected></option>
                                                @foreach ($servers as $server)
                                                    <option value="{{ $server->id }}">{{ $server->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-4">
                                            <label class="fs-6 fw-bold form-label mb-2">💵 Valor</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="value" value="1" />
                                        </div>
                                        <div class="fv-row mb-7 col-4">
                                            <label class="fs-6 fw-bold form-label mb-2">💴 Cupons</label>
                                            <input type="number" class="form-control form-control-sm form-control-solid"
                                                name="ammount" value="1" step="1" min="1" />
                                        </div>
                                        <div class="fv-row mb-7 col-4">
                                            <label class="fs-6 fw-bold form-label mb-2">⚡ Tipo</label>
                                            <select name="type" class="form-select form-select-sm form-select-solid w-60"
                                                data-control="select2" data-hide-search="true"
                                                data-placeholder="Selecione um tipo">
                                                <option value="1">Cupons</option>
                                                <option value="2" disabled>Pacote Vip (em breve)</option>
                                                <option value="3">Laboratorio</option>
                                                <option value="4" disabled>Pacote de missões (em breve)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-stack mb-7">
                                        <div class="me-5">
                                            <label class="fs-6 fw-bold form-label">🙈 Visível</label>
                                            <div class="fs-7 fw-bold text-muted">se desmarcado o produto <span
                                                    class="text-danger">não poderá</span> ser visto por jogadores.
                                            </div>
                                        </div>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-20px w-30px" type="checkbox" name="active"
                                                value="1" checked="">
                                        </label>
                                    </div>
                                    <div class="d-flex flex-stack mb-7">
                                        <div class="me-5">
                                            <label class="fs-6 fw-bold form-label">❓ Possui recompensa</label>
                                            <div class="fs-7 fw-bold text-muted">
                                                se marcado o produto <span class="text-primary">enviará recompensas</span> ao
                                                jogador quando ele comprar o produto.
                                            </div>
                                        </div>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-20px w-30px" type="checkbox" name="reward"
                                                value="1" checked="">
                                        </label>
                                    </div>

                                    <div id="rewards_body">
                                        <div class="mb-1 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">📦 Recompensa(s)</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Itens recebidos quando o jogador comprar este produto.
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#md_product_reward_new"
                                                    class="btn btn-light-primary btn-sm">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                                fill="currentColor"></rect>
                                                            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                                transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                                                fill="currentColor">
                                                            </rect>
                                                        </svg>
                                                    </span>
                                                    adicionar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="highlight p-0 blockui"
                                            style="position: relative; overflow: hidden;">
                                            <div class="w-100 p-5 pb-2 overflow-auto mh-400px" id="rewards_list" style="display: none;"></div>
                                            <div id="no_rewards">
                                                <div class="tab-pane fade active show" id="kt_topbar_notifications_1"
                                                    role="tabpanel">
                                                    <div data-kt-search-element="empty" class="text-center">
                                                        <div class="pt-10 pb-5">
                                                            <span class="svg-icon svg-icon-4x opacity-50">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                                                        fill="currentColor"></path>
                                                                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z"
                                                                        fill="currentColor">
                                                                    </path>
                                                                    <rect x="13.6993" y="13.6656" width="4.42828"
                                                                        height="1.73089" rx="0.865447"
                                                                        transform="rotate(45 13.6993 13.6656)" fill="currentColor">
                                                                    </rect>
                                                                    <path
                                                                        d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>


                                                        <div class="pb-15 fw-bold">
                                                            <h3 class="text-gray-600 fs-5 mb-2">Nenhuma recompensa</h3>
                                                            <div class="text-muted fs-7"> essa condição nao possui recompensas
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center pt-5">
                                        <button type="button" id="button_product_update" onclick="product.update()" class="btn btn-sm btn-light-primary w-100">
                                            <span class="indicator-label">Aplicar alterações</span>
                                            <span class="indicator-progress">
                                                aplicando...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_product_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-450px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">🛒 Novo produto</h4>
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
                        <div class="row">
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="name"
                                    value="Recarga" />
                            </div>
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🌍 Servidor</label>
                                <select name="sid" class="form-select form-select-sm form-select-solid w-60"
                                    data-control="select2" data-hide-search="true" data-placeholder="Selecione um servidor">
                                    <option selected></option>
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->id }}">{{ $server->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row mb-7 col-4">
                                <label class="fs-6 fw-bold form-label mb-2">💵 Valor</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="value"
                                    value="1" />
                            </div>
                            <div class="fv-row mb-7 col-4">
                                <label class="fs-6 fw-bold form-label mb-2">💴 Cupons</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" name="ammount"
                                    value="1" step="1" min="1" />
                            </div>
                            <div class="fv-row mb-7 col-4">
                                <label class="fs-6 fw-bold form-label mb-2">⚡ Tipo</label>
                                <select name="type" class="form-select form-select-sm form-select-solid w-60"
                                    data-control="select2" data-hide-search="true"
                                    data-placeholder="Selecione um tipo">
                                    <option value="1">Cupons</option>
                                    <option value="2" disabled>Pacote Vip (em breve)</option>
                                    <option value="3">Laboratorio</option>
                                    <option value="4" disabled>Pacote de missões (em breve)</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-stack mb-7">
                            <div class="me-5">
                                <label class="fs-6 fw-bold form-label">🙈 Visível</label>
                                <div class="fs-7 fw-bold text-muted">se desmarcado o produto <span
                                        class="text-danger">não poderá</span> ser visto por jogadores.
                                </div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-30px" type="checkbox" name="active" value="1"
                                    checked="">
                            </label>
                        </div>
                        <div class="d-flex flex-stack mb-7">
                            <div class="me-5">
                                <label class="fs-6 fw-bold form-label">📦 Recompensas</label>
                                <div class="fs-7 fw-bold text-muted">
                                    Os jogadores receberam items de recompensa quando compraram este produto.
                                </div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-30px" type="checkbox" name="reward" value="1">
                            </label>
                        </div>
                        <div id="product_create_alert" style="display:none;">
                            <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row w-100 p-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-warning me-4 mb-5 mb-sm-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path opacity="0.3"
                                        d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                        fill="currentColor"></path>
                                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="currentColor"></path>
                                    <rect x="13.6993" y="13.6656" width="4.42828" height="1.73089" rx="0.865447"
                                        transform="rotate(45 13.6993 13.6656)" fill="currentColor"></rect>
                                    <path
                                        d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h4 class="fw-bold">Atenção</h4>
                                <span>Ao adicionar o produto, as recompensas devem ser adicionadas clicando no botao editar
                                    na lista de produtos.</span>
                            </div>
                        </div>
                        </div>

                        <div class="text-center pt-5">
                            <button type="button" id="button_product_create" onclick="product.create()" class="btn btn-sm btn-light-primary w-100">
                                <span class="indicator-label">Criar produto</span>
                                <span class="indicator-progress">
                                    criando...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_product_reward_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="item_icon" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="item_name"></span>
                            <span id="item_id" class="text-gray-800 text-hover-primary mb-1"></span>
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
                    <form>
                        <div>
                            <div class="mb-10">
                                <label class="form-label required">Item</label>
                                <select class="form-select form-select-solid" data-dropdown-parent="#md_product_reward_new"
                                    data-placeholder="Selecione um item" data-allow-clear="true" name="TemplateID">
                                </select>
                            </div>
                        </div>
                        <div id="info_area" style="display: none">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6" id="strengthen_area">
                                        <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                            Level
                                        </label>
                                        <select class="form-select form-select-solid mb-2" data-control="select2"
                                            data-hide-search="true" data-placeholder="Nível" name="StrengthenLevel">
                                            <option></option>
                                            <option value="0">Sem level</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">Nível {{ $i }}</option>
                                            @endfor
                                            <option value="13">Avanço 1</option>
                                            <option value="14">Avanço 2</option>
                                            <option value="15">Avanço 3</option>
                                        </select>
                                        <div class="text-muted fs-7">Selecione o nível do item.</div>
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-12" id="count_area">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">Quantidade</span>
                                        </label>
                                        <input type="number" id="md-edit-reward-annex-in-max"
                                            class="form-control form-control-solid" min="1" max="1" name="ItemCount" value="1" />

                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-12" id="md-edit-reward-annex-valid-area">
                                        <label for="kt_ecommerce_add_category_store_template"
                                            class="form-label required">Validade</label>
                                        <select class="form-select form-select-solid mb-2" data-control="select2"
                                            data-hide-search="true" name="ItemValid">
                                            <option value="0" selected>Permanente</option>
                                            <option value="1">1 Dia</option>
                                            <option value="3">3 Dias</option>
                                            <option value="7">7 Dias</option>
                                            <option value="15">15 Dias</option>
                                            <option value="30">30 Dias</option>
                                            <option value="365">365 Dias</option>
                                        </select>
                                        <div class="text-muted fs-7">Selecione a validade do item.</div>
                                    </div>
                                </div>
                                <div class="row" id="attr_area">
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="AttackCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="DefendCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="AgilityCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="LuckCompose" value="0" />
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsBind</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se desmarcado o item poderá ser enviado
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind" value="1"
                                            checked="checked" />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="text-center">
                                    <button type="button" onclick="reward.create()" id="btn-send-box-create"
                                    class="btn btn-sm btn-light-primary w-100">
                                        <span class="indicator-label">Adicionar</span>
                                        <span class="indicator-progress">
                                            adicionando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_product_reward_edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="item_icon" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="item_name"></span>
                            <span id="item_id" class="text-gray-800 text-hover-primary mb-1"></span>
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
                    <form>
                        <input type="hidden" name="id">
                        <input type="hidden" name="TemplateID">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6" id="strengthen_area">
                                    <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                        Level
                                    </label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" data-placeholder="Nível" name="StrengthenLevel">
                                        <option></option>
                                        <option value="0">Sem level</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">Nível {{ $i }}</option>
                                        @endfor
                                        <option value="13">Avanço 1</option>
                                        <option value="14">Avanço 2</option>
                                        <option value="15">Avanço 3</option>
                                    </select>
                                    <div class="text-muted fs-7">Selecione o nível do item.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-12" id="count_area">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Quantidade</span>
                                    </label>
                                    <input type="number" id="md-edit-reward-annex-in-max"
                                        class="form-control form-control-solid" min="1" max="1" name="ItemCount" value="1" />

                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-12" id="md-edit-reward-annex-valid-area">
                                    <label for="kt_ecommerce_add_category_store_template"
                                        class="form-label required">Validade</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" name="ItemValid">
                                        <option value="0" selected>Permanente</option>
                                        <option value="1">1 Dia</option>
                                        <option value="3">3 Dias</option>
                                        <option value="7">7 Dias</option>
                                        <option value="15">15 Dias</option>
                                        <option value="30">30 Dias</option>
                                        <option value="365">365 Dias</option>
                                    </select>
                                    <div class="text-muted fs-7">Selecione a validade do item.</div>
                                </div>
                            </div>
                            <div class="row" id="attr_area">
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="AttackCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="DefendCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="AgilityCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="LuckCompose" value="0" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsBind</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado o item poderá ser enviado
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind" value="1"
                                        checked="checked" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="reward.update()" class="btn btn-sm btn-light-primary w-100">
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
    <script src="{{ url() }}/assets/js/admin/product/list.js"></script>
@endsection
