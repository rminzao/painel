function injectCustomCSS() {
    // Verificar se o CSS já foi injetado
    if (document.getElementById('npc-custom-styles')) {
        return;
    }

    const css = `
        /* ============================================================================ */
        /* 🎨 SISTEMA DE NPCs - CSS MODERNO E REFINADO */
        /* ============================================================================ */

        /* ========================================= */
        /* 🌍 CONFIGURAÇÕES GLOBAIS */
        /* ========================================= */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%) !important;
            --info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important;
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            --light-gradient: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            --glass-bg: rgba(255, 255, 255, 0.25) !important;
            --glass-border: rgba(255, 255, 255, 0.18) !important;
            --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.37) !important;
            --shadow-hover: 0 12px 40px rgba(31, 38, 135, 0.5) !important;
            --border-radius: 16px !important;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* ========================================= */
        /* 📦 CARDS E CONTAINERS PRINCIPAIS */
        /* ========================================= */
        .card {
            border-radius: var(--border-radius) !important;
            border: 1px solid rgba(255, 255, 255, 0.125) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: var(--shadow-soft) !important;
            transition: var(--transition) !important;
            overflow: hidden !important;
        }

        .card:hover {
            box-shadow: var(--shadow-hover) !important;
            transform: translateY(-2px) !important;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 249, 250, 0.8) 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(10px) !important;
        }

        /* ========================================= */
        /* 🤖 LISTA DE NPCs - DESIGN REFINADO */
        /* ========================================= */
        .npc-item {
            transition: var(--transition) !important;
            border-radius: 12px !important;
            margin-bottom: 1rem !important;
            cursor: pointer !important;
            position: relative !important;
            overflow: hidden !important;
            border: 2px solid transparent !important;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(8px) !important;
        }

        .npc-item::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent) !important;
            transition: left 0.5s ease !important;
        }

        .npc-item:hover::before {
            left: 100% !important;
        }

        .npc-item:hover {
            transform: translateY(-6px) scale(1.02) !important;
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.3) !important;
            border-color: #667eea !important;
            background: rgba(255, 255, 255, 0.95) !important;
        }

        .npc-item.selected {
            border-color: #667eea !important;
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4) !important;
            transform: translateY(-4px) !important;
        }

        .npc-item.selected * {
            color: white !important;
        }

        /* Ícones dos NPCs com efeito 3D */
        .npc-item .symbol-label {
            background: var(--light-gradient) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            transition: var(--transition) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            position: relative !important;
        }

        .npc-item .symbol-label::after {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, transparent 50%) !important;
            border-radius: inherit !important;
            pointer-events: none !important;
        }

        .npc-item:hover .symbol-label {
            background: var(--primary-gradient) !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
            color: white !important;
            transform: scale(1.15) rotateY(15deg) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }

        .npc-item.selected .symbol-label {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: white !important;
            transform: scale(1.1) !important;
        }

        /* ========================================= */
        /* 📑 SISTEMA DE ABAS MELHORADO */
        /* ========================================= */
        .nav-tabs {
            border: none !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            padding: 4px !important;
            backdrop-filter: blur(10px) !important;
        }

        .nav-link {
            cursor: pointer !important;
            transition: var(--transition) !important;
            border: none !important;
            border-radius: 8px !important;
            margin: 0 2px !important;
            padding: 12px 20px !important;
            font-weight: 600 !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .nav-link::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent) !important;
            transition: left 0.4s ease !important;
        }

        .nav-link:hover::before {
            left: 100% !important;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #667eea !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2) !important;
        }

        .nav-link.active {
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
            transform: translateY(-1px) !important;
        }

        /* ========================================= */
        /* 🧮 CALCULADORA - DESIGN FUTURISTA */
        /* ========================================= */
        #calculation_results {
            animation: slideInUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%) !important;
            border-radius: var(--border-radius) !important;
            padding: 24px !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Cards de atributos com efeito glassmorphism */
        .bg-light-success, .bg-light-primary, .bg-light-warning, 
        .bg-light-info, .bg-light-dark, .bg-light-secondary, 
        .bg-light-danger {
            transition: var(--transition) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(8px) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .bg-light-success {
            background: linear-gradient(135deg, rgba(75, 192, 192, 0.15) 0%, rgba(75, 192, 192, 0.05) 100%) !important;
        }

        .bg-light-primary {
            background: linear-gradient(135deg, rgba(54, 162, 235, 0.15) 0%, rgba(54, 162, 235, 0.05) 100%) !important;
        }

        .bg-light-warning {
            background: linear-gradient(135deg, rgba(255, 206, 84, 0.15) 0%, rgba(255, 206, 84, 0.05) 100%) !important;
        }

        .bg-light-info {
            background: linear-gradient(135deg, rgba(153, 102, 255, 0.15) 0%, rgba(153, 102, 255, 0.05) 100%) !important;
        }

        .bg-light-dark {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.15) 0%, rgba(108, 117, 125, 0.05) 100%) !important;
        }

        .bg-light-secondary {
            background: linear-gradient(135deg, rgba(128, 134, 139, 0.15) 0%, rgba(128, 134, 139, 0.05) 100%) !important;
        }

        .bg-light-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(220, 53, 69, 0.05) 100%) !important;
        }

        /* Efeitos hover para cards de atributos */
        .bg-light-success:hover { 
            border-color: #4bc0c0 !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(75, 192, 192, 0.3) !important;
        }

        .bg-light-primary:hover { 
            border-color: #36a2eb !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(54, 162, 235, 0.3) !important;
        }

        .bg-light-warning:hover { 
            border-color: #ffce54 !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(255, 206, 84, 0.3) !important;
        }

        .bg-light-info:hover { 
            border-color: #9966ff !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(153, 102, 255, 0.3) !important;
        }

        .bg-light-dark:hover { 
            border-color: #6c757d !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3) !important;
        }

        .bg-light-secondary:hover { 
            border-color: #80868b !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(128, 134, 139, 0.3) !important;
        }

        .bg-light-danger:hover { 
            border-color: #dc3545 !important; 
            transform: translateY(-4px) scale(1.02) !important; 
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3) !important;
        }

        /* ========================================= */
        /* 🎛️ BOTÕES MODERNOS */
        /* ========================================= */
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            transition: var(--transition) !important;
            position: relative !important;
            overflow: hidden !important;
            border: none !important;
        }

        .btn::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent) !important;
            transition: left 0.5s ease !important;
        }

        .btn:hover::before {
            left: 100% !important;
        }

        .btn-primary {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
        }

        .btn-primary:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6) !important;
        }

        .btn-success {
            background: var(--success-gradient) !important;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4) !important;
        }

        .btn-success:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.6) !important;
        }

        .btn-light-primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%) !important;
            color: #667eea !important;
            border: 1px solid rgba(102, 126, 234, 0.3) !important;
        }

        .btn-light-primary:hover {
            background: var(--primary-gradient) !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
        }

        .btn-light-success {
            background: linear-gradient(135deg, rgba(75, 192, 192, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%) !important;
            color: #4bc0c0 !important;
            border: 1px solid rgba(75, 192, 192, 0.3) !important;
        }

        .btn-light-success:hover {
            background: var(--success-gradient) !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(75, 192, 192, 0.4) !important;
        }

        .btn-light-danger {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(255, 167, 38, 0.1) 100%) !important;
            color: #ff6b6b !important;
            border: 1px solid rgba(255, 107, 107, 0.3) !important;
        }

        .btn-light-danger:hover {
            background: var(--danger-gradient) !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4) !important;
        }

        /* Botões específicos da calculadora */
        #btn_calculate_attributes {
            background: var(--primary-gradient) !important;
            border: none !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
        }

        #btn_calculate_attributes:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.6) !important;
        }

        #btn_apply_calculated {
            background: var(--success-gradient) !important;
            border: none !important;
            font-weight: 700 !important;
            box-shadow: 0 6px 20px rgba(75, 192, 192, 0.4) !important;
        }

        #btn_apply_calculated:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 10px 30px rgba(75, 192, 192, 0.6) !important;
        }

        #btn_new_npc_calculated {
            background: var(--warning-gradient) !important;
            border: none !important;
            font-weight: 700 !important;
            box-shadow: 0 6px 20px rgba(247, 112, 154, 0.4) !important;
        }

        #btn_new_npc_calculated:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 10px 30px rgba(247, 112, 154, 0.6) !important;
        }

        /* ========================================= */
        /* 📝 FORMULÁRIOS E INPUTS */
        /* ========================================= */
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(8px) !important;
            transition: var(--transition) !important;
            font-weight: 500 !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            transform: translateY(-1px) !important;
        }

        .form-label {
            font-weight: 700 !important;
            color: #495057 !important;
            margin-bottom: 8px !important;
            font-size: 0.875rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Select de dificuldade especial */
        #difficulty_select {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 249, 250, 0.8) 100%) !important;
            border: 2px solid rgba(102, 126, 234, 0.2) !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
        }

        #difficulty_select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
            transform: translateY(-2px) !important;
        }

        /* ========================================= */
        /* 🔔 ALERTAS E NOTIFICAÇÕES */
        /* ========================================= */
        .alert {
            border-radius: var(--border-radius) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(10px) !important;
            animation: slideInDown 0.5s ease-out !important;
        }

        @keyframes slideInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(168, 237, 234, 0.2) 0%, rgba(254, 214, 227, 0.2) 100%) !important;
            border-color: rgba(168, 237, 234, 0.3) !important;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 206, 84, 0.2) 0%, rgba(255, 167, 38, 0.2) 100%) !important;
            border-color: rgba(255, 206, 84, 0.3) !important;
        }

        .alert-light-primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%) !important;
            border-color: rgba(102, 126, 234, 0.2) !important;
        }

        /* ========================================= */
        /* 📱 RESPONSIVIDADE */
        /* ========================================= */
        @media (max-width: 768px) {
            .npc-item {
                margin-bottom: 0.75rem !important;
            }

            .npc-item:hover {
                transform: translateY(-3px) scale(1.01) !important;
            }

            .nav-link {
                padding: 8px 12px !important;
                font-size: 0.875rem !important;
            }

            .btn {
                padding: 8px 16px !important;
                font-size: 0.875rem !important;
            }

            #calculation_results {
                padding: 16px !important;
            }

            .card {
                border-radius: 12px !important;
            }
        }

        @media (max-width: 576px) {
            .npc-item .symbol-label {
                width: 40px !important;
                height: 40px !important;
                font-size: 1.25rem !important;
            }

            .nav-tabs {
                flex-direction: column !important;
            }

            .nav-link {
                margin: 2px 0 !important;
                text-align: center !important;
            }
        }

        /* ========================================= */
        /* ✨ MICRO-ANIMAÇÕES E EFEITOS ESPECIAIS */
        /* ========================================= */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0, 0, 0);
            }
            40%, 43% {
                transform: translate3d(0, -30px, 0);
            }
            70% {
                transform: translate3d(0, -15px, 0);
            }
            90% {
                transform: translate3d(0, -4px, 0);
            }
        }

        /* Efeito de loading personalizado */
        .spinner-border {
            animation: spin 1s linear infinite !important;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Efeitos de hover para ícones */
        .btn i, .symbol-label {
            transition: var(--transition) !important;
        }

        .btn:hover i {
            transform: scale(1.2) rotate(5deg) !important;
        }

        /* Scrollbar customizada */
        ::-webkit-scrollbar {
            width: 6px !important;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 3px !important;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient) !important;
            border-radius: 3px !important;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
        }

        /* ========================================= */
        /* 🎯 UTILITÁRIOS E ACABAMENTOS */
        /* ========================================= */
        .tab-content {
            min-height: 400px !important;
            padding: 20px !important;
        }

        .tab-pane {
            display: none !important;
            animation: fadeIn 0.4s ease-in-out !important;
        }

        .tab-pane.active {
            display: block !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Separador estilizado */
        .separator {
            border-color: rgba(102, 126, 234, 0.2) !important;
            margin: 2rem 0 !important;
            position: relative !important;
        }

        .separator::after {
            content: '✨' !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            background: white !important;
            padding: 0 10px !important;
            font-size: 1.2rem !important;
        }

        /* Badge personalizado */
        .badge {
            border-radius: 6px !important;
            font-weight: 600 !important;
            padding: 6px 10px !important;
            background: var(--primary-gradient) !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3) !important;
        }

        /* Tooltips melhorados */
        [data-bs-toggle="tooltip"] {
            position: relative !important;
        }

        /* Estados de foco melhorados */
        .form-control:focus,
        .form-select:focus,
        .btn:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25) !important;
        }

        /* Transições suaves para mudanças de estado */
        * {
            transition: var(--transition) !important;
        }
    `;

    // Criar elemento style
    const styleElement = document.createElement('style');
    styleElement.id = 'npc-custom-styles';
    styleElement.textContent = css;

    // Inserir no head
    document.head.appendChild(styleElement);

    console.log('✅ CSS customizado do NPC injetado com sucesso!');
}

// CLASSE PRINCIPAL DO NPC MANAGER

class NpcManager {
    constructor() {
        this.npcs = window.npcData || [];
        this.filteredNpcs = [...this.npcs];
        this.currentNpc = null;
        this.currentPage = 1;
        this.itemsPerPage = 5;
        this.currentSearch = '';
        this.currentLevelFilter = 'all';
        this.lastCalculatedAttributes = null;
        
        this.init();
    }

    init() {
        // Injetar CSS primeiro
        injectCustomCSS();
        
        this.setupEventListeners();
        this.setupTabSystem();
        this.loadNpcList();
        this.setupSelect2();
        this.setupModals();
        
        // Tentar restaurar NPC selecionado após reload
        this.restoreSelectedNpc();
    }

    // ATUALIZAÇÃO SILENCIOSA DE XML
    
    async updateXmlSilently() {
        const refreshBtn = document.getElementById('button_refresh_list');
        
        try {
            // Mostrar loading no botão
            this.setButtonLoading(refreshBtn, true);
            
            // Verificar se estamos em domínio diferente para usar método apropriado
            const currentDomain = window.location.hostname;
            const targetDomain = 'evotank.com.br';
            
            if (currentDomain !== targetDomain) {
                // Domínio diferente = usar iframe diretamente para evitar erro CORS no console
                console.log('Detectado domínio diferente, usando método iframe para evitar CORS');
                this.updateXmlAlternative();
                return;
            }
            
            // Mesmo domínio = tentar fetch direto
            const response = await fetch('https://evotank.com.br/quest/createallxml.ashx', {
                method: 'GET',
                headers: {
                    'Accept': 'text/xml, application/xml, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                // XML atualizada com sucesso
                this.showAlert('success', 'XML Atualizada!', 'Base de dados atualizada com sucesso!');
                
                // Aguardar um pouco e recarregar a página para pegar os novos dados
                setTimeout(() => {
                    this.smartRefresh();
                }, 1000);
                
            } else {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            
        } catch (error) {
            // Se der erro, usar método alternativo sem mostrar erro de CORS
            console.log('Fetch falhou, usando método alternativo:', error.message);
            this.updateXmlAlternative();
        } finally {
            this.setButtonLoading(refreshBtn, false);
        }
    }
    
    // Método alternativo usando iframe oculto
    updateXmlAlternative() {
        try {
            console.log('🔄 Iniciando atualização XML via iframe...');
            
            // Criar iframe oculto para fazer a requisição
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.position = 'absolute';
            iframe.style.left = '-9999px';
            iframe.setAttribute('sandbox', 'allow-same-origin allow-scripts');
            
            // Adicionar ao body
            document.body.appendChild(iframe);
            
            // Configurar eventos
            let completed = false;
            
            const cleanup = () => {
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
            };
            
            const complete = (success = true) => {
                if (completed) return;
                completed = true;
                
                setTimeout(() => {
                    cleanup();
                    
                    if (success) {
                        console.log('✅ XML atualizada com sucesso via iframe');
                        this.showAlert('success', 'XML Atualizada!', 'Base de dados atualizada com sucesso!');
                        
                        // Recarregar dados
                        setTimeout(() => {
                            this.smartRefresh();
                        }, 1000);
                    } else {
                        console.log('⚠️ Possível falha na atualização XML');
                        this.showAlert('warning', 'Atenção!', 'XML pode ter sido atualizada. Recarregando página...');
                        
                        setTimeout(() => {
                            this.smartRefresh();
                        }, 1000);
                    }
                }, 1500);
            };
            
            iframe.onload = () => complete(true);
            iframe.onerror = () => complete(false);
            
            // Timeout de segurança
            setTimeout(() => {
                if (!completed) {
                    console.log('⏱️ Timeout na atualização XML, assumindo sucesso');
                    complete(true);
                }
            }, 5000);
            
            // Fazer a requisição
            iframe.src = 'https://evotank.com.br/quest/createallxml.ashx?' + Date.now();
            
        } catch (error) {
            console.error('Erro no método iframe:', error);
            this.showAlert('error', 'Erro!', 'Erro ao tentar atualização alternativa: ' + error.message);
        }
    }

    // SISTEMA DE SELEÇÃO PERSISTENTE
    
    saveSelectedNpc(npcId) {
        if (npcId) {
            sessionStorage.setItem('selected_npc_id', npcId);
        } else {
            sessionStorage.removeItem('selected_npc_id');
        }
    }
    
    restoreSelectedNpc() {
        const savedNpcId = sessionStorage.getItem('selected_npc_id');
        if (savedNpcId) {
            // Aguardar um pouco para garantir que a lista foi renderizada
            setTimeout(() => {
                const npc = this.npcs.find(n => n.ID == savedNpcId);
                if (npc) {
                    this.selectNpc(savedNpcId, false);
                    console.log(`NPC #${savedNpcId} restaurado após reload`);
                }
            }, 200);
        }
    }
    
    clearSelectedNpc() {
        sessionStorage.removeItem('selected_npc_id');
    }

    // SISTEMA DE TABS
    
    setupTabSystem() {
        const tabLinks = document.querySelectorAll('#npc-tabs .nav-link');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const tabId = link.getAttribute('data-tab');
                if (tabId) {
                    this.showTab(tabId, link);
                }
            });
        });
    }

    showTab(tabId, linkElement) {
        // Esconder todas as abas
        const allTabs = document.querySelectorAll('.tab-pane');
        allTabs.forEach(tab => {
            tab.classList.remove('active');
            tab.style.display = 'none';
        });
        
        // Remover active de todos os links
        const allLinks = document.querySelectorAll('#npc-tabs .nav-link');
        allLinks.forEach(link => {
            link.classList.remove('active');
        });
        
        // Mostrar aba selecionada
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.add('active');
            targetTab.style.display = 'block';
        }
        
        // Ativar link clicado
        if (linkElement) {
            linkElement.classList.add('active');
        }
    }

    // EVENT LISTENERS
    
    setupEventListeners() {
        // Busca por nome/ID
        const searchInput = document.getElementById('npc_search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.handleSearch(e.target.value);
                }, 300);
            });
        }

        // Filtro por level
        const levelFilter = document.querySelector('[name="level_filter"]');
        if (levelFilter) {
            levelFilter.addEventListener('change', (e) => {
                this.handleLevelFilter(e.target.value);
            });
        }

        // Itens por página
        const limitSelect = document.querySelector('[name="limit"]');
        if (limitSelect) {
            limitSelect.addEventListener('change', (e) => {
                this.itemsPerPage = parseInt(e.target.value);
                this.currentPage = 1;
                this.renderNpcList();
            });
        }

        // Botão refresh COM ATUALIZAÇÃO XML SILENCIOSA
        const refreshBtn = document.getElementById('button_refresh_list');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Evitar comportamento padrão
                
                // Verificar se deve atualizar XML ou apenas recarregar
                if (e.ctrlKey || e.shiftKey) {
                    // Ctrl+Click ou Shift+Click = apenas recarregar sem XML
                    console.log('🔄 Recarga rápida (sem XML)');
                    this.smartRefresh();
                } else {
                    // Click normal = atualizar XML silenciosamente
                    console.log('🔄 Iniciando atualização XML + recarga...');
                    this.updateXmlSilently();
                }
            });
            
            // Adicionar tooltip informativo
            refreshBtn.setAttribute('title', 'Clique: Atualizar XML + Lista | Ctrl+Clique: Apenas Lista');
        }

        // Formulário de criação
        const createForm = document.getElementById('createForm');
        if (createForm) {
            createForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createNpc();
            });
        }

        // Botão de delete
        const deleteBtn = document.getElementById('btn_delete_npc');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => {
                this.confirmDelete();
            });
        }

        // Botão de duplicar
        const duplicateBtn = document.getElementById('btn_duplicate_npc');
        if (duplicateBtn) {
            duplicateBtn.addEventListener('click', () => {
                this.duplicateNpc();
            });
        }

        // Botão calcular atributos
        const calculateBtn = document.getElementById('btn_calculate_attributes');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', () => {
                this.calculateAttributes();
            });
        }

        // Botão aplicar atributos calculados
        const applyCalculatedBtn = document.getElementById('btn_apply_calculated');
        if (applyCalculatedBtn) {
            applyCalculatedBtn.addEventListener('click', () => {
                this.applyCalculatedAttributes();
            });
        }

        // Botão criar novo NPC com valores calculados
        const newNpcCalculatedBtn = document.getElementById('btn_new_npc_calculated');
        if (newNpcCalculatedBtn) {
            newNpcCalculatedBtn.addEventListener('click', () => {
                this.createNpcWithCalculatedAttributes();
            });
        }
    }

    setupSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('[data-control="select2"]').select2({
                minimumResultsForSearch: Infinity
            });
        }
    }

    setupModals() {
        const createModal = document.getElementById('createNpcModal');
        if (createModal && typeof bootstrap !== 'undefined') {
            createModal.addEventListener('hidden.bs.modal', () => {
                this.resetCreateForm();
            });
        }
    }

    // BUSCA E FILTROS

    handleSearch(searchTerm) {
        this.currentSearch = searchTerm.toLowerCase().trim();
        this.currentPage = 1;
        this.applyFilters();
    }

    handleLevelFilter(filterValue) {
        this.currentLevelFilter = filterValue;
        this.currentPage = 1;
        this.applyFilters();
    }

    applyFilters() {
        let filtered = [...this.npcs];

        // Filtro de busca
        if (this.currentSearch) {
            filtered = filtered.filter(npc => {
                return npc.Name.toLowerCase().includes(this.currentSearch) ||
                       npc.ID.toString().includes(this.currentSearch);
            });
        }

        // Filtro de level
        if (this.currentLevelFilter !== 'all') {
            filtered = filtered.filter(npc => {
                const level = parseInt(npc.Level);
                switch (this.currentLevelFilter) {
                    case '1-5': return level >= 1 && level <= 5;
                    case '6-10': return level >= 6 && level <= 10;
                    case '11-15': return level >= 11 && level <= 15;
                    case '16-20': return level >= 16 && level <= 20;
                    case '21-25': return level >= 21 && level <= 25;
                    case '26-30': return level >= 26 && level <= 30;
                    case '31-40': return level >= 31 && level <= 40;
                    case '41-50': return level >= 41 && level <= 50;
                    case '51-75': return level >= 51 && level <= 75;
                    default: return true;
                }
            });
        }

        this.filteredNpcs = filtered;
        this.renderNpcList();
    }

    // RENDERIZAÇÃO 

    loadNpcList() {
        this.applyFilters();
    }

    renderNpcList() {
        const listContainer = document.getElementById('npc_list');
        const notResults = document.getElementById('not_results');
        
        if (!listContainer || !notResults) return;

        if (this.filteredNpcs.length === 0) {
            listContainer.style.display = 'none';
            notResults.style.display = 'block';
            this.renderPagination();
            return;
        }

        notResults.style.display = 'none';
        listContainer.style.display = 'block';

        // Calcular itens da página atual
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const pageNpcs = this.filteredNpcs.slice(startIndex, endIndex);

        // Renderizar itens
        listContainer.innerHTML = pageNpcs.map(npc => this.renderNpcItem(npc)).join('');
        
        // Renderizar paginação
        this.renderPagination();
    }

    renderNpcItem(npc) {
        const typeIcon = this.getNpcTypeIcon(npc.Type);
        const levelColor = this.getLevelColor(npc.Level);
        
        return `
            <div class="d-flex align-items-center border border-gray-300 border-dashed rounded p-5 mb-5 npc-item cursor-pointer" 
                 data-id="${npc.ID}" 
                 onclick="window.npcManager.selectNpc(${npc.ID})">
                <div class="symbol symbol-50px overflow-hidden me-3">
                    <div class="symbol-label fs-3 fw-bold text-primary">
                        ${typeIcon}
                    </div>
                </div>
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-gray-800 text-hover-primary fs-6 fw-bold">${npc.Name}</span>
                        <span class="badge badge-light-${levelColor} fs-8">#${npc.ID}</span>
                    </div>
                    <span class="text-gray-400 fw-bold fs-7">Level ${npc.Level} • Tipo: ${npc.Type}</span>
                    <span class="text-gray-400 fw-bold fs-8">Atk: ${npc.Attack} • HP: ${npc.Blood || npc.MaxLife}</span>
                </div>
            </div>
        `;
    }

    renderPagination() {
        const paginatorContainer = document.getElementById('item_paginator');
        if (!paginatorContainer) return;

        const totalPages = Math.ceil(this.filteredNpcs.length / this.itemsPerPage);
        
        if (totalPages <= 1) {
            paginatorContainer.innerHTML = '';
            return;
        }

        let paginationHtml = '';
        
        const startPage = Math.max(1, this.currentPage - 1);
        const endPage = Math.min(totalPages, this.currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === this.currentPage ? 'btn-primary' : 'btn-light';
            paginationHtml += `
                <button class="btn btn-sm ${activeClass}" onclick="window.npcManager.goToPage(${i})">
                    ${i}
                </button>
            `;
        }

        if (this.currentPage < totalPages && (totalPages - this.currentPage) > 2) {
            paginationHtml += `
                <button class="btn btn-sm btn-light" onclick="window.npcManager.goToPage(${totalPages})" title="Ir para página ${totalPages}">
                    ...${totalPages}
                </button>
            `;
        }

        paginatorContainer.innerHTML = `
            <div class="d-flex gap-1 align-items-center justify-content-end flex-wrap" style="max-width: 200px;">
                ${paginationHtml}
            </div>
        `;
    }

    goToPage(page) {
        const totalPages = Math.ceil(this.filteredNpcs.length / this.itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.renderNpcList();
        }
    }

    // SELEÇÃO DE NPC

    selectNpc(id, saveToSession = true) {
        // Remove seleção anterior
        document.querySelectorAll('.npc-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-light-primary', 'selected');
            item.classList.add('border-gray-300');
        });
        
        // Adiciona seleção atual
        const selectedItem = document.querySelector(`[data-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('border-primary', 'bg-light-primary', 'selected');
            selectedItem.classList.remove('border-gray-300');
        }
        
        // Buscar dados do NPC
        const npc = this.npcs.find(n => n.ID == id);
        
        if (npc) {
            this.currentNpc = npc;
            this.showNpcData();
            this.populateForms(npc);
            
            // Salvar seleção no sessionStorage
            if (saveToSession) {
                this.saveSelectedNpc(id);
            }
        }
    }

    showNpcData() {
        const notSelected = document.getElementById('not_selected');
        const npcData = document.getElementById('npc_data');
        
        if (notSelected) notSelected.style.display = 'none';
        if (npcData) npcData.style.display = 'block';
        
        // Garantir que a primeira aba esteja ativa
        setTimeout(() => {
            const firstTab = document.getElementById('npc_details');
            const firstLink = document.querySelector('a[data-tab="npc_details"]');
            
            if (firstTab && firstLink) {
                this.showTab('npc_details', firstLink);
            }
        }, 100);
    }

    populateForms(npc) {
        // Preenche aba Detalhes
        const detailsForm = document.getElementById('form-npc-details-send');
        if (detailsForm) {
            this.setFormValue(detailsForm, '[name="npc_id"]', npc.ID);
            this.setFormValue(detailsForm, '[name="ID"]', npc.ID);
            this.setFormValue(detailsForm, '[name="name"]', npc.Name);
            this.setFormValue(detailsForm, '[name="level"]', npc.Level);
            this.setFormValue(detailsForm, '[name="type"]', npc.Type);
            this.setFormValue(detailsForm, '[name="blood"]', npc.Blood || npc.MaxLife);
        }
        
        // Preenche aba Atributos
        const attributesForm = document.getElementById('form-npc-attributes-send');
        if (attributesForm) {
            this.setFormValue(attributesForm, '[name="npc_id"]', npc.ID);
            this.setFormValue(attributesForm, '[name="attack"]', npc.Attack);
            this.setFormValue(attributesForm, '[name="defence"]', npc.Defence);
            this.setFormValue(attributesForm, '[name="magicattack"]', npc.MagicAttack);
            this.setFormValue(attributesForm, '[name="magicdefence"]', npc.MagicDefence);
            this.setFormValue(attributesForm, '[name="basedamage"]', npc.BaseDamage);
            this.setFormValue(attributesForm, '[name="baseguard"]', npc.BaseGuard);
            this.setFormValue(attributesForm, '[name="agility"]', npc.Agility);
            this.setFormValue(attributesForm, '[name="lucky"]', npc.Lucky);
            this.setFormValue(attributesForm, '[name="movemin"]', npc.MoveMin);
            this.setFormValue(attributesForm, '[name="movemax"]', npc.MoveMax);
            this.setFormValue(attributesForm, '[name="speed"]', npc.speed);
        }
    }

    setFormValue(form, selector, value) {
        const field = form.querySelector(selector);
        if (field) {
            field.value = value || '';
        }
    }

    // OPERAÇÕES CRUD

    async createNpc() {
        const form = document.getElementById('createForm');
        const button = form.querySelector('button[type="submit"]');
        
        if (!form || !button) return;
        
        try {
            this.setButtonLoading(button, true);
            
            const formData = new FormData(form);
            formData.set('Camp', '2');
            
            const requiredFields = {
                'X': '0', 'Y': '0', 'Width': '0', 'Height': '0', 'ModelID': '0',
                'ResourcesPath': '', 'DropRate': '0', 'Experience': '5', 'Delay': '0',
                'Immunity': '0', 'Alert': '0', 'Range': '0', 'Preserve': '0', 'Script': '',
                'FireX': '0', 'FireY': '0', 'DropId': '0', 'CurrentBallId': '0'
            };
            
            Object.keys(requiredFields).forEach(field => {
                if (!formData.has(field)) {
                    formData.set(field, requiredFields[field]);
                }
            });
            
            const response = await fetch(window.baseUrl + '/admin/game/npc', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Sucesso!', data.message);
                this.closeModal('createNpcModal');
                
                // Salvar ID do novo NPC para selecioná-lo após reload
                const newNpcId = formData.get('ID');
                this.saveSelectedNpc(newNpcId);
                
                setTimeout(() => this.smartRefresh(), 1000);
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão. Tente novamente.');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    async updateDetails() {
        if (!this.currentNpc) {
            this.showAlert('warning', 'Atenção!', 'Nenhum NPC selecionado!');
            return;
        }

        const form = document.getElementById('form-npc-details-send');
        const button = form.querySelector('button[type="button"]');
        
        if (!form || !button) return;
        
        try {
            this.setButtonLoading(button, true);
            
            const formData = new FormData(form);
            
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.currentNpc.ID}/details`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Sucesso!', data.message);
                // Manter seleção atual
                this.saveSelectedNpc(this.currentNpc.ID);
                setTimeout(() => this.smartRefresh(), 1000);
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão. Tente novamente.');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    async updateAttributes() {
        if (!this.currentNpc) {
            this.showAlert('warning', 'Atenção!', 'Nenhum NPC selecionado!');
            return;
        }

        const form = document.getElementById('form-npc-attributes-send');
        const button = form.querySelector('button[type="button"]');
        
        if (!form || !button) return;
        
        try {
            this.setButtonLoading(button, true);
            
            const formData = new FormData(form);
            
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.currentNpc.ID}/attributes`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Sucesso!', data.message);
                // Manter seleção atual
                this.saveSelectedNpc(this.currentNpc.ID);
                setTimeout(() => this.smartRefresh(), 1000);
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão. Tente novamente.');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    confirmDelete() {
        if (!this.currentNpc) {
            this.showAlert('warning', 'Atenção!', 'Nenhum NPC selecionado!');
            return;
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Tem certeza?',
                text: `Deseja deletar o NPC "${this.currentNpc.Name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteNpc();
                }
            });
        } else {
            if (confirm(`Tem certeza que deseja deletar o NPC "${this.currentNpc.Name}"?`)) {
                this.deleteNpc();
            }
        }
    }

    async deleteNpc() {
        if (!this.currentNpc) return;
        
        try {
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.currentNpc.ID}/delete`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Deletado!', data.message);
                
                // Limpar seleção já que o NPC foi deletado
                this.clearSelectedNpc();
                this.currentNpc = null;
                this.hideNpcData();
                
                setTimeout(() => this.smartRefresh(), 1000);
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão. Tente novamente.');
        }
    }

    async duplicateNpc() {
        if (!this.currentNpc) {
            this.showAlert('warning', 'Atenção!', 'Nenhum NPC selecionado para duplicar!');
            return;
        }

        try {
            const newId = Math.floor(Math.random() * 90000) + 10000;
            
            const duplicateData = {
                ID: newId,
                Name: `${this.currentNpc.Name} #copy`,
                Level: this.currentNpc.Level || 1,
                Type: this.currentNpc.Type || 0,
                Blood: this.currentNpc.Blood || this.currentNpc.MaxLife || 100,
                Attack: this.currentNpc.Attack || 1,
                Defence: this.currentNpc.Defence || 1,
                MagicAttack: this.currentNpc.MagicAttack || 0,
                MagicDefence: this.currentNpc.MagicDefence || 0,
                BaseDamage: this.currentNpc.BaseDamage || 1,
                BaseGuard: this.currentNpc.BaseGuard || 1,
                Agility: this.currentNpc.Agility || 0,
                Lucky: this.currentNpc.Lucky || 0,
                MoveMin: this.currentNpc.MoveMin || 0,
                MoveMax: this.currentNpc.MoveMax || 0,
                speed: this.currentNpc.speed || 0,
                Camp: 2,
                X: this.currentNpc.X || 0,
                Y: this.currentNpc.Y || 0,
                Width: this.currentNpc.Width || 0,
                Height: this.currentNpc.Height || 0,
                ModelID: this.currentNpc.ModelID || 0,
                ResourcesPath: this.currentNpc.ResourcesPath || '',
                DropRate: this.currentNpc.DropRate || 0,
                Experience: this.currentNpc.Experience || 5,
                Delay: this.currentNpc.Delay || 0,
                Immunity: this.currentNpc.Immunity || 0,
                Alert: this.currentNpc.Alert || 0,
                Range: this.currentNpc.Range || 0,
                Preserve: this.currentNpc.Preserve || 0,
                Script: this.currentNpc.Script || '',
                FireX: this.currentNpc.FireX || 0,
                FireY: this.currentNpc.FireY || 0,
                DropId: this.currentNpc.DropId || 0,
                CurrentBallId: this.currentNpc.CurrentBallId || 0
            };

            const formData = new FormData();
            Object.keys(duplicateData).forEach(key => {
                formData.append(key, duplicateData[key]);
            });

            const response = await fetch(window.baseUrl + '/admin/game/npc', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('success', 'Duplicado!', `NPC duplicado com ID ${newId}!`);
                
                // Selecionar o novo NPC duplicado após reload
                this.saveSelectedNpc(newId);
                
                setTimeout(() => this.smartRefresh(), 1000);
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão. Tente novamente.');
        }
    }

    // CALCULADORA DE ATRIBUTOS

    async calculateAttributes() {
        const button = document.getElementById('btn_calculate_attributes');
        const difficulty = document.getElementById('difficulty_select')?.value || 'médio';
        
        try {
            this.setButtonLoading(button, true);
            
            const response = await fetch(`${window.baseUrl}/admin/game/npc/calculate?difficulty=${encodeURIComponent(difficulty)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayCalculatedAttributes(data.data);
                this.showAlert('success', 'Sucesso!', 'Atributos calculados com base nos jogadores do servidor!');
            } else {
                this.showAlert('error', 'Erro!', data.message);
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro de conexão: ' + error.message);
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    displayCalculatedAttributes(data) {
        const results = document.getElementById('calculation_results');
        const attributes = data.attributes;
        
        results.style.display = 'block';
        
        // Preencher valores
        document.getElementById('calc_attack').textContent = attributes.Attack || 0;
        document.getElementById('calc_defence').textContent = attributes.Defence || 0;
        document.getElementById('calc_luck').textContent = attributes.Luck || 0;
        document.getElementById('calc_agility').textContent = attributes.Agility || 0;
        document.getElementById('calc_magic_attack').textContent = attributes.MagicAttack || 0;
        document.getElementById('calc_magic_defence').textContent = attributes.MagicDefence || 0;
        document.getElementById('calc_blood').textContent = attributes.Blood || 0;
        document.getElementById('calc_max_hp').textContent = data.maxHp || 0;
        
        // Informações do cálculo
        document.getElementById('info_difficulty').textContent = data.difficulty;
        document.getElementById('info_multiplier').textContent = 'x' + data.multiplier;
        document.getElementById('info_players').textContent = data.playersUsed + ' jogadores';
        
        this.lastCalculatedAttributes = attributes;
    }

    // SELEÇÃO PERSISTENTE
    async applyCalculatedAttributes() {
        if (!this.currentNpc) {
            this.showAlert('warning', 'Atenção!', 'Nenhum NPC selecionado para aplicar os atributos!');
            return;
        }
        
        if (!this.lastCalculatedAttributes) {
            this.showAlert('warning', 'Atenção!', 'Nenhum atributo calculado! Clique em "Calcular" primeiro.');
            return;
        }
        
        const button = document.getElementById('btn_apply_calculated');
        
        try {
            this.setButtonLoading(button, true);
            
            // 1. Atualizar atributos primeiro
            const attributesFormData = new FormData();
            attributesFormData.append('npc_id', this.currentNpc.ID);
            attributesFormData.append('attack', this.lastCalculatedAttributes.Attack || 0);
            attributesFormData.append('defence', this.lastCalculatedAttributes.Defence || 0);
            attributesFormData.append('lucky', this.lastCalculatedAttributes.Luck || 0);
            attributesFormData.append('agility', this.lastCalculatedAttributes.Agility || 0);
            attributesFormData.append('magicattack', this.lastCalculatedAttributes.MagicAttack || 0);
            attributesFormData.append('magicdefence', this.lastCalculatedAttributes.MagicDefence || 0);
            
            const attributesResponse = await fetch(`${window.baseUrl}/admin/game/npc/${this.currentNpc.ID}/attributes`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: attributesFormData
            });
            
            const attributesData = await attributesResponse.json();
            
            if (!attributesData.success) {
                throw new Error(attributesData.message || 'Erro ao atualizar atributos');
            }
            
            // 2. Atualizar Blood se existe valor calculado
            if (this.lastCalculatedAttributes.Blood) {
                const bloodFormData = new FormData();
                bloodFormData.append('npc_id', this.currentNpc.ID);
                bloodFormData.append('name', this.currentNpc.Name);
                bloodFormData.append('level', this.currentNpc.Level);
                bloodFormData.append('type', this.currentNpc.Type);
                bloodFormData.append('blood', this.lastCalculatedAttributes.Blood);
                
                await fetch(`${window.baseUrl}/admin/game/npc/${this.currentNpc.ID}/details`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: bloodFormData
                });
            }
            
            // 3. Sucesso - manter seleção atual e aguardar antes do refresh
            this.showAlert('success', 'Aplicado!', `Atributos calculados aplicados ao NPC "${this.currentNpc.Name}" com sucesso!`);
            
            // 4. Salvar seleção atual para manter após reload
            this.saveSelectedNpc(this.currentNpc.ID);
            
            // 5. Aguardar 2 segundos antes do refresh para evitar conflitos
            setTimeout(() => {
                this.smartRefresh();
            }, 2000);
            
        } catch (error) {
            console.error('Erro ao aplicar atributos:', error);
            this.showAlert('error', 'Erro!', 'Erro ao aplicar atributos: ' + error.message);
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    createNpcWithCalculatedAttributes() {
        if (!this.lastCalculatedAttributes) {
            this.showAlert('warning', 'Atenção!', 'Nenhum atributo calculado! Clique em "Calcular" primeiro.');
            return;
        }
        
        const newId = Math.floor(Math.random() * 90000) + 10000;
        
        const modal = document.getElementById('createNpcModal');
        if (modal) {
            const form = modal.querySelector('#createForm');
            if (form) {
                const attrs = this.lastCalculatedAttributes;
                
                form.querySelector('[name="ID"]').value = newId;
                form.querySelector('[name="Name"]').value = `NPC Auto ${newId}`;
                form.querySelector('[name="Level"]').value = 1;
                form.querySelector('[name="Type"]').value = 1;
                form.querySelector('[name="Blood"]').value = attrs.Blood || 100;
                form.querySelector('[name="Attack"]').value = attrs.Attack || 1;
                form.querySelector('[name="Defence"]').value = attrs.Defence || 1;
                form.querySelector('[name="MagicAttack"]').value = attrs.MagicAttack || 0;
                form.querySelector('[name="MagicDefence"]').value = attrs.MagicDefence || 0;
                form.querySelector('[name="BaseDamage"]').value = Math.max(1, Math.round((attrs.Attack || 1) * 0.8));
                form.querySelector('[name="BaseGuard"]').value = Math.max(1, Math.round((attrs.Defence || 1) * 0.8));
                form.querySelector('[name="Agility"]').value = attrs.Agility || 0;
                form.querySelector('[name="Lucky"]').value = attrs.Luck || 0;
                form.querySelector('[name="speed"]').value = Math.max(1, Math.round((attrs.Agility || 1) * 0.5));
            }
            
            if (typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            }
            
            this.showAlert('info', 'Modal Preenchido!', 'Os valores calculados foram aplicados ao formulário de criação!');
        }
    }

    // UTILITÁRIOS

    smartRefresh() {
        // Aguardar um pouco e fazer reload
        setTimeout(() => {
            location.reload();
        }, 500);
    }

    hideNpcData() {
        const notSelected = document.getElementById('not_selected');
        const npcData = document.getElementById('npc_data');
        
        if (notSelected) notSelected.style.display = 'block';
        if (npcData) npcData.style.display = 'none';
    }

    resetCreateForm() {
        const form = document.getElementById('createForm');
        if (form) {
            form.reset();
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal && typeof bootstrap !== 'undefined') {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    }

    setButtonLoading(button, loading) {
        if (!button) return;
        
        if (loading) {
            button.setAttribute('data-kt-indicator', 'on');
            button.disabled = true;
            
            // Salvar texto original e trocar durante loading
            if (!button.dataset.originalText) {
                const labelElement = button.querySelector('.indicator-label');
                if (labelElement) {
                    button.dataset.originalText = labelElement.textContent;
                    labelElement.textContent = '🔄 Atualizando XML...';
                }
            }
        } else {
            button.removeAttribute('data-kt-indicator');
            button.disabled = false;
            
            // Restaurar texto original
            if (button.dataset.originalText) {
                const labelElement = button.querySelector('.indicator-label');
                if (labelElement) {
                    labelElement.textContent = button.dataset.originalText;
                    delete button.dataset.originalText;
                }
            }
        }
    }

    showAlert(type, title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                timer: type === 'success' ? 2000 : undefined,
                showConfirmButton: type !== 'success'
            });
        } else {
            alert(`${title}: ${message}`);
        }
    }

    getNpcTypeIcon(type) {
        switch (parseInt(type)) {
            case 1: return '⚔️';
            case 2: return '🛡️';
            case 3: return '🏹';
            case 4: return '🔮';
            case 5: return '🗡️';
            default: return '🤖';
        }
    }

    getLevelColor(level) {
        const lvl = parseInt(level);
        if (lvl <= 10) return 'success';
        if (lvl <= 50) return 'warning';
        if (lvl <= 100) return 'danger';
        return 'dark';
    }
}

// COMPATIBILIDADE E INICIALIZAÇÃO

// Função global para compatibilidade
window.npc = {
    updateDetails: () => window.npcManager?.updateDetails(),
    updateAttributes: () => window.npcManager?.updateAttributes(),
    create: () => window.npcManager?.createNpc(),
    delete: (id) => window.npcManager?.confirmDelete()
};

// Inicialização do sistema
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        window.npcManager = new NpcManager();
        console.log('🚀 NPC Manager iniciado com sucesso e CSS injetado!');
    }, 100);
});

// Compatibilidade com jQuery
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        console.log('jQuery carregado');
    });
}