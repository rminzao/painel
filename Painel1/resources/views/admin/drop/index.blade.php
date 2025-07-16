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
                                    <!-- Campo de busca -->
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="🔍 Buscar por nome ou ID" id="fugura_search" />
                                        </div>
                                    </div>
                                    <!-- Filtro por sexo -->
                                    <select name="sexo_filter" class="form-select form-select-sm form-select-solid w-60"
                                        data-hide-search="true" data-control="select2" data-placeholder="Filtrar por Sexo">
                                        <option value="all" selected>🎭 Todos os Sexos</option>
                                        <option value="0">⚪ Unissex</option>
                                        <option value="1">🔵 Masculino</option>
                                        <option value="2">🔴 Feminino</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    ➕ Nova Fúgura
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7">
                            <!-- Área de resultados -->
                            <div id="not_results" style="display: none;">
                                <div class="d-flex flex-column text-center">
                                    <div class="text-gray-800 fs-6 fw-bolder mb-2">🔍 Nenhuma figura encontrada</div>
                                    <div class="text-gray-400 fs-7">Tente ajustar os filtros ou criar uma nova figura</div>
                                </div>
                            </div>

                            <!-- Lista de figuras -->
                            <div id="fugura_list" class="scroll-y me-n5 h-lg-auto"></div>

                            <!-- Footer com paginação -->
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
                        <!-- Estado inicial - nenhuma figura selecionada -->
                        <div id="not_selected">
                            <div class="card-body d-flex flex-column justify-content-center text-center p-15">
                                <div class="text-gray-800 fs-6 fw-bolder mb-4">🎭 Sem figura selecionada</div>
                                <div class="text-gray-400 fs-7 mb-7">Clique em uma figura na lista ao lado para visualizar e editar seus detalhes</div>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                    ➕ Criar Nova Fúgura
                                </button>
                            </div>
                        </div>
                        
                        <!-- Área de dados da figura selecionada -->
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
                            
                            <!-- Conteúdo das abas -->
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
                                                        <option value="0">⚪ Unissex</option>
                                                        <option value="1">🔵 Masculino</option>
                                                        <option value="2">🔴 Feminino</option>
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">⌨️ Tipo</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid" 
                                                           name="Type" value="" />
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
                                       name="Name" required placeholder="Nome da figura">
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🎭 Sexo</label>
                                <select class="form-select form-select-sm form-select-solid" 
                                        data-control="select2" data-hide-search="true" name="Sex">
                                    <option value="0">⚪ Unissex</option>
                                    <option value="1">🔵 Masculino</option>
                                    <option value="2">🔴 Feminino</option>
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">⌨️ Tipo</label>
                                <input type="number" class="form-control form-control-sm form-control-solid" 
                                       name="Type" required min="0" placeholder="Ex: 1, 2, 3...">
                            </div>
                        </div>
                        
                        <!-- Atributos em grid 2x4 -->
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

@section('custom-js')
    <script>
        // Função para renderizar item da lista COM TODOS OS DATA ATTRIBUTES
        function renderFuguraItem(fugura) {
            const sexoIcon = fugura.Sex == 0 ? '⚪' : fugura.Sex == 1 ? '🔵' : '🔴';
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
        
        // Carregar lista de figuras
        function loadFuguraList() {
            // Aqui você faria a requisição AJAX para buscar as figuras
            // Por enquanto vou usar os dados PHP existentes
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
        
        // Selecionar figura
        function selectFugura(id) {
            // Remover seleção anterior
            document.querySelectorAll('.fugura-item').forEach(item => {
                item.classList.remove('border-primary', 'bg-light-primary');
                item.classList.add('border-gray-300');
            });
            
            // Adicionar seleção atual
            const selectedItem = document.querySelector(`[data-id="${id}"]`);
            if (selectedItem) {
                selectedItem.classList.add('border-primary', 'bg-light-primary');
                selectedItem.classList.remove('border-gray-300');
            }
            
            // Buscar dados da figura
            const fuguras = @json($fuguras);
            const fugura = fuguras.find(f => f.ID == id);
            
            if (fugura) {
                // Mostrar área de dados
                document.getElementById('not_selected').style.display = 'none';
                document.getElementById('fugura_data').style.display = 'block';
                
                // Preencher formulários
                populateForms(fugura);
            }
        }
        
        // Preencher formulários com dados da figura
        function populateForms(fugura) {
            // Formulário de detalhes
            const detailsForm = document.getElementById('form-fugura-edit-send');
            detailsForm.querySelector('[name="ID"]').value = fugura.ID;
            detailsForm.querySelector('[name="Name"]').value = fugura.Name;
            detailsForm.querySelector('[name="Sex"]').value = fugura.Sex;
            detailsForm.querySelector('[name="Type"]').value = fugura.Type;
            
            // Formulário de atributos
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
        
        // Inicializar ao carregar página
        document.addEventListener('DOMContentLoaded', function() {
            loadFuguraList();
        });
    </script>
    <script src="{{ url() }}/assets/js/admin/fugura/fugura.js"></script>
@endsection