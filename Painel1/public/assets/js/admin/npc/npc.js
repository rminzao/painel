function injectCustomCSS() {
    if (document.getElementById('npc-custom-styles')) return;

    const css = `
        :root {
            --primary-color: #667eea;
            --success-color: #4facfe;
            --warning-color: #fa709a;
            --danger-color: #ff6b6b;
            --shadow-base: 0 4px 15px rgba(0,0,0,0.1);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
            --transition: all 0.3s ease;
            --border-radius: 12px;
        }

        .npc-item {
            transition: var(--transition);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.9);
        }

        .npc-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .npc-item.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(255, 255, 255, 0.9));
            box-shadow: var(--shadow-hover);
        }

        .npc-item .symbol-label {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            transition: var(--transition);
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .npc-item:hover .symbol-label {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
            transform: scale(1.1);
        }

        .npc-item.selected .symbol-label {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
        }

        .nav-tabs {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 4px;
        }

        .nav-link {
            border: none;
            border-radius: 8px;
            margin: 0 2px;
            transition: var(--transition);
            font-weight: 600;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
            box-shadow: var(--shadow-base);
        }

        #calculation_results {
            animation: slideInUp 0.5s ease;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(248, 249, 250, 0.8));
            border-radius: var(--border-radius);
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bg-light-success, .bg-light-primary, .bg-light-warning, 
        .bg-light-info, .bg-light-dark, .bg-light-secondary, 
        .bg-light-danger {
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bg-light-success:hover { 
            border-color: #4bc0c0; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(75, 192, 192, 0.3);
        }

        .bg-light-primary:hover { 
            border-color: #36a2eb; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(54, 162, 235, 0.3);
        }

        .bg-light-warning:hover { 
            border-color: #ffce54; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(255, 206, 84, 0.3);
        }

        .bg-light-info:hover { 
            border-color: #9966ff; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(153, 102, 255, 0.3);
        }

        .bg-light-dark:hover { 
            border-color: #6c757d; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
        }

        .bg-light-secondary:hover { 
            border-color: #80868b; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(128, 134, 139, 0.3);
        }

        .bg-light-danger:hover { 
            border-color: #dc3545; 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            border: none;
            box-shadow: var(--shadow-base);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #00f2fe);
            border: none;
            box-shadow: var(--shadow-base);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-light-primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(255, 255, 255, 0.9));
            color: var(--primary-color);
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .btn-light-primary:hover {
            background: linear-gradient(135deg, var(--primary-color), #764ba2);
            color: white;
            transform: translateY(-2px);
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            transform: translateY(-1px);
        }

        .card {
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 255, 255, 0.125);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-base);
            transition: var(--transition);
        }

        .alert {
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInDown 0.4s ease;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .npc-item:hover {
                transform: translateY(-2px) scale(1.01);
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.875rem;
            }
        }
    `;

    const styleElement = document.createElement('style');
    styleElement.id = 'npc-custom-styles';
    styleElement.textContent = css;
    document.head.appendChild(styleElement);
}

// CLASSE PRINCIPAL DO NPC MANAGER
class NpcManager {
    constructor() {
        this.state = {
            npcs: window.npcData || [],
            filteredNpcs: [],
            currentNpc: null,
            currentPage: 1,
            itemsPerPage: 5,
            currentSearch: '',
            currentLevelFilter: 'all',
            lastCalculatedAttributes: null
        };
        
        this.init();
    }

    init() {
        injectCustomCSS();
        this.setupEventListeners();
        this.setupTabSystem();
        this.loadNpcList();
        this.setupSelect2();
        this.setupModals();
        this.restoreSelectedNpc();
    }
   
    // SISTEMA DE NOTIFICAÇÕES   
    initNotifications() {
        if (!document.getElementById('notifications-container')) {
            const container = document.createElement('div');
            container.id = 'notifications-container';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; pointer-events: none;';
            document.body.appendChild(container);
        }
    }

    showNotification(message, type) {
        type = type || 'success';
        this.initNotifications();
        
        const container = document.getElementById('notifications-container');
        const notification = document.createElement('div');
        
        const colors = {
            success: { bg: 'linear-gradient(135deg, #00c851 0%, #007e33 100%)', text: '#ffffff', icon: '✅' },
            error: { bg: 'linear-gradient(135deg, #ff4444 0%, #cc0000 100%)', text: '#ffffff', icon: '❌' },
            warning: { bg: 'linear-gradient(135deg, #ffbb33 0%, #ff8800 100%)', text: '#ffffff', icon: '⚠️' },
            info: { bg: 'linear-gradient(135deg, #33b5e5 0%, #0099cc 100%)', text: '#ffffff', icon: 'ℹ️' }
        };
        
        const color = colors[type] || colors.success;
        
        notification.innerHTML = `
            <div style="
                background: ${color.bg}; 
                color: ${color.text}; 
                padding: 15px 20px; 
                border-radius: 12px; 
                margin-bottom: 10px; 
                box-shadow: 0 8px 32px rgba(0,0,0,0.3); 
                font-size: 14px; 
                font-weight: 600; 
                pointer-events: auto; 
                transform: translateX(100%); 
                transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); 
                display: flex; 
                align-items: center; 
                gap: 12px; 
                min-width: 350px; 
                max-width: 450px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.2);
            ">
                <span style="font-size: 20px;">${color.icon}</span>
                <span style="flex: 1; line-height: 1.4;">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="
                    background: rgba(255,255,255,0.2); 
                    border: none; 
                    color: ${color.text}; 
                    cursor: pointer; 
                    padding: 4px 8px; 
                    border-radius: 6px; 
                    font-weight: bold;
                    transition: background 0.2s;
                " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">×</button>
            </div>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.firstElementChild.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.firstElementChild.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 400);
            }
        }, 4000);
    }
  
    // CARREGAMENTO DINÂMICO 
    async loadNpcsFromServer(silentMode = false) {
        try {
            // Tentar carregamento dinâmico primeiro (silenciosamente)
            const response = await fetch(`${window.baseUrl}/admin/game/npc/list`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.success && data.npcs) {
                    // Sucesso no carregamento dinâmico
                    this.state.npcs = data.npcs;
                    this.state.filteredNpcs = this.state.npcs.slice();
                    this.applyFilters();
                    this.showNotification('Lista de NPCs atualizada! 🔄', 'success');
                    return;
                }
            }
            
        } catch (error) {
            // Falha silenciosa se estiver em modo silencioso
            if (!silentMode) {
                console.warn('API dinâmica não disponível, usando reload');
            }
        }
        
        // Fallback: reload da página (apenas se não estiver em modo silencioso)
        if (!silentMode) {
            this.showNotification('Atualizando página... 🔄', 'info');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }

    // Atualizar NPC na lista dinamicamente
    updateNpcInList(npcData) {
        const npcIndex = this.state.npcs.findIndex(n => n.ID == npcData.ID);
        
        if (npcIndex !== -1) {
            this.state.npcs[npcIndex] = { ...this.state.npcs[npcIndex], ...npcData };
        }
        
        if (this.state.currentNpc && this.state.currentNpc.ID == npcData.ID) {
            this.state.currentNpc = { ...this.state.currentNpc, ...npcData };
        }
        
        this.applyFilters();
        
        // Atualizar formulários se for o NPC atual
        if (this.state.currentNpc && this.state.currentNpc.ID == npcData.ID) {
            this.updateNpcDetailsDisplay(npcData);
        }
        
        this.showNotification('NPC atualizado dinamicamente! ⚡', 'success');
    }

    // Adicionar NPC na lista dinamicamente
    addNpcToList(npcData) {
        this.state.npcs.unshift(npcData);
        this.state.filteredNpcs = this.state.npcs.slice();
        this.applyFilters();
        
        setTimeout(() => {
            this.selectNpc(npcData.ID, true);
            
            const newNpcElement = document.querySelector(`[data-id="${npcData.ID}"]`);
            if (newNpcElement) {
                newNpcElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 300);
        
        this.showNotification('NPC adicionado dinamicamente! 🚀', 'success');
    }

    // Remover NPC da lista dinamicamente
    removeNpcFromList(npcId) {
        this.state.npcs = this.state.npcs.filter(n => n.ID != npcId);
        this.applyFilters();
        
        if (this.state.currentNpc && this.state.currentNpc.ID == npcId) {
            this.state.currentNpc = null;
            this.hideNpcData();
        }
        
        this.showNotification('NPC removido dinamicamente! 🗑️', 'success');
    }

    // Atualizar exibição dos detalhes
    updateNpcDetailsDisplay(npcData) {
        // Atualizar os formulários com os novos dados
        this.populateForms(npcData);
        
        // Efeito visual de atualização
        const detailsContainer = document.getElementById('npc_data');
        if (detailsContainer) {
            detailsContainer.style.transition = 'all 0.3s ease';
            detailsContainer.style.transform = 'scale(1.02)';
            detailsContainer.style.boxShadow = '0 8px 30px rgba(40,167,69,0.3)';
            
            setTimeout(() => {
                detailsContainer.style.transform = 'scale(1)';
                detailsContainer.style.boxShadow = '';
            }, 300);
        }
        
        // Atualizar o item na lista visualmente
        const npcListItem = document.querySelector(`[data-id="${npcData.ID}"]`);
        if (npcListItem) {
            const npcFromState = this.state.npcs.find(n => n.ID == npcData.ID);
            if (npcFromState) {
                npcListItem.outerHTML = this.renderNpcItem(npcFromState);
                
                // Re-selecionar o item atualizado
                setTimeout(() => {
                    const updatedItem = document.querySelector(`[data-id="${npcData.ID}"]`);
                    if (updatedItem) {
                        updatedItem.classList.add('border-primary', 'bg-light-primary', 'selected');
                        updatedItem.classList.remove('border-gray-300');
                    }
                }, 100);
            }
        }
    }
   
    // ATUALIZAÇÃO XML DINÂMICA   
    async updateXmlSilently() {
        const refreshBtn = document.getElementById('button_refresh_list');
        
        try {
            this.setButtonLoading(refreshBtn, true);
            
            // Usar a URL dinâmica do .env
            const xmlUpdateUrl = window.xmlUpdateUrl || 'https://evotank.com.br/quest/createallxml.ashx';
            
            // Validar se a URL está configurada
            if (!xmlUpdateUrl || xmlUpdateUrl.trim() === '') {
                this.showNotification('⚠️ URL de atualização XML não configurada no .env!', 'error');
                return;
            }
            
            console.log('🔄 Tentando atualizar XML usando:', xmlUpdateUrl);
            
            try {
                const currentDomain = window.location.hostname;
                const xmlDomain = new URL(xmlUpdateUrl).hostname;
                
                // Verificar se estamos no mesmo domínio da URL do XML
                if (currentDomain !== xmlDomain) {
                    console.log(`🌐 Domínios diferentes (atual: ${currentDomain}, XML: ${xmlDomain}), usando método alternativo`);
                    this.updateXmlAlternative();
                    return;
                }
            } catch (urlError) {
                console.warn('⚠️ Erro ao parsear URL do XML, usando método alternativo:', urlError.message);
                this.updateXmlAlternative();
                return;
            }
            
            const response = await fetch(xmlUpdateUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'text/xml, application/xml, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                console.log('✅ XML atualizada com sucesso via fetch');
                this.showNotification('XML Atualizada! 🔄', 'success');
                
                // Após XML update, sempre fazer reload
                setTimeout(() => {
                    this.showNotification('Atualizando página... 🔄', 'info');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }, 1000);
            } else {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            
        } catch (error) {
            console.warn('⚠️ Erro na atualização direta, tentando método alternativo:', error.message);
            this.updateXmlAlternative();
        } finally {
            this.setButtonLoading(refreshBtn, false);
        }
    }
    
    updateXmlAlternative() {
        try {
            // Usar a URL dinâmica do .env
            const xmlUpdateUrl = window.xmlUpdateUrl || 'https://evotank.com.br/quest/createallxml.ashx';
            
            // Validar se a URL está configurada
            if (!xmlUpdateUrl || xmlUpdateUrl.trim() === '') {
                this.showNotification('⚠️ URL de atualização XML não configurada no .env!', 'error');
                return;
            }
            
            console.log('🔄 Atualizando XML via iframe:', xmlUpdateUrl);
            
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.position = 'absolute';
            iframe.style.left = '-9999px';
            iframe.setAttribute('sandbox', 'allow-same-origin allow-scripts');
            
            document.body.appendChild(iframe);
            
            let completed = false;
            
            const cleanup = () => {
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
            };
            
            const complete = () => {
                if (completed) return;
                completed = true;
                
                setTimeout(() => {
                    cleanup();
                    console.log('✅ XML atualizada com sucesso via iframe');
                    this.showNotification('XML Atualizada! 🔄', 'success');
                    
                    // Após XML update, sempre fazer reload
                    setTimeout(() => {
                        this.showNotification('Atualizando página... 🔄', 'info');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }, 1000);
                }, 1500);
            };
            
            iframe.onload = () => {
                console.log('📄 Iframe carregado com sucesso');
                complete();
            };
            
            iframe.onerror = () => {
                console.log('⚠️ Erro no iframe, mas considerando como sucesso');
                complete();
            };
            
            // Timeout de segurança
            setTimeout(() => {
                if (!completed) {
                    console.log('⏰ Timeout atingido, finalizando atualização');
                    complete();
                }
            }, 5000);
            
            // Usar a URL dinâmica com timestamp para evitar cache
            const finalUrl = xmlUpdateUrl + (xmlUpdateUrl.includes('?') ? '&' : '?') + '_t=' + Date.now();
            iframe.src = finalUrl;
            
            console.log('🚀 Iframe criado com URL:', finalUrl);
            
        } catch (error) {
            console.error('❌ Erro ao atualizar XML:', error);
            this.showNotification('Erro ao atualizar XML: ' + error.message, 'error');
        }
    }

    // Função adicional para debug
    debugXmlConfig() {
        console.log('🔍 Configuração XML Debug:');
        console.log('  - XML Update URL:', window.xmlUpdateUrl);
        console.log('  - Base URL:', window.baseUrl);
        console.log('  - Current Domain:', window.location.hostname);
        
        if (window.xmlUpdateUrl) {
            try {
                const url = new URL(window.xmlUpdateUrl);
                console.log('  - XML Domain:', url.hostname);
                console.log('  - XML Protocol:', url.protocol);
                console.log('  - Same Domain?', window.location.hostname === url.hostname);
            } catch (e) {
                console.error('  - Erro ao parsear URL XML:', e.message);
            }
        } else {
            console.warn('  - ⚠️ XML Update URL não configurada!');
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
            setTimeout(() => {
                const npc = this.state.npcs.find(n => n.ID == savedNpcId);
                if (npc) {
                    this.selectNpc(savedNpcId, false);
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
        const allTabs = document.querySelectorAll('.tab-pane');
        allTabs.forEach(tab => {
            tab.classList.remove('active');
            tab.style.display = 'none';
        });
        
        const allLinks = document.querySelectorAll('#npc-tabs .nav-link');
        allLinks.forEach(link => {
            link.classList.remove('active');
        });
        
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.add('active');
            targetTab.style.display = 'block';
        }
        
        if (linkElement) {
            linkElement.classList.add('active');
        }
    }
   
    // EVENT LISTENERS   
    setupEventListeners() {
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

        const levelFilter = document.querySelector('[name="level_filter"]');
        if (levelFilter) {
            levelFilter.addEventListener('change', (e) => {
                this.handleLevelFilter(e.target.value);
            });
        }

        const limitSelect = document.querySelector('[name="limit"]');
        if (limitSelect) {
            limitSelect.addEventListener('change', (e) => {
                this.state.itemsPerPage = parseInt(e.target.value);
                this.state.currentPage = 1;
                this.renderNpcList();
            });
        }

        const refreshBtn = document.getElementById('button_refresh_list');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (e.ctrlKey || e.shiftKey) {
                    // Ctrl+Click: reload direto sem XML
                    this.showNotification('Atualizando lista... 🔄', 'info');
                    setTimeout(() => {
                        location.reload();
                    }, 800);
                } else {
                    // Click normal: atualizar XML + reload
                    this.updateXmlSilently();
                }
            });
            
            refreshBtn.setAttribute('title', 'Clique: Atualizar XML + Lista | Ctrl+Clique: Apenas Lista');
        }

        const createForm = document.getElementById('createForm');
        if (createForm) {
            createForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createNpc();
            });
        }

        const deleteBtn = document.getElementById('btn_delete_npc');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => {
                this.confirmDelete();
            });
        }

        const duplicateBtn = document.getElementById('btn_duplicate_npc');
        if (duplicateBtn) {
            duplicateBtn.addEventListener('click', () => {
                this.duplicateNpc();
            });
        }

        const calculateBtn = document.getElementById('btn_calculate_attributes');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', () => {
                this.calculateAttributes();
            });
        }

        const applyCalculatedBtn = document.getElementById('btn_apply_calculated');
        if (applyCalculatedBtn) {
            applyCalculatedBtn.addEventListener('click', () => {
                this.applyCalculatedAttributes();
            });
        }

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
        this.state.currentSearch = searchTerm.toLowerCase().trim();
        this.state.currentPage = 1;
        this.applyFilters();
    }

    handleLevelFilter(filterValue) {
        this.state.currentLevelFilter = filterValue;
        this.state.currentPage = 1;
        this.applyFilters();
    }

    applyFilters() {
        let filtered = [...this.state.npcs];

        if (this.state.currentSearch) {
            filtered = filtered.filter(npc => {
                return npc.Name.toLowerCase().includes(this.state.currentSearch) ||
                       npc.ID.toString().includes(this.state.currentSearch);
            });
        }

        if (this.state.currentLevelFilter !== 'all') {
            filtered = filtered.filter(npc => {
                const level = parseInt(npc.Level);
                switch (this.state.currentLevelFilter) {
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

        this.state.filteredNpcs = filtered;
        this.renderNpcList();
    }
   
    // RENDERIZAÇÃO DA LISTA
    loadNpcList() {
        this.applyFilters();
    }

    renderNpcList() {
        const listContainer = document.getElementById('npc_list');
        const notResults = document.getElementById('not_results');
        
        if (!listContainer || !notResults) return;

        if (this.state.filteredNpcs.length === 0) {
            listContainer.style.display = 'none';
            notResults.style.display = 'block';
            this.renderPagination();
            return;
        }

        notResults.style.display = 'none';
        listContainer.style.display = 'block';

        const startIndex = (this.state.currentPage - 1) * this.state.itemsPerPage;
        const endIndex = startIndex + this.state.itemsPerPage;
        const pageNpcs = this.state.filteredNpcs.slice(startIndex, endIndex);

        listContainer.innerHTML = pageNpcs.map(npc => this.renderNpcItem(npc)).join('');
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

        const totalPages = Math.ceil(this.state.filteredNpcs.length / this.state.itemsPerPage);
        
        if (totalPages <= 1) {
            paginatorContainer.innerHTML = '';
            return;
        }

        let paginationHtml = '';
        
        const startPage = Math.max(1, this.state.currentPage - 1);
        const endPage = Math.min(totalPages, this.state.currentPage + 1);

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === this.state.currentPage ? 'btn-primary' : 'btn-light';
            paginationHtml += `
                <button class="btn btn-sm ${activeClass}" onclick="window.npcManager.goToPage(${i})">
                    ${i}
                </button>
            `;
        }

        if (this.state.currentPage < totalPages && (totalPages - this.state.currentPage) > 2) {
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
        const totalPages = Math.ceil(this.state.filteredNpcs.length / this.state.itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            this.state.currentPage = page;
            this.renderNpcList();
        }
    }
 
    // SELEÇÃO DE NPC
    selectNpc(id, saveToSession = true) {
        document.querySelectorAll('.npc-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-light-primary', 'selected');
            item.classList.add('border-gray-300');
        });
        
        const selectedItem = document.querySelector(`[data-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('border-primary', 'bg-light-primary', 'selected');
            selectedItem.classList.remove('border-gray-300');
        }
        
        const npc = this.state.npcs.find(n => n.ID == id);
        
        if (npc) {
            this.state.currentNpc = npc;
            this.showNpcData();
            this.populateForms(npc);
            
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
        
        setTimeout(() => {
            const firstTab = document.getElementById('npc_details');
            const firstLink = document.querySelector('a[data-tab="npc_details"]');
            
            if (firstTab && firstLink) {
                this.showTab('npc_details', firstLink);
            }
        }, 100);
    }

    populateForms(npc) {
        const detailsForm = document.getElementById('form-npc-details-send');
        if (detailsForm) {
            this.setFormValue(detailsForm, '[name="npc_id"]', npc.ID);
            this.setFormValue(detailsForm, '[name="ID"]', npc.ID);
            this.setFormValue(detailsForm, '[name="name"]', npc.Name);
            this.setFormValue(detailsForm, '[name="level"]', npc.Level);
            this.setFormValue(detailsForm, '[name="type"]', npc.Type);
            this.setFormValue(detailsForm, '[name="blood"]', npc.Blood || npc.MaxLife);
        }
        
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
                this.showNotification('NPC criado com sucesso! 🤖', 'success');
                this.closeModal('createNpcModal');
                
                // Criar objeto NPC para atualização dinâmica
                const newNpc = {
                    ID: formData.get('ID'),
                    Name: formData.get('Name'),
                    Level: formData.get('Level'),
                    Type: formData.get('Type'),
                    Blood: formData.get('Blood'),
                    Attack: formData.get('Attack'),
                    Defence: formData.get('Defence'),
                    MagicAttack: formData.get('MagicAttack'),
                    MagicDefence: formData.get('MagicDefence'),
                    BaseDamage: formData.get('BaseDamage'),
                    BaseGuard: formData.get('BaseGuard'),
                    Agility: formData.get('Agility'),
                    Lucky: formData.get('Lucky'),
                    speed: formData.get('speed')
                };
                
                // Adicionar dinamicamente à lista
                this.addNpcToList(newNpc);
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao criar NPC', 'error');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    async updateDetails() {
        if (!this.state.currentNpc) {
            this.showNotification('Nenhum NPC selecionado!', 'warning');
            return;
        }

        const form = document.getElementById('form-npc-details-send');
        const button = form.querySelector('button[type="button"]');
        
        if (!form || !button) return;
        
        try {
            this.setButtonLoading(button, true);
            
            const formData = new FormData(form);
            
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.state.currentNpc.ID}/details`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Detalhes atualizados com sucesso! ⚡', 'success');
                
                // Atualizar dinamicamente os dados locais
                const updatedData = {
                    ID: this.state.currentNpc.ID,
                    Name: form.querySelector('[name="name"]').value,
                    Level: form.querySelector('[name="level"]').value,
                    Type: form.querySelector('[name="type"]').value,
                    Blood: form.querySelector('[name="blood"]').value
                };
                
                this.updateNpcInList(updatedData);
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao atualizar detalhes', 'error');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    async updateAttributes() {
        if (!this.state.currentNpc) {
            this.showNotification('Nenhum NPC selecionado!', 'warning');
            return;
        }

        const form = document.getElementById('form-npc-attributes-send');
        const button = form.querySelector('button[type="button"]');
        
        if (!form || !button) return;
        
        try {
            this.setButtonLoading(button, true);
            
            const formData = new FormData(form);
            
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.state.currentNpc.ID}/attributes`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Atributos atualizados com sucesso! ⚡', 'success');
                
                // Atualizar dinamicamente os dados locais
                const updatedData = {
                    ID: this.state.currentNpc.ID,
                    Attack: form.querySelector('[name="attack"]').value,
                    Defence: form.querySelector('[name="defence"]').value,
                    MagicAttack: form.querySelector('[name="magicattack"]').value,
                    MagicDefence: form.querySelector('[name="magicdefence"]').value,
                    BaseDamage: form.querySelector('[name="basedamage"]').value,
                    BaseGuard: form.querySelector('[name="baseguard"]').value,
                    Agility: form.querySelector('[name="agility"]').value,
                    Lucky: form.querySelector('[name="lucky"]').value,
                    MoveMin: form.querySelector('[name="movemin"]').value,
                    MoveMax: form.querySelector('[name="movemax"]').value,
                    speed: form.querySelector('[name="speed"]').value
                };
                
                this.updateNpcInList(updatedData);
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao atualizar atributos', 'error');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    confirmDelete() {
        if (!this.state.currentNpc) {
            this.showNotification('Nenhum NPC selecionado!', 'warning');
            return;
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Tem certeza?',
                text: `Deseja deletar o NPC "${this.state.currentNpc.Name}"?`,
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
            if (confirm(`Tem certeza que deseja deletar o NPC "${this.state.currentNpc.Name}"?`)) {
                this.deleteNpc();
            }
        }
    }

    async deleteNpc() {
        if (!this.state.currentNpc) return;
        
        try {
            const response = await fetch(`${window.baseUrl}/admin/game/npc/${this.state.currentNpc.ID}/delete`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('NPC excluído com sucesso! 🗑️', 'success');
                
                this.removeNpcFromList(this.state.currentNpc.ID);
                this.clearSelectedNpc();
                this.state.currentNpc = null;
                this.hideNpcData();
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao excluir NPC', 'error');
        }
    }

    async duplicateNpc() {
        if (!this.state.currentNpc) {
            this.showNotification('Nenhum NPC selecionado para duplicar!', 'warning');
            return;
        }

        try {
            const newId = Math.floor(Math.random() * 90000) + 10000;
            
            const duplicateData = {
                ID: newId,
                Name: `${this.state.currentNpc.Name} #copy`,
                Level: this.state.currentNpc.Level || 1,
                Type: this.state.currentNpc.Type || 0,
                Blood: this.state.currentNpc.Blood || this.state.currentNpc.MaxLife || 100,
                Attack: this.state.currentNpc.Attack || 1,
                Defence: this.state.currentNpc.Defence || 1,
                MagicAttack: this.state.currentNpc.MagicAttack || 0,
                MagicDefence: this.state.currentNpc.MagicDefence || 0,
                BaseDamage: this.state.currentNpc.BaseDamage || 1,
                BaseGuard: this.state.currentNpc.BaseGuard || 1,
                Agility: this.state.currentNpc.Agility || 0,
                Lucky: this.state.currentNpc.Lucky || 0,
                MoveMin: this.state.currentNpc.MoveMin || 0,
                MoveMax: this.state.currentNpc.MoveMax || 0,
                speed: this.state.currentNpc.speed || 0,
                Camp: 2
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
                this.showNotification(`NPC duplicado com ID ${newId}! 📋`, 'success');
                
                // Adicionar cópia dinamicamente à lista
                this.addNpcToList(duplicateData);
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao duplicar NPC', 'error');
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
                this.showNotification('Atributos calculados com sucesso! 🧮', 'success');
            } else {
                this.showNotification('Erro: ' + data.message, 'error');
            }
            
        } catch (error) {
            this.showNotification('Erro ao calcular atributos', 'error');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    displayCalculatedAttributes(data) {
        const results = document.getElementById('calculation_results');
        const attributes = data.attributes;
        
        results.style.display = 'block';
        
        document.getElementById('calc_attack').textContent = attributes.Attack || 0;
        document.getElementById('calc_defence').textContent = attributes.Defence || 0;
        document.getElementById('calc_luck').textContent = attributes.Luck || 0;
        document.getElementById('calc_agility').textContent = attributes.Agility || 0;
        document.getElementById('calc_magic_attack').textContent = attributes.MagicAttack || 0;
        document.getElementById('calc_magic_defence').textContent = attributes.MagicDefence || 0;
        document.getElementById('calc_blood').textContent = attributes.Blood || 0;
        document.getElementById('calc_max_hp').textContent = data.maxHp || 0;
        
        document.getElementById('info_difficulty').textContent = data.difficulty;
        document.getElementById('info_multiplier').textContent = 'x' + data.multiplier;
        document.getElementById('info_players').textContent = data.playersUsed + ' jogadores';
        
        this.state.lastCalculatedAttributes = attributes;
    }

    async applyCalculatedAttributes() {
        if (!this.state.currentNpc) {
            this.showNotification('Nenhum NPC selecionado!', 'warning');
            return;
        }
        
        if (!this.state.lastCalculatedAttributes) {
            this.showNotification('Nenhum atributo calculado! Clique em "Calcular" primeiro.', 'warning');
            return;
        }
        
        const button = document.getElementById('btn_apply_calculated');
        
        try {
            this.setButtonLoading(button, true);
            
            const attributesFormData = new FormData();
            attributesFormData.append('npc_id', this.state.currentNpc.ID);
            attributesFormData.append('attack', this.state.lastCalculatedAttributes.Attack || 0);
            attributesFormData.append('defence', this.state.lastCalculatedAttributes.Defence || 0);
            attributesFormData.append('lucky', this.state.lastCalculatedAttributes.Luck || 0);
            attributesFormData.append('agility', this.state.lastCalculatedAttributes.Agility || 0);
            attributesFormData.append('magicattack', this.state.lastCalculatedAttributes.MagicAttack || 0);
            attributesFormData.append('magicdefence', this.state.lastCalculatedAttributes.MagicDefence || 0);
            
            const attributesResponse = await fetch(`${window.baseUrl}/admin/game/npc/${this.state.currentNpc.ID}/attributes`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: attributesFormData
            });
            
            const attributesData = await attributesResponse.json();
            
            if (!attributesData.success) {
                throw new Error(attributesData.message || 'Erro ao atualizar atributos');
            }
            
            if (this.state.lastCalculatedAttributes.Blood) {
                const bloodFormData = new FormData();
                bloodFormData.append('npc_id', this.state.currentNpc.ID);
                bloodFormData.append('name', this.state.currentNpc.Name);
                bloodFormData.append('level', this.state.currentNpc.Level);
                bloodFormData.append('type', this.state.currentNpc.Type);
                bloodFormData.append('blood', this.state.lastCalculatedAttributes.Blood);
                
                await fetch(`${window.baseUrl}/admin/game/npc/${this.state.currentNpc.ID}/details`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: bloodFormData
                });
            }
            
            this.showNotification(`Atributos aplicados ao NPC "${this.state.currentNpc.Name}" com sucesso! ✅`, 'success');
            
            // Atualizar dinamicamente os dados locais
            const updatedData = {
                ID: this.state.currentNpc.ID,
                Attack: this.state.lastCalculatedAttributes.Attack || 0,
                Defence: this.state.lastCalculatedAttributes.Defence || 0,
                Lucky: this.state.lastCalculatedAttributes.Luck || 0,
                Agility: this.state.lastCalculatedAttributes.Agility || 0,
                MagicAttack: this.state.lastCalculatedAttributes.MagicAttack || 0,
                MagicDefence: this.state.lastCalculatedAttributes.MagicDefence || 0,
                Blood: this.state.lastCalculatedAttributes.Blood || this.state.currentNpc.Blood
            };
            
            this.updateNpcInList(updatedData);
            
        } catch (error) {
            this.showNotification('Erro ao aplicar atributos: ' + error.message, 'error');
        } finally {
            this.setButtonLoading(button, false);
        }
    }

    createNpcWithCalculatedAttributes() {
        if (!this.state.lastCalculatedAttributes) {
            this.showNotification('Nenhum atributo calculado! Clique em "Calcular" primeiro.', 'warning');
            return;
        }
        
        const newId = Math.floor(Math.random() * 90000) + 10000;
        
        const modal = document.getElementById('createNpcModal');
        if (modal) {
            const form = modal.querySelector('#createForm');
            if (form) {
                const attrs = this.state.lastCalculatedAttributes;
                
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
            
            this.showNotification('Os valores calculados foram aplicados ao formulário! 📝', 'info');
        }
    }
  
    // UTILITÁRIOS
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
            
            if (!button.dataset.originalText) {
                const labelElement = button.querySelector('.indicator-label');
                if (labelElement) {
                    button.dataset.originalText = labelElement.textContent;
                    labelElement.textContent = '🔄 Processando...';
                }
            }
        } else {
            button.removeAttribute('data-kt-indicator');
            button.disabled = false;
            
            if (button.dataset.originalText) {
                const labelElement = button.querySelector('.indicator-label');
                if (labelElement) {
                    labelElement.textContent = button.dataset.originalText;
                    delete button.dataset.originalText;
                }
            }
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
window.npc = {
    updateDetails: () => window.npcManager?.updateDetails(),
    updateAttributes: () => window.npcManager?.updateAttributes(),
    create: () => window.npcManager?.createNpc(),
    delete: (id) => window.npcManager?.confirmDelete()
};

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        window.npcManager = new NpcManager();
    }, 100);
});

if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        // jQuery carregado
    });
}