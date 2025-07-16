@extends('layouts.app')

@section('title', 'Missões')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🦄 Missões</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Missões</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select form-select-sm form-select-solid w-200px"
                        data-control="select2" data-hide-search="true" data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" data-version="{{ $server->version }}">
                                {{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-light-primary" id="btn_quest_game_update"
                    onclick="quest.updateOnGame()">
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
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="quest_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Título da missão" name="search" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select name="questType_filter"
                                                class="form-select form-select-sm form-select-solid w-60"
                                                data-hide-search="true" data-control="select2"
                                                data-placeholder="Tipo de missão">
                                                <option value="all" selected>Todos</option>
                                                @foreach ($questTypes as $types => $key)
                                                    <option value="{{ $types }}">{{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="questState_filter"
                                                class="form-select form-select-sm form-select-solid w-60"
                                                data-hide-search="true" data-control="select2"
                                                data-placeholder="Status da missão">
                                                <option value="all">🧁 Todos</option>
                                                <option value="enable" selected>🟢 Ativas</option>
                                                <option value="disable">❓ Expiradas</option>
                                                <option value="future">🚀 Futuras</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_quest_create">
                                    nova missão
                                </button>
                            </div>
                        </div>

                        <div class="card-body pt-4 ps-7">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhuma missão encontrada',
                                ])
                            </div>
                            <div id="quest_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>
                            <div id="quest_list_footer" class="me-n5" style="display: none;">
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
                                'message' => 'clique em uma missão para continuar',
                            ])
                        </div>
                        <div id="unbearable" style="display:none;">
                            @include('components.default.notfound', [
                                'title' => 'Whoops',
                                'message' =>
                                    'o site ainda não fornece suporte a esta função para <br>o servidor selecionado.',
                            ])
                        </div>
                        <div id="quest_data" style="display:none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#detail">
                                                    📝 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#condition">
                                                    🎯 Condições
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#reward">
                                                    📦 Recompensas
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div id="condition_toolbar" style="display: none;">
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
                                            apagar tudo
                                        </button>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#md_condition_create" class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                        height="20" rx="5" fill="currentColor"></rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2"
                                                        rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2"
                                                        rx="1" fill="currentColor"></rect>
                                                </svg>
                                            </span>
                                            nova condição
                                        </button>
                                    </div>
                                    <div id="reward_toolbar" style="display: none;">
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
                                            apagar tudo
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_reward_create"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                        height="20" rx="5" fill="currentColor"></rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2"
                                                        rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2"
                                                        rx="1" fill="currentColor"></rect>
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
                                        @include('components.admin.game.quest.detail')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="condition" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="no_condition">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem condições',
                                                'message' => 'essa missão não possui nenhuma condição',
                                            ])
                                        </div>
                                        <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px" id="condition_list"
                                            style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reward" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="no_reward">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem recompensas',
                                                'message' => 'essa missão não possui nenhuma recompensa',
                                            ])
                                        </div>
                                        <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px" id="reward_list"
                                            style="display: none;">
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
    <div class="modal fade" id="md_quest_create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
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
                    <div id="modal_data">
                        @include('components.admin.game.quest.create')
                    </div>
                    <div id="unbearable" style="display:none;">
                        @include('components.default.notfound', [
                            'title' => 'Whoops',
                            'message' =>
                                'o site ainda não fornece suporte para a <br>versão do servidor selecionado.',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_condition_create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Adicionar condition</h4>
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
                    @include('components.admin.game.quest.condition.create')
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_condition_update" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Editar condição</h4>
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
                    @include('components.admin.game.quest.condition.update')
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
                    @include('components.admin.game.quest.reward.create')
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
                    @include('components.admin.game.quest.reward.update')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        const questConditionTypes = @json($questConditions);
    </script>
    <script src="{{ url() }}/assets/js/admin/quest/list.js"></script>
@endsection
