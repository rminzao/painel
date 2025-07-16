@extends('layouts.app')

@section('title', 'Missão de atividade')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📖 Missão de atividade</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Missão de atividade</li>
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

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="activity.updateOnGame()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
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
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Título da missão" id="search" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <select name="questState_filter"
                                            class="form-select form-select-sm form-select-solid w-60"
                                            data-hide-search="true" data-control="select2"
                                            data-placeholder="Status da missão">
                                            <option value="1" selected>☀️ Deus guia</option>
                                            <option value="2" disabled>⛅ 7 Prêmios (em breve)</option>
                                            <option value="3" disabled>🎌 Evento novato (em breve)</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_quest_new">
                                    nova missão
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7" id="activity_body">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum evento encontrado',
                                ])
                            </div>
                            <div id="activity_list"></div>
                            <div id="activity_list_footer" style="display:none;">
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
                                    <div id="paginator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card">
                        <div id="no_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um evento para continuar',
                            ])
                        </div>
                        <div id="activity_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#detail">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#conditions">
                                                    🎯 Condições
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#rewards">
                                                    📦 Recompensas
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div id="toolbar_conditions" style="display:none;">
                                        <button type="button" onclick="condition.delete(0)"
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
                                            apagar todas
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_condition_new"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                        height="20" rx="5" fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2"
                                                        rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2"
                                                        rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            nova condição
                                        </button>
                                    </div>
                                    <div id="toolbar_rewards" style="display:none;">
                                        <button type="button" onclick="reward.delete(0)"
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
                                            apagar todas
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_reward_create"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                        height="20" rx="5" fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2"
                                                        rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2"
                                                        rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            nova recompensa
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="detail" role="tabpanel">
                                    <div class="card-body">
                                        <form>
                                            <input type="hidden" name="ID">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-8">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Title" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">❓ Tipo</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="QuestType" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">🎫 Descrição</label>
                                                        <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Detail"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">📜 Objetivo</label>
                                                        <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Objective"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="QuestID" class="form-label">🕋 Nv. Mínimo</label>
                                                        <input type="number"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="NeedMinLevel" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="" class="form-label">🕋 Nv. Máximo</label>
                                                        <input type="number"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="NeedMaxLevel" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label for="" class="form-label">🦽 Periodo</label>
                                                        <input type="number"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Period" value="" />
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-sm btn-light-primary w-100"
                                                        onclick="activity.update()" id="btn_activity_update">
                                                        <span class="indicator-label">Aplicar alterações</span>
                                                        <span class="indicator-progress">
                                                            aplicando...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="conditions" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="conditions_body">
                                            <div id="no_conditions">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem condições',
                                                    'message' => 'esse evento não possui nenhuma condição',
                                                ])
                                            </div>
                                            <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px"
                                                id="conditions_list" style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="rewards" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="rewards_body">
                                            <div id="no_rewards">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem recompensas',
                                                    'message' => 'esse evento não possui nenhuma recompensa',
                                                ])
                                            </div>
                                            <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px" id="rewards_list"
                                                style="display:none;">
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
    <div class="modal fade" id="md_quest_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bolder">🎯 Nova missão</h4>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-8">
                                    <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="Title" value="" placeholder="Ex: Vencer 20 batalhas" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">❓ Tipo</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="QuestType" value="2" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🎫 Descrição</label>
                                    <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Detail"
                                        data-kt-autosize="true" placeholder="Ex: vença batalhas"></textarea>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">📜 Objetivo</label>
                                    <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Objective"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label for="QuestID" class="form-label">🕋 Nv. Mínimo</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="NeedMinLevel" value="0" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🕋 Nv. Máximo</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="NeedMaxLevel" value="70" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🦽 Periodo</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Period" value="1" />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100"
                                    onclick="activity.create()" id="btn_activity_create">
                                    <span class="indicator-label">Adicionar</span>
                                    <span class="indicator-progress">
                                        adicionando...
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
    <div class="modal fade" id="md_condition_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bolder">📜 Nova condição</h4>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-9">
                                    <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="CondictionTitle" value="" placeholder="Ex: Vencer 20 batalhas" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">❓ CondictionID</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="CondictionID" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📼 Tipo</label>
                                    <select name="CondictionType" class="form-select form-select-sm form-select-solid"
                                        data-control="select2" data-hide-search="true"
                                        data-placeholder="Selecione um tipo">
                                        <option value="" selected></option>
                                        @foreach ($activityConditions as $key => $types)
                                            <option value="{{ $key }}">
                                                ({{ $key }})
                                                - {{ $types['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🦽 IndexType</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="IndexType" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="Para1" class="form-label">❓ Para1</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Para1" value="0" />
                                    <div id="para1_select" style="display:none;">
                                        <select name="Para1" class="form-select form-select-sm form-select-solid"
                                            data-control="select2" data-hide-search="true" data-placeholder="para1"
                                            disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="Para2" class="form-label">❓ Para 2</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Para2" value="0" />
                                    <div id="para2_select" style="display:none;">
                                        <select name="Para2" class="form-select form-select-sm form-select-solid"
                                            data-control="select2" data-hide-search="true" data-placeholder="para2"
                                            disabled>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100"
                                    onclick="condition.create()" id="btn_condition_create">
                                    <span class="indicator-label">Adicionar</span>
                                    <span class="indicator-progress">
                                        adicionando...
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
    <div class="modal fade" id="md_condition_update" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-bolder">📜 Editando condição</h4>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form>
                        <input type="hidden" name="QuestID">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-9">
                                    <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="CondictionTitle" value="" placeholder="Ex: Vencer 20 batalhas" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">❓ CondictionID</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="CondictionID" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📼 Tipo</label>
                                    <select name="CondictionType" class="form-select form-select-sm form-select-solid"
                                        data-control="select2" data-hide-search="true"
                                        data-placeholder="Selecione um tipo">
                                        <option value="" selected></option>
                                        @foreach ($activityConditions as $key => $types)
                                            <option value="{{ $key }}">
                                                ({{ $key }})
                                                - {{ $types['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🦽 IndexType</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="IndexType" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="Para1" class="form-label">❓ Para1</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Para1" value="0" />
                                    <div id="para1_select" style="display:none;">
                                        <select name="Para1" class="form-select form-select-sm form-select-solid"
                                            data-control="select2" data-hide-search="true" data-placeholder="para1"
                                            disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="Para2" class="form-label">❓ Para 2</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Para2" value="0" />
                                    <div id="para2_select" style="display:none;">
                                        <select name="Para2" class="form-select form-select-sm form-select-solid"
                                            data-control="select2" data-hide-search="true" data-placeholder="para2"
                                            disabled>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100"
                                    onclick="condition.update()" id="btn_condition_update">
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
    <div class="modal fade" id="md_reward_create" tabindex="-1" aria-hidden="true">
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
                    <form>
                        <div class="row">
                            <label class="form-label required">Item</label>
                            <select class="form-select form-select-sm form-select-solid"
                                data-dropdown-parent="#md_reward_create" data-placeholder="Selecione um item"
                                data-allow-clear="true" name="TemplateId">
                            </select>
                        </div>
                        <div id="info_area" style="display: none">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mt-7">
                                    <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                                        <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                            Level
                                        </label>
                                        <select class="form-select form-select-sm form-select-solid mb-2"
                                            data-control="select2" data-hide-search="true" data-placeholder="Nível"
                                            name="StrengthenLevel">
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
                                    <div class="d-flex flex-column mb-5 fv-row col-12" id="count_area">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">Quantidade</span>
                                        </label>
                                        <input type="number" id="md-edit-reward-annex-in-max"
                                            class="form-control form-control-sm form-control-solid" min="1"
                                            max="1" name="Count" value="1" />

                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-5 fv-row col-12"
                                        id="md-edit-reward-annex-valid-area">
                                        <label for="kt_ecommerce_add_category_store_template"
                                            class="form-label required">Validade</label>
                                        <select class="form-select form-select-sm form-select-solid mb-2"
                                            data-control="select2" data-hide-search="true" name="ValidDate">
                                            <option value="0" selected>Permanente</option>
                                            <option value="1">1 Dia</option>
                                            <option value="3">3 Dias</option>
                                            <option value="7">7 Dias</option>
                                            <option value="15">15 Dias</option>
                                            <option value="30">30 Dias</option>
                                            <option value="365">365 Dias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="attr_area">
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="AttackCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="DefendCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="AgilityCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="LuckCompose" value="0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-5 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">❓ Period</span>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            placeholder="" step="0" min="0" name="Period"
                                            value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">❓ QuestType</span>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            placeholder="" step="0" min="0" name="QuestType"
                                            value="0" />
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">🍃 Limitado</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se desmarcado o item <span class="text-danger">poderá ser enviado</span>
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBinds"
                                            value="1" checked="checked" />
                                    </label>
                                </div>
                                <div class="text-center">
                                    <button type="button" onclick="reward.create()" id="btn_reward_create"
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
    <div class="modal fade" id="md_reward_update" tabindex="-1" aria-hidden="true">
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
                    <form>
                        <input type="hidden" name="ID">
                        <input type="hidden" name="TemplateID">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                                    <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                        Level
                                    </label>
                                    <select class="form-select form-select-sm form-select-solid mb-2"
                                        data-control="select2" data-hide-search="true" data-placeholder="Nível"
                                        name="StrengthenLevel">
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
                                <div class="d-flex flex-column mb-5 fv-row col-12" id="count_area">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Quantidade</span>
                                    </label>
                                    <input type="number" id="md-edit-reward-annex-in-max"
                                        class="form-control form-control-sm form-control-solid" min="1"
                                        max="1" name="Count" value="1" />

                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-12" id="md-edit-reward-annex-valid-area">
                                    <label for="kt_ecommerce_add_category_store_template" class="form-label required">📅
                                        Validade</label>
                                    <select class="form-select form-select-sm form-select-solid mb-2"
                                        data-control="select2" data-hide-search="true" name="ValidDate">
                                        <option value="0" selected>Permanente</option>
                                        <option value="1">1 Dia</option>
                                        <option value="3">3 Dias</option>
                                        <option value="7">7 Dias</option>
                                        <option value="15">15 Dias</option>
                                        <option value="30">30 Dias</option>
                                        <option value="365">365 Dias</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="attr_area">
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔴 Atq.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="AttackCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔵 Def.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="DefendCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟢 Agl.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="AgilityCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟡 Srt.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="LuckCompose" value="0" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-6">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">❓ Period</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        placeholder="" step="0" min="0" name="Period" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-6">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">❓ QuestType</span>
                                    </label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        placeholder="" step="0" min="0" name="QuestType" value="0" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🍃 Limitado</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado o item <span class="text-danger">poderá ser enviado</span>
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBinds"
                                        value="1" checked="checked" />
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="reward.update()" id="btn_reward_update"
                                    class="btn btn-sm btn-light-primary w-100">
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
        const activityConditions = @json($activityConditions);
    </script>
    <script src="{{ url() }}/assets/js/admin/event/activityQuest/list.js"></script>
@endsection
