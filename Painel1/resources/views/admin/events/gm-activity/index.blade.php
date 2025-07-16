@extends('layouts.app')

@section('title', 'GM Activity')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📅 GM Activity</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">GM Activity</li>
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
                                                placeholder="Nome do evento" name="search">
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="w-100 me-3">
                                            <select name="type" class="form-select form-select-sm form-select-solid w-60"
                                                data-control="select2" data-placeholder="Categoria do evento">
                                                <option value="all" selected>Todos</option>
                                                @foreach ($activityTypes as $key => $type)
                                                    <option value="{{ $key }}">
                                                        {{ $type['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-30">
                                            <select id="eventState_filter"
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
                                    data-bs-target="#md_new_event">
                                    novo evento
                                </button>
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
                                                <a class="nav-link active" data-bs-toggle="tab" href="#event_detail">📃
                                                    Detalhes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#event_gift">📁 Gift &
                                                    condições</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#event_rewards">📦
                                                    Recompensas</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div id="gift_buttons" style="display:none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_gift"
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
                                                        rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar gift
                                        </button>
                                    </div>
                                    <div id="condition_buttons" style="display:none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_condition"
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
                                                        rx="1" fill="currentColor">
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
                                    <div class="tab-pane fade show active" id="event_detail" role="tabpanel">
                                        <form>
                                            <input type="hidden" name="activityId">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="activityName" value="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🥖 Categoria</label>
                                                        <select name="activityType" class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-placeholder="Categoria do evento">
                                                            @foreach ($activityTypes as $key => $type)
                                                                <option value="{{ $key }}">
                                                                    {{ $type['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">💽 Sub
                                                            categoria</label>
                                                        <select name="activityChildType"
                                                            class="form-select form-select-sm form-select-solid" data-control="select2"
                                                            data-placeholder="Categoria do evento">
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">❓ getWay</label>
                                                        <select name="getWay" class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-hide-search="true"
                                                            data-placeholder="getWay">
                                                            <option value="0" selected>0</option>
                                                            <option value="1">1</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🍃 Descrição</label>
                                                        <textarea class="form-control form-control form-control-solid" name="desc" rows="6"></textarea>
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">📦 Recompensas</label>
                                                        <textarea class="form-control form-control form-control-solid" name="rewardDesc" rows="6"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">📅 Data inicial</label>
                                                        <input class="form-control form-control-sm form-control-solid" name="beginTime"
                                                            value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">📅 Data de
                                                            término</label>
                                                        <input class="form-control form-control-sm form-control-solid" name="endTime"
                                                            value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">❓ Remain1</label>
                                                        <input class="form-control form-control-sm form-control-solid" name="remain1"
                                                            value="1" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label for="" class="form-label">❓ Remain2</label>
                                                        <input class="form-control form-control-sm form-control-solid" name="remain2"
                                                            value="" />
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary w-100"
                                                        onclick="events.update()">
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
                                    <div class="tab-pane fade" id="event_gift" role="tabpanel">
                                        <div id="no_gifts" class="highlight" style="display: none;">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem gift',
                                                'message' => 'esse evento não possui nenhum gift',
                                            ])
                                        </div>
                                        <div id="gift_data">
                                            <label class="fs-6 fw-bold form-label mb-2">📁 Giftbag</label>
                                            <select name="giftbagId"
                                                class="form-select form-select-sm form-select-solid mb-4 w-60"
                                                data-control="select2" data-placeholder="Selecione o giftbagId">
                                            </select>
                                            <div id="gift_data_buttons" style="display:none;">
                                                <div class="d-flex align-items-center mb-5">
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#md_new_condition"
                                                        class="btn btn-light-primary btn-sm w-100 me-3">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2"
                                                                    width="20" height="20" rx="5"
                                                                    fill="currentColor"></rect>
                                                                <rect x="10.8891" y="17.8033" width="12"
                                                                    height="2" rx="1"
                                                                    transform="rotate(-90 10.8891 17.8033)"
                                                                    fill="currentColor">
                                                                </rect>
                                                                <rect x="6.01041" y="10.9247" width="12"
                                                                    height="2" rx="1" fill="currentColor">
                                                                </rect>
                                                            </svg>
                                                        </span>
                                                        nova condição
                                                    </button>
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#md_edit_gift"
                                                        class="btn btn-light-primary btn-sm w-100 me-2"
                                                        onclick="gift.detail()">
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
                                                        editar giftbag
                                                    </button>
                                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger"
                                                        onclick="gift.delete()" style="width: 80px!important;">
                                                        <span class="svg-icon svg-icon-3" id="delete">
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
                                            <div id="no_conditions_body" class="highlight">
                                                @include('components.default.notfound', [
                                                    'title' => 'Nada encontrado',
                                                    'message' =>
                                                        'selecione um giftbagId ou crie uma condição para continuar',
                                                ])
                                            </div>
                                            <div class="px-0 d-none" id="gift_data_body">
                                                <div class="mb-1 d-flex flex-stack">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">🎯 Condições</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            condições do evento relacionado ao giftbagId.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px"
                                                    id="conditionsList">
                                                </div>
                                                <div id="no_conditions_gift_body" class="highlight">
                                                    @include('components.default.notfound', [
                                                        'title' => 'Nada encontrado',
                                                        'message' => 'Esse giftbag não possui condições',
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="event_rewards" role="tabpanel">
                                        <div id="no_gifts" class="highlight" style="display: none;">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem gift',
                                                'message' => 'esse evento não possui nenhum gift',
                                            ])
                                        </div>
                                        <div id="gift_data">
                                            <label class="fs-6 fw-bold form-label mb-2">📁 Giftbag</label>
                                            <select name="giftbagId"
                                                class="form-select form-select-sm form-select-solid mb-4 w-60"
                                                data-control="select2" data-placeholder="Selecione o giftbagId">
                                            </select>
                                            <div id="gift_data_buttons" style="display:none;">
                                                <div class="d-flex align-items-center mb-5">
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#md_new_reward"
                                                        class="btn btn-light-primary btn-sm w-100 me-3">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2"
                                                                    width="20" height="20" rx="5"
                                                                    fill="currentColor"></rect>
                                                                <rect x="10.8891" y="17.8033" width="12"
                                                                    height="2" rx="1"
                                                                    transform="rotate(-90 10.8891 17.8033)"
                                                                    fill="currentColor">
                                                                </rect>
                                                                <rect x="6.01041" y="10.9247" width="12"
                                                                    height="2" rx="1" fill="currentColor">
                                                                </rect>
                                                            </svg>
                                                        </span>
                                                        nova recompensa
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="px-0" id="gift_data_body">
                                                <div class="mb-1 d-flex flex-stack">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">📦 Recompensas</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Recompensas do evento relacionadas ao giftbagId.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px"
                                                    id="rewardBody">
                                                    <div id="no_rewards_gift_body">
                                                        @include('components.default.notfound', [
                                                            'title' => 'Nada encontrado',
                                                            'message' => 'Esse giftbag não possui recompensas',
                                                        ])
                                                    </div>
                                                    <div id="rewardList"></div>
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
    <div class="modal fade" id="md_new_event" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">📅 Novo evento</h3>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-8">
                                    <label class="fs-6 fw-bold form-label mb-2">🎈 Título</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="activityName"
                                        value="">
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">❓ getWay</label>
                                    <select name="getWay" data-hide-search="true" class="form-select form-select-sm form-select-solid"
                                        data-control="select2" data-placeholder="getWay">
                                        <option value="0" selected>0</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🥖 Categoria</label>
                                    <select name="activityType" class="form-select form-select-sm form-select-solid"
                                        data-control="select2" data-placeholder="Categoria do evento">
                                        <option></option>
                                        @foreach ($activityTypes as $key => $type)
                                            <option value="{{ $key }}">
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">💽 Sub categoria</label>
                                    <select name="activityChildType" class="form-select form-select-sm form-select-solid"
                                        data-control="select2" data-placeholder="Sub-categoria">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🍃 Descrição</label>
                                    <textarea class="form-control form-control form-control-solid" name="desc" rows="5"></textarea>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📦 Recompensas</label>
                                    <textarea class="form-control form-control form-control-solid" name="rewardDesc" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">📅 Data inicial</label>
                                    <input class="form-control form-control-sm form-control-solid" name="beginTime"
                                        value="{{ date('d/m/Y 00:00:00') }}" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">📅 Data de término</label>
                                    <input class="form-control form-control-sm form-control-solid" name="endTime"
                                        value="{{ date('2050-m-d 23:59:00') }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">❓ Remain1</label>
                                    <input class="form-control form-control-sm form-control-solid" name="remain1"
                                        value="1" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">❓ Remain2</label>
                                    <input class="form-control form-control-sm form-control-solid" name="remain2"
                                        value="0" />
                                </div>
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
    <div class="modal fade" id="md_new_gift" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">📁 Novo gift</h3>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">🎲 giftbagOrder</label>
                                    <input name="giftbagOrder" class="form-control form-control-sm form-control-solid" value="0"
                                        type="number">
                                    <div class="text-muted fs-7">Posição de exibição.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ rewardMark</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" name="rewardMark">
                                        <option value="0" selected>Mark 0</option>
                                        <option value="1">Mark 1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="gift.create()">
                                    <span class="indicator-label">Criar gift</span>
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
    <div class="modal fade" id="md_edit_gift" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">📁 Editando gift</h3>
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
                        <input type="hidden" name="giftbagId">
                        <input type="hidden" name="activityId">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">🎲 giftbagOrder</label>
                                    <input name="giftbagOrder" class="form-control form-control-sm form-control-solid" value="0"
                                        type="number">
                                    <div class="text-muted fs-7">Posição de exibição.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ rewardMark</label>
                                    <select class="form-select form-select-solid mb-2" data-control="select2"
                                        data-hide-search="true" name="rewardMark">
                                        <option value="0" selected>Mark 0</option>
                                        <option value="1">Mark 1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="gift.update()">
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

    <div class="modal fade" id="md_new_condition" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">🎯 Criar condição</h3>
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
                        <input type="hidden" name="giftbagId">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ conditionIndex</label>
                                    <input name="conditionIndex" class="form-control form-control-sm form-control-solid" value="0"
                                        type="number">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ conditionValue</label>
                                    <input name="conditionValue" class="form-control form-control-sm form-control-solid" value="0"
                                        type="number">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ remain1</label>
                                    <input name="remain1" class="form-control form-control-sm form-control-solid" type="number"
                                        value="0">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ remain2</label>
                                    <input name="remain2" class="form-control form-control-sm form-control-solid" type="string">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="conditions.create()">
                                    <span class="indicator-label">Criar condição</span>
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
                    <h3 class="modal-title">🎯 Editando condição</h3>
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
                        <input type="hidden" name="giftbagId">
                        <input type="hidden" name="originalConditionIndex">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ conditionIndex</label>
                                    <input name="conditionIndex" class="form-control form-control-sm form-control-solid"
                                        value="0" type="number">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ conditionValue</label>
                                    <input name="conditionValue" class="form-control form-control-sm form-control-solid"
                                        value="0" type="number">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ remain1</label>
                                    <input name="remain1" class="form-control form-control-sm form-control-solid"
                                        type="number" value="0">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-6">
                                    <label class="form-label fs-6 fw-bolder text-gray-700">❓ remain2</label>
                                    <input name="remain2" class="form-control form-control-sm form-control-solid"
                                        type="string">
                                    <div class="text-muted fs-7">Desconhecido.</div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100"
                                    onclick="conditions.update()">
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
                        <input type="hidden" name="remain1">
                        <div>
                            <div class="mb-10">
                                <label for="templateId" class="form-label required">Item</label>
                                <select class="form-select form-select-sm form-select-solid" data-dropdown-parent="#md_new_reward"
                                    data-placeholder="Selecione um item" data-allow-clear="true"
                                    data-placeholder="Selecione o user" id="templateId" name="templateId">
                                </select>
                            </div>
                        </div>
                        <div id="md-item-info" style="display: none">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            occupationOrSex
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            name="occupationOrSex" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            rewardType
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid" name="rewardType"
                                            value="0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            property
                                        </label>
                                        <input type="text" class="form-control form-control-sm form-control-solid" name="property"
                                            value="0,0,0,0,0,0,0,0,0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6" id="md-annex-level-area">
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
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span class="required">Quantidade</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="" data-bs-original-title="Specify a card holder's name"
                                                aria-label="Specify a card holder's name"></i>
                                        </label>

                                        <input type="number" class="form-control form-control-sm form-control-solid" placeholder=""
                                            step="1" min="1" max="1" name="count"
                                            value="1" />

                                        <div class="fv-plugins-message-container invalid-feedback"></div>
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
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="isBind"
                                            value="1" checked="checked" />
                                    </label>
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
                        <input type="hidden" name="remain1">
                        <input type="hidden" name="templateId">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        occupationOrSex
                                    </label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="occupationOrSex"
                                        value="0" />
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row col-6">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        rewardType
                                    </label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="rewardType"
                                        value="0" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row col-6" id="md-annex-level-area">
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
                                <div class="d-flex flex-column mb-7 fv-row col-6">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Quantidade</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                            title="" data-bs-original-title="Specify a card holder's name"
                                            aria-label="Specify a card holder's name"></i>
                                    </label>

                                    <input type="number" class="form-control form-control-sm form-control-solid" placeholder=""
                                        step="1" min="1" max="1" name="count" value="1" />

                                    <div class="fv-plugins-message-container invalid-feedback"></div>
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
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="isBind"
                                        value="1" checked="checked" />
                                </label>
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

    <div class="modal fade" id="md_reset_event" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title fs-5 fw-bolder">🗑️ Resetar evento</span>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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
                    <form>
                        <input type="hidden" name="id">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="alert d-flex align-items-center p-0 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="mb-1 text-warning fw-bolder fs-6">Atenção</span>
                                    <span class="highlight">
                                        Essas alterações terão efeito apenas em jogadores
                                        <span class="fw-bolder text-danger">desconectados</span>.
                                        É recomendado que você <span class="fw-bolder text-danger">desconecte</span>
                                        todos os jogadores antes de resetar o evento.
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">🚀 Progresso</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            se marcado o progresso de todos os evento
                                            <span class="text-danger">serão perdidos</span>.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="progress"
                                            value="1">
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">📦 Recompensas</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            se marcado os <span class="text-success">jogadores poderão</span> coletar
                                            novamente os evento já concluídos.</div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="rewarded"
                                            value="1">
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-danger w-100"
                                    onclick="events.reset()">
                                    <span class="indicator-label">Resetar evento</span>
                                    <span class="indicator-progress">
                                        resetando...
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
        const activityTypeList = @json($activityTypes);
    </script>
    <script src="{{ url() }}/assets/js/admin/event/gmActivity/list.js"></script>
@endsection
