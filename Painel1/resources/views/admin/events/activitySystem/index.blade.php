@extends('layouts.app')

@section('title', 'Activity System')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🎁 Atividasdes do sistema</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">ActivitySystem</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>{{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">

                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card">
                        <div class="card-body pt-4 ps-7">
                            @if (isset($activityTypes) and !empty($activityTypes))
                                <div class="scroll-y me-n5 h-lg-auto">
                                    @foreach ($activityTypes as $key => $type)
                                        <div class="d-flex flex-stack pt-2">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <a href="javascript:;"
                                                                onclick="activity.list({{ $key }})"
                                                                class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">
                                                                {{ $type }}
                                                            </a>
                                                            <div class="text-muted fs-7 mb-1">
                                                                🎫 Type: {{ $key }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-end ms-2">
                                                <button type="button"
                                                    class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary"
                                                    onclick="activity.list({{ $key }})">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                                fill="currentColor" />
                                                            <path
                                                                d="M11.9343 12.5657L9.53696 14.963C9.22669 15.2733 9.18488 15.7619 9.43792 16.1204C9.7616 16.5789 10.4211 16.6334 10.8156 16.2342L14.3054 12.7029C14.6903 12.3134 14.6903 11.6866 14.3054 11.2971L10.8156 7.76582C10.4211 7.3666 9.7616 7.42107 9.43792 7.87962C9.18488 8.23809 9.22669 8.72669 9.53696 9.03696L11.9343 11.4343C12.2467 11.7467 12.2467 12.2533 11.9343 12.5657Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                        @if (!$loop->last)
                                            <div class="pt-2 separator separator-dashed"></div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div>
                                    @include('components.default.notfound', [
                                        'title' => 'Opss',
                                        'message' => 'nenhum evento encontrado',
                                    ])
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="activity_data_body">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um evento para continuar',
                            ])
                        </div>
                        <div id="activity_data" style="display: none;">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <!--begin::User-->
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link">🎯 <span class="ms-2"
                                                        id="event_selected_title"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row">
                                    <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6"
                                        id="qualities_tab"></ul>
                                    <div class="tab-content w-100">
                                        <div class="p-0 tab-pane fade" id="kt_tab_condition_rewards" role="tabpanel">
                                            <div class="mb-1 d-flex flex-stack">
                                                <div class="me-5">
                                                    <label class="fs-6 fw-bold form-label">🎁 Recompensa's</label>
                                                    <div class="fs-7 fw-bold text-muted">
                                                        Itens recebidos quando a condição for atendida.
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#md_new_item" class="btn btn-light-primary btn-sm">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                    rx="5" fill="currentColor"></rect>
                                                                <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                                    transform="rotate(-90 10.8891 17.8033)" fill="currentColor">
                                                                </rect>
                                                                <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                                                                    fill="currentColor">
                                                                </rect>
                                                            </svg>
                                                        </span>
                                                        adicionar
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="highlight p-0" id="rewards_body">
                                                <div class="w-100 p-5 pb-2 overflow-auto mh-400px" id="rewards_list"></div>
                                                <div id="no_rewards">
                                                    <div class="tab-pane fade active show" id="kt_topbar_notifications_1"
                                                        role="tabpanel">
                                                        <div data-kt-search-element="empty" class="text-center">
                                                            <div class="pt-10 pb-5">
                                                                <span class="svg-icon svg-icon-4x opacity-50">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path opacity="0.3"
                                                                            d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                                                            fill="currentColor"></path>
                                                                        <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z"
                                                                            fill="currentColor">
                                                                        </path>
                                                                        <rect x="13.6993" y="13.6656" width="4.42828"
                                                                            height="1.73089" rx="0.865447"
                                                                            transform="rotate(45 13.6993 13.6656)"
                                                                            fill="currentColor"></rect>
                                                                        <path
                                                                            d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>

                                                            <div class="pb-15 fw-bold">
                                                                <h3 class="text-gray-600 fs-5 mb-2">Nenhuma recompensa</h3>
                                                                <div class="text-muted fs-7"> essa condição nao possui
                                                                    recompensas</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="highlight tab-pane fade show active" id="kt_tab_condition_no_selected">
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
                                                        <h3 class="text-gray-600 fs-5 mb-2">Nenhuma condição selecionada
                                                        </h3>
                                                        <div class="text-muted fs-7"> selecione uma condição para continuar
                                                        </div>
                                                    </div>
                                                </div>
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
    <div class="modal fade" id="md_new_item" tabindex="-1" aria-hidden="true">
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="TemplateID" class="form-label required">📦 Item</label>
                                    <select class="form-select form-select-solid" data-dropdown-parent="#md_new_item"
                                        data-placeholder="Selecione um item" data-allow-clear="true" id="TemplateID"
                                        name="TemplateID">
                                    </select>
                                </div>
                            </div>
                            <div id="md-item-info" style="display: none">
                                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row">
                                        <div class="fv-row col-6 mb-7" id="md-annex-level-area">
                                            <label class="fs-6 fw-bold form-label mb-2">💪 Level</label>
                                            <select class="form-select form-select-solid mb-2" data-control="select2"
                                                data-hide-search="true" id="md-annex-in-level" data-placeholder="Nível"
                                                name="StrengthLevel">
                                                <option></option>
                                                <option value="0" selected>Sem level</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">Nível {{ $i }}</option>
                                                @endfor
                                                <option value="13">Avanço 1</option>
                                                <option value="14">Avanço 2</option>
                                                <option value="15">Avanço 3</option>
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-12" id="md-annex-amount-area">
                                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                <span class="required">Quantidade</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                    title="" data-bs-original-title="Specify a card holder's name"
                                                    aria-label="Specify a card holder's name"></i>
                                            </label>

                                            <input type="number" id="md-annex-in-max"
                                                class="form-control form-control-solid" placeholder="" step="1" min="1"
                                                max="1" name="count" value="1" />

                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-12">
                                            <label class="fs-6 fw-bold form-label mb-2">👛 Quality</label>
                                            <input type="number" class="form-control form-control-solid" name="Quality"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6" id="validData">
                                            <label class="fs-6 fw-bold form-label mb-2">📅 Validade</label>
                                            <select class="form-select form-select-solid mb-2" data-control="select2"
                                                data-hide-search="true" name="ValidDate" data-placeholder="Validade">
                                                <option value="0" selected>Permanente</option>
                                                <option value="1">1 Dia</option>
                                                <option value="3">3 Dias</option>
                                                <option value="7">7 Dias</option>
                                                <option value="15">15 Dias</option>
                                                <option value="30">30 Dias</option>
                                                <option value="365">365 Dias</option>
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">🎲 Probability</label>
                                            <input type="number" class="form-control form-control-solid" name="Probability"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row" id="md-annex-attribute-area">
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🔺 Atq.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="AttackCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🔹 Def.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="DefendCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🟢 Agl.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="AgilityCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🟨 Srt.</label>
                                            <input type="number" class="form-control form-control-solid" name="LuckCompose"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">IsBind</label>
                                                <div class="fs-7 fw-bold text-muted"> Se desmarcado o item irá ficar <span
                                                        class="text-success">ilimitado</span> (<span
                                                        class="text-primary">pode
                                                        ser enviado</span>) </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind"
                                                    value="1" />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canRenew</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canRenew" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canTransfer</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canTransfer" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canRepeat</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canRepeat" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary w-100"
                                            onclick="activityReward.create()">
                                            <span class="indicator-label">Adicionar item</span>
                                            <span class="indicator-progress">
                                                criando...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_edit_item" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-edit-annex-pic" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-edit-annex-name"></span>
                            <a href="javascript:;" id="md-edit-annex-id" class="text-gray-800 text-hover-primary mb-1"></a>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div>
                                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row">
                                        <input type="hidden" name="ID">
                                        <div class="fv-row col-6 mb-7">
                                            <label class="fs-6 fw-bold form-label mb-2">💪 Level</label>
                                            <select class="form-select form-select-solid mb-2" data-control="select2"
                                                data-hide-search="true" data-placeholder="Nível"
                                                name="StrengthLevel">
                                                <option></option>
                                                <option value="0">Sem level</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">Nível {{ $i }}</option>
                                                @endfor
                                                <option value="13">Avanço 1</option>
                                                <option value="14">Avanço 2</option>
                                                <option value="15">Avanço 3</option>
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-6">
                                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                <span class="required">Quantidade</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                    title="" data-bs-original-title="Specify a card holder's name"
                                                    aria-label="Specify a card holder's name"></i>
                                            </label>

                                            <input type="number"
                                                class="form-control form-control-solid" placeholder="" step="1" min="1"
                                                max="1" name="Count" value="1" />

                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">📅 Validade</label>
                                            <select class="form-select form-select-solid mb-2" data-control="select2"
                                                data-hide-search="true" name="ValidDate" data-placeholder="Validade">
                                                <option value="0" selected>Permanente</option>
                                                <option value="1">1 Dia</option>
                                                <option value="3">3 Dias</option>
                                                <option value="7">7 Dias</option>
                                                <option value="15">15 Dias</option>
                                                <option value="30">30 Dias</option>
                                                <option value="365">365 Dias</option>
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">🎲 Probability</label>
                                            <input type="number" class="form-control form-control-solid" name="Probability"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row" id="md-annex-attribute-area">
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🔺 Atq.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="AttackCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🔹 Def.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="DefendCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🟢 Agl.</label>
                                            <input type="number" class="form-control form-control-solid"
                                                name="AgilityCompose" value="0" />
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-3">
                                            <label class="fs-6 fw-bold form-label mb-2">🟨 Srt.</label>
                                            <input type="number" class="form-control form-control-solid" name="LuckCompose"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">IsBind</label>
                                                <div class="fs-7 fw-bold text-muted"> Se desmarcado o item irá ficar <span
                                                        class="text-success">ilimitado</span> (<span
                                                        class="text-primary">pode
                                                        ser enviado</span>) </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind"
                                                    value="1" />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canRenew</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canRenew" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canTransfer</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canTransfer" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                        <div class="d-flex flex-stack mb-7">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">canRepeat</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="canRepeat" value="1" checked />
                                                <span class="form-check-label fw-bold text-muted"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary w-100"
                                            onclick="activityReward.update()">
                                            <span class="indicator-label">Aplicar alterações</span>
                                            <span class="indicator-progress">
                                                aplicando...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
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
        const activityTypeList = @json($activityTypes)
    </script>
    <script src="{{ url() }}/assets/js/admin/event/activitySystem/list.js"></script>
@endsection
