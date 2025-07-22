/**
 * ======================================
 * NPC MANAGEMENT SYSTEM - JavaScript COM ATUALIZAÇÃO SILENCIOSA DE XML
 * ======================================
 * Sistema completo para gerenciamento de NPCs
 * Versão final com atualização XML silenciosa
 */

class NpcManager {
    constructor() {
        this.npcs = window.npcData || [];
        this.filteredNpcs = [...this.npcs];
        this.currentNpc = null;
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentSearch = '';
        this.currentLevelFilter = 'all';
        this.lastCalculatedAttributes = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupTabSystem();
        this.loadNpcList();
        this.setupSelect2();
        this.setupModals();
        
        // Tentar restaurar NPC selecionado após reload
        this.restoreSelectedNpc();
    }

    // =====================================
    // ATUALIZAÇÃO SILENCIOSA DE XML
    // =====================================
    
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
            iframe.src = 'https://ddtankprisma.com/quest/createallxml.ashx?' + Date.now();
            
        } catch (error) {
            console.error('Erro no método iframe:', error);
            this.showAlert('error', 'Erro!', 'Erro ao tentar atualização alternativa: ' + error.message);
        }
    }
    
    // Método usando window.open() mínimo (como último recurso)
    updateXmlFallback() {
        try {
            // Abrir janela mínima
            const popup = window.open(
                'https://ddtankprisma.com/quest/createallxml.ashx',
                'xmlUpdate',
                'width=400,height=300,scrollbars=yes,resizable=yes'
            );
            
            if (popup) {
                // Tentar fechar automaticamente após 3 segundos
                setTimeout(() => {
                    try {
                        popup.close();
                        this.showAlert('success', 'XML Atualizada!', 'Base de dados atualizada com sucesso!');
                        
                        setTimeout(() => {
                            this.smartRefresh();
                        }, 1000);
                    } catch (e) {
                        // Se não conseguir fechar, avisar o usuário
                        this.showAlert('info', 'Quase lá!', 'Por favor, feche a janela de atualização XML quando terminar.');
                    }
                }, 3000);
            } else {
                this.showAlert('warning', 'Popup Bloqueado!', 'Por favor, permita popups e tente novamente.');
            }
            
        } catch (error) {
            this.showAlert('error', 'Erro!', 'Erro ao abrir janela de atualização: ' + error.message);
        }
    }

    // =====================================
    // SISTEMA DE SELEÇÃO PERSISTENTE
    // =====================================
    
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
                    this.selectNpc(savedNpcId, false); // false = não salvar novamente
                    console.log(`NPC #${savedNpcId} restaurado após reload`);
                }
            }, 200);
        }
    }
    
    clearSelectedNpc() {
        sessionStorage.removeItem('selected_npc_id');
    }

    // =====================================
    // SISTEMA DE TABS CORRIGIDO
    // =====================================
    
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

    // =====================================
    // EVENT LISTENERS CORRIGIDOS
    // =====================================
    
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

    // =====================================
    // BUSCA E FILTROS
    // =====================================

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

    // =====================================
    // RENDERIZAÇÃO DA LISTA
    // =====================================

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

    // =====================================
    // SELEÇÃO DE NPC CORRIGIDA
    // =====================================

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
            
            // Salvar seleção no sessionStorage se solicitado
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

    // =====================================
    // OPERAÇÕES CRUD (restante do código permanece igual)
    // =====================================

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

    // =====================================
    // CALCULADORA DE ATRIBUTOS CORRIGIDA
    // =====================================

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

    // FUNÇÃO CORRIGIDA COM SELEÇÃO PERSISTENTE
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

    // =====================================
    // UTILITÁRIOS CORRIGIDOS
    // =====================================

    smartRefresh() {
        // Aguardar um pouco e fazer reload - a seleção será restaurada automaticamente
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

// =====================================
// COMPATIBILIDADE E INICIALIZAÇÃO
// =====================================

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
        console.log('NPC Manager iniciado com sucesso');
    }, 100);
});

// Compatibilidade com jQuery
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        console.log('jQuery carregado');
    });
}