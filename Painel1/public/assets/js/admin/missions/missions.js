const mission = {
    state: {
        missions: [],
        currentMission: null,
        currentRewards: [],
        selectedItem: null,
        filteredMissions: []
    },

    // Nomes das missões por tipo
    missionNames: {
        1: "Recompensas por Aumentar de nível",
        2: "Vitória no PvP",
        3: "Consumir Cupons - Acumulado",
        4: "Força de combate",
        5: "Recarga de cupons - Acumulado",
        6: "Recarga de cupons - Diária",
        7: "Vitória Gvg",
        8: "Tempo Online",
        9: "Consumir Cupons - Diária",
    },

    // Inicialização
    init: function() {
        this.loadMissions();
        this.initNotifications();
        this.initFilters();
        this.createSimpleDeleteModal();
    },

    // Criar modal de confirmação simples
    createSimpleDeleteModal: function() {
        if (document.getElementById('simple-delete-modal')) {
            return;
        }

        const modalHtml = `
            <div id="simple-delete-modal" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                opacity: 0;
                transition: opacity 0.3s ease;
            ">
                <div style="
                    background: white;
                    border-radius: 8px;
                    padding: 30px;
                    max-width: 400px;
                    width: 90%;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                    text-align: center;
                    transform: scale(0.9);
                    transition: transform 0.3s ease;
                ">
                    <div style="
                        width: 60px;
                        height: 60px;
                        background: #f8f9fa;
                        border-radius: 50%;
                        margin: 0 auto 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </div>

                    <h3 id="delete-modal-title" style="
                        color: #dc3545;
                        font-size: 18px;
                        font-weight: 600;
                        margin-bottom: 15px;
                        font-family: 'Segoe UI', sans-serif;
                    ">Deletar Item</h3>

                    <p id="delete-modal-message" style="
                        color: #6c757d;
                        font-size: 14px;
                        line-height: 1.5;
                        margin-bottom: 20px;
                        font-family: 'Segoe UI', sans-serif;
                    ">
                        Tem certeza que deseja deletar este item?
                    </p>

                    <div style="color: #ffc107; font-size: 13px; margin-bottom: 25px;">
                        ⚠️ Esta ação não pode ser desfeita!
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button id="cancel-delete-btn" style="
                            background: #6c757d;
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 6px;
                            font-size: 14px;
                            cursor: pointer;
                            transition: background 0.2s ease;
                        ">Cancelar</button>
                        
                        <button id="confirm-delete-btn" style="
                            background: #dc3545;
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 6px;
                            font-size: 14px;
                            cursor: pointer;
                            transition: background 0.2s ease;
                        ">Sim, Deletar</button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Event listeners
        document.getElementById('cancel-delete-btn').addEventListener('click', () => {
            this.hideSimpleDeleteModal();
        });

        document.getElementById('simple-delete-modal').addEventListener('click', (e) => {
            if (e.target.id === 'simple-delete-modal') {
                this.hideSimpleDeleteModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('simple-delete-modal').style.display === 'flex') {
                this.hideSimpleDeleteModal();
            }
        });
    },

    // Mostrar modal simples
    showSimpleDeleteModal: function(title, message, onConfirm) {
        const modal = document.getElementById('simple-delete-modal');
        const modalContent = modal.querySelector('div');
        
        document.getElementById('delete-modal-title').textContent = title;
        document.getElementById('delete-modal-message').innerHTML = message;
        
        // Remover listener anterior e adicionar novo
        const confirmBtn = document.getElementById('confirm-delete-btn');
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        newConfirmBtn.addEventListener('click', () => {
            this.hideSimpleDeleteModal();
            onConfirm();
        });
        
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    },

    // Esconder modal simples
    hideSimpleDeleteModal: function() {
        const modal = document.getElementById('simple-delete-modal');
        const modalContent = modal.querySelector('div');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.9)';
        
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    },

    // Inicializar filtros
    initFilters: function() {
        const self = this;
        
        $('#mission_search').on('input', function() {
            self.applyFilters();
        });

        $('select[name="missionType_filter"]').on('change', function() {
            self.applyFilters();
        });

        $('select[name="limit"]').on('change', function() {
            self.applyFilters();
        });
    },

    // Aplicar filtros
    applyFilters: function() {
        const self = this;
        const searchTerm = $('#mission_search').val().toLowerCase();
        const typeFilter = $('select[name="missionType_filter"]').val();
        const limit = parseInt($('select[name="limit"]').val()) || 10;

        let filtered = this.state.missions.filter(function(mission) {
            if (typeFilter !== 'all' && mission.ActivityType != typeFilter) {
                return false;
            }

            if (searchTerm) {
                const missionName = self.getMissionName(mission.ActivityType, mission.SubActivityType).toLowerCase();
                const activityType = mission.ActivityType.toString();
                const subType = mission.SubActivityType.toString();
                const condition = mission.Condition.toString();

                return missionName.includes(searchTerm) ||
                       activityType.includes(searchTerm) ||
                       subType.includes(searchTerm) ||
                       condition.includes(searchTerm);
            }

            return true;
        });

        this.state.filteredMissions = filtered.slice(0, limit);
        this.renderMissionsList();
    },

    // Obter nome da missão
    getMissionName: function(activityType, subActivityType) {
        const baseName = this.missionNames[activityType] || 'Missão Tipo ' + activityType;
        return baseName + ' - Nível ' + subActivityType;
    },

    // Inicializar sistema de notificações
    initNotifications: function() {
        if (!document.getElementById('notifications-container')) {
            const container = document.createElement('div');
            container.id = 'notifications-container';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; pointer-events: none;';
            document.body.appendChild(container);
        }
    },

    // Mostrar notificação
    showNotification: function(message, type) {
        type = type || 'success';
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
        
        setTimeout(function() {
            notification.firstElementChild.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(function() {
            if (notification.parentElement) {
                notification.firstElementChild.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 400);
            }
        }, 4000);
    },

    // SISTEMA DE ATUALIZAÇÃO DE CAMPOS

    // Função para atualizar os campos do formulário
    forceUpdateFormFields: function(missionData) {
        const targetData = {
            ActivityType: missionData.ActivityType,
            SubActivityType: missionData.SubActivityType,
            Condition: missionData.Condition
        };
        
        this.updateFieldsStrategy1(targetData);
        
        setTimeout(() => {
            this.updateFieldsStrategy2(targetData);
        }, 100);
        
        setTimeout(() => {
            this.updateFieldsStrategy3(targetData);
        }, 300);
        
        setTimeout(() => {
            this.verifyFieldsUpdated(targetData);
        }, 500);
    },

    // Estratégia 1: Seletores múltiplos jQuery
    updateFieldsStrategy1: function(data) {
        const activitySelectors = [
            'select[name="ActivityType"]',
            '#ActivityType',
            'select[name="activityType"]',
            'select[name="activity_type"]',
            '.activity-type-select',
            '[data-field="ActivityType"]'
        ];
        
        let activityField = null;
        
        for (let selector of activitySelectors) {
            const field = $(selector);
            if (field.length > 0) {
                activityField = field;
                break;
            }
        }
        
        if (activityField) {
            activityField.val(data.ActivityType);
            activityField.trigger('change');
        }
        
        const subField = $('input[name="SubActivityType"], #SubActivityType, input[name="subActivityType"]');
        if (subField.length) {
            subField.val(data.SubActivityType);
        }
        
        const conditionField = $('input[name="Condition"], #Condition, input[name="condition"]');
        if (conditionField.length) {
            conditionField.val(data.Condition);
        }
    },

    // Estratégia 2: DOM nativo
    updateFieldsStrategy2: function(data) {
        const allSelects = document.querySelectorAll('select');
        let activitySelect = null;
        
        for (let select of allSelects) {
            const name = select.getAttribute('name');
            const id = select.getAttribute('id');
            
            if (name === 'ActivityType' || id === 'ActivityType' || 
                name === 'activityType' || id === 'activityType') {
                activitySelect = select;
                break;
            }
        }
        
        if (activitySelect) {
            activitySelect.value = data.ActivityType;
            
            ['change', 'input', 'blur', 'focus'].forEach(eventType => {
                const event = new Event(eventType, { bubbles: true });
                activitySelect.dispatchEvent(event);
            });
        }
    },

    // Estratégia 3: Select2 específico
    updateFieldsStrategy3: function(data) {
        const select2Elements = $('.select2-hidden-accessible');
        
        select2Elements.each(function(index, element) {
            const $element = $(element);
            const name = $element.attr('name');
            const id = $element.attr('id');
            
            if (name === 'ActivityType' || id === 'ActivityType') {
                try {
                    $element.val(data.ActivityType).trigger('change.select2');
                } catch (e) {}
                
                try {
                    $element.select2('val', data.ActivityType);
                } catch (e) {}
                
                try {
                    $element.val(data.ActivityType).trigger('change');
                } catch (e) {}
            }
        });
    },

    // Verificação final
    verifyFieldsUpdated: function(expectedData) {
        const activityField = $('select[name="ActivityType"], #ActivityType').first();
        
        if (!activityField.val() || activityField.val() !== expectedData.ActivityType.toString()) {
            setTimeout(() => {
                this.lastResortUpdate(expectedData);
            }, 100);
        }
    },

    // Última tentativa
    lastResortUpdate: function(data) {
        $('select').each(function() {
            const $this = $(this);
            const name = $this.attr('name');
            const id = $this.attr('id');
            const classes = $this.attr('class') || '';
            
            if (name && name.toLowerCase().includes('activity') || 
                id && id.toLowerCase().includes('activity') ||
                classes.toLowerCase().includes('activity')) {
                
                $this.val(data.ActivityType);
                $this.prop('selectedIndex', data.ActivityType - 1);
                $this.trigger('change');
            }
        });
    },

    // ATUALIZAÇÕES DINÂMICAS

    // Adicionar missão
    addMissionToList: function(missionData) {
        this.state.missions.unshift(missionData);
        this.state.filteredMissions = this.state.missions.slice();
        this.applyFilters();
        
        setTimeout(() => {
            this.selectMission(missionData.ActivityType, missionData.SubActivityType);
            
            const newMissionElement = $(`.mission-item[data-activity="${missionData.ActivityType}"][data-sub="${missionData.SubActivityType}"]`);
            if (newMissionElement.length) {
                newMissionElement[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 300);
        
        this.showNotification('Missão adicionada com sucesso! 🚀', 'success');
    },

    // Atualizar missão na lista
    updateMissionInList: function(missionData) {
        const missionIndex = this.state.missions.findIndex(m => 
            m.ActivityType === missionData.ActivityType && m.SubActivityType === missionData.SubActivityType
        );
        
        if (missionIndex !== -1) {
            this.state.missions[missionIndex] = { ...this.state.missions[missionIndex], ...missionData };
        }
        
        if (this.state.currentMission && 
            this.state.currentMission.ActivityType === missionData.ActivityType &&
            this.state.currentMission.SubActivityType === missionData.SubActivityType) {
            this.state.currentMission = { ...this.state.currentMission, ...missionData };
        }
        
        this.applyFilters();
        
        if (this.state.currentMission && 
            this.state.currentMission.ActivityType === missionData.ActivityType &&
            this.state.currentMission.SubActivityType === missionData.SubActivityType) {
            this.updateMissionDetailsDisplay(missionData);
        }
        
        this.showNotification('Missão atualizada com sucesso! ⚡', 'success');
    },

    // Atualizar exibição dos detalhes
    updateMissionDetailsDisplay: function(missionData) {
        this.forceUpdateFormFields(missionData);
        
        const detailsContainer = $('#mission_data');
        if (detailsContainer.length) {
            detailsContainer.css({
                'transition': 'all 0.3s ease',
                'transform': 'scale(1.02)',
                'box-shadow': '0 8px 30px rgba(40,167,69,0.3)'
            });
            
            setTimeout(() => {
                detailsContainer.css({
                    'transform': 'scale(1)',
                    'box-shadow': ''
                });
            }, 300);
        }
        
        const missionListItem = $(`.mission-item[data-activity="${missionData.ActivityType}"][data-sub="${missionData.SubActivityType}"]`);
        if (missionListItem.length) {
            const updatedHtml = this.renderSingleMissionItem(missionData);
            missionListItem.replaceWith(updatedHtml);
            
            setTimeout(() => {
                $(`.mission-item[data-activity="${missionData.ActivityType}"][data-sub="${missionData.SubActivityType}"]`)
                    .addClass('active')
                    .attr('data-selected', 'true');
            }, 100);
        }
    },

    // Remover missão da lista
    removeMissionFromList: function(activityType, subActivityType) {
        this.state.missions = this.state.missions.filter(m => 
            !(m.ActivityType === activityType && m.SubActivityType === subActivityType)
        );
        
        this.applyFilters();
        
        if (this.state.currentMission && 
            this.state.currentMission.ActivityType === activityType &&
            this.state.currentMission.SubActivityType === subActivityType) {
            this.state.currentMission = null;
            $('#mission_data').hide();
            $('#not_selected').show();
        }
        
        this.showNotification('Missão removida com sucesso! 🗑️', 'success');
    },

    // Renderizar item específico da missão
    renderSingleMissionItem: function(mission) {
        const missionName = this.getMissionName(mission.ActivityType, mission.SubActivityType);
        
        return `<div class="mission-item" onclick="mission.selectMission(${mission.ActivityType}, ${mission.SubActivityType})" data-activity="${mission.ActivityType}" data-sub="${mission.SubActivityType}">
            <div class="mission-content">
                <div class="mission-header">
                    <div class="mission-title">${missionName}</div>
                    <button class="mission-delete-btn" onclick="event.stopPropagation(); mission.confirmDelete(${mission.ActivityType}, ${mission.SubActivityType})" title="Excluir missão">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="mission-meta">
                    <div class="mission-badge">Type ${mission.ActivityType}</div>
                    <div class="mission-condition">Condition: ${mission.Condition}</div>
                </div>
            </div>
        </div>`;
    },

    // FUNÇÕES PRINCIPAIS
    // Carregar missões
    loadMissions: function() {
        const self = this;
        
        fetch('/admin/game/event/missions/data')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    self.state.missions = data.data.missions || [];
                    self.state.filteredMissions = self.state.missions.slice();
                    self.applyFilters();
                    $('#not_results').hide();
                }
            })
            .catch(function(error) {
                console.error('Erro ao carregar missões:', error);
                self.showNotification('Erro ao carregar missões: ' + error.message, 'error');
            });
    },

    // Renderizar lista de missões
    renderMissionsList: function() {
        const container = $('#mission_list');
        const self = this;
        
        if (this.state.filteredMissions.length === 0) {
            container.hide();
            $('#not_results').show();
            return;
        }

        let html = '';
        this.state.filteredMissions.forEach(function(mission) {
            html += self.renderSingleMissionItem(mission);
        });

        container.html(html).show();
        $('#not_results').hide();

        const totalText = this.state.filteredMissions.length === this.state.missions.length 
            ? this.state.missions.length + ' missões' 
            : this.state.filteredMissions.length + ' de ' + this.state.missions.length + ' missões';
        
        $('#mission_footer .total-count').remove();
        $('#mission_footer').prepend('<div class="total-count text-muted fs-7">' + totalText + '</div>');
        
        if (this.state.currentMission) {
            $(`.mission-item[data-activity="${this.state.currentMission.ActivityType}"][data-sub="${this.state.currentMission.SubActivityType}"]`)
                .addClass('active')
                .attr('data-selected', 'true');
        }
    },

    // Selecionar missão
    selectMission: function(activityType, subActivityType) {
        const self = this;
        
        $('.mission-item').removeClass('active').removeAttr('data-selected');
        
        $(`.mission-item[data-activity="${activityType}"][data-sub="${subActivityType}"]`)
            .addClass('active')
            .attr('data-selected', 'true');

        fetch('/admin/game/event/missions/show/' + activityType + '/' + subActivityType)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    self.state.currentMission = data.data.mission;
                    self.renderMissionDetails();
                    self.loadMissionRewards(activityType, subActivityType);
                    
                    $('#not_selected').hide();
                    $('#mission_data').show();
                }
            })
            .catch(function(error) {
                console.error('Erro ao selecionar missão:', error);
                self.showNotification('Erro ao carregar missão', 'error');
            });
    },

    // Renderizar detalhes da missão
    renderMissionDetails: function() {
        const mission = this.state.currentMission;
        if (!mission) return;

        this.forceUpdateFormFields(mission);
        $('#reward_buttons').show();
    },

    // Carregar recompensas
    loadMissionRewards: function(activityType, subActivityType) {
        const self = this;
        
        fetch('/admin/game/event/missions/' + activityType + '/' + subActivityType + '/items')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    self.state.currentRewards = data.data.rewards || [];
                    self.renderRewardsList(data.data.rewards || []);
                }
            })
            .catch(function(error) {
                console.error('Erro ao carregar recompensas:', error);
                self.renderRewardsList([]);
            });
    },

    // Renderizar recompensas
    renderRewardsList: function(rewards) {
        if (rewards.length === 0) {
            $('#no_rewards').show();
            $('#rewards_list').hide();
            return;
        }

        let html = '';
        rewards.forEach(function(reward) {
            const templateId = reward.TemplateId;
            const itemName = reward.ItemName || 'Item ' + templateId;
            const iconUrl = reward.Icon || '/assets/media/svg/files/blank-image.svg';
            const itemNum = reward.Count || 1;
            
            html += `
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-light rounded reward-card" style="transition: all 0.3s ease; border: 1px solid #e1e3ea;">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <img src="${iconUrl}" alt="${itemName}" style="border-radius: 6px;" />
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold me-3">${itemName}</div>
                                <div class="badge badge-light-primary me-2">x${itemNum}</div>
                            </div>
                            <div class="text-muted fs-7">🌍 ID: ${templateId}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-sm btn-light-primary me-2" onclick="missionReward.edit(${templateId})" style="transition: all 0.2s ease;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-light-danger" onclick="missionReward.confirmDelete(${templateId})" style="transition: all 0.2s ease;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        $('#rewards_list').html(html).show();
        $('#no_rewards').hide();
        
        $('.reward-card').hover(
            function() {
                $(this).css({
                    'transform': 'translateY(-2px)',
                    'box-shadow': '0 4px 12px rgba(0,0,0,0.1)',
                    'border-color': '#007bff'
                });
            },
            function() {
                $(this).css({
                    'transform': 'translateY(0)',
                    'box-shadow': '',
                    'border-color': '#e1e3ea'
                });
            }
        );
    },

    // Criar nova missão
    create: function() {
        const self = this;
        const form = document.getElementById('form_mission_create');
        const formData = new FormData(form);

        fetch('/admin/game/event/missions/store', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                self.showNotification('Missão criada com sucesso! 🎯', 'success');
                $('#md_mission_new').modal('hide');
                form.reset();
                
                if (data.data && data.data.mission) {
                    self.addMissionToList(data.data.mission);
                } else {
                    self.loadMissions();
                }
            } else {
                self.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao criar missão:', error);
            self.showNotification('Erro ao criar missão', 'error');
        });
    },

    // Atualizar missão
    update: function() {
        const self = this;
        
        if (!this.state.currentMission) {
            this.showNotification('Nenhuma missão selecionada', 'warning');
            return;
        }

        const form = document.getElementById('form-mission-edit-send');
        const formData = new FormData(form);

        fetch('/admin/game/event/missions/' + this.state.currentMission.ActivityType + '/' + this.state.currentMission.SubActivityType + '/update', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                self.showNotification('Missão atualizada com sucesso! ⚡', 'success');
                
                if (data.data && data.data.mission) {
                    self.updateMissionInList(data.data.mission);
                } else {
                    self.loadMissions();
                }
            } else {
                self.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao atualizar missão:', error);
            self.showNotification('Erro ao atualizar missão', 'error');
        });
    },

    // Confirmar exclusão de missão com modal simples
    confirmDelete: function(activityType, subActivityType) {
        const missionName = this.getMissionName(activityType, subActivityType);
        
        this.showSimpleDeleteModal(
            'Deletar Missão',
            `Tem certeza que deseja deletar a missão:<br><strong>'${missionName}'</strong>?<br><br>Esta ação também excluirá todas as recompensas desta missão.`,
            () => {
                this.delete(activityType, subActivityType);
            }
        );
    },

    // Deletar missão
    delete: function(activityType, subActivityType) {
        const self = this;
        
        fetch('/admin/game/event/missions/' + activityType + '/' + subActivityType + '/delete', {
            method: 'DELETE'
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                self.showNotification('Missão excluída com sucesso! 🗑️', 'success');
                self.removeMissionFromList(activityType, subActivityType);
            } else {
                self.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao excluir missão:', error);
            self.showNotification('Erro ao excluir missão', 'error');
        });
    },

    // Encontrar próximo ID disponível
    getNextAvailableSubActivityType: function(activityType) {
        const existingMissions = this.state.missions.filter(function(m) {
            return m.ActivityType == activityType;
        });
        
        if (existingMissions.length === 0) {
            return 1;
        }
        
        const subTypes = existingMissions.map(function(m) {
            return parseInt(m.SubActivityType);
        }).sort(function(a, b) {
            return a - b;
        });
        
        for (let i = 1; i <= subTypes.length + 1; i++) {
            if (subTypes.indexOf(i) === -1) {
                return i;
            }
        }
        
        return subTypes.length + 1;
    },

    // Obter última condição usada
    getLastConditionForActivityType: function(activityType) {
        const existingMissions = this.state.missions.filter(function(m) {
            return m.ActivityType == activityType;
        });
        
        if (existingMissions.length === 0) {
            return 1;
        }
        
        const lastMission = existingMissions[existingMissions.length - 1];
        return lastMission.Condition || 1;
    },

    // Preparar formulário de nova missão
    prepareNewMissionForm: function() {
        const self = this;
        const activityTypeSelect = $('#form_mission_create select[name="ActivityType"]');
        const subActivityInput = $('#form_mission_create input[name="SubActivityType"]');
        const conditionInput = $('#form_mission_create input[name="Condition"]');
        
        activityTypeSelect.off('change.mission').on('change.mission', function() {
            const selectedActivityType = activityTypeSelect.val();
            if (selectedActivityType) {
                const nextSubType = self.getNextAvailableSubActivityType(selectedActivityType);
                const lastCondition = self.getLastConditionForActivityType(selectedActivityType);
                
                subActivityInput.val(nextSubType);
                conditionInput.val(lastCondition);
            }
        });
        
        if (activityTypeSelect.val()) {
            const selectedActivityType = activityTypeSelect.val();
            const nextSubType = self.getNextAvailableSubActivityType(selectedActivityType);
            const lastCondition = self.getLastConditionForActivityType(selectedActivityType);
            
            subActivityInput.val(nextSubType);
            conditionInput.val(lastCondition);
        }
    },

    // Recarregar
    reload: function() {
        this.showNotification('Atualizando lista... 🔄', 'info');
        setTimeout(() => {
            this.loadMissions();
        }, 500);
    }
};

// GERENCIADOR DE RECOMPENSAS COM MODAL SIMPLES
const missionReward = {
    // Confirmar exclusão com modal simples
    confirmDelete: function(templateId) {
        let itemName = 'este item';
        
        if (templateId === 0) {
            mission.showSimpleDeleteModal(
                'Deletar Todas as Recompensas',
                'Tem certeza que deseja deletar <strong>TODAS as recompensas</strong> desta missão?<br><br>Esta ação removerá todos os itens de recompensa.',
                () => {
                    this.deleteAll();
                }
            );
            return;
        }
        
        const currentReward = mission.state.currentRewards.find(function(reward) {
            return (reward.TemplateId || reward.TemplateID) == templateId;
        });
        
        if (currentReward) {
            itemName = currentReward.ItemName || `Item ID: ${templateId}`;
        }
        
        mission.showSimpleDeleteModal(
            'Deletar Item',
            `Tem certeza que deseja deletar o item:<br><strong>'${itemName}' (ID: ${templateId})</strong>?`,
            () => {
                this.delete(templateId);
            }
        );
    },

    // Criar recompensa
    create: function() {
        if (!mission.state.currentMission || !mission.state.selectedItem) {
            mission.showNotification('Selecione uma missão e um item', 'warning');
            return;
        }

        const form = document.getElementById('form-mission-reward-send');
        const formData = new FormData(form);
        formData.append('template_id', mission.state.selectedItem.templateId);

        fetch('/admin/game/event/missions/' + mission.state.currentMission.ActivityType + '/' + mission.state.currentMission.SubActivityType + '/items', {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                mission.showNotification('Recompensa adicionada com sucesso! 🎁', 'success');
                $('#md_mission_new_item').modal('hide');
                form.reset();
                $('#md-item-info').hide();
                mission.state.selectedItem = null;
                
                mission.loadMissionRewards(
                    mission.state.currentMission.ActivityType,
                    mission.state.currentMission.SubActivityType
                );
            } else {
                mission.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao criar recompensa:', error);
            mission.showNotification('Erro ao adicionar recompensa', 'error');
        });
    },

    // Editar recompensa
    edit: function(templateId) {
        if (!mission.state.currentMission) {
            mission.showNotification('Nenhuma missão selecionada', 'warning');
            return;
        }

        const currentReward = mission.state.currentRewards.find(function(reward) {
            return (reward.TemplateId || reward.TemplateID) == templateId;
        });

        if (!currentReward) {
            mission.showNotification('Recompensa não encontrada', 'error');
            return;
        }

        fetch('/admin/game/event/missions/items/info?template_id=' + templateId)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (!data.success) {
                    mission.showNotification('Erro ao carregar dados do item', 'error');
                    return;
                }

                const shopItemData = data.data;

                $('#md-edit-reward-pic').attr('src', shopItemData.Icon);
                $('#md-edit-reward-name').text(shopItemData.Name);
                $('#md-edit-reward-id').text('ID: ' + shopItemData.TemplateID);
                
                const form = document.getElementById('form-mission-reward-edit-send');
                
                let hiddenField = form.querySelector('input[name="template_id"]');
                if (!hiddenField) {
                    hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'template_id';
                    form.appendChild(hiddenField);
                }
                hiddenField.value = templateId;
                
                form.querySelector('input[name="count"]').value = currentReward.Count || 1;
                form.querySelector('input[name="validity"]').value = currentReward.ValidDate || 0;
                form.querySelector('input[name="strength_level"]').value = currentReward.StrengthLevel || 0;
                form.querySelector('input[name="attack_compose"]').value = currentReward.AttackCompose || 0;
                form.querySelector('input[name="defend_compose"]').value = currentReward.DefendCompose || 0;
                form.querySelector('input[name="luck_compose"]').value = currentReward.LuckCompose || 0;
                form.querySelector('input[name="agility_compose"]').value = currentReward.AgilityCompose || 0;
                form.querySelector('input[name="is_bind"]').checked = (currentReward.IsBind == 1);
                
                $('#md_mission_edit_item').modal('show');
            })
            .catch(function(error) {
                console.error('Erro ao carregar recompensa:', error);
                mission.showNotification('Erro ao carregar recompensa', 'error');
            });
    },

    // Atualizar recompensa
    update: function() {
        if (!mission.state.currentMission) {
            mission.showNotification('Nenhuma missão selecionada', 'warning');
            return;
        }

        const form = document.getElementById('form-mission-reward-edit-send');
        const templateId = form.querySelector('input[name="template_id"]').value;
        
        if (!templateId) {
            mission.showNotification('Template ID não encontrado', 'error');
            return;
        }
        
        const formData = new FormData(form);

        fetch('/test/update-item/' + mission.state.currentMission.ActivityType + '/' + mission.state.currentMission.SubActivityType + '/' + templateId, {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                mission.showNotification('Recompensa atualizada com sucesso! ⚡', 'success');
                $('#md_mission_edit_item').modal('hide');
                
                mission.loadMissionRewards(
                    mission.state.currentMission.ActivityType,
                    mission.state.currentMission.SubActivityType
                );
            } else {
                mission.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao atualizar:', error);
            mission.showNotification('Erro ao atualizar recompensa', 'error');
        });
    },

    // Deletar recompensa específica
    delete: function(templateId) {
        if (!mission.state.currentMission) {
            mission.showNotification('Nenhuma missão selecionada', 'warning');
            return;
        }

        fetch('/test/delete-item/' + mission.state.currentMission.ActivityType + '/' + mission.state.currentMission.SubActivityType + '/' + templateId, {
            method: 'POST'
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                mission.showNotification('Recompensa excluída com sucesso! 🗑️', 'success');
                
                mission.loadMissionRewards(
                    mission.state.currentMission.ActivityType,
                    mission.state.currentMission.SubActivityType
                );
            } else {
                mission.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao deletar:', error);
            mission.showNotification('Erro ao excluir recompensa', 'error');
        });
    },

    // Deletar todas as recompensas
    deleteAll: function() {
        if (!mission.state.currentMission) {
            mission.showNotification('Nenhuma missão selecionada', 'warning');
            return;
        }

        const rewards = mission.state.currentRewards || [];
        let deleted = 0;
        let promises = [];
        
        rewards.forEach(function(reward) {
            const templateId = reward.TemplateId || reward.TemplateID;
            const promise = fetch('/test/delete-item/' + mission.state.currentMission.ActivityType + '/' + mission.state.currentMission.SubActivityType + '/' + templateId, {
                method: 'POST'
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    deleted++;
                }
            });
            
            promises.push(promise);
        });
        
        Promise.all(promises).then(function() {
            if (deleted > 0) {
                mission.showNotification(deleted + ' recompensa(s) excluída(s) com sucesso! 🗑️', 'success');
                mission.loadMissionRewards(
                    mission.state.currentMission.ActivityType,
                    mission.state.currentMission.SubActivityType
                );
            } else {
                mission.showNotification('Nenhuma recompensa foi excluída', 'warning');
            }
        }).catch(function(error) {
            console.error('Erro ao deletar todas:', error);
            mission.showNotification('Erro ao excluir recompensas', 'error');
        });
    }
};

// INICIALIZAÇÃO DO SISTEMA
$(document).ready(function() {
    mission.init();

    $('#md_mission_new').on('shown.bs.modal', function() {
        mission.prepareNewMissionForm();
    });

    $('#itemID').select2({
        placeholder: 'Busque por um item...',
        allowClear: true,
        ajax: {
            url: '/admin/game/event/missions/items/search',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term || '' };
            },
            processResults: function(data) {
                if (data.success) {
                    return {
                        results: data.data.items.map(function(item) {
                            return {
                                id: item.TemplateID,
                                text: item.Name + ' (ID: ' + item.TemplateID + ')',
                                icon: item.Icon,
                                name: item.Name
                            };
                        })
                    };
                }
                return { results: [] };
            }
        },
        templateResult: function(item) {
            if (!item.id) return item.text;
            
            return $(`
                <div class="d-flex align-items-center">
                    <img src="${item.icon}" class="w-30px h-30px me-2" style="border-radius: 4px;" />
                    <span>${item.text}</span>
                </div>
            `);
        }
    });

    $('#itemID').on('select2:select', function(e) {
        const data = e.params.data;
        mission.state.selectedItem = {
            templateId: data.id,
            name: data.name,
            icon: data.icon
        };

        $('#md-reward-pic').attr('src', data.icon);
        $('#md-reward-name').text(data.name);
        $('#md-reward-id').text('ID: ' + data.id);
        $('#md-item-info').show();
        $('#btn_reward_create').prop('disabled', false);
        
        mission.showNotification('Item selecionado: ' + data.name, 'info');
    });

    $('#md_mission_new').on('hidden.bs.modal', function() {
        $('#form_mission_create')[0].reset();
    });

    $('#md_mission_new_item').on('hidden.bs.modal', function() {
        $('#form-mission-reward-send')[0].reset();
        $('#md-item-info').hide();
        $('#btn_reward_create').prop('disabled', true);
        mission.state.selectedItem = null;
        $('#itemID').val(null).trigger('change');
    });

    $('#md_mission_edit_item').on('hidden.bs.modal', function() {
        $('#form-mission-reward-edit-send')[0].reset();
    });
});

// FUNÇÕES GLOBAIS
window.mission = mission;
window.missionReward = missionReward;