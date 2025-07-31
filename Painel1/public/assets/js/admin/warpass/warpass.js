window.WarPass = {
    // Configuration
    config: {
        baseUrl: '/admin/game/warpass',
        levelsPerPage: 5,
        currentPage: 1,
        totalLevels: 0,
        allLevels: []
    },

    // Cache de nomes de itens
    itemNamesCache: new Map(),
    nameLoadingQueue: new Set(),

    //images
    imageSystem: {
        cache: new Map(),
        loadingQueue: new Set(),
        observer: null,
        stats: {cacheHits: 0, cacheMisses: 0, loadedImages: 0, failedImages: 0},

        init: function() {
            if (typeof IntersectionObserver !== 'undefined') {
                this.observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.loadImageAsync(entry.target);
                            this.observer.unobserve(entry.target);
                        }
                    });
                }, { rootMargin: '50px', threshold: 0.1 });
            }
            
            setInterval(() => {
                if (this.cache.size > 500) {
                    this.cache.clear();
                }
            }, 300000);
        },

        createOptimizedImage: function(templateId, itemName = '', size = '32px') {
            const uniqueId = `warpass-img-${templateId}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            
            return {
                id: uniqueId,
                html: `
                    <div class="warpass-image-container" 
                         data-template-id="${templateId}" 
                         data-unique-id="${uniqueId}"
                         style="width: ${size}; height: ${size}; border-radius: 6px; overflow: hidden; background: #f8fafc; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center;">
                        <div class="image-loading-state" style="
                            width: 100%; 
                            height: 100%; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center;
                            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                        ">
                            <i class="fas fa-spinner fa-spin" style="color: #94a3b8; font-size: 12px;"></i>
                        </div>
                    </div>
                `
            };
        },

        loadImageAsync: function(containerElement) {
            const templateId = parseInt(containerElement.dataset.templateId);
            const uniqueId = containerElement.dataset.uniqueId;
            
            if (!templateId || templateId === 0) {
                this.setImagePlaceholder(uniqueId);
                return;
            }
            
            if (this.loadingQueue.has(templateId)) {
                setTimeout(() => this.loadImageAsync(containerElement), 100);
                return;
            }
            
            this.loadImageWithCache(templateId, uniqueId);
        },

        async loadImageWithCache(templateId, containerId) {
            if (this.cache.has(templateId)) {
                this.stats.cacheHits++;
                const cachedData = this.cache.get(templateId);
                this.updateImageDisplay(containerId, cachedData);
                return;
            }
            
            this.loadingQueue.add(templateId);
            this.stats.cacheMisses++;
            
            try {
                const response = await fetch(`${window.WarPass.config.baseUrl}/items/info?template_id=${templateId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    const itemData = {
                        icon: result.data.Icon,
                        name: result.data.Name,
                        id: result.data.TemplateID
                    };
                    
                    this.cache.set(templateId, itemData);
                    this.updateImageDisplay(containerId, itemData);
                    this.stats.loadedImages++;
                } else {
                    this.cache.set(templateId, null);
                    this.setImageError(containerId);
                    this.stats.failedImages++;
                }
                
            } catch (error) {
                this.setImageError(containerId);
                this.stats.failedImages++;
            } finally {
                this.loadingQueue.delete(templateId);
            }
        },

        updateImageDisplay: function(containerId, itemData) {
            const container = document.querySelector(`[data-unique-id="${containerId}"]`);
            if (!container) return;
            
            if (itemData && itemData.icon) {
                container.innerHTML = `
                    <img src="${itemData.icon}" 
                         alt=""
                         class="warpass-item-image-optimized"
                         style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.2s ease; opacity: 0;"
                         onload="this.style.opacity = 1"
                         onerror="this.parentNode.innerHTML='<i class=\\'fas fa-cube\\' style=\\'color: #94a3b8; font-size: 14px;\\'></i>'">
                `;
            } else {
                this.setImagePlaceholder(containerId);
            }
        },

        setImagePlaceholder: function(containerId) {
            const container = document.querySelector(`[data-unique-id="${containerId}"]`);
            if (!container) return;
            
            container.innerHTML = `
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                    <i class="fas fa-plus" style="color: #94a3b8; font-size: 12px;"></i>
                </div>
            `;
        },

        setImageError: function(containerId) {
            const container = document.querySelector(`[data-unique-id="${containerId}"]`);
            if (!container) return;
            
            container.innerHTML = `
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fef2f2; border: 1px solid #fecaca;">
                    <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 12px;"></i>
                </div>
            `;
        },

        registerForLazyLoading: function(element) {
            if (this.observer) {
                this.observer.observe(element);
            }
        },

        clearCache: function() {
            this.cache.clear();
        }
    },

    // Current editing context
    editContext: {level: null, rewardType: null, slotIndex: null},
    currentEditingReward: null,

   //reward names
    async loadItemName(templateId, nameElementId) {
        if (!templateId || templateId === 0) {
            return;
        }

        if (this.itemNamesCache.has(templateId)) {
            const itemData = this.itemNamesCache.get(templateId);
            this.updateItemName(nameElementId, itemData);
            return;
        }

        if (this.nameLoadingQueue.has(templateId)) {
            return;
        }

        this.nameLoadingQueue.add(templateId);

        try {
            const response = await fetch(`${this.config.baseUrl}/items/info?template_id=${templateId}`);
            const result = await response.json();

            if (result.success && result.data) {
                const itemData = {
                    name: result.data.Name,
                    id: result.data.TemplateID
                };
                
                this.itemNamesCache.set(templateId, itemData);
                this.updateItemName(nameElementId, itemData);
            } else {
                const fallback = { name: `Item ${templateId}`, id: templateId };
                this.itemNamesCache.set(templateId, fallback);
                this.updateItemName(nameElementId, fallback);
            }
        } catch (error) {
            const fallback = { name: `Item ${templateId}`, id: templateId };
            this.updateItemName(nameElementId, fallback);
        } finally {
            this.nameLoadingQueue.delete(templateId);
        }
    },

    updateItemName(elementId, itemData) {
        const element = document.getElementById(elementId);
        if (element && itemData) {
            element.textContent = itemData.name;
            element.removeAttribute('title');
        }
    },

    clearNamesCache() {
        this.itemNamesCache.clear();
    },

   //initial
    
    init() {
        if (typeof $ === 'undefined' || typeof bootstrap === 'undefined') {
            return;
        }
        
        this.imageSystem.init();
        this.bindEvents();
        this.setupNavigation();
        this.loadLevels().then(() => {
            this.initializeSelect2();
        }).catch(() => {
            this.initializeSelect2();
        });

        this.initTooltipRemoval();
    },

    initTooltipRemoval() {
        const removeTooltips = () => {
            document.querySelectorAll('*[title]').forEach(el => {
                el.removeAttribute('title');
            });
            
            document.querySelectorAll('img[alt]').forEach(el => {
                el.removeAttribute('alt');
            });
        };

        setInterval(removeTooltips, 2000);
        removeTooltips();
    },

    //navegation
    setupNavigation() {
        document.getElementById('premios-tab')?.addEventListener('click', () => {
            this.switchToSection('premios');
        });

        document.getElementById('missoes-tab')?.addEventListener('click', () => {
            this.switchToSection('missoes');
        });

        document.getElementById('shop-tab')?.addEventListener('click', () => {
            this.switchToSection('shop');
        });

        document.getElementById('config-tab')?.addEventListener('click', () => {
            this.switchToSection('config');
        });
    },

    switchToSection(section) {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });

        document.getElementById('not_selected')?.style.setProperty('display', 'block');
        document.getElementById('premios_panel')?.style.setProperty('display', 'none');

        if (section === 'premios') {
            document.getElementById('premios-tab')?.classList.add('active');
            document.getElementById('not_selected')?.style.setProperty('display', 'none');
            document.getElementById('premios_panel')?.style.setProperty('display', 'block');
        }
    },

   //events
    bindEvents() {
        document.getElementById('prev-levels')?.addEventListener('click', () => this.previousPage());
        document.getElementById('next-levels')?.addEventListener('click', () => this.nextPage());
        
        document.getElementById('saveRewardBtn')?.addEventListener('click', () => this.saveReward());
        document.getElementById('clearRewardBtn')?.addEventListener('click', () => this.clearReward());
        document.getElementById('addLevelBtn')?.addEventListener('click', () => this.addLevel());
        document.getElementById('add-level-btn')?.addEventListener('click', () => this.showAddLevelModal());

        document.getElementById('item-search')?.addEventListener('change', () => this.onItemSelected());
    },

   //iniciar select2
    initializeSelect2() {
        const newSelects = $('.item-search-new');
        if (newSelects.length > 0) {
            newSelects.each(function() {
                $(this).select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Digite o nome ou ID do item...',
                    allowClear: true,
                    minimumInputLength: 2,
                    dropdownParent: $('#addLevelModal'),
                    ajax: {
                        url: `${window.WarPass.config.baseUrl}/items/search`,
                        dataType: 'json',
                        delay: 300,
                        data: function (params) {
                            return {search: params.term};
                        },
                        processResults: function (data) {
                            if (data.success && data.items) {
                                return {
                                    results: data.items.map(item => ({
                                        id: item.id,
                                        text: item.text,
                                        data: item.data
                                    }))
                                };
                            }
                            return { results: [] };
                        },
                        cache: true
                    },
                    templateResult: window.WarPass.formatItemResult,
                    templateSelection: window.WarPass.formatItemSelection
                });
            });
        }
    },

    initializeEditModalSelect2() {
        const itemSearch = $('#item-search');
        if (itemSearch.hasClass('select2-hidden-accessible')) {
            itemSearch.select2('destroy');
        }
        
        itemSearch.select2({
            theme: 'bootstrap-5',
            placeholder: 'Digite o nome ou ID do item...',
            allowClear: true,
            minimumInputLength: 2,
            dropdownParent: $('#editRewardModal'),
            width: '100%',
            ajax: {
                url: `${this.config.baseUrl}/items/search`,
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {search: params.term};
                },
                processResults: function (data) {
                    if (data.success && data.items) {
                        return {
                            results: data.items.map(item => ({
                                id: item.id,
                                text: item.text,
                                data: item.data
                            }))
                        };
                    }
                    return { results: [] };
                },
                cache: true
            },
            templateResult: this.formatItemResult,
            templateSelection: this.formatItemSelection
        });
        
        itemSearch.off('change.warpass').on('change.warpass', () => {
            this.onItemSelected();
        });
        
        setTimeout(() => {
            if (this.currentEditingReward && this.currentEditingReward.TemplateId && this.currentEditingReward.TemplateId > 0) {
                this.setSelect2Value(this.currentEditingReward.TemplateId);
            } else {
                itemSearch.val(null).trigger('change');
                this.hideItemPreview();
            }
        }, 100);
    },

  //select2
    formatItemResult(item) {
        if (item.loading) {
            return item.text;
        }

        if (!item.data || !item.data.Icon) {
            return $(`<div class="select2-item">
                <span>${item.text}</span>
            </div>`);
        }

        const imageData = window.WarPass.imageSystem.createOptimizedImage(item.id, item.text, '32px');

        const $result = $(`<div class="select2-item">
            ${imageData.html}
            <span style="margin-left: 10px;">${item.text}</span>
        </div>`);

        setTimeout(() => {
            const container = $result.find('.warpass-image-container')[0];
            if (container) {
                window.WarPass.imageSystem.registerForLazyLoading(container);
            }
        }, 10);

        return $result;
    },

    formatItemSelection(item) {
        return item.text || item.id;
    },

    //puxar dados
    async loadLevels() {
        try {
            this.showLoading();
            
            const response = await fetch(`${this.config.baseUrl}/data`);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Erro ao carregar níveis');
            }

            this.config.allLevels = result.data || [];
            this.config.totalLevels = this.config.allLevels.length;
            this.config.currentPage = 1;

            this.renderLevels();
            this.hideLoading();
            
            if (this.itemNamesCache.size > 200) {
                this.clearNamesCache();
            }

        } catch (error) {
            this.showError(error.message);
            throw error;
        }
    },

    showLoading() {
        document.getElementById('loading-state')?.classList.remove('d-none');
        document.getElementById('error-state')?.classList.add('d-none');
        document.getElementById('levels-container')?.classList.add('d-none');
    },

    hideLoading() {
        document.getElementById('loading-state')?.classList.add('d-none');
        document.getElementById('error-state')?.classList.add('d-none');
        document.getElementById('levels-container')?.classList.remove('d-none');
    },

    showError(message) {
        document.getElementById('loading-state')?.classList.add('d-none');
        document.getElementById('levels-container')?.classList.add('d-none');
        
        const errorState = document.getElementById('error-state');
        const errorMessage = document.getElementById('error-message');
        
        if (errorState && errorMessage) {
            errorMessage.textContent = message;
            errorState.classList.remove('d-none');
        }
    },

   //render items
    renderLevels() {
        const levelsRow = document.getElementById('levels-row');
        if (!levelsRow) return;

        const startIndex = (this.config.currentPage - 1) * this.config.levelsPerPage;
        const endIndex = Math.min(startIndex + this.config.levelsPerPage, this.config.totalLevels);
        const currentLevels = this.config.allLevels.slice(startIndex, endIndex);

        levelsRow.innerHTML = '';

        currentLevels.forEach(level => {
            const levelCard = this.createLevelCard(level);
            levelsRow.appendChild(levelCard);
        });

        this.updateNavigation();
        this.updatePageInfo();

        setTimeout(() => {
            document.querySelectorAll('.warpass-image-container').forEach(container => {
                this.imageSystem.registerForLazyLoading(container);
            });
        }, 100);

        setTimeout(() => {
            document.querySelectorAll('.item-name').forEach(element => {
                const matches = element.id.match(/name-(\d+)-(\w+)-(\d+)/);
                if (matches) {
                    const level = parseInt(matches[1]);
                    const type = matches[2];
                    const slot = parseInt(matches[3]);
                    
                    const levelData = this.config.allLevels.find(l => l.Level === level);
                    if (levelData) {
                        let reward;
                        if (type === 'normal') {
                            reward = this.parseReward(levelData.NormalAward);
                        } else {
                            const extraRewards = this.parseExtraRewards(levelData.ExtraAward);
                            reward = extraRewards[slot];
                        }
                        
                        if (reward && reward.TemplateId && reward.TemplateId > 0) {
                            this.loadItemName(reward.TemplateId, element.id);
                        }
                    }
                }
            });
        }, 300);
    },

    createLevelCard(level) {
        const levelDiv = document.createElement('div');
        levelDiv.className = 'level-card';

        const normalReward = this.parseReward(level.NormalAward);
        const extraRewards = this.parseExtraRewards(level.ExtraAward);

        levelDiv.innerHTML = `
            <div class="level-header">
                <div class="level-number">Nível ${level.Level}</div>
                <div class="level-actions">
                    <button class="btn-level-delete" onclick="window.WarPass.deleteLevel(${level.Level})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="level-content">
                <div class="reward-section normal-section">
                    <div class="reward-label">🎁 FREE</div>
                    <div class="reward-slot" data-level="${level.Level}" data-type="normal" data-slot="0">
                        ${this.renderRewardSlot(normalReward, level.Level, 'normal', 0)}
                    </div>
                </div>

                <div class="reward-section vip-section">
                    <div class="reward-label vip-label">👑 VIP</div>
                    <div class="vip-rewards">
                        <div class="reward-slot" data-level="${level.Level}" data-type="extra" data-slot="0">
                            ${this.renderRewardSlot(extraRewards[0] || {}, level.Level, 'extra', 0)}
                        </div>
                        <div class="reward-slot" data-level="${level.Level}" data-type="extra" data-slot="1">
                            ${this.renderRewardSlot(extraRewards[1] || {}, level.Level, 'extra', 1)}
                        </div>
                    </div>
                </div>
            </div>
        `;

        return levelDiv;
    },

    renderRewardSlot(reward, level, type, slot) {
        const isEmpty = !reward || reward.IsEmpty;
        const nameId = `name-${level}-${type}-${slot}`;
        
        if (isEmpty) {
            return `
                <div class="slot-content empty-slot" onclick="window.WarPass.editReward(${level}, '${type}', ${slot})">
                    <div class="slot-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="slot-overlay">
                        <i class="fas fa-edit"></i>
                    </div>
                </div>
            `;
        }

        const imageData = this.imageSystem.createOptimizedImage(reward.TemplateId, '', '32px');
        const validityText = reward.ValidDate === 0 ? 'Perm' : `${reward.ValidDate}d`;
        
        return `
            <div class="slot-content filled-slot" onclick="window.WarPass.editReward(${level}, '${type}', ${slot})">
                <div class="slot-icon">
                    ${imageData.html}
                </div>
                <div class="slot-details">
                    <div class="item-name" id="${nameId}">
                        <i class="fas fa-spinner fa-spin" style="font-size: 10px; color: #94a3b8;"></i>
                    </div>
                    <div class="item-stats">
                        <span class="item-count">×${reward.Count}</span>
                        <span class="item-validity">${validityText}</span>
                    </div>
                </div>
                <div class="slot-overlay">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="slot-actions">
                    <button class="btn-slot-delete" onclick="event.stopPropagation(); window.WarPass.clearRewardDirect(${level}, '${type}', ${slot})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    },

    //parsing
    
    parseReward(rewardString) {
        if (!rewardString || rewardString.trim() === '') {
            return { TemplateId: 0, Count: 0, ValidDate: 0, IsEmpty: true };
        }

        const parts = rewardString.split(',');
        if (parts.length >= 3) {
            const templateId = parseInt(parts[0]) || 0;
            const count = parseInt(parts[1]) || 0;
            const days = parseInt(parts[2]) || 0;

            return {
                TemplateId: templateId,
                Count: count,
                ValidDate: days,
                IsEmpty: templateId === 0 && count === 0
            };
        }

        return { TemplateId: 0, Count: 0, ValidDate: 0, IsEmpty: true };
    },

    parseExtraRewards(extraString) {
        if (!extraString || extraString.trim() === '') {
            return [{}, {}];
        }

        const slots = extraString.split('|');
        const rewards = [];

        for (let i = 0; i < 2; i++) {
            if (i < slots.length) {
                rewards.push(this.parseReward(slots[i]));
            } else {
                rewards.push({ TemplateId: 0, Count: 0, ValidDate: 0, IsEmpty: true });
            }
        }

        return rewards;
    },

    //editar reward
    
    editReward(level, type, slot) {
        const modalElement = document.getElementById('editRewardModal');
        if (!modalElement) {
            alert('Modal de edição não encontrado. Verifique se o HTML está correto.');
            return;
        }
        
        this.editContext = { level, rewardType: type, slotIndex: slot };
        
        const levelData = this.config.allLevels.find(l => l.Level === level);
        if (!levelData) {
            return;
        }

        let currentReward = { TemplateId: 0, Count: 0, ValidDate: 0 };
        
        if (type === 'normal') {
            currentReward = this.parseReward(levelData.NormalAward);
        } else {
            const extraRewards = this.parseExtraRewards(levelData.ExtraAward);
            currentReward = extraRewards[slot] || currentReward;
        }

        const levelInput = document.getElementById('edit-level');
        const typeInput = document.getElementById('edit-reward-type');
        const slotInput = document.getElementById('edit-reward-slot');
        const countInput = document.getElementById('reward_count');
        const daysInput = document.getElementById('reward_days');
        const titleElement = document.getElementById('modal-reward-title');
        
        if (!levelInput || !typeInput || !slotInput || !countInput || !daysInput) {
            alert('Elementos do formulário não encontrados. Verifique o HTML do modal.');
            return;
        }
        
        levelInput.value = level;
        typeInput.value = type;
        slotInput.value = slot;
        countInput.value = currentReward.Count || 0;
        daysInput.value = currentReward.ValidDate || 0;
        
        if (titleElement) {
            titleElement.textContent = 
                `Editar ${type === 'normal' ? 'Prêmio FREE' : `Prêmio VIP ${slot + 1}`} - Nível ${level}`;
        }

        this.currentEditingReward = currentReward;

        try {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } catch (error) {
            alert('Erro ao abrir modal. Verifique se Bootstrap está carregado.');
        }
    },

    async setSelect2Value(templateId) {
        try {
            const response = await fetch(`${this.config.baseUrl}/items/info?template_id=${templateId}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                const option = new Option(
                    `[${result.data.TemplateID}] ${result.data.Name}`,
                    result.data.TemplateID,
                    true,
                    true
                );
                $('#item-search').append(option).trigger('change');
                
                this.updateItemPreview(result.data);
            }
        } catch (error) {
            // Falha silenciosa
        }
    },

    onItemSelected() {
        const selectedData = $('#item-search').select2('data')[0];
        if (selectedData && selectedData.data) {
            this.updateItemPreview(selectedData.data);
        } else {
            this.hideItemPreview();
        }
    },

    updateItemPreview(itemData) {
        const preview = document.getElementById('item-preview');
        const icon = document.getElementById('preview-icon');
        const name = document.getElementById('preview-name');
        const details = document.getElementById('preview-details');
        
        if (preview && icon && name && details) {
            icon.src = itemData.Icon || '';
            icon.removeAttribute('title');
            icon.removeAttribute('alt');
            name.textContent = itemData.Name || `Item ${itemData.TemplateID}`;
            details.textContent = `ID: ${itemData.TemplateID} | Categoria: ${itemData.CategoryID || 'N/A'}`;
            preview.style.display = 'block';
        }
    },

    hideItemPreview() {
        const preview = document.getElementById('item-preview');
        if (preview) {
            preview.style.display = 'none';
        }
    },

   //salvar dados
    
    async saveReward() {
        try {
            const level = this.editContext.level;
            const type = this.editContext.rewardType;
            const slot = this.editContext.slotIndex;
            
            const selectedItem = $('#item-search').select2('data')[0];
            const templateId = selectedItem ? selectedItem.id : 0;
            const count = document.getElementById('reward_count').value || 0;
            const days = document.getElementById('reward_days').value || 0;

            const saveBtn = document.getElementById('saveRewardBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
            saveBtn.disabled = true;

            const formData = new FormData();
            
            if (type === 'normal') {
                formData.append('normal_template', templateId);
                formData.append('normal_count', count);
                formData.append('normal_days', days);
            } else {
                const levelData = this.config.allLevels.find(l => l.Level === level);
                const extraRewards = this.parseExtraRewards(levelData.ExtraAward);
                
                if (slot === 0) {
                    formData.append('extra_slot1_template', templateId);
                    formData.append('extra_slot1_count', count);
                    formData.append('extra_slot1_days', days);
                    
                    const slot2 = extraRewards[1] || {};
                    formData.append('extra_slot2_template', slot2.TemplateId || 0);
                    formData.append('extra_slot2_count', slot2.Count || 0);
                    formData.append('extra_slot2_days', slot2.ValidDate || 0);
                } else {
                    const slot1 = extraRewards[0] || {};
                    formData.append('extra_slot1_template', slot1.TemplateId || 0);
                    formData.append('extra_slot1_count', slot1.Count || 0);
                    formData.append('extra_slot1_days', slot1.ValidDate || 0);
                    
                    formData.append('extra_slot2_template', templateId);
                    formData.append('extra_slot2_count', count);
                    formData.append('extra_slot2_days', days);
                }
            }

            const response = await fetch(`${this.config.baseUrl}/update/${level}`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Erro ao salvar alterações');
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('editRewardModal'));
            modal.hide();

            await this.loadLevels();
            this.showAlert('Recompensa atualizada com sucesso!', 'success');

        } catch (error) {
            this.showAlert('Erro ao salvar alterações: ' + error.message, 'error');
        } finally {
            const saveBtn = document.getElementById('saveRewardBtn');
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Salvar';
            saveBtn.disabled = false;
        }
    },

    clearReward() {
        $('#item-search').val(null).trigger('change');
        document.getElementById('reward_count').value = 0;
        document.getElementById('reward_days').value = 0;
        this.hideItemPreview();
        
        this.saveReward();
    },

    async clearRewardDirect(level, type, slot) {
        if (!confirm('Tem certeza que deseja limpar esta recompensa?')) {
            return;
        }

        try {
            const formData = new FormData();
            
            if (type === 'normal') {
                formData.append('normal_template', 0);
                formData.append('normal_count', 0);
                formData.append('normal_days', 0);
            } else {
                const levelData = this.config.allLevels.find(l => l.Level === level);
                const extraRewards = this.parseExtraRewards(levelData.ExtraAward);
                
                if (slot === 0) {
                    formData.append('extra_slot1_template', 0);
                    formData.append('extra_slot1_count', 0);
                    formData.append('extra_slot1_days', 0);
                    
                    const slot2 = extraRewards[1] || {};
                    formData.append('extra_slot2_template', slot2.TemplateId || 0);
                    formData.append('extra_slot2_count', slot2.Count || 0);
                    formData.append('extra_slot2_days', slot2.ValidDate || 0);
                } else {
                    const slot1 = extraRewards[0] || {};
                    formData.append('extra_slot1_template', slot1.TemplateId || 0);
                    formData.append('extra_slot1_count', slot1.Count || 0);
                    formData.append('extra_slot1_days', slot1.ValidDate || 0);
                    
                    formData.append('extra_slot2_template', 0);
                    formData.append('extra_slot2_count', 0);
                    formData.append('extra_slot2_days', 0);
                }
            }

            const response = await fetch(`${this.config.baseUrl}/update/${level}`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Erro ao limpar recompensa');
            }

            await this.loadLevels();
            this.showAlert('Recompensa removida com sucesso!', 'success');

        } catch (error) {
            this.showAlert('Erro ao limpar recompensa: ' + error.message, 'error');
        }
    },

   //add nivel
    
    showAddLevelModal() {
        const modalElement = document.getElementById('addLevelModal');
        if (!modalElement) {
            alert('Modal de adicionar nível não encontrado. Verifique se o HTML está correto.');
            return;
        }
        
        const form = document.getElementById('addLevelForm');
        if (!form) {
            alert('Formulário não encontrado. Verifique se o HTML está correto.');
            return;
        }
        
        form.reset();
        
        const select2Elements = $('.item-search-new');
        if (select2Elements.length > 0) {
            select2Elements.val(null).trigger('change');
        }
        
        const newLevelInput = document.getElementById('new_level');
        if (newLevelInput) {
            const maxLevel = Math.max(...this.config.allLevels.map(l => l.Level), 0);
            newLevelInput.value = maxLevel + 1;
        }

        try {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } catch (error) {
            alert('Erro ao abrir modal. Verifique se Bootstrap está carregado.');
        }
    },

    async addLevel() {
        try {
            const form = document.getElementById('addLevelForm');
            const formData = new FormData(form);

            const normalItem = $('#normal-item-search').select2('data')[0];
            const extra1Item = $('#extra1-item-search').select2('data')[0];
            const extra2Item = $('#extra2-item-search').select2('data')[0];

            formData.set('normal_template', normalItem ? normalItem.id : 0);
            formData.set('extra_slot1_template', extra1Item ? extra1Item.id : 0);
            formData.set('extra_slot2_template', extra2Item ? extra2Item.id : 0);

            const levelNumber = parseInt(formData.get('Level'));
            if (!levelNumber || levelNumber < 1) {
                this.showAlert('Número do nível deve ser maior que 0!', 'error');
                return;
            }

            if (this.config.allLevels.some(l => l.Level === levelNumber)) {
                this.showAlert('Este nível já existe!', 'error');
                return;
            }

            const addBtn = document.getElementById('addLevelBtn');
            const originalText = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Criando...';
            addBtn.disabled = true;

            const response = await fetch(`${this.config.baseUrl}/store`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Erro ao criar nível');
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('addLevelModal'));
            modal.hide();

            await this.loadLevels();
            this.showAlert('Nível criado com sucesso!', 'success');

        } catch (error) {
            this.showAlert('Erro ao criar nível: ' + error.message, 'error');
        } finally {
            const addBtn = document.getElementById('addLevelBtn');
            addBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Criar Nível';
            addBtn.disabled = false;
        }
    },

   //EXCLUIR NIVEL
    
    async deleteLevel(levelNumber) {
        if (!confirm(`Tem certeza que deseja excluir o Nível ${levelNumber}?\n\nEsta ação não pode ser desfeita!`)) {
            return;
        }

        try {
            const response = await fetch(`${this.config.baseUrl}/delete/${levelNumber}`, {
                method: 'POST'
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Erro ao excluir nível');
            }

            await this.loadLevels();
            this.showAlert('Nível excluído com sucesso!', 'success');

        } catch (error) {
            this.showAlert('Erro ao excluir nível: ' + error.message, 'error');
        }
    },

  //TABS
    
    updateNavigation() {
        const prevBtn = document.getElementById('prev-levels');
        const nextBtn = document.getElementById('next-levels');
        
        if (prevBtn) {
            prevBtn.disabled = this.config.currentPage <= 1;
        }
        
        if (nextBtn) {
            const maxPage = Math.ceil(this.config.totalLevels / this.config.levelsPerPage);
            nextBtn.disabled = this.config.currentPage >= maxPage;
        }
    },

    updatePageInfo() {
        const startLevel = (this.config.currentPage - 1) * this.config.levelsPerPage + 1;
        const endLevel = Math.min(this.config.currentPage * this.config.levelsPerPage, this.config.totalLevels);
        
        const pageInfo = document.getElementById('page-info');
        const totalInfo = document.getElementById('total-info');
        
        if (pageInfo) {
            if (this.config.totalLevels === 0) {
                pageInfo.textContent = 'Nenhum nível';
            } else {
                pageInfo.textContent = `Níveis ${startLevel}-${endLevel}`;
            }
        }
        
        if (totalInfo) {
            totalInfo.textContent = `Total: ${this.config.totalLevels} níveis`;
        }
    },

    previousPage() {
        if (this.config.currentPage > 1) {
            this.config.currentPage--;
            this.renderLevels();
        }
    },

    nextPage() {
        const maxPage = Math.ceil(this.config.totalLevels / this.config.levelsPerPage);
        if (this.config.currentPage < maxPage) {
            this.config.currentPage++;
            this.renderLevels();
        }
    },

  // Utilitarios
    
    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
};

const WarPass = window.WarPass;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
        return;
    }
    
    setTimeout(() => {
        window.WarPass.init();
        window.WarPass.switchToSection('premios');

        $('#editRewardModal').on('shown.bs.modal', function () {
            window.WarPass.initializeEditModalSelect2();
        });
        
        $('#addLevelModal').on('shown.bs.modal', function () {
            $('.item-search-new').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });
            window.WarPass.initializeSelect2();
        });
    }, 100);
});

document.addEventListener('visibilitychange', function() {
    if (!document.hidden && window.WarPass.config.allLevels.length === 0) {
        window.WarPass.loadLevels();
    }
});