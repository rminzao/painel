@extends('layouts.app')

@section('title', 'Enviar item')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📦 Enviar item</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Enviar item</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!--begin::Container-->
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid row" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Main column-->
                <div class="col-lg-7 mb-10 mb-lg-0 me-lg-7 me-xl-5">
                    <form action="{{ url('api/admin/item/send') }}" method="post">
                        <!--begin::General options-->
                        <div class="card" id="kt_block_ui_mail_detail">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-10">
                                        <label for="kt_ecommerce_add_category_store_template" class="form-label">🌍
                                            Servidor</label>
                                        <select class="form-select form-select-solid" data-control="select2"
                                            data-hide-search="true" data-placeholder="Selecione o servidor" name="sid"
                                            id="sid">
                                            <option></option>
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                                    {{ $server->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-muted fs-7">Selecione o servidor.</div>
                                    </div>
                                    <div class="col-6 mb-10">
                                        <label for="uid" class="form-label">👨‍💼 Usuário's</label>
                                        <select class="form-select form-select-solid" data-control="select2"
                                            multiple="multiple" data-placeholder="Selecione o user" name="uid[]"
                                            id="uid">
                                            <option></option>
                                        </select>
                                        <div class="text-muted fs-7">Selecione o's usuário's que irá receber os itens.</div>
                                    </div>
                                </div>

                                <div class="mb-10 d-flex flex-stack">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">🎁 Online only</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se marcado enviará os itens a todos os jogadores online
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="isOnline"
                                            id="isOnline" value="1">
                                    </label>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">📌 Título</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Título do email" name="title" value="Envios da administração">
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">📃 Mensagem</label>
                                    <textarea class="form-control form-control-solid" name="content" id="" cols="30" rows="3">Itens enviados pela administração</textarea>
                                </div>

                                <div class="mb-10">
                                    <span class="form-label mb-5" style="width: 100%; line-height: 36px;">
                                        📦 Anexos
                                        <button type="button" id="clear-annex-button" class="btn btn-light-danger btn-sm"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                            style="float: right;">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
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
                                            Remover todos
                                        </button>
                                    </span>
                                    <div class="highlight w-100 mt-3 pt-5 pb-2 overflow-auto mh-250px">
                                        <div id="not_annex">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem dados',
                                                'message' => 'nenhum item selecionado',
                                            ])
                                        </div>
                                        <div id="annex-list"></div>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <button type="submit" class="btn btn-primary w-100" id="button_send_item">
                                        <span class="indicator-label">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z"
                                                        fill="currentColor"></path>
                                                    <path opacity="0.3"
                                                        d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>Enviar item
                                        </span>
                                        <span class="indicator-progress">enviando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::General options-->
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="card card-flush" id="kt_block_ui_item_list">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome do item" id="search">
                                        </div>
                                    </div>
                                    <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                        data-placeholder="Categoria" id="category">
                                        <option value="0" selected>Todos</option>
                                    </select>
                                </div>
                                <a class="btn btn-sm btn-light-primary d-flex
                                align-items-center"
                                    href="{{ url('admin/game/item') }}" target="_blank">
                                    Lista de item
                                </a>
                            </div>
                        </div>


                        <div class="card-body d-flex flex-column align-items-center pt-4 px-5 w-100">
                            <div id="item-list" style="max-width: 490px!important;" class="row w-100"></div>
                            <div id="item_paginator"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Post-->
    </div>
    <!--end::Container-->
@endsection

@section('modals')
    <div class="modal fade" id="kt_modal_append_mail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-annex-pic"
                                    onerror="if (this.src != '{{ url('assets/media/icons/original.png') }}') this.src = '{{ url('assets/media/icons/original.png') }}';"
                                    class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-annex-name"></span>
                            <span id="md-annex-id" class="text-gray-800 mb-1"></span>
                        </div>
                    </div>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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
                        <input type="hidden" id="md-annex-in-id" />
                        <input type="hidden" id="md-annex-in-pic" />
                        <input type="hidden" id="md-annex-in-name" />
                        <input type="hidden" id="md-annex-in-cat" />
                        <input type="hidden" id="md-annex-in-strengthen" />
                        <input type="hidden" id="md-annex-in-compose" />

                        <div class="row">
                            <div class="d-flex flex-column mb-5 fv-row col-6" id="md-annex-level-area">
                                <label for="kt_ecommerce_add_category_store_template" class="form-label">📂 Level</label>
                                <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                                    data-hide-search="true" id="md-annex-in-level" data-placeholder="Nível"
                                    name="level[]">
                                    <option></option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">Nível {{ $i }}</option>
                                    @endfor
                                    <option value="13">Avanço 1</option>
                                    <option value="14">Avanço 2</option>
                                    <option value="15">Avanço 3</option>
                                </select>
                                <div class="text-muted fs-7">Selecione o nível do item.</div>
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row col-12" id="md-annex-amount-area">
                                <div class="d-flex justify-content-between">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        💾 Quantidade
                                    </label>
                                    <span class="text-muted text-hover-primary cursor-pointer" onclick="setMaxCount()">
                                        max
                                    </span>
                                </div>

                                <input type="number" id="md-annex-in-max"
                                    class="form-control form-control-sm form-control-solid" placeholder="" step="1"
                                    min="1" max="999" name="amount[]" value="1" />

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="d-flex flex-column mb-5 fv-row col-12" id="md-annex-level-area">
                                <label for="kt_ecommerce_add_category_store_template"
                                    class="form-label">🗓️ Validade</label>
                                <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                                    data-hide-search="true" id="md-annex-in-validate" data-placeholder="Validade">
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
                            <div class="d-flex flex-column mb-5 fv-row col-3">
                                <label class="fs-6 fw-bold form-label mb-2">Attack</label>

                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    placeholder="Enter card number" name="card_number" id="md-annex-in-attack"
                                    value="0" />
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row col-3">
                                <label class="fs-6 fw-bold form-label mb-2">Defence</label>

                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    placeholder="Enter card number" name="card_number" id="md-annex-in-defence"
                                    value="0" />
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row col-3">
                                <label class="fs-6 fw-bold form-label mb-2">Agility</label>

                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    placeholder="Enter card number" name="card_number" id="md-annex-in-agility"
                                    value="0" />
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row col-3">
                                <label class="fs-6 fw-bold form-label mb-2">Luck</label>

                                <input type="text" class="form-control form-control-sm form-control-solid"
                                    placeholder="Enter card number" name="card_number" id="md-annex-in-luck"
                                    value="0" />
                            </div>
                        </div>

                        <div class="d-flex flex-stack mb-5">
                            <div class="me-5">
                                <label class="fs-6 fw-bold form-label">🍃 Limitado</label>
                                <div class="fs-7 fw-bold text-muted">
                                    Se desmarcado o item <span class="text-danger">poderá ser enviado</span>
                                </div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-30px" type="checkbox" id="md-annex-in-isbinds"
                                    value="1" checked="checked">
                            </label>
                        </div>

                        <div class="text-center">
                            <button type="button" onclick="appendAttachments()"
                                class="btn btn-sm btn-light-primary w-100">
                                <span class="indicator-label">Adicionar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/item/send.js"></script>
@endsection
