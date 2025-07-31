@extends('layouts.app')

@section('title', '🎟 Passe de Batalha')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🎟 Warpass</h1>

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
                    <li class="breadcrumb-item text-white opacity-75">Game Utils</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Warpass</li>
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
                <button type="button" class="btn btn-light-primary me-2" onclick="WarPass.loadLevels()">
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
            <!-- Sidebar com navegação -->
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-380px mb-10 mb-lg-0">
                <div class="card card-flush" id="warpass_sidebar">
                    <!-- Header da Sidebar -->
                    <div class="warpass-sidebar-header">
                        <div class="sidebar-title">
                            <i class="fas fa-ticket-alt me-3"></i>
                            Warpass
                        </div>
                    </div>

                    <!-- Navegação -->
                    <div class="card-body pt-4 ps-7">
                        <div class="warpass-nav">
                            <div class="nav-item active" id="premios-tab">
                                <div class="nav-content">
                                    <i class="fas fa-gift nav-icon"></i>
                                    <div class="nav-text">
                                        <div class="nav-title">Prêmios</div>
                                        <div class="nav-desc">Gerenciar níveis e recompensas</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="nav-item disabled" id="missoes-tab">
                                <div class="nav-content">
                                    <i class="fas fa-tasks nav-icon"></i>
                                    <div class="nav-text">
                                        <div class="nav-title">Missões</div>
                                        <div class="nav-desc">Configurar missões do passe</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="nav-item disabled" id="shop-tab">
                                <div class="nav-content">
                                    <i class="fas fa-store nav-icon"></i>
                                    <div class="nav-text">
                                        <div class="nav-title">Shop</div>
                                        <div class="nav-desc">Loja de itens premium</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="nav-item disabled" id="config-tab">
                                <div class="nav-content">
                                    <i class="fas fa-cog nav-icon"></i>
                                    <div class="nav-text">
                                        <div class="nav-title">Configuração</div>
                                        <div class="nav-desc">Configurações gerais</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel principal -->
            <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                <div class="card" id="kt_chat_messenger">
                    <!-- Estado inicial - não selecionado -->
                    <div id="not_selected">
                        @include('components.default.notfound', [
                            'title' => 'Selecione uma seção',
                            'message' => 'clique em uma das opções da sidebar para continuar',
                        ])
                    </div>

                    <!-- Painel de Prêmios -->
                    <div id="premios_panel" style="display: none;">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="d-flex justify-content-center flex-column me-3">
                                    <h2 class="panel-title">
                                        <i class="fas fa-gift me-3"></i>
                                        Gerenciar Prêmios
                                    </h2>
                                    <div class="panel-subtitle text-muted">
                                        Configure os níveis e recompensas do passe de batalha
                                    </div>
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light-success btn-sm" id="add-level-btn">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor"></rect>
                                                <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor"></rect>
                                            </svg>
                                        </span>
                                        Adicionar Nível
                                    </button>
                                    <button type="button" class="btn btn-light-primary btn-sm" onclick="WarPass.loadLevels()">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z" fill="currentColor" />
                                                <path d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        Recarregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Loading State -->
                            <div id="loading-state" class="loading-container">
                                <div class="loading-spinner">
                                    <div class="spinner"></div>
                                    <p>Carregando níveis do passe...</p>
                                </div>
                            </div>

                            <!-- Error State -->
                            <div id="error-state" class="error-container d-none">
                                <div class="error-content">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <h4>Erro ao carregar dados</h4>
                                    <p id="error-message">Erro desconhecido</p>
                                    <button class="btn btn-primary" onclick="WarPass.loadLevels()">
                                        <i class="fas fa-redo me-2"></i>Tentar novamente
                                    </button>
                                </div>
                            </div>

                            <!-- Levels Container -->
                            <div id="levels-container" class="d-none">
                                <!-- Navigation -->
                                <div class="levels-navigation">
                                    <div class="nav-info">
                                        <span class="page-badge" id="page-info">Níveis 1-5</span>
                                        <span class="total-badge" id="total-info">Total: 0 níveis</span>
                                    </div>
                                    
                                    <div class="nav-controls">
                                        <button id="prev-levels" class="nav-arrow nav-arrow-up">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <button id="next-levels" class="nav-arrow nav-arrow-down">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Levels Grid - Vertical Layout -->
                                <div class="levels-grid-vertical" id="levels-row">
                                    <!-- Levels will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
/* Container de imagem otimizada */
.warpass-image-container {
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease;
}

.warpass-item-image-optimized {
    transition: opacity 0.2s ease;
    border-radius: 4px;
}

.image-loading-state {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

/* Remove tooltips e titles */
* {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

*[title] {
    --title: attr(title);
    title: "" !important;
}

*[title]:hover::before,
*[title]:hover::after {
    display: none !important;
    content: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

.item-image,
.warpass-item-image-optimized,
.slot-icon img,
.slot-content img,
.warpass-image-container img {
    title: "" !important;
    alt: "" !important;
}

.item-name,
.slot-content,
.level-card,
.reward-slot,
.warpass-image-container {
    title: "" !important;
}

.no-tooltip * {
    title: "" !important;
}

/* Container principal compacto */
.levels-container-wrapper {
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
    padding: 0 20px;
}

.levels-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    max-width: 800px;
    margin: 0 auto 20px auto;
}

.nav-info {
    display: flex;
    gap: 12px;
    align-items: center;
}

.nav-controls {
    display: flex;
    gap: 6px;
}

.nav-arrow {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-arrow:hover:not(:disabled) {
    background: #667eea;
    color: white;
    transform: scale(1.05);
}

.nav-arrow:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.page-badge, .total-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 16px;
    font-weight: 600;
    font-size: 12px;
}

.page-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.total-badge {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.levels-grid-vertical {
    display: flex;
    flex-direction: row;
    gap: 16px;
    align-items: stretch;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    overflow-x: auto;
    padding: 10px 0;
}

.level-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    min-width: 140px;
    max-width: 140px;
    flex-shrink: 0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    animation: fadeIn 0.3s ease-out;
}

.level-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: #667eea;
}

.level-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px;
    text-align: center;
    position: relative;
}

.level-number {
    font-size: 16px;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    margin: 0;
}

.level-actions {
    position: absolute;
    top: 4px;
    right: 4px;
}

.btn-level-delete {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    background: rgba(239, 68, 68, 0.8);
    color: white;
    font-size: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-level-delete:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.level-content {
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.reward-section {
    width: 100%;
}

.reward-label {
    display: block;
    font-weight: 600;
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
    width: 100%;
}

.normal-section .reward-label {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.vip-section .reward-label {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.reward-slot {
    margin-bottom: 8px;
}

.vip-rewards {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.slot-content {
    position: relative;
    background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    min-height: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6px;
    text-align: center;
}

.slot-content:hover {
    border-color: #667eea;
    box-shadow: 0 0 12px rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
}

.empty-slot {
    opacity: 0.6;
    border-style: dashed;
    min-height: 40px;
}

.empty-slot .slot-icon {
    color: #6b7280;
    font-size: 16px;
    background: transparent;
}

.slot-icon {
    width: 32px;
    height: 32px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    margin-bottom: 4px;
    position: relative;
}

.item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.item-placeholder {
    color: #6b7280;
    font-size: 14px;
}

.slot-details {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    width: 100%;
}

.item-name {
    font-weight: 600;
    font-size: 10px;
    color: #1f2937;
    text-align: center;
    line-height: 1.2;
    max-height: 24px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.item-stats {
    display: flex;
    gap: 4px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.item-count {
    font-weight: 600;
    font-size: 9px;
    color: #059669;
    background: #dcfce7;
    padding: 2px 6px;
    border-radius: 4px;
    border: 1px solid #bbf7d0;
}

.item-validity {
    font-size: 9px;
    color: #d97706;
    background: #fef3c7;
    padding: 2px 6px;
    border-radius: 4px;
    border: 1px solid #fde68a;
}

.slot-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
    color: white;
    font-size: 14px;
}

.slot-content:hover .slot-overlay {
    opacity: 1;
}

.slot-actions {
    position: absolute;
    top: 4px;
    right: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.slot-content:hover .slot-actions {
    opacity: 1;
}

.btn-slot-delete {
    width: 18px;
    height: 18px;
    border: 1px solid white;
    border-radius: 4px;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    font-size: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-slot-delete:hover {
    background: #ef4444;
    transform: scale(1.1);
}

/* ============================================================ */
/* 📱 RESPONSIVIDADE PARA LAYOUT HORIZONTAL */
/* ============================================================ */
@media (max-width: 768px) {
    .levels-container-wrapper {
        max-width: 100%;
        padding: 0 10px;
    }
    
    .levels-grid-vertical {
        gap: 12px;
        padding: 8px 0;
    }
    
    .level-card {
        min-width: 120px;
        max-width: 120px;
    }
    
    .level-content {
        padding: 10px;
        gap: 10px;
    }
    
    .slot-content {
        min-height: 45px;
        padding: 4px;
    }
    
    .slot-icon,
    .warpass-image-container {
        width: 28px;
        height: 28px;
    }
    
    .levels-navigation {
        padding: 12px 16px;
        margin-bottom: 16px;
    }
    
    .nav-arrow {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .levels-grid-vertical {
        gap: 8px;
    }
    
    .level-card {
        min-width: 100px;
        max-width: 100px;
    }
    
    .level-content {
        padding: 8px;
        gap: 8px;
    }
    
    .slot-content {
        min-height: 40px;
        padding: 3px;
    }
    
    .slot-icon,
    .warpass-image-container {
        width: 24px;
        height: 24px;
    }
    
    .item-name {
        font-size: 9px;
    }
    
    .item-count, .item-validity {
        font-size: 8px;
        padding: 1px 4px;
    }
    
    .reward-label {
        font-size: 9px;
        padding: 3px 6px;
        margin-bottom: 6px;
    }
}

/* ============================================================ */
/*  SIDEBAR E OUTROS ELEMENTOS  */
/* ============================================================ */
.warpass-sidebar-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 24px;
    text-align: center;
    border-radius: 12px 12px 0 0;
}

.sidebar-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.warpass-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 0;
}

.nav-item {
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid transparent;
    margin-bottom: 8px;
    background: #f8fafc;
}

.nav-item:hover:not(.disabled) {
    border-color: #667eea;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.nav-item.active {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-color: #667eea;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
}

.nav-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.nav-content {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.nav-icon {
    font-size: 24px;
    color: #6b7280;
    width: 32px;
    text-align: center;
    transition: all 0.2s ease;
}

.nav-item.active .nav-icon {
    color: #667eea;
    transform: scale(1.1);
}

.nav-text {
    flex: 1;
}

.nav-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.nav-item.active .nav-title {
    color: #764ba2;
}

.nav-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
}

.panel-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.panel-subtitle {
    font-size: 14px;
    margin-top: 8px;
    color: #6b7280;
}

/* ============================================================ */
/* ESTADOS DE LOADING E ERROR */
/* ============================================================ */
.loading-container, .error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
    flex-direction: column;
    text-align: center;
}

.loading-spinner {
    color: #667eea;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e5e7eb;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 12px;
}

.error-content {
    color: #ef4444;
}

.error-content i {
    font-size: 40px;
    margin-bottom: 12px;
}

/* ============================================================ */
/* ANIMAÇÕES */
/* ============================================================ */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(15px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

/* ============================================================ */
/*  BOTÕES E OUTROS ELEMENTOS OTIMIZADOS */
/* ============================================================ */
.btn {
    transition: all 0.2s ease;
    border-radius: 8px;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-light-success {
    background: #dcfce7;
    border-color: #bbf7d0;
    color: #059669;
}

.btn-light-success:hover {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-color: #059669;
    color: white;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
}

.btn-light-primary {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #764ba2;
}

.btn-light-primary:hover {
    background: #667eea;
    border-color: #764ba2;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-light-danger {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
}

.btn-light-danger:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #dc2626;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

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

/* ============================================================ */
/*  SCROLLBAR HORIZONTAL OTIMIZADA PARA MOBILE */
/* ============================================================ */
.levels-grid-vertical::-webkit-scrollbar {
    height: 6px;
}

.levels-grid-vertical::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.levels-grid-vertical::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.levels-grid-vertical::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

/* ============================================================ */
/* SELECT2 */
/* ============================================================ */
.select2-container--bootstrap-5 .select2-selection--single {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    height: calc(1.5em + 0.75rem + 2px);
    transition: all 0.2s ease;
}

.select2-container--bootstrap-5 .select2-selection--single:focus,
.select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.select2-dropdown {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.select2-results__option {
    padding: 8px 12px;
    border-radius: 4px;
    margin: 2px 4px;
    transition: all 0.2s ease;
}

.select2-results__option--highlighted {
    background-color: #667eea !important;
    color: white !important;
}

/* Style para itens do Select2 com imagens */
.select2-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ============================================================ */
/*  MODAL */
/* ============================================================ */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-header {
    border-radius: 12px 12px 0 0;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

/* ============================================================ */
/*  FORM CONTROLS */
/* ============================================================ */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: all 0.2s ease;
}

/* ============================================================ */
/* BADGES */
/* ============================================================ */
.badge {
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
}

.badge-light-primary {
    background: #eff6ff;
    color: #667eea;
    border: 1px solid #bfdbfe;
}

.badge-light-success {
    background: #dcfce7;
    color: #059669;
    border: 1px solid #bbf7d0;
}

.badge-light-danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* ============================================================ */
/* ITEM PREVIEW*/
/* ============================================================ */
.item-preview {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
}

.preview-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.preview-info {
    flex: 1;
}

#preview-icon {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    object-fit: cover;
}

#preview-name {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

#preview-details {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

/* ============================================================ */
/*  OTIMIZAÇÕES DE PERFORMANCE */
/* ============================================================ */

/* GPU acceleration para elementos animados */
.level-card,
.slot-content,
.btn,
.nav-item,
.warpass-image-container {
    will-change: transform;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

/* Otimizar rendering de imagens */
.warpass-item-image-optimized,
.item-image {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: optimize-contrast;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Smooth scrolling otimizado */
.levels-grid-vertical {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Preload de fontes críticas */
@media (prefers-reduced-motion: no-preference) {
    * {
        scroll-behavior: smooth;
    }
}

/* ============================================================ */
/*  DARK MODE SUPPORT (FUTURO) */
/* ============================================================ */
@media (prefers-color-scheme: dark) {
    .level-card {
        background: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }
    
    .slot-content {
        background: linear-gradient(145deg, #374151 0%, #1f2937 100%);
        border-color: #4b5563;
    }
    
    .item-name {
        color: #f9fafb;
    }
}
</style>
@endsection

@section('modals')
<!-- Modal de Edição de Recompensa Individual -->
<div class="modal fade" id="editRewardModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    <span id="modal-reward-title">Editar Recompensa</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRewardForm">
                    <input type="hidden" id="edit-level" name="level">
                    <input type="hidden" id="edit-reward-type" name="reward_type">
                    <input type="hidden" id="edit-reward-slot" name="reward_slot">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Buscar Item</label>
                            <select class="form-control" id="item-search" style="width: 100%;"></select>
                            <div class="form-text">Digite o nome ou ID do item para buscar</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="reward_count" name="count" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Dias de Validade</label>
                            <input type="number" class="form-control" id="reward_days" name="days" min="0" placeholder="0 = Permanente">
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="item-preview" id="item-preview" style="display: none;">
                            <h6>Preview do Item:</h6>
                            <div class="preview-content">
                                <img id="preview-icon" src="" alt="" style="width: 64px; height: 64px;">
                                <div class="preview-info">
                                    <strong id="preview-name">Nome do Item</strong>
                                    <p id="preview-details">Detalhes do item</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="clearRewardBtn">
                    <i class="fas fa-trash me-2"></i>Limpar Slot
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="saveRewardBtn">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Adicionar Nível -->
<div class="modal fade" id="addLevelModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Adicionar Novo Nível
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addLevelForm">
                    <!-- Level Number -->
                    <div class="mb-4">
                        <label class="form-label">Número do Nível</label>
                        <input type="number" class="form-control" id="new_level" name="Level" min="1" placeholder="Digite o número do nível" required>
                        <div class="form-text">O nível deve ser único.</div>
                    </div>
                    
                    <div class="row">
                        <!-- Normal Award -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-gift me-2"></i>Prêmio Normal</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Buscar Item</label>
                                        <select class="form-control item-search-new" id="normal-item-search" style="width: 100%;"></select>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" name="normal_count" min="0" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Dias</label>
                                            <input type="number" class="form-control" name="normal_days" min="0" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extra Award 1 -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-star me-2"></i>Prêmio Extra 1</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Buscar Item</label>
                                        <select class="form-control item-search-new" id="extra1-item-search" style="width: 100%;"></select>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" name="extra_slot1_count" min="0" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Dias</label>
                                            <input type="number" class="form-control" name="extra_slot1_days" min="0" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extra Award 2 -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-star me-2"></i>Prêmio Extra 2</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Buscar Item</label>
                                        <select class="form-control item-search-new" id="extra2-item-search" style="width: 100%;"></select>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" name="extra_slot2_count" min="0" placeholder="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Dias</label>
                                            <input type="number" class="form-control" name="extra_slot2_days" min="0" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="addLevelBtn">
                    <i class="fas fa-plus me-2"></i>Criar Nível
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-css')

@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ url('assets/js/admin/warpass/warpass.js') }}"></script>
</script>
@endsection