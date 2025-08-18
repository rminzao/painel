@extends('layouts.app')

@section('title', 'Instâncias')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">☃️ Instâncias (PVE)</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Instâncias (PVE)</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                {{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="pve.updateOnGame()">
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
                        Recarregar EMULADORES
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
                <!-- Sidebar com lista de PVEs -->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="pve_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome da instância" id="search" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <select name="type_filter" class="form-select form-select-sm form-select-solid w-60"
                                            data-hide-search="true" data-control="select2"
                                            data-placeholder="Status da missão">
                                            <option value="0" selected>🧁 todos</option>
                                            @foreach ($pveTypes as $type => $item)
                                                <option value="{{ $type }}">
                                                    {{ $item['name'] != '' ? $item['name'] : '❓ ' . $item['prefix'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_pve_create">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhuma instância encontrada',
                                ])
                            </div>
                            <div id="pve_list"></div>
                            <div id="pve_list_footer" style="display:none;">
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

                <!-- Área Principal -->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card">
                        <div id="no_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em uma instância para continuar',
                            ])
                        </div>
                        
                        <div id="pve_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#pve_details_tab">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#pve_templates_tab">
                                                    📦 Templates
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" id="btn_pve_update"
                                        onclick="pve.update()">
                                        <span class="indicator-label">Aplicar alterações</span>
                                        <span class="indicator-progress">
                                            aplicando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="tab-content">
                                <!-- Tab: Detalhes -->
                                <div class="tab-pane fade show active" id="pve_details_tab" role="tabpanel">
                                    <div class="card-body">
                                        <form id="pve_details_form">
                                            <input type="hidden" name="OriginalID">
                                            
                                            <!-- Informações Básicas -->
                                            <div class="row mb-7">
                                                <div class="col-2">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎫 ID</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="ID" readonly />
                                                </div>
                                                <div class="col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">📜 Nome</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="Name" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="fs-6 fw-bold form-label mb-2">🕋 Type</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="Type" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="fs-6 fw-bold form-label mb-2">📊 Level</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="LevelLimits" />
                                                </div>
                                            </div>

                                            <div class="row mb-7">
                                                <div class="col-12">
                                                    <label class="fs-6 fw-bold form-label mb-2">📝 Descrição</label>
                                                    <textarea class="form-control form-control-solid" rows="3" 
                                                        name="Description" data-kt-autosize="true"></textarea>
                                                </div>
                                            </div>

                                            <!-- Scripts das 6 Dificuldades -->
                                            <div class="separator separator-dashed my-7"></div>
                                            <h6 class="fw-bold mb-5">📝 Scripts das Dificuldades</h6>
                                            
                                            <div class="row mb-5">
                                                <div class="col-4">
                                                    <label class="form-label">🟢 Simple</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="SimpleGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">🔵 Normal</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="NormalGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">🟠 Hard</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="HardGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                            </div>

                                            <div class="row mb-5">
                                                <div class="col-4">
                                                    <label class="form-label">🔴 Terror</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="TerrorGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">⚫ Nightmare</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="NightmareGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">💜 Epic</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="EpicGameScript" placeholder="GameServerScript.AI.Game..." />
                                                </div>
                                            </div>

                                            <!-- Custos e Configurações -->
                                            <div class="separator separator-dashed my-7"></div>
                                            <h6 class="fw-bold mb-5">💰 Custos por Dificuldade</h6>
                                            
                                            <div class="row mb-5">
                                                <div class="col-2">
                                                    <label class="form-label">🟢 Simple</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_simple" value="100" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">🔵 Normal</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_normal" value="100" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">🟠 Hard</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_hard" value="100" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">🔴 Terror</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_terror" value="100" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">⚫ Nightmare</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_nightmare" value="0" />
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label">💜 Epic</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="cost_epic" value="0" />
                                                </div>
                                            </div>

                                            <!-- Outras Configurações -->
                                            <div class="separator separator-dashed my-7"></div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="form-label">🔢 Ordering</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                        name="Ordering" />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">💡 Advice Tips</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="AdviceTips" placeholder="8-50|57-65|70" />
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label">🖼️ Pic</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                        name="Pic" value="1072" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Tab: Templates -->
                                <div class="tab-pane fade" id="pve_templates_tab" role="tabpanel">
                                    <div class="card-body p-0">
                                        <!-- Tabs das Dificuldades -->
                                        <div class="d-flex align-items-center border-bottom">
                                            <ul class="nav nav-pills nav-pills-custom flex-row border-0 fs-7" id="difficulty_tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active px-4 py-3" data-bs-toggle="pill" href="#difficulty_simple">
                                                        🟢 Simple
                                                        <span class="badge badge-circle badge-light-success ms-2" id="simple_count">0</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-4 py-3" data-bs-toggle="pill" href="#difficulty_normal">
                                                        🔵 Normal
                                                        <span class="badge badge-circle badge-light-primary ms-2" id="normal_count">0</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-4 py-3" data-bs-toggle="pill" href="#difficulty_hard">
                                                        🟠 Hard
                                                        <span class="badge badge-circle badge-light-warning ms-2" id="hard_count">0</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-4 py-3" data-bs-toggle="pill" href="#difficulty_terror">
                                                        🔴 Terror
                                                        <span class="badge badge-circle badge-light-danger ms-2" id="terror_count">0</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-4 py-3" data-bs-toggle="pill" href="#difficulty_nightmare">
                                                        ⚫ Nightmare
                                                        <span class="badge badge-circle badge-light-dark ms-2" id="nightmare_count">0</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link px-4 py-3" data-bs-toggle="pill" href="#difficulty_epic">
                                                        💜 Epic
                                                        <span class="badge badge-circle badge-light-info ms-2" id="epic_count">0</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Conteúdo das Dificuldades -->
                                        <div class="tab-content p-4">
                                            <!-- Simple -->
                                            <div class="tab-pane fade show active" id="difficulty_simple">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="simple_templates_grid" class="templates-grid" data-difficulty="simple">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-success alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>Selecione um item para adicionar à dificuldade atual</small>
                                                        </div>
                                                        <div id="items_library">
                                                            <!-- Select2 será inserido aqui via JavaScript -->
                                                        </div>
                                                        
                                                        <!-- Instruções de uso -->
                                                        <div class="mt-3">
                                                            <div class="card card-flush bg-light border-0">
                                                                <div class="card-body p-3">
                                                                    <h6 class="fw-bold text-gray-800 mb-2">💡 Como usar:</h6>
                                                                    <ul class="list-unstyled mb-0 small text-gray-600">
                                                                        <li class="mb-1">🔍 <strong>Busque</strong> pelo nome ou ID do item</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Normal -->
                                            <div class="tab-pane fade" id="difficulty_normal">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="normal_templates_grid" class="templates-grid" data-difficulty="normal">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-primary alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>A biblioteca é compartilhada entre todas as dificuldades</small>
                                                        </div>
                                                        <div class="items-library-shared">
                                                            <!-- Biblioteca compartilhada - mesmo select2 -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Hard -->
                                            <div class="tab-pane fade" id="difficulty_hard">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="hard_templates_grid" class="templates-grid" data-difficulty="hard">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-warning alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>Dificil</small>
                                                        </div>
                                                        <div class="items-library-shared">
                                                            <!-- Biblioteca compartilhada -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Terror -->
                                            <div class="tab-pane fade" id="difficulty_terror">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="terror_templates_grid" class="templates-grid" data-difficulty="terror">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-danger alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>Terror</small>
                                                        </div>
                                                        <div class="items-library-shared">
                                                            <!-- Biblioteca compartilhada -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Nightmare -->
                                            <div class="tab-pane fade" id="difficulty_nightmare">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="nightmare_templates_grid" class="templates-grid" data-difficulty="nightmare">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-dark alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>Nightmare</small>
                                                        </div>
                                                        <div class="items-library-shared">
                                                            <!-- Biblioteca compartilhada -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Epic -->
                                            <div class="tab-pane fade" id="difficulty_epic">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-3">📦 Templates Configurados</h6>
                                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <span>Arraste os templates para reordenar como aparecem no jogo</span>
                                                        </div>
                                                        <div id="epic_templates_grid" class="templates-grid" data-difficulty="epic">
                                                            <!-- Templates serão inseridos aqui via JavaScript -->
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class="fw-bold mb-3">🔍 Biblioteca de Items</h6>
                                                        <div class="alert alert-info alert-sm mb-3">
                                                            <i class="fas fa-lightbulb me-1"></i>
                                                            <small>Epic</small>
                                                        </div>
                                                        <div class="items-library-shared">
                                                            <!-- Biblioteca compartilhada -->
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
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_pve_create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">☃️ Nova instância</h3>
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
                    <form id="pve_create_form">
                        <div class="row mb-5">
                            <div class="col-3">
                                <label class="fs-6 fw-bold form-label mb-2">🎫 ID</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" 
                                    name="ID" placeholder="Auto" />
                                <small class="text-muted">Deixe vazio para auto-gerar</small>
                            </div>
                            <div class="col-9">
                                <label class="fs-6 fw-bold form-label mb-2">📜 Nome</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" 
                                    name="Name" required />
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="fs-6 fw-bold form-label mb-2">📝 Descrição</label>
                                <textarea class="form-control form-control-solid" rows="3" 
                                    name="Description" data-kt-autosize="true"></textarea>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-4">
                                <label class="form-label">🕋 Type</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                    name="Type" value="4" />
                            </div>
                            <div class="col-4">
                                <label class="form-label">📊 Level Limits</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                    name="LevelLimits" value="1" />
                            </div>
                            <div class="col-4">
                                <label class="form-label">🖼️ Pic</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" 
                                    name="Pic" value="1072" />
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="form-label">🔢 Ordering</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                    name="Ordering" value="1" />
                            </div>
                            <div class="col-6">
                                <label class="form-label">💡 Advice Tips</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" 
                                    name="AdviceTips" placeholder="8-50|57-65|70" />
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-sm btn-light-primary w-100" id="btn_pve_create"
                                onclick="pve.create()">
                                <span class="indicator-label">Criar Instância</span>
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
@endsection

@section('custom-css')
<style>
/* 🎮 CSS TEMPLATES ESTILO GAME COMPLETO */

/* ====================================
   1. BIBLIOTECA DE ITENS SELECT2
   ==================================== */

#items_library .select2-container {
    width: 100% !important;
}

#items_library .select2-selection {
    border: 2px solid #e1e5e9 !important;
    border-radius: 8px !important;
    min-height: 45px !important;
    transition: all 0.3s ease !important;
}

#items_library .select2-selection:focus,
#items_library .select2-container--open .select2-selection {
    border-color: #3699ff !important;
    box-shadow: 0 0 0 0.2rem rgba(54, 153, 255, 0.25) !important;
}

#items_library .select2-selection__placeholder {
    color: #7e8299 !important;
    font-style: italic;
}

#items_library .select2-results__option {
    padding: 8px 12px !important;
}

/* ====================================
   2. GRID DE TEMPLATES ESTILO GAME
   ==================================== */

.templates-grid {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #e1e5e9;
    border-radius: 12px;
    min-height: 250px;
    padding: 20px;
    position: relative;
}

.game-templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 15px;
    width: 100%;
}

.game-template-slot {
    width: 100px;
    height: 120px;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border: 2px solid #d3d3d3;
    border-radius: 12px;
    position: relative;
    cursor: move;
    transition: all 0.3s ease;
    box-shadow: 
        0 4px 8px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px;
    user-select: none;
}

.game-template-slot:hover {
    transform: translateY(-3px) scale(1.02);
    border-color: #3699ff;
    box-shadow: 
        0 8px 25px rgba(54, 153, 255, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    background: linear-gradient(145deg, #f8faff, #e3f0ff);
}

.game-template-slot.sortable-ghost {
    opacity: 0.5;
    background: linear-gradient(145deg, #ffebf0, #ffe0e6);
    border-color: #f64e60;
}

.game-template-slot.sortable-chosen {
    transform: rotate(5deg) scale(0.95);
    z-index: 999;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.game-template-slot.sortable-drag {
    opacity: 0.8;
    transform: rotate(8deg);
}

/* ====================================
   3. ELEMENTOS DOS SLOTS
   ==================================== */

.slot-position {
    position: absolute;
    top: -8px;
    left: -8px;
    width: 24px;
    height: 24px;
    background: linear-gradient(145deg, #3699ff, #2884ef);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    border: 3px solid white;
    box-shadow: 0 3px 8px rgba(54, 153, 255, 0.4);
    z-index: 10;
}

.slot-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 22px;
    height: 22px;
    background: linear-gradient(145deg, #f64e60, #e63946);
    color: white;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 3px 8px rgba(246, 78, 96, 0.4);
    z-index: 10;
    transition: all 0.2s ease;
}

.slot-remove:hover {
    background: linear-gradient(145deg, #dc3545, #c82333);
    transform: scale(1.1);
}

.game-template-slot:hover .slot-remove {
    display: flex;
}

.slot-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e1e5e9;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.slot-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.game-template-slot:hover .slot-image img {
    transform: scale(1.1);
}

.slot-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-height: 0;
}

.slot-name {
    font-size: 0.7rem;
    font-weight: 600;
    color: #2d3748;
    line-height: 1.1;
    max-height: 22px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-break: break-word;
    margin-bottom: 2px;
}

.slot-id {
    font-size: 0.6rem;
    color: #7e8299;
    background: rgba(126, 130, 153, 0.1);
    padding: 1px 4px;
    border-radius: 3px;
    font-weight: 500;
}

/* ====================================
   4. ESTADO VAZIO
   ==================================== */

.templates-empty {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #7e8299;
    width: 80%;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.7;
}

.empty-text {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #5a6c7d;
}

.empty-subtext {
    font-size: 0.9rem;
    color: #7e8299;
}

/* ====================================
   5. ANIMAÇÕES
   ==================================== */

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.game-template-slot {
    animation: slideInUp 0.3s ease-out;
}

.slot-position, .slot-remove {
    animation: bounceIn 0.4s ease-out;
}

/* ====================================
   6. RESPONSIVIDADE
   ==================================== */

@media (max-width: 1200px) {
    .game-templates-grid {
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 12px;
    }
    
    .game-template-slot {
        width: 90px;
        height: 110px;
        padding: 6px;
    }
    
    .slot-image {
        width: 45px;
        height: 45px;
    }
}

@media (max-width: 768px) {
    .game-templates-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 10px;
    }
    
    .game-template-slot {
        width: 80px;
        height: 100px;
        padding: 5px;
    }
    
    .slot-image {
        width: 40px;
        height: 40px;
    }
    
    .slot-name {
        font-size: 0.65rem;
        max-height: 20px;
    }
    
    .slot-id {
        font-size: 0.55rem;
    }
}

/* ====================================
   7. EFEITOS ESPECIAIS
   ==================================== */

.game-template-slot::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255, 255, 255, 0.3) 50%,
        transparent 70%
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 10px;
    pointer-events: none;
}

.game-template-slot:hover::before {
    opacity: 1;
}

/* ====================================
   8. DRAG & DROP INDICATORS
   ==================================== */

.templates-grid.drag-over {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border-color: #ffc107;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* ====================================
   9. BADGES DE SEXO
   ==================================== */

.slot-sex-indicator {
    position: absolute;
    top: 2px;
    right: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    font-size: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #e1e5e9;
    z-index: 5;
}

.slot-sex-male {
    color: #3699ff;
    background: rgba(54, 153, 255, 0.1);
}

.slot-sex-female {
    color: #f64e60;
    background: rgba(246, 78, 96, 0.1);
}

/* ====================================
   10. TABS DE DIFICULDADE
   ==================================== */

#difficulty_tabs .nav-link {
    border-radius: 8px;
    margin-right: 5px;
    transition: all 0.2s ease;
    font-weight: 500;
}

#difficulty_tabs .nav-link:hover {
    background: rgba(54, 153, 255, 0.1);
    transform: translateY(-1px);
}

#difficulty_tabs .nav-link.active {
    background: #3699ff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(54, 153, 255, 0.3);
}

/* ====================================
   11. BADGES DE CONTADORES
   ==================================== */

.badge-circle {
    min-width: 20px;
    height: 20px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 600;
}
</style>
@endsection

@section('custom-js')
    <!-- Dependências necessárias -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    
    <!-- Script de inicialização -->
    <script>
        $(document).ready(function() {
            // Aguardar carregamento completo antes de inicializar biblioteca
            setTimeout(() => {
                if (typeof library !== 'undefined') {
                    library.init();
                    console.log('🎮 Biblioteca de templates inicializada');
                }
            }, 1000);
        });
    </script>
    
    <!-- Arquivo JavaScript principal -->
    <script src="{{ url() }}/assets/js/admin/game/pve/list.js"></script>
@endsection