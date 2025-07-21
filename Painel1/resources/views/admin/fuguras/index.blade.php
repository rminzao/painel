@extends('layouts.app')

@section('title', 'Fúguras')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🎭 Fúguras</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] ?? 'Sistema' }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Administração</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Game Utils</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Fúguras</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <button type="button" class="btn btn-light-primary me-2" id="button_refresh_list">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z" fill="currentColor" />
                                <path d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z" fill="currentColor" />
                            </svg>
                        </span>
                        Atualizar Lista
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
                <!-- SIDEBAR ESQUERDA - LISTA DE FIGURAS -->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="fugura_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="🔍 Buscar por nome ou ID" id="fugura_search" />
                                        </div>
                                    </div>
                                    <select name="sexo_filter" class="form-select form-select-sm form-select-solid w-60"
                                        data-hide-search="true" data-control="select2" data-placeholder="Filtrar por Sexo">
                                        <option value="all" selected>🎭 Todos os Sexos</option>
                                        <option value="0">👤 Unissex</option>
                                        <option value="1">👦 Masculino</option>
                                        <option value="2">👧 Feminino</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    ➕ Nova Fúgura
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7">
                            <div id="not_results" style="display: none;">
                                <div class="d-flex flex-column text-center">
                                    <div class="text-gray-800 fs-6 fw-bolder mb-2">🔍 Nenhuma fugura encontrada</div>
                                    <div class="text-gray-400 fs-7">Tente ajustar os filtros ou criar uma nova fugura</div>
                                </div>
                            </div>

                            <div id="fugura_list" class="scroll-y me-n5 h-lg-auto"></div>

                            <div id="fugura_footer">
                                <div class="d-flex justify-content-between mt-5">
                                    <div>
                                        <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                            data-control="select2" data-hide-search="true">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div id="item_paginator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÁREA PRINCIPAL - DETALHES/EDIÇÃO -->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_fugura_messenger">
                        <div id="not_selected">
                            <div class="card-body d-flex flex-column justify-content-center text-center p-15">
                                <div class="text-gray-800 fs-6 fw-bolder mb-4">🎭 Sem fugura secionada</div>
                                <div class="text-gray-400 fs-7 mb-7">Clique em uma fugura na lista ao lado para visualizar e editar seus detalhes</div>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                    ➕ Criar Nova Fúgura
                                </button>
                            </div>
                        </div>
                        
                        <div id="fugura_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#fugura_info">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#fugura_stats">
                                                    ⚔️ Atributos
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#fugura_items">
                                                    🎒 Itens
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-icon btn-light-danger" id="btn_delete_fugura"
                                                data-bs-toggle="tooltip" title="Deletar Figura">
                                            <i class="bi bi-trash fs-3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-content">
                                <!-- ABA DETALHES -->
                                <div class="tab-pane fade show active" id="fugura_info" role="tabpanel">
                                    <div class="card-body">
                                        <form id="form-fugura-edit-send">
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🆔 ID</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="ID" value="" readonly />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                           name="Name" value="" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎭 Sexo</label>
                                                    <select class="form-select form-select-sm form-select-solid" 
                                                            data-control="select2" data-hide-search="true" name="Sex">
                                                        <option value="0">👤 Unissex</option>
                                                        <option value="1">👦 Masculino</option>
                                                        <option value="2">👧 Feminino</option>
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">⌨️ Tipo</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Type" value="" />
                                                </div>
                                            </div>
                                            
                                            <div class="text-center pt-5">
                                                <button type="button" onclick="fugura.update()"
                                                    class="btn btn-sm btn-light-primary w-100">
                                                    <span class="indicator-label">💾 Aplicar alterações</span>
                                                    <span class="indicator-progress">
                                                        aplicando alterações...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- ABA ATRIBUTOS -->
                                <div class="tab-pane fade" id="fugura_stats" role="tabpanel">
                                    <div class="card-body">
                                        <form id="form-fugura-stats-send">
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">⚔️ Attack</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Attack" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🛡️ Defend</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Defend" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏃 Agility</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Agility" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🍀 Luck</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Luck" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🩸 Blood</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Blood" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">💥 Damage</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Damage" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🛡️ Guard</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Guard" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">💰 Cost</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Cost" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="text-center pt-5">
                                                <button type="button" onclick="fugura.updateStats()"
                                                    class="btn btn-sm btn-light-primary w-100">
                                                    <span class="indicator-label">💾 Aplicar alterações</span>
                                                    <span class="indicator-progress">
                                                        aplicando alterações...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- ABA ITENS -->
                                <div class="tab-pane fade" id="fugura_items" role="tabpanel">
                                    <div class="container-fluid p-4">
                                        <div class="row g-4" style="min-height: 600px;">
                                            
                                            <!-- CARD 1: ADICIONAR ITEM -->
                                            <div class="col-12 col-xl-4">
                                                <div class="card card-flush h-100 border-2 border-primary" style="max-height: 550px;">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">
                                                            <h4 class="text-white fw-bold mb-0">
                                                                <i class="bi bi-plus-circle me-2"></i>
                                                                Adicionar Item
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card-body" style="overflow-y: auto;">
                                                        <form id="form-add-item" class="form">
                                                            
                                                            <!-- Template ID -->
                                                            <div class="mb-4">
                                                                <label class="form-label required fw-bold fs-6">Template ID</label>
                                                                <select id="add-template-id" name="template_id" class="form-select" required>
                                                                    <option value="">Digite para buscar...</option>
                                                                </select>
                                                                <div class="form-text">
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-info-circle me-1"></i>
                                                                        Digite pelo menos 2 caracteres para buscar
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Preview do Item -->
                                                            <div id="item-selection-hint" class="mb-4">
                                                                <div class="alert alert-light-info d-flex align-items-center p-3">
                                                                    <i class="bi bi-lightbulb fs-2x text-info me-3"></i>
                                                                    <div>
                                                                        <div class="fw-bold">Selecione um item</div>
                                                                        <small>O preview aparecerá aqui após a seleção</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div id="item-preview-area" class="mb-4" style="display: none;">
                                                                <div class="card border-success border-2">
                                                                    <div class="card-body text-center p-3">
                                                                        <img id="item-preview-image" src="" alt="Preview" 
                                                                             class="img-fluid rounded mb-2" 
                                                                             style="max-height: 80px; display: none;">
                                                                        <h6 id="item-preview-name" class="fw-bold text-success mb-1">Nome do Item</h6>
                                                                        <div class="row g-1">
                                                                            <div class="col-6">
                                                                                <small class="text-muted">ID:</small>
                                                                                <div id="item-preview-id" class="fw-bold">-</div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <small class="text-muted">Tipo:</small>
                                                                                <div id="item-preview-type" class="fw-bold">-</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Descrição -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Descrição</label>
                                                                <textarea id="add-description" name="description" 
                                                                          class="form-control form-control-sm" 
                                                                          rows="2" 
                                                                          placeholder="Descrição opcional do item..."></textarea>
                                                            </div>
                                                            
                                                            <!-- Sexo -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Sexo</label>
                                                                <select id="add-sex" name="sex" class="form-select form-select-sm">
                                                                    <option value="0">👤 Unissex</option>
                                                                    <option value="1">👦 Masculino</option>
                                                                    <option value="2">👧 Feminino</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Custo -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Custo</label>
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text">💰</span>
                                                                    <input id="add-cost" name="cost" type="number" 
                                                                           class="form-control" 
                                                                           value="0" min="0" 
                                                                           placeholder="0">
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Tipo -->
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Tipo</label>
                                                                <input id="add-type" name="type" type="number" 
                                                                       class="form-control form-control-sm" 
                                                                       value="1" min="1" required>
                                                            </div>
                                                            
                                                            <!-- Botão Submit -->
                                                            <div class="text-center">
                                                                <button type="submit" class="btn btn-success w-100 fw-bold">
                                                                    <i class="bi bi-plus-lg me-2"></i>
                                                                    Adicionar Item à Fugura
                                                                </button>
                                                            </div>
                                                            
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- CARD 2: LISTA DE ITENS DA FUGURA -->
                                            <div class="col-12 col-xl-8">
                                                <div class="card card-flush h-100 border-2 border-success" style="max-height: 550px;">
                                                    <div class="card-header bg-success">
                                                        <div class="card-title text-white">
                                                            <h4 class="text-white fw-bold mb-0">
                                                                <i class="bi bi-backpack me-2"></i>
                                                                Itens da Fugura
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card-body p-0" style="overflow: hidden;">
                                                        
                                                        <!-- Estado de Loading -->
                                                        <div id="items-loading" class="d-flex justify-content-center align-items-center" 
                                                             style="height: 300px; display: none !important;">
                                                            <div class="text-center">
                                                                <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                                                                    <span class="visually-hidden">Carregando...</span>
                                                                </div>
                                                                <div class="text-muted fw-bold">Carregando itens...</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Estado Vazio -->
                                                        <div id="items-empty" class="d-flex flex-column justify-content-center align-items-center text-center" 
                                                             style="height: 300px; display: none !important;">
                                                            <i class="bi bi-inbox fs-1 text-muted mb-3" style="font-size: 4rem !important;"></i>
                                                            <h5 class="text-muted fw-bold">Nenhum item encontrado</h5>
                                                            <p class="text-muted mb-0">
                                                                Esta fugura ainda não possui itens.<br>
                                                                Use o formulário ao lado para adicionar itens.
                                                            </p>
                                                        </div>
                                                        
                                                        <!-- Lista de Itens com Paginação -->
                                                        <div id="items-list" style="display: none; height: 100%; overflow: hidden;">
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
    <!-- MODAL DE CRIAÇÃO -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-6">➕ Criar Nova Fúgura</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y">
                    <form id="createForm">
                        <input type="hidden" name="_token" value="{{ $_SESSION['token'] ?? '' }}">
                        
                        <div class="row mb-5">
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🆔 ID</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                       name="ID" required min="1" placeholder="Ex: 17, 18, 19...">
                                <div class="text-muted fs-7">ID deve ser único</div>
                            </div>
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" 
                                       name="Name" required placeholder="Nome da fugura">
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🎭 Sexo</label>
                                <select class="form-select form-select-sm form-select-solid" 
                                        data-control="select2" data-hide-search="true" name="Sex">
                                    <option value="0">👤 Unissex</option>
                                    <option value="1">👦 Masculino</option>
                                    <option value="2">👧 Feminino</option>
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">⌨️ Tipo</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                       name="Type" required min="0" placeholder="Ex: 1, 2, 3...">
                            </div>
                        </div>
                        
                        <div class="separator separator-dashed my-5"></div>
                        <div class="mb-3">
                            <label class="fs-6 fw-bold form-label mb-3">⚔️ Atributos</label>
                        </div>
                        
                        <div class="row">
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">⚔️ Attack</label>
                                <input type="number" name="Attack" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">🛡️ Defend</label>
                                <input type="number" name="Defend" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">🏃 Agility</label>
                                <input type="number" name="Agility" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">🍀 Luck</label>
                                <input type="number" name="Luck" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">🩸 Blood</label>
                                <input type="number" name="Blood" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">💥 Damage</label>
                                <input type="number" name="Damage" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">🛡️ Guard</label>
                                <input type="number" name="Guard" class="form-control form-control-sm" value="0" min="0">
                            </div>
                            <div class="fv-row col-6 mb-3">
                                <label class="fs-7 fw-bold form-label mb-1">💰 Cost</label>
                                <input type="number" name="Cost" class="form-control form-control-sm" value="0" min="0">
                            </div>
                        </div>
                        
                        <div class="text-center pt-5">
                            <button type="submit" id="form_fugura_submit" class="btn btn-sm btn-light-primary w-100">
                                <span class="indicator-label">✨ Criar Fúgura</span>
                                <span class="indicator-progress">
                                    criando aguarde...
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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
		/* Container principal da paginação */
		.items-pagination {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			background: #f8f9fa !important;
			border-top: 2px solid #e1e3ea !important;
			padding: 12px 15px !important;
			text-align: center !important;
			margin: 0 !important;
			position: relative !important;
			z-index: 10 !important;
		}

		/* Info da paginação */
		.pagination-info {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			font-size: 12px !important;
			color: #495057 !important;
			margin: 0 0 8px 0 !important;
			font-weight: 600 !important;
			text-align: center !important;
		}

		/* Container dos botões */
		.items-pagination > div {
			display: flex !important;
			justify-content: center !important;
			align-items: center !important;
			gap: 5px !important;
			flex-wrap: wrap !important;
			margin-top: 8px !important;
		}

		/* Botões de paginação */
		.pagination-btn {
			display: inline-block !important;
			visibility: visible !important;
			opacity: 1 !important;
			background: #28a745 !important;
			color: white !important;
			border: none !important;
			padding: 6px 12px !important;
			margin: 0 2px !important;
			border-radius: 4px !important;
			cursor: pointer !important;
			font-size: 12px !important;
			font-weight: 600 !important;
			transition: all 0.3s ease !important;
			box-shadow: 0 2px 4px rgba(40,167,69,0.2) !important;
			min-width: 32px !important;
			text-align: center !important;
			line-height: 1.2 !important;
		}

		/* Estados dos botões */
		.pagination-btn:hover {
			background: #1e7e34 !important;
			transform: translateY(-1px) !important;
			box-shadow: 0 4px 8px rgba(40,167,69,0.3) !important;
		}

		.pagination-btn:disabled {
			background: #6c757d !important;
			cursor: not-allowed !important;
			transform: none !important;
			box-shadow: none !important;
			opacity: 0.6 !important;
		}

		.pagination-btn.active {
			background: #007bff !important;
			font-weight: bold !important;
			box-shadow: 0 4px 8px rgba(0,123,255,0.3) !important;
			transform: scale(1.05) !important;
		}

		/* Forçar visibilidade da área de itens */
		#items-list {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			height: 100% !important;
			overflow: hidden !important;
			position: relative !important;
		}

		/* Header dos itens */
		.items-header {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
			color: white !important;
			padding: 12px 15px !important;
			margin: 0 !important;
			font-weight: 700 !important;
			font-size: 16px !important;
			text-align: center !important;
			box-shadow: 0 2px 6px rgba(40,167,69,0.2) !important;
		}

		/* Container dos itens */
		.items-content {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			height: auto !important;
			max-height: 350px !important;
			overflow-y: auto !important;
			padding: 12px !important;
			background: white !important;
		}

		/* Grid dos itens */
		.items-content .row {
			display: flex !important;
			flex-wrap: wrap !important;
			margin: 0 !important;
			gap: 8px !important;
		}

		/* Cards dos itens */
		.item-card {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			height: auto !important;
			min-height: 150px !important;
			transition: all 0.3s ease !important;
			background: #ffffff !important;
			border: 1px solid #e1e3ea !important;
			border-radius: 8px !important;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
		}

		.item-card:hover {
			transform: translateY(-2px) !important;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
			border-color: #28a745 !important;
		}

		/* Scrollbar da área de itens */
		.items-content::-webkit-scrollbar {
			width: 6px !important;
		}

		.items-content::-webkit-scrollbar-track {
			background: #f1f1f1 !important;
			border-radius: 3px !important;
		}

		.items-content::-webkit-scrollbar-thumb {
			background: #28a745 !important;
			border-radius: 3px !important;
		}

		.items-content::-webkit-scrollbar-thumb:hover {
			background: #1e7e34 !important;
		}

		/* Responsividade */
		@media (max-width: 768px) {
			.pagination-btn {
				padding: 4px 8px !important;
				font-size: 11px !important;
				min-width: 28px !important;
			}
			
			.items-content {
				max-height: 250px !important;
			}
		}
	
        /* CSS otimizado para compatibilidade com Bootstrap */
        .alert-light-info {
            background-color: rgba(0, 123, 255, 0.08);
            border: 1px solid rgba(0, 123, 255, 0.2);
            color: #0056b3;
            border-radius: 8px;
        }
        
        /* Altura controlada para a aba de itens */
        .tab-content {
            min-height: 600px;
        }
        
        /* Cards com bordas coloridas e melhor separação */
        .card.border-primary {
            border-color: #007bff !important;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15) !important;
        }
        
        .card.border-success {
            border-color: #28a745 !important;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15) !important;
        }
        
        .card-header.bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
        
        .card-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
        }
        
        /* Melhor espaçamento entre colunas */
        .row.g-4 > .col-12 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Scrollbar customizada para áreas de scroll */
        .card-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .card-body::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        .card-body::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
        
        /* Responsividade melhorada */
        @media (max-width: 1399.98px) {
            .col-xl-4 {
                flex: 0 0 auto;
                width: 100%;
                margin-bottom: 1.5rem;
            }
            
            .col-xl-8 {
                flex: 0 0 auto;
                width: 100%;
            }
            
            .row.g-4 {
                flex-direction: column;
            }
        }
        
        /* Estados visuais melhorados */
        #items-loading .spinner-border {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
		
		/* Esconder campos Sex duplicados */
        .modal-body [name="Sex"]:not(:first-of-type) {
            display: none !important;
        }
        
        /* Garantir que labels duplicadas também sejam escondidas */
        .modal-body .fv-row:has([name="Sex"]):not(:first-of-type) {
            display: none !important;
        }
    </style>
@endsection

@section('custom-js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        function renderFuguraItem(fugura) {
            const sexoIcon = fugura.Sex == 0 ? '👤' : fugura.Sex == 1 ? '👦' : '👧';
            const sexoText = fugura.Sex == 0 ? 'Unissex' : fugura.Sex == 1 ? 'Masculino' : 'Feminino';
            
            return `
                <div class="d-flex align-items-center border border-gray-300 border-dashed rounded p-5 mb-5 fugura-item cursor-pointer" 
                     data-id="${fugura.ID}" 
                     data-name="${fugura.Name}"
                     data-sex="${fugura.Sex}"
                     data-type="${fugura.Type}"
                     data-attack="${fugura.Attack}"
                     data-defend="${fugura.Defend}"
                     data-agility="${fugura.Agility}"
                     data-luck="${fugura.Luck}"
                     data-blood="${fugura.Blood}"
                     data-damage="${fugura.Damage}"
                     data-guard="${fugura.Guard}"
                     data-cost="${fugura.Cost}"
                     onclick="selectFugura(${fugura.ID})">
                    <div class="symbol symbol-50px overflow-hidden me-3">
                        <div class="symbol-label fs-3 fw-bold text-primary">
                            ${sexoIcon}
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-gray-800 text-hover-primary fs-6 fw-bold">${fugura.Name}</span>
                            <span class="badge badge-light-primary fs-8">#${fugura.ID}</span>
                        </div>
                        <span class="text-gray-400 fw-bold fs-7">${sexoText} • Tipo: ${fugura.Type}</span>
                        <span class="text-gray-400 fw-bold fs-8">Atk: ${fugura.Attack} • Def: ${fugura.Defend}</span>
                    </div>
                </div>
            `;
        }
        
        function loadFuguraList() {
            const fuguras = @json($fuguras);
            const listContainer = document.getElementById('fugura_list');
            const notResults = document.getElementById('not_results');
            
            if (fuguras.length === 0) {
                listContainer.style.display = 'none';
                notResults.style.display = 'block';
            } else {
                notResults.style.display = 'none';
                listContainer.style.display = 'block';
                listContainer.innerHTML = fuguras.map(renderFuguraItem).join('');
            }
        }
        
        function selectFugura(id) {
            document.querySelectorAll('.fugura-item').forEach(item => {
                item.classList.remove('border-primary', 'bg-light-primary');
                item.classList.add('border-gray-300');
            });
            
            const selectedItem = document.querySelector(`[data-id="${id}"]`);
            if (selectedItem) {
                selectedItem.classList.add('border-primary', 'bg-light-primary');
                selectedItem.classList.remove('border-gray-300');
            }
            
            const fuguras = @json($fuguras);
            const fugura = fuguras.find(f => f.ID == id);
            
            if (fugura) {
                window.currentFugura = fugura;
                document.getElementById('not_selected').style.display = 'none';
                document.getElementById('fugura_data').style.display = 'block';
                populateForms(fugura);
            }
        }
        
        function populateForms(fugura) {
            const detailsForm = document.getElementById('form-fugura-edit-send');
            detailsForm.querySelector('[name="ID"]').value = fugura.ID;
            detailsForm.querySelector('[name="Name"]').value = fugura.Name;
            detailsForm.querySelector('[name="Sex"]').value = fugura.Sex;
            detailsForm.querySelector('[name="Type"]').value = fugura.Type;
            
            const statsForm = document.getElementById('form-fugura-stats-send');
            statsForm.querySelector('[name="Attack"]').value = fugura.Attack;
            statsForm.querySelector('[name="Defend"]').value = fugura.Defend;
            statsForm.querySelector('[name="Agility"]').value = fugura.Agility;
            statsForm.querySelector('[name="Luck"]').value = fugura.Luck;
            statsForm.querySelector('[name="Blood"]').value = fugura.Blood;
            statsForm.querySelector('[name="Damage"]').value = fugura.Damage;
            statsForm.querySelector('[name="Guard"]').value = fugura.Guard;
            statsForm.querySelector('[name="Cost"]').value = fugura.Cost;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            loadFuguraList();
        });
    </script>
    
    <script src="{{ url() }}/assets/js/admin/fugura/fugura.js"></script>
@endsection