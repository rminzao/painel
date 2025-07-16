@extends('layouts.app')

@section('title', 'Evento Belo')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📅 Evento Belo</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Evento Belo</li>
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

                <button type="button" class="btn btn-light-primary me-2" data-bs-toggle="modal"
                    data-bs-target="#md_new_event">
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
                        novo evento
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                    </span>
                </button>
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

                        <div class="card-body pt-4 ps-7">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum evento encontrado',
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
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <!--begin::User-->
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab"
                                                    href="#event_detail">📃 Detalhes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab"
                                                    href="#event_rewards">📦 Recompensas</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <div id="reward_buttons" style="display:none;">
                                        <button type="button" onclick="rewards.delete(0)"
                                            class="btn btn-light-danger btn-sm me-3">
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
                                            Apagar todas
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_reward"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                        fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar recompensa
                                        </button>
                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body" id="kt_chat_messenger_body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="event_detail" role="tabpanel">
                                        <form>
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                                        <input type="text" class="form-control form-control-solid" name="Title" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">ActionTimeContent</label>
                                                        <input type="text" class="form-control form-control-solid" name="ActionTimeContent" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🎫 Descrição</label>
                                                        <textarea class="form-control form-control form-control-solid" rows="1" name="Description" data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">🧵 Content</label>
                                                        <textarea class="form-control form-control form-control-solid" rows="1" name="Content" data-kt-autosize="true"></textarea>
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">🎠 AwardContent</label>
                                                        <textarea class="form-control form-control form-control-solid" rows="1" name="AwardContent" data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">📅 StartDate</label>
                                                        <input class="form-control form-control-solid" name="StartDate" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">📅 EndDate</label>
                                                        <input class="form-control form-control-solid" name="EndDate" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="QuestID" class="form-label">🔑 HasKey</label>
                                                        <select class="form-select form-select-solid" data-control="select2"
                                                            data-placeholder="Selecione o Tipo" data-hide-search="true" name="HasKey">
                                                            @for ($i = 1; $i <= 4; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="" class="form-label">🕋 Type</label>
                                                        <select class="form-select form-select-solid" data-control="select2"
                                                            data-placeholder="Selecione o Tipo" data-hide-search="true" name="Type">
                                                            <option value="1">1</option>
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="" class="form-label">🦽 ActiveType</label>
                                                        <select class="form-select form-select-solid" data-control="select2"
                                                            data-placeholder="Selecione o Tipo" data-hide-search="true" name="ActiveType">
                                                            <option value="0">0</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">GoodsExchangeTypes</label>
                                                        <input type="text" class="form-control form-control-solid" name="GoodsExchangeTypes" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">GoodsExchangeNum</label>
                                                        <input type="text" class="form-control form-control-solid" name="GoodsExchangeNum" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">limitType</label>
                                                        <input type="text" class="form-control form-control-solid" name="limitType" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">limitValue</label>
                                                        <input type="text" class="form-control form-control-solid" name="limitValue" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">IconID</label>
                                                        <input type="text" class="form-control form-control-solid" name="IconID" value="0" />
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">IsOnly</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                        </div>
                                                    </div>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsOnly" value="1" checked />
                                                        <span class="form-check-label fw-bold text-muted"></span>
                                                    </label>
                                                </div>
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">IsAdvance</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                        </div>
                                                    </div>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsAdvance" value="1" checked />
                                                        <span class="form-check-label fw-bold text-muted"></span>
                                                    </label>
                                                </div>
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">IsShow</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                        </div>
                                                    </div>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsShow" value="1" checked />
                                                        <span class="form-check-label fw-bold text-muted"></span>
                                                    </label>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary w-100" onclick="events.update()">
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
                                    <div class="tab-pane fade" id="event_rewards" role="tabpanel">
                                        <div id="rewards_body">
                                            <div id="no_rewards" style="display:none;">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem recompensas',
                                                    'message' => 'esse evento não possui nenhuma recompensa',
                                                ])
                                            </div>
                                            <div class="highlight w-100 pt-5 pb-2 overflow-auto mh-400px" id="rewards_list">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_new_event" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Novo evento</h3>
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
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                    <input type="text" class="form-control form-control-solid" name="Title" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">ActionTimeContent</label>
                                    <input type="text" class="form-control form-control-solid" name="ActionTimeContent" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🎫 Descrição</label>
                                    <textarea class="form-control form-control form-control-solid" rows="1" name="Description" data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🧵 Content</label>
                                    <textarea class="form-control form-control form-control-solid" rows="1" name="Content" data-kt-autosize="true"></textarea>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🎠 AwardContent</label>
                                    <textarea class="form-control form-control form-control-solid" rows="1" name="AwardContent" data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">📅 StartDate</label>
                                    <input class="form-control form-control-solid" name="StartDate" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">📅 EndDate</label>
                                    <input class="form-control form-control-solid" name="EndDate" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label for="QuestID" class="form-label">🔑 HasKey</label>
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Selecione o Tipo" data-hide-search="true" name="HasKey">
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🕋 Type</label>
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Selecione o Tipo" data-hide-search="true" name="Type">
                                        <option value="1">1</option>
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🦽 ActiveType</label>
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Selecione o Tipo" data-hide-search="true" name="ActiveType">
                                        <option value="0">0</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">GoodsExchangeTypes</label>
                                    <input type="text" class="form-control form-control-solid" name="GoodsExchangeTypes" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">GoodsExchangeNum</label>
                                    <input type="text" class="form-control form-control-solid" name="GoodsExchangeNum" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">limitType</label>
                                    <input type="text" class="form-control form-control-solid" name="limitType" value="" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">limitValue</label>
                                    <input type="text" class="form-control form-control-solid" name="limitValue" value="" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">IconID</label>
                                    <input type="text" class="form-control form-control-solid" name="IconID" value="0" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsOnly</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsOnly" value="1" checked />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsAdvance</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsAdvance" value="1" checked />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsShow</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsShow" value="1" checked />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="events.create()">
                                    <span class="indicator-label">Criar evento</span>
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
                                <div class="text-center">
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
                            <div class="text-center">
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

    </script>
    <script src="{{ url() }}/assets/js/admin/event/activity/list.js"></script>
@endsection
