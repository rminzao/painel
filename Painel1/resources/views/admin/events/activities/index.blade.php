@extends('layouts.app')

@section('title', 'Eventos')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📅 Atividades</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Eventos</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Atividades</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>{{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="events.updateOnGame()">
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
                        </span>
                        atualizar
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
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="events_body">
                        <div class="p-5 pt-7 pb-0" id="kt_chat_contacts_header">
                            <div class="w-100 position-relative" autocomplete="off">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-solid" placeholder="Nome do evento"
                                        name="search" />
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-4 ps-7 pb-0">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum evento encontrada',
                                ])
                            </div>

                            <div class="scroll-y me-n5 h-lg-auto" id="event_list" style="display: none;"></div>

                            <div class="mt-10" id="item_paginator"></div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="event_data_body">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um evento para continuar',
                            ])
                        </div>
                        <div id="event_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link">🎯 <span class="ms-2"
                                                        id="event_selected_title"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div id="condiction-buttons">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_condition"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                        fill="currentColor"></rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar condição
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" id="kt_chat_messenger_body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab_info" role="tabpanel">
                                        <div id="no_conditions" style="display: none;">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem condições',
                                                'message' => 'esse evento não possui nenhuma condição',
                                            ])
                                        </div>
                                        <div id="condition_data">
                                            <div class="d-flex flex-column flex-md-row">
                                                <ul
                                                    class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                                                </ul>
                                                <div class="tab-content w-100">
                                                    <div class="p-0 tab-pane fade" id="kt_tab_condition_rewards"
                                                        role="tabpanel">
                                                        <div class="mb-5 d-flex flex-stack">
                                                            <div class="me-5">
                                                                <label class="fs-6 fw-bold form-label">🎲 Condição</label>
                                                                <div class="fs-7 fw-bold text-muted" id="condiction_desc">
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex justify-content-end align-items-center p-5 pb-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-icon btn-active-light-primary w-30px h-30px me-2 ms-auto"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#md_edit_condition">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path opacity="0.3"
                                                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                                fill="currentColor"></path>
                                                                            <path
                                                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                                fill="currentColor"></path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                                <button id="delete" type="button"
                                                                    class="btn btn-sm btn-icon btn-active-light-danger w-30px h-30px ms-auto">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path
                                                                                d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                                                fill="currentColor"></path>
                                                                            <path opacity="0.5"
                                                                                d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                                                fill="currentColor"></path>
                                                                            <path opacity="0.5"
                                                                                d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                                                fill="currentColor"></path>
                                                                        </svg>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 d-flex flex-stack">
                                                            <div class="me-5">
                                                                <label class="fs-6 fw-bold form-label">🎁
                                                                    Recompensa's</label>
                                                                <div class="fs-7 fw-bold text-muted">
                                                                    Itens recebidos quando a condição for atendida.
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end align-items-center">
                                                                <button type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#md_new_reward"
                                                                    class="btn btn-light-primary btn-sm">
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.3" x="2" y="2" width="20"
                                                                                height="20" rx="5" fill="currentColor"></rect>
                                                                            <rect x="10.8891" y="17.8033" width="12"
                                                                                height="2" rx="1"
                                                                                transform="rotate(-90 10.8891 17.8033)"
                                                                                fill="currentColor"></rect>
                                                                            <rect x="6.01041" y="10.9247" width="12"
                                                                                height="2" rx="1" fill="currentColor">
                                                                            </rect>
                                                                        </svg>
                                                                    </span>
                                                                    adicionar
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="highlight p-0" id="rewards_body">
                                                            <div class="w-100 p-5 pb-2 overflow-auto mh-400px"
                                                                id="rewards_list"></div>
                                                            <div id="no_rewards">
                                                                @include(
                                                                    'components.default.notfound',
                                                                    [
                                                                        'title' => 'Nenhuma recompensa',
                                                                        'message' =>
                                                                            'essa condição nao possui recompensas',
                                                                    ]
                                                                )
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="highlight tab-pane fade show active"
                                                        id="kt_tab_condition_no_selected">
                                                        @include(
                                                            'components.default.notfound',
                                                            [
                                                                'title' => 'Nenhuma condição selecionada',
                                                                'message' =>
                                                                    'selecione uma condição para continuar',
                                                            ]
                                                        )
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_new_condition" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Nova condição</h3>
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
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">🎲 Condição</label>
                                    <input name="condition" class="form-control form-control-solid" step="0" min="0"
                                        value="0" type="number">
                                    <div class="text-muted fs-7" id="prefix">Personagem atingir level <span
                                            class="text-primary">0</span>.</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="conditions.create()">
                                    <span class="indicator-label">Adicionar condição</span>
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
    <div class="modal fade" id="md_edit_condition" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Editando condição</h3>
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
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">🎲 Condição</label>
                                    <input name="condition" class="form-control form-control-solid" step="0" min="0"
                                        value="0" type="number">
                                    <div class="text-muted fs-7" id="prefix"></div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="conditions.update()">
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
    <div class="modal fade" id="md_new_reward" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-annex-pic" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-annex-name"></span>
                            <a href="javascript:;" id="md-annex-id" class="text-gray-800 text-hover-primary mb-1"></a>
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
                                <label for="itemID" class="form-label required">Item</label>
                                <select class="form-select form-select-solid" data-dropdown-parent="#md_new_reward"
                                    data-placeholder="Selecione um item" data-allow-clear="true"
                                    data-placeholder="Selecione o user" id="itemID" name="itemID">
                                </select>
                            </div>
                        </div>
                        <div id="md-item-info" style="display: none">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6" id="md-annex-level-area">
                                        <label for="kt_ecommerce_add_category_store_template"
                                            class="form-label required">Level</label>
                                        <select class="form-select form-select-solid mb-2" data-control="select2"
                                            data-hide-search="true" id="md-annex-in-level" data-placeholder="Nível"
                                            name="strengthLevel">
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
                                    <div class="d-flex flex-column mb-7 fv-row col-12" id="md-annex-amount-area">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">Quantidade</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="" data-bs-original-title="Specify a card holder's name"
                                                aria-label="Specify a card holder's name"></i>
                                        </label>

                                        <input type="number" id="md-annex-in-max" class="form-control form-control-solid"
                                            placeholder="" step="1" min="1" max="1" name="count" value="1" />

                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-12" id="md-annex-level-area">
                                        <label for="kt_ecommerce_add_category_store_template"
                                            class="form-label required">Validade</label>
                                        <select class="form-select form-select-solid mb-2" data-control="select2"
                                            data-hide-search="true" id="md-annex-in-validate" name="validDate"
                                            data-placeholder="Validade">
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
                                <div class="row" id="md-annex-attribute-area">
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Attack</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="attackCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Defence</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="defendCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Agility</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="agilityCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Luck</label>

                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Enter card number" name="luckCompose" value="0" />
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsBind</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se desmarcado o item <span class="text-primary">poderá ser enviado</span>
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="isBind"
                                            value="1" checked="checked" />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="text-center pt-15">
                                    <button type="button" class="btn btn-primary w-100" onclick="rewards.create()">
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
    <div class="modal fade" id="md_edit_reward" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-edit-reward-annex-pic" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-edit-reward-annex-name"></span>
                            <a href="javascript:;" id="md-edit-reward-annex-id"
                                class="text-gray-800 text-hover-primary mb-1"></a>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6" id="md-edit-reward-annex-level-area">
                                    <label for="kt_ecommerce_add_category_store_template"
                                        class="form-label required">Level</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" data-placeholder="Nível" name="strengthLevel">
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
                                <div class="d-flex flex-column mb-7 fv-row col-12" id="md-edit-reward-annex-amount-area">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Quantidade</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Specify a card holder's name"
                                            aria-label="Specify a card holder's name"></i>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" min="1" max="1"
                                        name="count" value="1" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-12" id="md-edit-reward-annex-valid-area">
                                    <label for="kt_ecommerce_add_category_store_template"
                                        class="form-label required">Validade</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" id="md-edit-reward-annex-in-validate" name="validDate"
                                        data-placeholder="Validade">
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
                            <div class="row" id="md-edit-reward-annex-attribute-area">
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Attack</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="attackCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Defence</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="defendCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Agility</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="agilityCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">Luck</label>

                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter card number" name="luckCompose" value="0" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-2">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsBind</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado o item poderá ser enviado
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="isBind" value="1"
                                        checked="checked" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="text-center pt-15">
                                <button type="button" class="btn btn-primary w-100" onclick="rewards.update()">
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
    <script>
        const activityTypes = @json($activityTypes);
    </script>
    <script src="{{ url() }}/assets/js/admin/event/activities/list.js"></script>
@endsection
