@extends('layouts.app')

@section('title', 'NPCs')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🤖 NPCs ({{ $total_npcs ?? count($npcs ?? []) }})</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">NPCs</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#createNpcModal">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor" />
                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                        </svg>
                    </span>
                    Criar NPC
                </button>
                <button type="button" class="btn btn-light-primary me-2" id="button_refresh_list" 
                        title="Clique: Atualizar XML + Lista | Ctrl+Clique: Apenas Lista">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z" fill="currentColor" />
                                <path d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z" fill="currentColor" />
                            </svg>
                        </span>
                        Atualizar XML
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Criação de NPC -->
    <div class="modal fade" id="createNpcModal" tabindex="-1" aria-labelledby="createNpcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNpcModalLabel">🤖 Criar Novo NPC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createForm">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">🆔 ID</label>
                                <input type="number" class="form-control" name="ID" required min="1" placeholder="Ex: 12345">
                            </div>
                            <div class="col-6">
                                <label class="form-label">🏷️ Nome</label>
                                <input type="text" class="form-control" name="Name" required placeholder="Ex: Demônio Vermelho">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-4">
                                <label class="form-label">📈 Level</label>
                                <input type="number" class="form-control" name="Level" value="1" min="1" max="999">
                            </div>
                            <div class="col-4">
                                <label class="form-label">🎭 Tipo</label>
                                <select name="Type" class="form-select" required>
                                    <option value="0">Comum</option>
                                    <option value="1">Guerreiro</option>
                                    <option value="2">Guarda</option>
                                    <option value="3">Arqueiro</option>
                                    <option value="4">Mago</option>
                                    <option value="5">Assassino</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">❤️ Vida</label>
                                <input type="number" class="form-control" name="Blood" value="100" min="1">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">⚔️ Attack</label>
                                <input type="number" class="form-control" name="Attack" value="10" min="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label">🛡️ Defence</label>
                                <input type="number" class="form-control" name="Defence" value="5" min="0">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">🔮 Magic Attack</label>
                                <input type="number" class="form-control" name="MagicAttack" value="0" min="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label">🛡️ Magic Defence</label>
                                <input type="number" class="form-control" name="MagicDefence" value="0" min="0">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-4">
                                <label class="form-label">💥 Dano Base</label>
                                <input type="number" class="form-control" name="BaseDamage" value="5" min="0">
                            </div>
                            <div class="col-4">
                                <label class="form-label">🛡️ Guarda Base</label>
                                <input type="number" class="form-control" name="BaseGuard" value="3" min="0">
                            </div>
                            <div class="col-4">
                                <label class="form-label">⚡ Agilidade</label>
                                <input type="number" class="form-control" name="Agility" value="0" min="0">
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">🍀 Sorte</label>
                                <input type="number" class="form-control" name="Lucky" value="0" min="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label">💨 Velocidade</label>
                                <input type="number" class="form-control" name="speed" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">🤖 Criar NPC</span>
                            <span class="indicator-progress">
                                Criando...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            
            @if(isset($error_message))
                <div class="alert alert-warning d-flex align-items-center p-5 mb-10">
                    <span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="currentColor"></path>
                            <path d="M19 15V13C19 10.8 17.2 9 15 9H14.5C14.5 8.4 14.1 8 13.5 8H10.5C9.9 8 9.5 8.4 9.5 9H9C6.8 9 5 10.8 5 13V15C5 15.6 5.4 16 6 16H18C18.6 16 19 15.6 19 15Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-warning">Problema no carregamento</h4>
                        <span>{{ $error_message }}</span>
                    </div>
                </div>
            @endif
            
            <div class="d-flex flex-column flex-lg-row">
                <!-- SIDEBAR ESQUERDA - LISTA DE NPCs -->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="npc_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="🔍 Buscar por nome ou ID" id="npc_search" />
                                        </div>
                                    </div>
                                    <select name="level_filter" class="form-select form-select-sm form-select-solid w-100"
                                        data-hide-search="true" data-placeholder="Filtrar por Level">
                                        <option value="all" selected>🎯 Todos os Levels</option>
                                        <option value="1-5">Level 1-5</option>
                                        <option value="6-10">Level 6-10</option>
                                        <option value="11-15">Level 11-15</option>
                                        <option value="16-20">Level 16-20</option>
                                        <option value="21-25">Level 21-25</option>
                                        <option value="26-30">Level 26-30</option>
                                        <option value="31-40">Level 31-40</option>
                                        <option value="41-50">Level 41-50</option>
                                        <option value="51-75">Level 51-75</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7">
                            <div id="not_results" style="display: none;">
                                <div class="d-flex flex-column text-center">
                                    <div class="text-gray-800 fs-6 fw-bolder mb-2">🔍 Nenhum NPC encontrado</div>
                                    <div class="text-gray-400 fs-7">Tente ajustar os filtros</div>
                                </div>
                            </div>

                            <div id="npc_list" class="scroll-y me-n5 h-lg-auto"></div>

                            <div id="npc_footer">
                                <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                                    <div>
                                        <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                            data-hide-search="true">
                                            <option value="5" selected>5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                        </select>
                                    </div>
                                    <div id="item_paginator" class="flex-grow-1 d-flex justify-content-end"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÁREA PRINCIPAL - DETALHES/EDIÇÃO -->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_npc_messenger">
                        <div id="not_selected">
                            <div class="card-body d-flex flex-column justify-content-center text-center p-15">
                                <div class="text-gray-800 fs-6 fw-bolder mb-4">🤖 Nenhum NPC selecionado</div>
                                <div class="text-gray-400 fs-7 mb-7">Clique em um NPC na lista ao lado para visualizar e editar seus atributos</div>
                            </div>
                        </div>
                        
                        <div id="npc_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0" id="npc-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#npc_details" data-tab="npc_details">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#npc_attributes" data-tab="npc_attributes">
                                                    ⚔️ Atributos
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#npc_calculator" data-tab="npc_calculator">
                                                    🧮 Calculadora
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-icon btn-light-success me-2" id="btn_duplicate_npc"
                                                data-bs-toggle="tooltip" title="Duplicar NPC">
                                            <i class="bi bi-files fs-3"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger" id="btn_delete_npc"
                                                data-bs-toggle="tooltip" title="Deletar NPC">
                                            <i class="bi bi-trash fs-3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-content" id="npc-tab-content">
                                <!-- ABA DETALHES -->
                                <div class="tab-pane active" id="npc_details" role="tabpanel" style="display: block;">
                                    <div class="card-body">
                                        <form id="form-npc-details-send">
                                            <input type="hidden" name="npc_id" value="">
                                            
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🆔 ID</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="ID" value="" readonly />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                                    <input type="text" class="form-control form-control-sm form-control-solid" 
                                                           name="name" value="" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">📈 Level</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="level" value="" min="1" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎭 Tipo</label>
                                                    <select name="type" class="form-select form-select-sm form-select-solid">
                                                        <option value="0">Comum</option>
                                                        <option value="1">Guerreiro</option>
                                                        <option value="2">Guarda</option>
                                                        <option value="3">Arqueiro</option>
                                                        <option value="4">Mago</option>
                                                        <option value="5">Assassino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-5">
                                                <div class="fv-row col-12">
                                                    <label class="fs-6 fw-bold form-label mb-2">❤️ Vida (Blood)</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="blood" value="" min="1" />
                                                </div>
                                            </div>
                                            
                                            <div class="text-center pt-5">
                                                <button type="button" onclick="window.npcManager.updateDetails()"
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
                                <div class="tab-pane" id="npc_attributes" role="tabpanel" style="display: none;">
                                    <div class="card-body">
                                        <form id="form-npc-attributes-send">
                                            <input type="hidden" name="npc_id" value="">
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">⚔️ Attack</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="attack" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🛡️ Defence</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="defence" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🔮 Magic Attack</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="magicattack" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🛡️ Magic Defence</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="magicdefence" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">💥 Dano Base</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="basedamage" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🛡️ Guarda Base</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="baseguard" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">⚡ Agilidade</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="agility" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🍀 Sorte</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="lucky" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="fv-row col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏃 Move Min</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="movemin" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏃 Move Max</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="movemax" value="" min="0" />
                                                </div>
                                                <div class="fv-row col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">💨 Velocidade</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="speed" value="" min="0" />
                                                </div>
                                            </div>
                                            
                                            <div class="text-center pt-5">
                                                <button type="button" onclick="window.npcManager.updateAttributes()"
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
                                
                                <!-- ABA CALCULADORA -->
                                <div class="tab-pane" id="npc_calculator" role="tabpanel" style="display: none;">
                                    <div class="card-body">
                                        <div class="alert alert-info d-flex align-items-center p-5 mb-6">
                                            <span class="fs-1 me-4">🧮</span>
                                            <div class="d-flex flex-column">
                                                <h4 class="mb-1 text-info">Calculadora Automática de Atributos</h4>
                                                <span>Baseada nos <strong>4 jogadores intermediários</strong> mais fortes do servidor (posições 3-6).</span>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-5">
                                            <div class="col-8">
                                                <label class="fs-6 fw-bold form-label mb-2">🎯 Nível de Dificuldade</label>
                                                <select id="difficulty_select" class="form-select form-select-sm form-select-solid">
                                                    <option value="fácil">🟢 Fácil (x0.6) - NPCs mais fracos</option>
                                                    <option value="médio" selected>🟡 Médio (x0.9) - Balanceado</option>
                                                    <option value="difícil">🟠 Difícil (x1.1) - Desafiador</option>
                                                    <option value="insano">🔴 Insano (x1.3) - Apenas experts</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label class="fs-6 fw-bold form-label mb-2">&nbsp;</label>
                                                <button type="button" id="btn_calculate_attributes" class="btn btn-primary w-100">
                                                    <span class="indicator-label">🧮 Calcular Atributos</span>
                                                    <span class="indicator-progress">
                                                        Calculando...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- RESULTADOS -->
                                        <div id="calculation_results" style="display: none;">
                                            <div class="separator border-2 my-6"></div>
                                            
                                            <h5 class="mb-4">📊 Atributos Calculados</h5>
                                            
                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-success rounded p-3">
                                                        <span class="fs-4 me-3">⚔️</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Attack</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_attack">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-primary rounded p-3">
                                                        <span class="fs-4 me-3">🛡️</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Defence</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_defence">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-warning rounded p-3">
                                                        <span class="fs-4 me-3">🍀</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Luck</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_luck">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-info rounded p-3">
                                                        <span class="fs-4 me-3">⚡</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Agility</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_agility">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-dark rounded p-3">
                                                        <span class="fs-4 me-3">🔮</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Magic Attack</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_magic_attack">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center bg-light-secondary rounded p-3">
                                                        <span class="fs-4 me-3">🛡️</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Magic Defence</div>
                                                            <div class="fs-3 fw-bold text-gray-800" id="calc_magic_defence">--</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center bg-light-danger rounded p-3">
                                                        <span class="fs-2 me-3">❤️</span>
                                                        <div class="flex-grow-1">
                                                            <div class="fs-7 text-muted">Blood (Vida)</div>
                                                            <div class="fs-2 fw-bold text-gray-800" id="calc_blood">--</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="fs-7 text-muted">Baseado no maior HP do servidor</div>
                                                            <div class="fs-6 fw-bold text-gray-600">Max HP: <span id="calc_max_hp">--</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- BOTÕES DE AÇÃO -->
                                            <div class="text-center pt-5">
                                                <button type="button" id="btn_apply_calculated" class="btn btn-success me-3">
                                                    <span class="indicator-label">✅ Aplicar aos Atributos</span>
                                                    <span class="indicator-progress">
                                                        Aplicando...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                                <button type="button" id="btn_new_npc_calculated" class="btn btn-primary">
                                                    🤖 Criar Novo NPC com estes valores
                                                </button>
                                            </div>
                                            
                                            <!-- INFO ADICIONAL -->
                                            <div class="alert alert-light-primary d-flex align-items-center p-4 mt-5">
                                                <span class="fs-1 me-4">ℹ️</span>
                                                <div class="d-flex flex-column">
                                                    <div class="fs-7 fw-bold text-primary">Informações do Cálculo</div>
                                                    <span class="fs-8 text-gray-600">
                                                        Dificuldade: <span id="info_difficulty">--</span> | 
                                                        Multiplicador: <span id="info_multiplier">--</span> | 
                                                        Jogadores analisados: <span id="info_players">--</span>
                                                    </span>
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

@section('custom-css')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* Lista de NPCs com estilo melhorado */
        .npc-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            margin-bottom: 1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .npc-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,123,255,0.2);
            border-color: #007bff !important;
        }

        .npc-item.selected {
            border-color: #007bff !important;
            background: linear-gradient(135deg, rgba(0,123,255,0.1) 0%, rgba(0,123,255,0.05) 100%);
            box-shadow: 0 4px 20px rgba(0,123,255,0.3);
        }

        /* Ícones dos NPCs */
        .npc-item .symbol-label {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .npc-item:hover .symbol-label {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-color: #007bff;
            color: white !important;
            transform: scale(1.1);
        }

        .npc-item.selected .symbol-label {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-color: #007bff;
            color: white !important;
        }

        /* Garantir que as abas funcionem */
        .nav-link {
            cursor: pointer !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(0, 123, 255, 0.1) !important;
            border-radius: 8px !important;
        }

        .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
            border-radius: 8px !important;
        }

        .tab-content {
            min-height: 400px;
        }

        .tab-pane {
            display: none !important;
        }

        .tab-pane.active {
            display: block !important;
        }

        /* Estilos da Calculadora */
        #calculation_results {
            animation: fadeInUp 0.5s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-light-success, .bg-light-primary, .bg-light-warning, 
        .bg-light-info, .bg-light-dark, .bg-light-secondary, 
        .bg-light-danger, .bg-light-primary {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .bg-light-success:hover { border-color: #1bc5bd; transform: translateY(-2px); }
        .bg-light-primary:hover { border-color: #3699ff; transform: translateY(-2px); }
        .bg-light-warning:hover { border-color: #ffa800; transform: translateY(-2px); }
        .bg-light-info:hover { border-color: #8950fc; transform: translateY(-2px); }
        .bg-light-dark:hover { border-color: #3f4254; transform: translateY(-2px); }
        .bg-light-secondary:hover { border-color: #e4e6ef; transform: translateY(-2px); }
        .bg-light-danger:hover { border-color: #f64e60; transform: translateY(-2px); }

        /* Botões da calculadora */
        #btn_calculate_attributes {
            background: linear-gradient(135deg, #3699ff 0%, #0056b3 100%);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        #btn_calculate_attributes:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(54, 153, 255, 0.4);
        }

        #btn_apply_calculated {
            background: linear-gradient(135deg, #1bc5bd 0%, #0bb7af 100%);
            border: none;
            font-weight: 600;
        }

        #btn_new_npc_calculated {
            background: linear-gradient(135deg, #8950fc 0%, #7239ea 100%);
            border: none;
            font-weight: 600;
        }

        /* Select de dificuldade */
        #difficulty_select {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid #e1e5e9;
            font-weight: 500;
        }

        #difficulty_select:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 0.2rem rgba(54, 153, 255, 0.25);
        }
    </style>
@endsection

@section('custom-js')
    <!-- jQuery e Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Dados globais do NPC antes do JS externo -->
    <script>
        window.npcData = @json($npcs ?? []);
        window.baseUrl = "{{ url('') }}";
    </script>

    <!-- Sistema NPC -->
    <script src="{{ url() }}/assets/js/admin/npc/npc.js"></script>
@endsection