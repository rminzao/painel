@extends('layouts.app')

@section('title', 'Mapas')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📌 Mapas</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Mapas</li>
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

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="suit.updateOnGame()">
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
                    <div class="card card-flush" id="map_list_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome do mapa" name="search" />
                                        </div>
                                    </div>
                                    <select name="type" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-placeholder="Categoria da loja">
                                        <option value="0" selected>Todos</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" href="javascript:;"
                                    title="Em breve" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                    data-bs-dismiss="click" data-bs-placement="bottom">
                                    novo mapa
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7 pe-7">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum mapa encontrado',
                                ])
                            </div>
                            <div id="map_list"></div>
                            <div id="map_list_footer" style="display:none;">
                                <div class="d-flex justify-content-between mt-5">
                                    <div>
                                        <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                            data-control="select2" data-hide-search="true">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
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
                        <div id="area_map" style="display:none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_detail">
                                                    📝 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#tab_editor">
                                                    🎯 Editor
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" id="button_map_update" onclick="map.update()"
                                        class="btn btn-sm btn-light-primary">
                                        <span class="indicator-label">Aplicar alterações</span>
                                        <span class="indicator-progress">
                                            aplicando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="tab_detail" role="tabpanel">
                                            <form class="pt-4">
                                                <input type="hidden" name="ID">
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">📓 Nome</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Name">
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">📒 Descrição</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Description">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                          📐 ForegroundWidth
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="ForegroundWidth">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                            📏 ForegroundHeight
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="ForegroundHeight">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                            📐 BackroundWidht
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="BackroundWidht">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                            📏 BackroundHeight
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="BackroundHeight">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">📐 DeadWidth</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="DeadWidth">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">📏 DeadHeight</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="DeadHeight">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">⚖️ Weight</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Weight">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">❓ DragIndex</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="DragIndex">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">🖌️ ForePic</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="ForePic">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">🖌️ BackPic</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="BackPic">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">🖌️ DeadPic</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="DeadPic">
                                                    </div>
                                                    <div class="fv-row mb-7 col-3">
                                                        <label class="fs-6 fw-bold form-label mb-2">🖼️ Pic</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Pic">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🏗️ Remark</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Remark">
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎺 BackMusic</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="BackMusic">
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🏷️ Type</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="Type">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade pt-3" id="tab_editor" role="tabpanel">
                                            <div class="position-sticky" onmousemove="controls.cursor(event)"
                                                ondragover="drag.end(event)" ondrop="drag.drop(event)">
                                                <div class="btn btn-icon position-absolute top-3 start-3"><i class="fa-regular fa-play-pause"></i></div>
                                                <div class="position-absolute" style="z-index: 99;pointer-events: none;"
                                                    id="mouse_pointer_current"></div>
                                                <div id="player_positions" class="text-danger"></div>
                                                <img id="map_background" style="pointer-events: none; user-select: none;"
                                                    src="" class="w-100 rounded">
                                                <img id="map_foreground" src=""
                                                    class="w-100 h-100 position-absolute top-0 start-0 rounded"
                                                    style="display:none;z-index: 2;pointer-events: none; user-select: none;"
                                                    onerror="this.style.display='none'">
                                                <img id="map_deadground" src=""
                                                    class="w-100 h-100 position-absolute top-0 start-0 rounded"
                                                    onerror="this.style.display='none'"
                                                    style="display:none;z-index: 2;pointer-events: none; user-select: none;filter: drop-shadow(0px 1px 5px red)">
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <span class="w-100 text-muted">
                                                    Areas sombreadas com a <span class="text-danger">cor vermelha</span>
                                                    não podem ser cavadas.
                                                </span>
                                                <div class="w-50 text-muted text-end" id="current_position">Posição atual
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-6 d-flex flex-column">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted fs-6">Time 1</span>
                                                        </div>
                                                    </div>
                                                    <div id="team1_playerList" class="d-flex flex-column highlight p-0">
                                                    </div>
                                                    <div class="highlight pb-0" id="not_players_t1">
                                                        @include('components.default.notfound', [
                                                            'title' => 'sem posições',
                                                            'message' =>
                                                                'Esse time ainda não possui posições definidas',
                                                            'icon' => false,
                                                        ])
                                                    </div>
                                                    <button class="btn btn-sm btn-light-primary mt-2" onclick="controls.addTeam(1)">
                                                        nova posição
                                                    </button>
                                                </div>
                                                <div class="col-6 d-flex flex-column">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted fs-6">Time 2</span>
                                                        </div>
                                                    </div>
                                                    <div id="team2_playerList" class="d-flex flex-column highlight p-0">
                                                    </div>
                                                    <div class="highlight pb-0" id="not_players_t2">
                                                        @include('components.default.notfound', [
                                                            'title' => 'sem posições',
                                                            'message' =>
                                                                'Esse time ainda não possui posições definidas',
                                                            'icon' => false,
                                                        ])
                                                    </div>
                                                    <button class="btn btn-sm btn-light-primary mt-2" onclick="controls.addTeam(2)">
                                                        nova posição
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um mapa para continuar',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <video id="videoElement" name="videoElement" class="centeredVideo d-none" controls="" autoplay="">
        Your browser is too old which doesn't support HTML5 video.
    </video>
@endsection

@section('modals')

@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/custom/flv.js"></script>
    <script src="{{ url() }}/assets/js/admin/map/list.js"></script>
@endsection
