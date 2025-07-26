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
        6: "Conseguir Honor",
        7: "Conseguir Riches",
        8: "Conseguir GP",
        9: "Conseguir Offer",
    },

    // Inicialização
    init: function() {
        console.log('🚀 Sistema de missões carregado!');
        this.loadMissions();
        this.initNotifications();
        this.initFilters();
    },

    // Inicializar filtros
    initFilters: function() {
        const self = this;
        
        // Filtro por busca
        $('#mission_search').on('input', function() {
            self.applyFilters();
        });

        // Filtro por tipo
        $('select[name="missionType_filter"]').on('change', function() {
            self.applyFilters();
        });

        // Filtro por limite
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
            // Filtro por tipo
            if (typeFilter !== 'all' && mission.ActivityType != typeFilter) {
                return false;
            }

            // Filtro por busca
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

        // Aplicar limite
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
            success: { bg: '#f0fdf4', border: '#22c55e', text: '#15803d', icon: '✅' },
            error: { bg: '#fef2f2', border: '#ef4444', text: '#dc2626', icon: '❌' },
            warning: { bg: '#fffbeb', border: '#f59e0b', text: '#d97706', icon: '⚠️' },
            info: { bg: '#f0f9ff', border: '#3b82f6', text: '#1d4ed8', icon: 'ℹ️' }
        };
        
        const color = colors[type] || colors.success;
        
        notification.innerHTML = '<div style="background: ' + color.bg + '; border: 1px solid ' + color.border + '; color: ' + color.text + '; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-size: 14px; font-weight: 500; pointer-events: auto; transform: translateX(100%); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; min-width: 300px; max-width: 400px;"><span style="font-size: 16px;">' + color.icon + '</span><span style="flex: 1;">' + message + '</span><button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: ' + color.text + '; cursor: pointer; padding: 0; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; border-radius: 4px; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity=\'1\'" onmouseout="this.style.opacity=\'0.7\'">×</button></div>';
        
        container.appendChild(notification);
        
        // Animar entrada
        setTimeout(function() {
            notification.firstElementChild.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remover após 5 segundos
        setTimeout(function() {
            if (notification.parentElement) {
                notification.firstElementChild.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    },

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
            const missionName = self.getMissionName(mission.ActivityType, mission.SubActivityType);
            html += '<div class="mission-item" onclick="mission.selectMission(' + mission.ActivityType + ', ' + mission.SubActivityType + ')" data-activity="' + mission.ActivityType + '" data-sub="' + mission.SubActivityType + '">' +
                '<div class="mission-content">' +
                    '<div class="mission-header">' +
                        '<div class="mission-title">' + missionName + '</div>' +
                        '<button class="mission-delete-btn" onclick="event.stopPropagation(); mission.confirmDelete(' + mission.ActivityType + ', ' + mission.SubActivityType + ')" title="Excluir missão">' +
                            '<i class="fas fa-trash"></i>' +
                        '</button>' +
                    '</div>' +
                    '<div class="mission-meta">' +
                        '<div class="mission-badge">Type ' + mission.ActivityType + '</div>' +
                        '<div class="mission-condition">Condition: ' + mission.Condition + '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        });

        container.html(html).show();
        $('#not_results').hide();

        // Mostrar total de resultados
        const totalText = this.state.filteredMissions.length === this.state.missions.length 
            ? this.state.missions.length + ' missões' 
            : this.state.filteredMissions.length + ' de ' + this.state.missions.length + ' missões';
        
        $('#mission_footer .total-count').remove();
        $('#mission_footer').prepend('<div class="total-count text-muted fs-7">' + totalText + '</div>');
    },

    // Selecionar missão
    selectMission: function(activityType, subActivityType) {
        const self = this;
        
        // Remover seleção anterior
        $('.mission-item').removeClass('active').removeAttr('data-selected');
        
        // Marcar missão atual como ativa
        $('.mission-item[data-activity="' + activityType + '"][data-sub="' + subActivityType + '"]')
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

        $('select[name="ActivityType"]').val(mission.ActivityType);
        $('input[name="SubActivityType"]').val(mission.SubActivityType);
        $('input[name="Condition"]').val(mission.Condition);
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
            
            html += '<div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-light rounded reward-card">' +
                '<div class="d-flex align-items-center">' +
                    '<div class="symbol symbol-40px me-3">' +
                        '<img src="' + iconUrl + '" alt="' + itemName + '" />' +
                    '</div>' +
                    '<div>' +
                        '<div class="d-flex align-items-center">' +
                            '<div class="fw-bold me-3">' + itemName + '</div>' +
                            '<div class="badge badge-light-primary me-2">x' + itemNum + '</div>' +
                        '</div>' +
                        '<div class="text-muted fs-7">🌍 ID: ' + templateId + '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="d-flex">' +
                    '<button class="btn btn-sm btn-light-primary me-2" onclick="missionReward.edit(' + templateId + ')">' +
                        '<i class="fas fa-edit"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-light-danger" onclick="missionReward.confirmDelete(' + templateId + ')">' +
                        '<i class="fas fa-trash"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';
        });

        $('#rewards_list').html(html).show();
        $('#no_rewards').hide();
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
                self.showNotification('Missão criada com sucesso!', 'success');
                $('#md_mission_new').modal('hide');
                form.reset();
                self.loadMissions();
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
                self.showNotification('Missão atualizada com sucesso!', 'success');
                self.loadMissions();
            } else {
                self.showNotification('Erro: ' + data.message, 'error');
            }
        })
        .catch(function(error) {
            console.error('Erro ao atualizar missão:', error);
            self.showNotification('Erro ao atualizar missão', 'error');
        });
    },

    // Confirmar exclusão de missão
    confirmDelete: function(activityType, subActivityType) {
        const missionName = this.getMissionName(activityType, subActivityType);
        
        const confirmed = confirm('Tem certeza que deseja excluir a missão "' + missionName + '"?\n\nEsta ação não pode ser desfeita e também excluirá todas as recompensas desta missão.');
        
        if (confirmed) {
            this.delete(activityType, subActivityType);
        }
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
                self.showNotification('Missão excluída com sucesso!', 'success');
                self.loadMissions();
                
                if (self.state.currentMission && 
                    self.state.currentMission.ActivityType == activityType && 
                    self.state.currentMission.SubActivityType == subActivityType) {
                    self.state.currentMission = null;
                    $('#mission_data').hide();
                    $('#not_selected').show();
                }
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
        
        // Evento para quando mudar o tipo de atividade
        activityTypeSelect.off('change.mission').on('change.mission', function() {
            const selectedActivityType = activityTypeSelect.val();
            if (selectedActivityType) {
                const nextSubType = self.getNextAvailableSubActivityType(selectedActivityType);
                const lastCondition = self.getLastConditionForActivityType(selectedActivityType);
                
                subActivityInput.val(nextSubType);
                conditionInput.val(lastCondition);
            }
        });
        
        // Pré-preencher se já tiver um tipo selecionado
        if (activityTypeSelect.val()) {
            const selectedActivityType = activityTypeSelect.val();
            const nextSubType = self.getNextAvailableSubActivityType(selectedActivityType);
            const lastCondition = self.getLastConditionForActivityType(selectedActivityType);
            
            subActivityInput.val(nextSubType);
            conditionInput.val(lastCondition);
        }
    },

    // 🔄 Recarregar
    reload: function() {
        this.loadMissions();
    }
};

// 🎁 GERENCIADOR DE RECOMPENSAS

const missionReward = {
    // ➕ Criar recompensa
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
                mission.showNotification('Recompensa adicionada com sucesso!', 'success');
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
                mission.showNotification('Recompensa atualizada com sucesso!', 'success');
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

    // Confirmar exclusão de recompensa
    confirmDelete: function(templateId) {
        if (templateId === 0) {
            const confirmed = confirm('Tem certeza que deseja excluir TODAS as recompensas desta missão?\n\nEsta ação não pode ser desfeita.');
            if (confirmed) {
                this.deleteAll();
            }
        } else {
            const confirmed = confirm('Tem certeza que deseja excluir esta recompensa?\n\nEsta ação não pode ser desfeita.');
            if (confirmed) {
                this.delete(templateId);
            }
        }
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
                mission.showNotification('Recompensa excluída com sucesso!', 'success');
                
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
                mission.showNotification(deleted + ' recompensa(s) excluída(s) com sucesso!', 'success');
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

// 🚀 INICIALIZAÇÃO DO SISTEMA

$(document).ready(function() {
    console.log('🚀 Inicializando sistema...');
    
    mission.init();

    // Preparar formulário quando modal for aberto
    $('#md_mission_new').on('shown.bs.modal', function() {
        mission.prepareNewMissionForm();
    });

    // Select2 para busca de itens
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
            
            return $('<div class="d-flex align-items-center"><img src="' + item.icon + '" class="w-30px h-30px me-2" /><span>' + item.text + '</span></div>');
        }
    });

    // Quando selecionar item
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
    });

    // Limpar modais
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

    console.log('✅ Sistema inicializado!');
});

//  FUNÇÕES GLOBAIS PARA COMPATIBILIDADE

window.mission = mission;
window.missionReward = missionReward;