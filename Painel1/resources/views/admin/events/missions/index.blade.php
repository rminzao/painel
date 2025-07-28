@extends('layouts.app')

@section('title', 'Missões de Eventos')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🏆 Missões de Eventos</h1>

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
                    <li class="breadcrumb-item text-white opacity-75">Missões</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select id="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary me-2" id="button_reload_missions"
                    onclick="mission.reload()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z" fill="currentColor" />
                                <path d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z" fill="currentColor" />
                            </svg>
                        </span>
                        recarregar
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
                <!-- Sidebar com lista de missões -->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="mission_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Buscar por ActivityType, SubType..." id="mission_search" />
                                        </div>
                                    </div>
                                    <select name="missionType_filter" class="form-select form-select-sm form-select-solid w-60"
                                        data-hide-search="true" data-control="select2" data-placeholder="Tipo de Missão">
                                        <option value="all" selected>Todos</option>
                                        @foreach ($missionTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_mission_new">
                                    adicionar missão
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhuma missão encontrada',
                                ])
                            </div>

                            <div id="mission_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>

                            <div id="mission_footer">
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
                                    <div id="mission_paginator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Painel principal com detalhes da missão -->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_chat_messenger">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em uma missão para continuar',
                            ])
                        </div>
                        <div id="mission_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#mission_info">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#mission_reward">
                                                    🎁 Recompensas
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex" id="reward_buttons" style="display: none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_mission_new_item"
                                            class="btn btn-light-primary btn-sm me-2">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor"></rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor"></rect>
                                                </svg>
                                            </span>
                                            Adicionar recompensa
                                        </button>
                                        <div>
                                            <button class="btn btn-sm btn-icon btn-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" id="menu_utils">
                                                <span class="indicator-label"><i class="bi bi-three-dots fs-2"></i></span>
                                                <span class="indicator-progress">
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>

                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Recompensas</div>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" onclick="missionReward.confirmDelete(0)" class="menu-link bg-transparent text-danger px-3">☠️ Apagar todas</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content">
                                <!-- Aba Detalhes -->
                                <div class="tab-pane fade show active" id="mission_info" role="tabpanel">
                                    <div class="card-body">
                                        <form id="form-mission-edit-send">
                                            <div class="row mb-5">
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Tipo de Atividade</label>
                                                    <select class="form-select form-select-sm form-select-solid"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="Selecione o tipo" name="ActivityType">
                                                        @foreach ($missionTypes as $key => $value)
                                                            <option value="{{ $key }}">{{ $key }} - {{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎯 Sub-Atividade</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                                        name="SubActivityType" value="" min="1" />
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="fv-row col-12">
                                                    <label class="fs-6 fw-bold form-label mb-2">💯 Condição</label>
                                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                                        name="Condition" value="" min="1" />
                                                    <div class="form-text">Valor necessário para completar a missão</div>
                                                </div>
                                            </div>
                                            <div class="text-center pt-5">
                                                <button type="button" onclick="mission.update()" id="btn_mission_update"
                                                    class="btn btn-sm btn-light-primary w-100">
                                                    <span class="indicator-label">Aplicar alterações</span>
                                                    <span class="indicator-progress">
                                                        aplicando alterações...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Aba Recompensas -->
                                <div class="tab-pane fade" id="mission_reward" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="no_rewards">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem recompensas',
                                                'message' => 'essa missão não possui nenhuma recompensa',
                                            ])
                                        </div>
                                        <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px" style="display: none;" id="rewards_list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS Customizado para Melhorias Visuais -->
    <style>
        /* ============================================================ */
        /* 📦 DESIGN DAS BOXES DE MISSÕES - LAYOUT MELHORADO */
        /* ============================================================ */
        .mission-item {
            cursor: pointer;
            margin: 8px 0;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            background: #ffffff;
            overflow: hidden;
            position: relative;
        }

        .mission-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            transform: translateY(-2px);
        }

        .mission-item.active {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.2);
        }

        .mission-content {
            padding: 16px;
        }

        .mission-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 8px;
        }

        .mission-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.4;
            flex: 1;
            margin: 0;
        }

        /* 🔄 NOVOS ESTILOS PARA BOTÕES DE AÇÃO */
        .mission-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .mission-reset-btn {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #0284c7;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .mission-reset-btn:hover {
            background: #e0f2fe;
            border-color: #7dd3fc;
            color: #0369a1;
            opacity: 1;
            transform: scale(1.05);
        }

        .mission-delete-btn {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .mission-delete-btn:hover {
            background: #fee2e2;
            border-color: #f87171;
            color: #b91c1c;
            opacity: 1;
            transform: scale(1.05);
        }

        .mission-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .mission-badge {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
            box-shadow: 0 1px 3px rgba(107, 114, 128, 0.3);
        }

        .mission-condition {
            font-size: 12px;
            color: #059669;
            font-weight: 600;
            background: #d1fae5;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Contador de resultados */
        .total-count {
            font-size: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 8px;
        }

        /* ============================================================ */
        /* 🎨 HOVERS DE BOTÕES */
        /* ============================================================ */
        .btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            font-weight: 500;
        }

        /* Botão Primary */
        .btn-light-primary {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }

        .btn-light-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-light-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.4);
        }

        /* Botão Danger */
        .btn-light-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .btn-light-danger:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-color: #dc2626;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .btn-light-danger:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
        }

        /* Botão Success */
        .btn-light-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .btn-light-success:hover {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            border-color: #15803d;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        /* Botão pequeno refinado */
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
        }

        /* ============================================================ */
        /* 🎨 MELHORIAS GERAIS */
        /* ============================================================ */
        .card {
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }

        .card-header {
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border-bottom: 1px solid #e5e7eb;
        }

        /* Tabs melhoradas */
        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            transition: all 0.2s ease;
            color: #6b7280;
            border: none;
            margin-right: 4px;
        }

        .nav-tabs .nav-link:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        .nav-tabs .nav-link.active {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-bottom: 1px solid white;
            color: #3b82f6;
            font-weight: 600;
        }

        /* Forms melhorados */
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Badges melhorados */
        .badge {
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
        }

        /* Recompensas melhoradas */
        .reward-card {
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
        }

        .reward-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #3b82f6;
        }

        /* Modal melhorado */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Select2 melhorado */
        .select2-container--default .select2-selection--single {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            height: calc(1.5em + 0.75rem + 2px);
            transition: all 0.2s ease;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Animações suaves */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mission-item {
            animation: fadeIn 0.3s ease-out;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .mission-content {
                padding: 12px;
            }
            
            .mission-badge {
                font-size: 11px;
                padding: 3px 8px;
            }
            
            .btn-sm {
                font-size: 12px;
                padding: 5px 10px;
            }
            
            .mission-actions {
                gap: 2px;
            }
            
            .mission-reset-btn,
            .mission-delete-btn {
                width: 24px;
                height: 24px;
            }
        }

        /* Seleção de missão ativa */
        .mission-item[data-selected="true"] {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.2);
        }

        /* Efeito de pulse para loading */
        .loading-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
@endsection

@section('modals')
    <!-- Modal: Nova Missão -->
    <div class="modal fade" id="md_mission_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-6">🏆 Adicionar nova missão</h4>
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
                    <form id="form_mission_create">
                        <div class="row mb-5">
                            <div class="fv-row">
                                <label class="fs-6 fw-bold form-label mb-2">🏷️ Tipo de Atividade</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-hide-search="true" data-placeholder="Selecione o tipo" name="ActivityType">
                                    @foreach ($missionTypes as $key => $value)
                                        <option value="{{ $key }}">{{ $key }} - {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🎯 Sub-Atividade</label>
                                <input type="number" class="form-control form-control-sm form-control-solid"
                                    name="SubActivityType" value="" min="1" placeholder="Auto preenchido">
                                <div class="form-text">Próximo ID disponível será preenchido automaticamente</div>
                            </div>
                            <div class="fv-row col-6">
                                <label class="fs-6 fw-bold form-label mb-2">💯 Condição</label>
                                <input type="number" class="form-control form-control-sm form-control-solid"
                                    name="Condition" value="" min="1" placeholder="Auto preenchido">
                                <div class="form-text">Baseado na última condição</div>
                            </div>
                        </div>
                        <div class="text-center pt-5">
                            <button type="button" onclick="mission.create()" id="form_mission_submit"
                                class="btn btn-sm btn-light-primary w-100">
                                <span class="indicator-label">Criar missão</span>
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

    <!-- Modal: Adicionar Recompensa -->
    <div class="modal fade" id="md_mission_new_item" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="md-reward-pic" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="md-reward-name"></span>
                            <a href="javascript:;" id="md-reward-id" class="text-gray-800 text-hover-primary mb-1"></a>
                        </div>
                    </div>
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
                    <form id="form-mission-reward-send">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="mb-7">
                                <label for="itemID" class="form-label">🎁 Item</label>
                                <select class="form-select form-select-sm form-select-solid"
                                    data-dropdown-parent="#md_mission_new_item" data-placeholder="Selecione um item"
                                    data-allow-clear="true" id="itemID" name="template_id">
                                </select>
                            </div>
                            <div id="md-item-info" style="display: none">
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>🗳 Quantidade</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Quantidade do item na recompensa"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="1" name="count" value="1" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>📆 Validade (dias)</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Validade do item em dias (0 = permanente)"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="validity" value="0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>💪 Strength Level</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Nível de força do item"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="strength_level" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>⚔️ Attack Compose</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Composição de ataque"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="attack_compose" value="0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>🛡️ Defend Compose</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Composição de defesa"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="defend_compose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>🍀 Luck Compose</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Composição de sorte"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="luck_compose" value="0" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-7 fv-row col-6">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>💨 Agility Compose</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                title="Composição de agilidade"></i>
                                        </label>
                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                            step="1" min="0" name="agility_compose" value="0" />
                                    </div>
                                    <div class="d-flex flex-stack mb-7 col-6">
                                        <div class="me-5">
                                            <label class="fs-6 fw-bold form-label">🔒 Is Bind</label>
                                            <div class="fs-7 fw-bold text-muted">Item será vinculado ao personagem.</div>
                                        </div>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-20px w-30px" type="checkbox" name="is_bind"
                                                value="1" checked="checked" />
                                            <span class="form-check-label fw-bold text-muted"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" onclick="missionReward.create()" id="btn_reward_create"
                                        class="btn btn-sm btn-light-primary w-100" disabled>
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

    <!-- Modal: Editar Recompensa -->
	<div class="modal fade" id="md_mission_edit_item" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-400px">
			<div class="modal-content">
				<div class="modal-header">
					<div class="d-flex align-items-center">
						<div class="symbol symbol-50px overflow-hidden me-3">
							<div class="symbol-label fs-2 fw-bold text-success">
								<img id="md-edit-reward-pic" class="w-100" />
							</div>
						</div>
						<div class="d-flex flex-column">
							<span id="md-edit-reward-name"></span>
							<a href="javascript:;" id="md-edit-reward-id" class="text-gray-800 text-hover-primary mb-1"></a>
						</div>
					</div>
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
					<form id="form-mission-reward-edit-send">
						<input type="hidden" name="template_id" />
						
						<div class="form fv-plugins-bootstrap5 fv-plugins-framework">
							<div class="row">
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>🗳 Quantidade</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="1" name="count" value="1" />
								</div>
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>📆 Validade (dias)</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="validity" value="0" />
								</div>
							</div>
							
							<div class="row">
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>💪 Strength Level</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="strength_level" value="0" />
								</div>
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>⚔️ Attack Compose</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="attack_compose" value="0" />
								</div>
							</div>
							
							<div class="row">
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>🛡️ Defend Compose</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="defend_compose" value="0" />
								</div>
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>🍀 Luck Compose</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="luck_compose" value="0" />
								</div>
							</div>
							
							<div class="row">
								<div class="d-flex flex-column mb-7 fv-row col-6">
									<label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
										<span>💨 Agility Compose</span>
									</label>
									<input type="number" class="form-control form-control-sm form-control-solid"
										step="1" min="0" name="agility_compose" value="0" />
								</div>
								<div class="d-flex flex-stack mb-7 col-6">
									<div class="me-5">
										<label class="fs-6 fw-bold form-label">🔒 Is Bind</label>
										<div class="fs-7 fw-bold text-muted">Item será vinculado ao personagem.</div>
									</div>
									<label class="form-check form-switch form-check-custom form-check-solid">
										<input class="form-check-input h-20px w-30px" type="checkbox" name="is_bind" value="1" />
										<span class="form-check-label fw-bold text-muted"></span>
									</label>
								</div>
							</div>
							
							<div class="text-center">
								<button type="button" onclick="missionReward.update()" id="btn_reward_edit"
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
    <script src="{{ url() }}/assets/js/admin/missions/missions.js"></script>
@endsection