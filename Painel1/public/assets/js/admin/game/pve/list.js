// Sistema PVE v12.1 - Biblioteca Multi-Dificuldade + Notificações Melhoradas

const STORAGE_KEY = 'pve_system_state_v2';
const CACHE_KEY = 'pve_items_cache_v2';
const CACHE_EXPIRY = 1000 * 60 * 30;

const parameters = {
  pve: {
    params: {
      sid: null,
      page: 1,
      limit: 5,
      search: '',
      type: 0,
    }
  }
};

const pveState = {
  currentPve: null,
  currentDifficulty: 'simple',
  currentTab: 'pve_details_tab',
  templatesData: {
    simple: [],
    normal: [],
    hard: [],
    terror: [],
    nightmare: [],
    epic: []
  },
  templatesLoaded: {
    simple: false,
    normal: false,
    hard: false,
    terror: false,
    nightmare: false,
    epic: false
  },
  itemsLibrary: [],
  isSearching: false,
  isInitialized: false,
  hasUnsavedChanges: false,
  lastSaved: null,
  allPveInstances: [],
  librarySearchCache: new Map(),
  activeNotificationTimeout: null
};

// ====================================
// SISTEMA DE ESTADO PERSISTENTE
// ====================================

const persistentState = {
  save: () => {
    try {
      const stateToSave = {
        version: '2.0',
        timestamp: Date.now(),
        currentPve: pveState.currentPve ? {
          ID: pveState.currentPve.ID,
          Name: pveState.currentPve.Name,
          Type: pveState.currentPve.Type
        } : null,
        currentDifficulty: pveState.currentDifficulty,
        currentTab: pveState.currentTab,
        parameters: parameters.pve.params,
        templatesLoaded: pveState.templatesLoaded,
        lastSaved: pveState.lastSaved
      };
      
      localStorage.setItem(STORAGE_KEY, JSON.stringify(stateToSave));
    } catch (error) {
      console.warn('Erro ao salvar estado:', error);
    }
  },

  load: () => {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (!saved) return false;

      const state = JSON.parse(saved);
      
      if (!state.version || state.version !== '2.0' || 
          (Date.now() - state.timestamp) > (24 * 60 * 60 * 1000)) {
        persistentState.clear();
        return false;
      }

      if (state.parameters) {
        Object.assign(parameters.pve.params, state.parameters);
      }
      
      pveState.currentDifficulty = state.currentDifficulty || 'simple';
      pveState.currentTab = state.currentTab || 'pve_details_tab';
      pveState.templatesLoaded = state.templatesLoaded || {};
      pveState.lastSaved = state.lastSaved;
      
      if (state.currentPve && state.currentPve.ID) {
        pveState.currentPve = state.currentPve;
      }

      return true;
    } catch (error) {
      console.warn('Erro ao carregar estado:', error);
      persistentState.clear();
      return false;
    }
  },

  clear: () => {
    localStorage.removeItem(STORAGE_KEY);
  }
};

// ====================================
// SISTEMA DE CACHE
// ====================================

const cacheSystem = {
  items: new Map(),
  
  saveItemsCache: () => {
    try {
      const cacheData = {
        version: '2.0',
        timestamp: Date.now(),
        items: Array.from(cacheSystem.items.entries())
      };
      localStorage.setItem(CACHE_KEY, JSON.stringify(cacheData));
    } catch (error) {
      console.warn('Erro ao salvar cache:', error);
    }
  },

  loadItemsCache: () => {
    try {
      const cached = localStorage.getItem(CACHE_KEY);
      if (!cached) return;

      const data = JSON.parse(cached);
      
      if (Date.now() - data.timestamp > CACHE_EXPIRY) {
        cacheSystem.clearItemsCache();
        return;
      }

      cacheSystem.items = new Map(data.items);
    } catch (error) {
      console.warn('Erro ao carregar cache:', error);
      cacheSystem.clearItemsCache();
    }
  },

  clearItemsCache: () => {
    cacheSystem.items.clear();
    localStorage.removeItem(CACHE_KEY);
  },

  getItem: (id) => {
    return cacheSystem.items.get(id.toString());
  },

  setItem: (id, data) => {
    cacheSystem.items.set(id.toString(), {
      ...data,
      cached_at: Date.now()
    });
    if (cacheSystem.items.size % 10 === 0) {
      cacheSystem.saveItemsCache();
    }
  }
};

// ====================================
// SISTEMA DE NOTIFICAÇÕES
// ====================================

const unsavedChanges = {
  mark: (description = 'Alterações pendentes') => {
    pveState.hasUnsavedChanges = true;
    persistentState.save();
    unsavedChanges.updateUI();
    
    if (window.showWarningToast) {
      window.showWarningToast(description, '⚠️');
    }
  },

  clear: () => {
    pveState.hasUnsavedChanges = false;
    pveState.lastSaved = Date.now();
    persistentState.save();
    unsavedChanges.updateUI();
  },

  updateUI: () => {
    const saveBtn = $('#btn_pve_update');

    let stack = $('#save_times_stack');
    if (!stack.length) {
      saveBtn.before(`
	  <div id="save_times_stack" class="d-flex flex-column align-items-end mt-2" style="gap:4px; min-width: 180px;"></div>
	`);
      stack = $('#save_times_stack');
    }

    if (pveState.hasUnsavedChanges) {
      saveBtn.removeClass('btn-primary').addClass('btn-warning')
             .find('.indicator-label').html('<i class="fas fa-exclamation-triangle"></i> Salvar Alterações');

      $('#unsaved_status, .notification-item').remove();
      
      stack.prepend(`
        <div id="unsaved_status" class="alert alert-warning p-2 w-100 notification-item" style="font-size: .8rem; margin:0;">
          <i class="fas fa-exclamation-triangle"></i> Você tem alterações não salvas
        </div>
      `);
    } else {
      saveBtn.removeClass('btn-warning').addClass('btn-primary')
             .find('.indicator-label').html('<i class="fas fa-save"></i> Salvar');

      $('#unsaved_status').remove();

      if (pveState.lastSaved) {
        const lastSavedTime = new Date(pveState.lastSaved).toLocaleTimeString();
        
        $('.save-notification').remove();
        
        const $notification = $(`
          <div class="text-success d-block px-2 py-1 rounded border bg-white w-100 save-notification notification-item" style="position: relative; overflow: hidden;">
            <div class="notification-content" style="position: relative; z-index: 2;">
              <i class="fas fa-check-circle"></i> Salvo às ${lastSavedTime}
            </div>
            <div class="countdown-bar" style="position: absolute; bottom: 0; left: 0; height: 2px; background: #28a745; width: 100%; z-index: 1; animation: countdown 1.5s linear forwards;"></div>
          </div>
        `);
        
        stack.prepend($notification);
        
        unsavedChanges.scheduleNotificationRemoval($notification, 1500);
        
        const lines = stack.find('.notification-item');
        if (lines.length > 3) lines.last().remove();
      }
    }
  },

  scheduleNotificationRemoval: ($notification, delay = 1500) => {
    if (pveState.activeNotificationTimeout) {
      clearTimeout(pveState.activeNotificationTimeout);
    }

    pveState.activeNotificationTimeout = setTimeout(() => {
      $notification.fadeOut(300, function() {
        $(this).remove();
      });
      pveState.activeNotificationTimeout = null;
    }, delay);
  }
};

// ====================================
// SISTEMA PRINCIPAL PVE
// ====================================

const pve = {
  list: (page = 1) => {
    parameters.pve.params.page = page;
    
    if (!parameters.pve.params.sid) {
      const sidFromSelect = $('select[name="sid"]').val();
      if (sidFromSelect && sidFromSelect !== '') {
        parameters.pve.params.sid = sidFromSelect;
      } else {
        swMessage('error', 'Selecione um servidor primeiro');
        return Promise.reject('No SID');
      }
    }
    
    helper.loader('#pve_body', true);
    
    const requestParams = {
      sid: parameters.pve.params.sid,
      page: parameters.pve.params.page || 1,
      limit: parameters.pve.params.limit || 5,
      search: parameters.pve.params.search || '',
      type: parameters.pve.params.type || 0
    };
    
    return axios.get(`${baseUrl}/api/admin/game/pve`, {
      params: requestParams,
      timeout: 15000
    }).then(res => {
      if (res.data && res.data.state) {
        pveState.allPveInstances = res.data.items || [];
        pve.populate(res.data);
        persistentState.save();
        return res.data;
      } else {
        helper.loader('#pve_body', false);
        swMessage('warning', res.data.message || 'Nenhuma instância encontrada');
        return res.data;
      }
    }).catch(error => {
      console.error('Erro PVE:', error);
      helper.loader('#pve_body', false);
      
      let errorMessage = 'Erro ao carregar dados';
      if (error.response) {
        errorMessage = `Erro ${error.response.status}: ${error.response.data?.message || 'Erro do servidor'}`;
      } else if (error.code === 'ECONNABORTED') {
        errorMessage = 'Timeout: Servidor demorou para responder';
      }
      
      swMessage('error', errorMessage);
      throw error;
    });
  },

  populate: (data) => {
    const list = $('#pve_list'),
      no_result = $('#no_result'),
      paginator = $('#paginator'),
      footer = $('#pve_list_footer');

    if (!data?.items || data.items.length <= 0) {
      no_result.show();
      list.hide();
      footer.hide();
      helper.loader('#pve_body', false);
      return;
    }

    list.empty();
    paginator.empty();

    data.items.forEach((info, index) => {
      const isLast = index === data.items.length - 1;
      
      const pveData = {
        ID: info.ID || 0,
        Name: info.Name || `PVE ${info.ID}`,
        Type: info.Type || 1,
        Image: info.Image || (info.ImageDefault || ''),
        ImageDefault: info.ImageDefault || '',
        Difficulties: info.Difficulties || {},
        SimpleTemplateIds: info.SimpleTemplateIds || '',
        NormalTemplateIds: info.NormalTemplateIds || '',
        HardTemplateIds: info.HardTemplateIds || '',
        TerrorTemplateIds: info.TerrorTemplateIds || '',
        NightmareTemplateIds: info.NightmareTemplateIds || '',
        EpicTemplateIds: info.EpicTemplateIds || ''
      };
      
      const itemHtml = `
        <div class="d-flex flex-stack pt-2" id="pve-${pveData.ID}">
          <div class="d-flex align-items-center">
            <div class="w-60px h-40px min-w-60px me-3 rounded bg-light">
              <img src="${pveData.Image}" onerror="this.src='${pveData.ImageDefault}';" 
                   class="w-100 h-100 rounded" style="object-fit: cover;" />
            </div>
            <div>
              <a href="javascript:;" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">
                ${pveData.Name}
              </a>
              <div class="text-muted fs-7 mb-1">🌍 ID: ${pveData.ID} | 🕋 Type: ${pveData.Type}</div>
              <div class="d-flex gap-1 flex-wrap">
                ${pve.generateDifficultyBadgesWithCounts(pveData)}
              </div>
            </div>
          </div>
          <div class="d-flex align-items-end ms-2">
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary edit-btn" 
                    data-id="${pveData.ID}" title="Editar">
              <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger delete-btn" 
                    data-id="${pveData.ID}" title="Deletar">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
        ${isLast ? '' : '<div class="pt-2 separator separator-dashed"></div>'}
      `;
      
      list.append(itemHtml);
    });

    $('.edit-btn').off('click').on('click', function() {
      const id = $(this).data('id');
      const pveData = data.items.find(item => item.ID == id);
      if (pveData) {
        pve.detail(pveData);
      }
    });

    $('.delete-btn').off('click').on('click', function() {
      const id = $(this).data('id');
      pve.delete(id);
    });

    if (data.paginator && data.paginator.rendered) {
      paginator.html(data.paginator.rendered);
      paginator.show();
      footer.show();
    } else {
      paginator.hide();
      footer.hide();
    }

    list.show();
    no_result.hide();
    helper.loader('#pve_body', false);
  },

  generateDifficultyBadgesWithCounts: (pveData) => {
    const badges = [];
    const difficulties = {
      simple: { field: 'SimpleTemplateIds', color: 'success', icon: '🟢', name: 'Fácil' },
      normal: { field: 'NormalTemplateIds', color: 'primary', icon: '🔵', name: 'Normal' },
      hard: { field: 'HardTemplateIds', color: 'warning', icon: '🟡', name: 'Difícil' },
      terror: { field: 'TerrorTemplateIds', color: 'danger', icon: '🔴', name: 'Terror' },
      nightmare: { field: 'NightmareTemplateIds', color: 'dark', icon: '⚫', name: 'Pesadelo' },
      epic: { field: 'EpicTemplateIds', color: 'info', icon: '🟣', name: 'Épico' }
    };

    Object.keys(difficulties).forEach(diff => {
      const config = difficulties[diff];
      const templateIds = pveData[config.field] || '';
      const count = templateIds ? templateIds.split(',').filter(id => id.trim() !== '').length : 0;
      
      if (count > 0) {
        badges.push(`
          <span class="badge badge-light-${config.color} d-flex align-items-center gap-1" 
                style="font-size: 0.65rem; padding: 2px 6px;"
                title="${config.name}: ${count} template(s)">
            ${config.icon}
            <span class="fw-bold">${count}</span>
          </span>
        `);
      } else {
        badges.push(`
          <span class="badge badge-light-secondary d-flex align-items-center gap-1" 
                style="font-size: 0.65rem; padding: 2px 6px; opacity: 0.5;"
                title="${config.name}: 0 templates">
            ${config.icon}
            <span>0</span>
          </span>
        `);
      }
    });

    return badges.join('');
  },

  detail: (data) => {
    pveState.currentPve = data;

    templates.seedCountersFromPve(data);
    unsavedChanges.clear();
    
    if ($('#pve_details_form').length > 0) {
      const form = $('#pve_details_form');
      
      form.find('input[name="OriginalID"]').val(data.ID || '');
      form.find('input[name="ID"]').val(data.ID || '');
      form.find('input[name="Name"]').val(data.Name || `PVE ${data.ID}`);
      form.find('input[name="LevelLimits"]').val(data.LevelLimits || '20');
      form.find('textarea[name="Description"]').val(data.Description || '');
      form.find('input[name="AdviceTips"]').val(data.AdviceTips || '');
      form.find('input[name="Pic"]').val(data.Pic || '1072');
      
      const orderingField = form.find('input[name="Ordering"]');
      if (data.Ordering) {
        orderingField.val(data.Ordering);
      } else {
        const suggestedOrdering = pve.suggestNextOrdering(data.Type);
        orderingField.val(suggestedOrdering);
        orderingField.attr('placeholder', `Sugerido: ${suggestedOrdering}`);
      }
      
      const realTypeValue = data.Type;
      if (realTypeValue !== undefined && realTypeValue !== null) {
        form.find('input[name="Type"]').val(realTypeValue.toString());
      } else {
        form.find('input[name="Type"]').val('1');
      }
      
      if (data.CostArray) {
        Object.keys(data.CostArray).forEach(difficulty => {
          form.find(`input[name="cost_${difficulty}"]`).val(data.CostArray[difficulty] || '0');
        });
      }
      
      const scriptMappings = {
        'SimpleGameScript': 'SimpleGameScript',
        'NormalGameScript': 'NormalGameScript',
        'HardGameScript': 'HardGameScript',
        'TerrorGameScript': 'TerrorGameScript',
        'EpicGameScript': 'EpicGameScript'
      };
      
      Object.entries(scriptMappings).forEach(([apiField, formField]) => {
        const scriptValue = data[apiField];
        
        if (scriptValue && scriptValue.trim() !== '') {
          let scriptName = scriptValue.replace('GameServerScript.AI.Game.', '');
          const inputField = form.find(`input[name="${formField}"]`);
          
          if (inputField.length > 0) {
            inputField.val(scriptName);
          }
        } else {
          const inputField = form.find(`input[name="${formField}"]`);
          if (inputField.length > 0) {
            inputField.val('');
          }
        }
      });
      
      form.find('input, textarea, select').off('change.unsaved').on('change.unsaved', function() {
        unsavedChanges.mark('Dados do PVE alterados');
      });
    }

    Object.keys(pveState.templatesLoaded).forEach(diff => {
      pveState.templatesLoaded[diff] = false;
    });

    templates.lazyLoadForDifficulty(pveState.currentDifficulty);

    $('#no_selected').hide();
    $('#pve_data').show();
    
    if (pveState.currentTab && pveState.currentTab !== 'pve_details_tab') {
      setTimeout(() => {
        $(`a[href="#${pveState.currentTab}"]`).tab('show');
      }, 100);
    }
    
    setTimeout(() => {
      library.initAll();
    }, 200);

    persistentState.save();
  },

  suggestNextOrdering: (type) => {
    if (!pveState.allPveInstances || pveState.allPveInstances.length === 0) {
      return 1;
    }
    const sameTypePves = pveState.allPveInstances.filter(pve => pve.Type == type);
    if (sameTypePves.length === 0) return 1;
    const maxOrdering = Math.max(...sameTypePves.map(pve => parseInt(pve.Ordering) || 0));
    return maxOrdering + 1;
  },

  update: () => {
    const form = $("#pve_details_form");
    if (form.length === 0) {
      swMessage('warning', 'Formulário não encontrado');
      return;
    }
    
    if (!pveState.currentPve) {
      swMessage('error', 'Nenhum PVE selecionado');
      return;
    }

    const formData = new FormData(form[0]);
    const data = {
      sid: parameters.pve.params.sid,
      pveId: pveState.currentPve.ID
    };
    
    for (let [key, value] of formData.entries()) {
      if (value !== null && value !== undefined) {
        data[key] = value.toString().trim();
      }
    }

    const costFields = ['cost_simple', 'cost_normal', 'cost_hard', 'cost_terror', 'cost_nightmare', 'cost_epic'];
    const costs = [];
    costFields.forEach(field => {
      if (data[field]) {
        costs.push(data[field]);
        delete data[field];
      } else {
        costs.push('0');
      }
    });
    
    if (costs.some(cost => cost !== '0')) {
      data.costs = costs;
    }

    const difficulties = ['simple', 'normal', 'hard', 'terror', 'nightmare', 'epic'];
    difficulties.forEach(diff => {
      const scriptKey = diff + 'Script';
      if (data[scriptKey] && data[scriptKey].trim() !== '') {
        if (!data[scriptKey].startsWith('GameServerScript.AI.Game.')) {
          data[scriptKey] = data[scriptKey].trim();
        }
      }
    });

    if (!data.Name) {
      swMessage('error', 'Nome é obrigatório');
      return;
    }

    const button = document.querySelector("#btn_pve_update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/pve`, data).then(res => {
      const su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      
      if (su.state) {
        unsavedChanges.clear();
        Object.assign(pveState.currentPve, data);
        wsdlNotification.show();
        persistentState.save();
        pve.list(parameters.pve.params.page);
      }
    }).catch(error => {
      console.error('Erro ao atualizar PVE:', error);
      swMessage("error", "Erro interno, verifique o console.");
      changeButtonState(button, false);
    });
  },

  delete: (id) => {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: "error",
        title: "Deletar PVE",
        html: "Você tem certeza que deseja apagar essa instância? Esta alteração não pode ser desfeita e pode ocorrer erros imprevistos no servidor.",
        showCancelButton: true,
        confirmButtonText: "Sim, delete isso!",
        cancelButtonText: "Não, cancele!",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-light",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`${baseUrl}/api/admin/game/pve`, {
            params: { pveId: id, sid: parameters.pve.params.sid }
          }).then((results) => {
            const su = results.data;
            swMessage(su.state ? "success" : "warning", su.message || 'PVE deletado');
            if (su.state) {
              $('#no_selected').show();
              $('#pve_data').hide();
              
              if (pveState.currentPve && pveState.currentPve.ID == id) {
                pveState.currentPve = null;
                unsavedChanges.clear();
                persistentState.save();
              }
              
              pve.list(parameters.pve.params.page);
            }
          }).catch(error => {
            console.error('Erro ao deletar:', error);
            swMessage("error", "Erro ao deletar PVE");
          });
        }
      });
    } else {
      if (confirm('Tem certeza que deseja deletar este PVE?')) {
        axios.delete(`${baseUrl}/api/admin/game/pve`, {
          params: { pveId: id, sid: parameters.pve.params.sid }
        }).then((results) => {
          const su = results.data;
          alert(su.message || 'PVE deletado');
          if (su.state) {
            pve.list(parameters.pve.params.page);
          }
        });
      }
    }
  }
};

// ====================================
// SISTEMA WSDL
// ====================================

const wsdlNotification = {
  show: () => {
    if ($('#wsdl_notification_modal').length === 0) {
      $('body').append(`
        <div class="modal fade" id="wsdl_notification_modal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                  <i class="fas fa-check-circle me-2"></i>PVE Atualizado com Sucesso!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <div class="mb-4">
                  <i class="fas fa-server fa-3x text-warning mb-3"></i>
                  <h6>⚠️ Atenção: Atualize os Emuladores</h6>
                  <p class="text-muted">
                    Para que as alterações tenham efeito no jogo, é necessário 
                    <strong>atualizar os emuladores</strong> do servidor.
                  </p>
                </div>
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-warning btn-lg" id="wsdl_update_now">
                    <i class="fas fa-sync-alt me-2"></i>Atualizar Emuladores Agora
                  </button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fazer Depois
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      `);
      
      $('#wsdl_update_now').on('click', function() {
        $('#wsdl_notification_modal').modal('hide');
        pve.updateOnGame();
      });
    }
    
    $('#wsdl_notification_modal').modal('show');
    
    setTimeout(() => {
      if ($('#wsdl_notification_modal').hasClass('show')) {
        $('#wsdl_notification_modal').modal('hide');
      }
    }, 10000);
  }
};

pve.updateOnGame = () => {
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      icon: "question",
      title: "Atualizar PVEs no Jogo",
      html: "Você tem certeza que deseja atualizar os PVEs? Ao fazer isso os <b>emuladores</b> serão atualizados.",
      showCancelButton: true,
      confirmButtonText: "Sim, atualize!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        pve.executeUpdateOnGame();
      }
    });
  } else {
    if (confirm('Atualizar PVEs no jogo?')) {
      pve.executeUpdateOnGame();
    }
  }
};

pve.executeUpdateOnGame = () => {
  const button = document.querySelector("#update_on_game, #wsdl_update_now");
  changeButtonState(button, true);
  
  axios.get(`${baseUrl}/api/admin/game/pve/update-on-game`, {
    params: { sid: parameters.pve.params.sid }
  }).then((results) => {
    const su = results.data;
    swMessage(su.state ? "success" : "warning", su.message || 'Atualizado');
    changeButtonState(button, false);
    
    if (su.state && window.showSuccessToast) {
      window.showSuccessToast('Emuladores atualizados com sucesso! 🚀', '⚡');
    }
  }).catch(error => {
    console.error('Erro ao atualizar WSDL:', error);
    swMessage("error", "Erro interno, verifique o console.");
    changeButtonState(button, false);
  });
};

// ====================================
// SISTEMA DE TEMPLATES
// ====================================

const templates = {
  lazyLoadForDifficulty: (difficulty) => {
    if (pveState.templatesLoaded[difficulty] || !pveState.currentPve) {
      return;
    }
    
    templates.loadFromPveData(pveState.currentPve, difficulty);
    pveState.templatesLoaded[difficulty] = true;
  },

  seedCountersFromPve: (pveData) => {
    const map = {
      simple: 'SimpleTemplateIds',
      normal: 'NormalTemplateIds',
      hard: 'HardTemplateIds',
      terror: 'TerrorTemplateIds',
      nightmare: 'NightmareTemplateIds',
      epic: 'EpicTemplateIds'
    };

    Object.keys(map).forEach(diff => {
      const ids = (pveData[map[diff]] || '')
        .split(',')
        .map(s => s.trim())
        .filter(Boolean);

      if (!pveState.templatesLoaded[diff]) {
        pveState.templatesData[diff] = ids.map((id, i) => ({
          TemplateID: id,
          Name: `Template ${id}`,
          NeedSex: 0,
          Image: '/assets/media/svg/files/blank-image.svg',
          position: i + 1
        }));
      }
    });

    templates.updateAllTabCounters();
  },

  loadFromPveData: (pveData, specificDifficulty = null) => {
    const difficulties = specificDifficulty ? [specificDifficulty] : 
                        ['simple', 'normal', 'hard', 'terror', 'nightmare', 'epic'];
    
    const templateFieldMapping = {
      'simple': 'SimpleTemplateIds',
      'normal': 'NormalTemplateIds', 
      'hard': 'HardTemplateIds',
      'terror': 'TerrorTemplateIds',
      'nightmare': 'NightmareTemplateIds',
      'epic': 'EpicTemplateIds'
    };
    
    difficulties.forEach(difficulty => {
      const templateField = templateFieldMapping[difficulty];
      
      pveState.templatesData[difficulty] = [];
      
      if (pveData[templateField] && pveData[templateField].trim() !== '') {
        const templateIds = pveData[templateField].split(',')
          .map(id => id.trim())
          .filter(id => id !== '');
        
        templateIds.forEach((templateId, index) => {
          const templateObj = {
            TemplateID: templateId,
            Name: `Template ${templateId}`,
            NeedSex: 0,
            Image: '/assets/media/svg/files/blank-image.svg',
            position: index + 1
          };
          
          pveState.templatesData[difficulty].push(templateObj);
          templates.loadItemDataWithCache(templateId, difficulty, index);
        });
      }
      
      templates.renderGrid(difficulty);
    });
  },

  loadItemDataWithCache: (templateId, difficulty, index) => {
    const cachedItem = cacheSystem.getItem(templateId);
    if (cachedItem) {
      templates.updateTemplateFromCache(cachedItem, difficulty, index);
      return;
    }
    
    axios.get('/admin/gameutils/items/search', {
      params: {
        search: templateId,
        sid: parameters.pve.params.sid
      }
    }).then(response => {
      if (response.data && response.data.success && response.data.items && response.data.items.length > 0) {
        const item = response.data.items.find(item => item.id == templateId);
        if (item && item.data) {
          cacheSystem.setItem(templateId, item.data);
          templates.updateTemplateFromCache(item.data, difficulty, index);
        }
      }
    }).catch(error => {
      // Silently fail
    });
  },

  updateTemplateFromCache: (itemData, difficulty, index) => {
    if (pveState.templatesData[difficulty][index]) {
      pveState.templatesData[difficulty][index].Name = itemData.Name || itemData.name || `Template ${itemData.TemplateID || itemData.id}`;
      pveState.templatesData[difficulty][index].NeedSex = itemData.NeedSex || itemData.sex || 0;
      pveState.templatesData[difficulty][index].Image = itemData.Icon || itemData.pic || '/assets/media/svg/files/blank-image.svg';
      
      setTimeout(() => {
        templates.renderGrid(difficulty);
      }, 100);
    }
  },

  renderGrid: (difficulty) => {
    const grid = $(`#${difficulty}_templates_grid`);
    if (grid.length === 0) {
      return;
    }
    
    const templates_data = pveState.templatesData[difficulty] || [];
    
    grid.empty();
    
    if (templates_data.length === 0) {
      grid.html(`
        <div class="templates-empty text-center p-4" style="border: 2px dashed #e9ecef; border-radius: 8px; background: #f8f9fa; min-height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
          <div style="font-size: 2rem; margin-bottom: 0.5rem;">📦</div>
          <div class="text-muted fw-bold">Nenhum template</div>
          <small class="text-muted">Arraste itens aqui da biblioteca</small>
        </div>
      `);
    } else {
      const containerHTML = `
        <div class="templates-container d-flex flex-wrap gap-2 p-3" 
             id="${difficulty}_sortable_container"
             style="min-height: 100px; border: 2px dashed #e9ecef; border-radius: 8px; background: #f8f9fa;">
          ${templates_data.map((item, index) => templates.createTemplateHTML(item, index + 1, difficulty)).join('')}
        </div>
      `;
      
      grid.html(containerHTML);
      
      if (typeof Sortable !== 'undefined') {
        templates.initializeSortable(`${difficulty}_sortable_container`, difficulty);
      }
    }
    
    templates.updateCounter(difficulty);
  },

  createTemplateHTML: (item, position, difficulty) => {
    const sexIcon = item.NeedSex == 1 ? ' 🧢' : item.NeedSex == 2 ? ' 🎀' : '';
    const imageUrl = item.Image || '/assets/media/svg/files/blank-image.svg';
    
    return `
      <div class="template-slot card shadow-sm border" 
           data-template-id="${item.TemplateID}" 
           data-position="${position}" 
           style="width: 100px; height: 120px; cursor: move; position: relative; transition: all 0.2s ease; background: white;">
        
        <div class="position-absolute top-0 start-0 m-1" style="z-index: 2;">
          <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">${position}</span>
        </div>
        
        <div class="position-absolute top-0 end-0 m-1" style="z-index: 2;">
          <button type="button" 
                  class="btn btn-danger btn-sm rounded-circle" 
                  style="width: 18px; height: 18px; padding: 0; font-size: 0.6rem; line-height: 1; display: flex; align-items: center; justify-content: center;" 
                  onclick="templates.removeTemplate('${difficulty}', '${item.TemplateID}')"
                  title="Remover template">×</button>
        </div>
        
        <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center h-100">
          <div class="template-image mb-2" style="width: 35px; height: 35px; flex-shrink: 0;">
            <img src="${imageUrl}" 
                 alt="${item.Name}" 
                 class="w-100 h-100 rounded border" 
                 style="object-fit: cover;" 
                 onerror="this.src='/assets/media/svg/files/blank-image.svg';">
          </div>
          
          <div class="text-center" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
            <div class="template-name text-truncate" 
                 style="font-size: 0.65rem; max-width: 85px; font-weight: 500;" 
                 title="${item.Name}${sexIcon}">${item.Name}${sexIcon}</div>
            <small class="text-muted" style="font-size: 0.55rem;">ID: ${item.TemplateID}</small>
          </div>
        </div>
      </div>
    `;
  },

  initializeSortable: (containerId, difficulty) => {
    const container = document.getElementById(containerId);
    if (!container) return;

    try {
      new Sortable(container, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
          const item = pveState.templatesData[difficulty].splice(evt.oldIndex, 1)[0];
          pveState.templatesData[difficulty].splice(evt.newIndex, 0, item);
          
          templates.updatePositions(difficulty);
          templates.saveToDatabase(difficulty);
          
          if (window.showSuccessToast) {
            window.showSuccessToast('Ordem dos templates atualizada! 🔄', '📦');
          }
        }
      });
    } catch (error) {
      console.error('Erro ao inicializar Sortable:', error);
    }
  },

  updatePositions: (difficulty) => {
    const container = $(`#${difficulty}_sortable_container`);
    container.find('.template-slot').each(function(index) {
      $(this).find('.badge').text(index + 1);
      $(this).attr('data-position', index + 1);
    });
    
    templates.updateCounter(difficulty);
  },

  updateCounter: (difficulty) => {
    const count = pveState.templatesData[difficulty] ? pveState.templatesData[difficulty].length : 0;
    $(`#${difficulty}_count`).text(count);
    templates.updateAllTabCounters();
    
    if (typeof library !== 'undefined' && library.updateIndividualCounter) {
      library.updateIndividualCounter(difficulty);
    }
  },

  updateAllTabCounters: () => {
    const difficulties = ['simple', 'normal', 'hard', 'terror', 'nightmare', 'epic'];
    
    difficulties.forEach(difficulty => {
      const count = pveState.templatesData[difficulty] ? pveState.templatesData[difficulty].length : 0;
      const tabButton = $(`.nav-link[href="#difficulty_${difficulty}"]`);
      if (tabButton.length > 0) {
        tabButton.find('.badge').remove();
        if (count > 0) tabButton.append(` <span class="badge bg-primary rounded-pill ms-1">${count}</span>`);
      }
      $(`#${difficulty}_count`).text(count);
    });
  },

  removeTemplate: (difficulty, templateId) => {
    if (confirm('Remover este template?')) {
      pveState.templatesData[difficulty] = pveState.templatesData[difficulty].filter(
        item => item.TemplateID != templateId
      );
      templates.renderGrid(difficulty);
      templates.saveToDatabase(difficulty);
      
      unsavedChanges.mark(`Template removido da dificuldade ${difficulty}`);
      
      if (window.showSuccessToast) {
        window.showSuccessToast('Template removido!', '🗑️');
      }
    }
  },

  addTemplate: (difficulty, itemData) => {
    if (!pveState.templatesData[difficulty]) {
      pveState.templatesData[difficulty] = [];
    }
    
    const templateId = itemData.TemplateID || itemData.id;
    const itemName = itemData.Name || itemData.name || `Item ${templateId}`;
    const itemSex = itemData.NeedSex || itemData.sex || 0;
    const itemIcon = itemData.Icon || itemData.pic || '/assets/media/svg/files/blank-image.svg';
    
    const exists = pveState.templatesData[difficulty].find(item => item.TemplateID == templateId);
    if (exists) {
      if (window.showErrorToast) {
        window.showErrorToast('Este item já está adicionado nesta dificuldade.');
      }
      return;
    }
    
    const newTemplate = {
      TemplateID: templateId,
      Name: itemName,
      NeedSex: itemSex,
      Image: itemIcon
    };
    
    pveState.templatesData[difficulty].push(newTemplate);
    
    templates.renderGrid(difficulty);
    cacheSystem.setItem(templateId, itemData);
    templates.saveToDatabase(difficulty);
    unsavedChanges.mark(`Template adicionado à dificuldade ${difficulty}`);
    
    if (window.showSuccessToast) {
      window.showSuccessToast(`Item "${itemName}" adicionado à ${difficulty}!`, '📦');
    }
  },

  saveToDatabase: (difficulty) => {
    if (!pveState.currentPve) return;
    
    const templateIds = pveState.templatesData[difficulty].map(item => item.TemplateID).join(',');
    
    const fieldMappings = {
      'simple': 'SimpleTemplateIds',
      'normal': 'NormalTemplateIds',
      'hard': 'HardTemplateIds',
      'terror': 'TerrorTemplateIds',
      'nightmare': 'NightmareTemplateIds',
      'epic': 'EpicTemplateIds'
    };
    
    const fieldName = fieldMappings[difficulty];
    if (!fieldName) return;
    
    const form = $('#pve_details_form');
    let formData = {};
    
    if (form.length > 0) {
      const formArray = form.serializeArray();
      formArray.forEach(field => {
        formData[field.name] = field.value;
      });
    }
    
    const data = {
      sid: parameters.pve.params.sid,
      pveId: pveState.currentPve.ID,
      
      ID: formData.ID || pveState.currentPve.ID,
      Name: formData.Name || pveState.currentPve.Name,
      Type: formData.Type || pveState.currentPve.Type,
      LevelLimits: formData.LevelLimits || pveState.currentPve.LevelLimits,
      Description: formData.Description || pveState.currentPve.Description || '',
      AdviceTips: formData.AdviceTips || pveState.currentPve.AdviceTips || '',
      Pic: formData.Pic || pveState.currentPve.Pic || '1072',
      Ordering: formData.Ordering || pveState.currentPve.Ordering || '0',
      
      SimpleGameScript: formData.SimpleGameScript || pveState.currentPve.SimpleGameScript || '',
      NormalGameScript: formData.NormalGameScript || pveState.currentPve.NormalGameScript || '',
      HardGameScript: formData.HardGameScript || pveState.currentPve.HardGameScript || '',
      TerrorGameScript: formData.TerrorGameScript || pveState.currentPve.TerrorGameScript || '',
      EpicGameScript: formData.EpicGameScript || pveState.currentPve.EpicGameScript || '',
      
      SimpleTemplateIds: difficulty === 'simple' ? templateIds : (pveState.currentPve.SimpleTemplateIds || ''),
      NormalTemplateIds: difficulty === 'normal' ? templateIds : (pveState.currentPve.NormalTemplateIds || ''),
      HardTemplateIds: difficulty === 'hard' ? templateIds : (pveState.currentPve.HardTemplateIds || ''),
      TerrorTemplateIds: difficulty === 'terror' ? templateIds : (pveState.currentPve.TerrorTemplateIds || ''),
      NightmareTemplateIds: difficulty === 'nightmare' ? templateIds : (pveState.currentPve.NightmareTemplateIds || ''),
      EpicTemplateIds: difficulty === 'epic' ? templateIds : (pveState.currentPve.EpicTemplateIds || ''),
      
      BossFightNeedMoney: pveState.currentPve.BossFightNeedMoney || ''
    };
    
    const costFields = ['cost_simple', 'cost_normal', 'cost_hard', 'cost_terror', 'cost_nightmare', 'cost_epic'];
    const costs = [];
    costFields.forEach(field => {
      costs.push(formData[field] || '0');
    });
    
    if (costs.some(cost => cost !== '0')) {
      data.costs = costs;
    }
    
    axios.put(`${baseUrl}/api/admin/game/pve`, data).then(res => {
      if (res.data && res.data.state) {
        if (pveState.currentPve) {
          pveState.currentPve[fieldName] = templateIds;
          Object.keys(data).forEach(key => {
            if (key !== 'sid' && key !== 'pveId' && key !== 'costs') {
              pveState.currentPve[key] = data[key];
            }
          });
        }
      }
    }).catch(error => {
      console.error('Erro ao salvar templates no servidor:', error);
      if (window.showErrorToast) {
        window.showErrorToast('Erro ao salvar no servidor');
      }
    });
  }
};

// ====================================
// BIBLIOTECA DE ITENS
// ====================================

const library = {
  initAll: () => {
    const difficulties = ['simple', 'normal', 'hard', 'terror', 'nightmare', 'epic'];
    
    difficulties.forEach(diff => {
      const tabId = `#difficulty_${diff}`;
      const tabPane = $(tabId);
      
      if (tabPane.length) {
        tabPane.find('.individual-library-container').remove();
        
        let insertTarget = tabPane.find('.col-lg-4, .col-md-4, .col-4').last();
        if (!insertTarget.length) {
          insertTarget = tabPane.find('.row').last();
          if (!insertTarget.length) {
            insertTarget = tabPane;
          }
        }
        
        const libraryHtml = `
          <div class="individual-library-container" data-difficulty="${diff}">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center gap-2">
                  <i class="fas fa-search"></i>
                  <span class="fw-bold">Biblioteca de Itens</span>
                </div>
              </div>
              <div class="card-body">
                <div class="library-counter-info bg-light rounded p-2 mb-3">
                  <small class="text-muted d-block">
                    📊 Templates em <strong>${library.getDifficultyDisplayName(diff)}</strong>: 
                    <span id="counter_${diff}" class="fw-bold text-success">0</span>
                  </small>
                </div>
                
                <div class="alert alert-info p-2 mb-3" style="font-size: 0.85rem;">
                  <i class="fas fa-info-circle"></i> 
                  Selecione um item para adicionar à dificuldade <strong>${library.getDifficultyDisplayName(diff)}</strong>
                </div>
                
                <select id="items_library_select_${diff}" class="form-select library-select" style="width: 100%;">
                  <option value="">🔍 Buscar item por nome ou ID...</option>
                </select>
              </div>
            </div>
          </div>
        `;
        
        insertTarget.append(libraryHtml);
        
        setTimeout(() => {
          library.initSelectForDifficulty(diff);
          library.updateIndividualCounter(diff);
        }, 100);
      }
    });
  },

  initSelectForDifficulty: (difficulty) => {
    if (typeof $.fn.select2 === 'undefined') {
      return;
    }

    const selectId = `items_library_select_${difficulty}`;
    const templateSelect = $(`#${selectId}`);
    
    if (!templateSelect.length) {
      return;
    }

    if (templateSelect.hasClass('select2-hidden-accessible')) {
      templateSelect.select2('destroy');
    }

    const optionFormat = (item) => {
      if (!item.id) return item.text;
      const span = document.createElement('span');
      span.innerHTML = '<img src="' + (item.pic || '/assets/media/svg/files/blank-image.svg') + '" class="h-30px me-2" alt="image" onerror="this.src=\'/assets/media/svg/files/blank-image.svg\'"/>' + item.text;
      return $(span);
    };

    templateSelect.select2({
      minimumInputLength: 2,
      templateResult: optionFormat,
      placeholder: '🔍 Buscar item por nome ou ID...',
      allowClear: true,
      ajax: {
        url: '/admin/gameutils/items/search',
        dataType: 'json',
        type: 'GET',
        delay: 250,
        data: (params) => {
          const searchKey = `${difficulty}_${params.term}`;
          return {
            search: params.term,
            pveId: pveState.currentPve ? pveState.currentPve.ID : null,
            sid: parameters.pve.params.sid,
            _cacheKey: searchKey
          };
        },
        processResults: (response, params) => {
          const data = typeof response === 'string' ? JSON.parse(response) : response;
          if (!data || !data.success || !Array.isArray(data.items)) return { results: [] };
          
          data.items.forEach(it => { 
            if (it.data) cacheSystem.setItem(it.id, it.data); 
          });
          
          const searchKey = `${difficulty}_${params.term}`;
          pveState.librarySearchCache.set(searchKey, {
            results: data.items,
            timestamp: Date.now()
          });
          
          return { 
            results: data.items.map(it => ({ 
              id: it.id, 
              text: it.text, 
              pic: it.pic, 
              itemData: it.data 
            })) 
          };
        },
        cache: true
      }
    });

    templateSelect.off('select2:select').on('select2:select', (e) => {
      const selected = e.params.data;
      if (!selected.itemData) return;
      
      const templateId = selected.itemData.TemplateID || selected.id;
      const exists = (pveState.templatesData[difficulty] || []).some(t => t.TemplateID == templateId);
      
      if (exists) {
        window.showErrorToast && window.showErrorToast(`Este item (ID: ${templateId}) já foi adicionado em ${library.getDifficultyDisplayName(difficulty)}!`);
        templateSelect.val(null).trigger('change');
        return;
      }
      
      templates.addTemplate(difficulty, selected.itemData);
      templateSelect.val(null).trigger('change');
      
      library.updateIndividualCounter(difficulty);
      library.updateAllCounters();
    });
  },

  showCurrent: (difficulty) => {
    if (!$(`.individual-library-container[data-difficulty="${difficulty}"]`).length) {
      library.initAll();
      return;
    }
    
    library.updateIndividualCounter(difficulty);
  },

  updateIndividualCounter: (difficulty) => {
    const count = pveState.templatesData[difficulty] ? pveState.templatesData[difficulty].length : 0;
    $(`#counter_${difficulty}`).text(count);
  },

  updateAllCounters: () => {
    const difficulties = ['simple', 'normal', 'hard', 'terror', 'nightmare', 'epic'];
    difficulties.forEach(diff => {
      library.updateIndividualCounter(diff);
    });
  },

  getDifficultyDisplayName: (difficulty) => {
    const names = {
      simple: 'Fácil',
      normal: 'Normal',
      hard: 'Difícil', 
      terror: 'Terror',
      nightmare: 'Pesadelo',
      epic: 'Épico'
    };
    return names[difficulty] || difficulty;
  },

  init: (difficulty = pveState.currentDifficulty) => {
    if (!$(`.individual-library-container[data-difficulty="${difficulty}"]`).length) {
      library.initAll();
      setTimeout(() => {
        library.showCurrent(difficulty);
      }, 200);
    } else {
      library.showCurrent(difficulty);
    }
  }
};

// ====================================
// CONTROLES E INICIALIZAÇÃO
// ====================================

const controls = {
  init: () => {
    if (typeof $ === 'undefined') {
      console.error('jQuery não carregado!');
      return;
    }
    
    if (typeof axios === 'undefined') {
      console.error('Axios não carregado!');
      return;
    }
    
    cacheSystem.loadItemsCache();
    const stateLoaded = persistentState.load();
    
    const sidSelect = $('select[name="sid"]');
    if (sidSelect.length > 0) {
      let sid = parameters.pve.params.sid || sidSelect.val();
      if (!sid) {
        sid = sidSelect.find('option:first').val();
        sidSelect.val(sid);
      }
      parameters.pve.params.sid = sid;
    }
    
    controls.setupEventListeners();
    controls.setupBeforeUnloadWarning();
    controls.setupStyles();
    pveState.isInitialized = true;
    
    if (parameters.pve.params.sid) {
      pve.list(parameters.pve.params.page).then(() => {
        if (stateLoaded && pveState.currentPve && pveState.currentPve.ID) {
          setTimeout(() => {
            const editBtn = $(`.edit-btn[data-id="${pveState.currentPve.ID}"]`);
            if (editBtn.length > 0) {
              editBtn.click();
            }
          }, 500);
        }
      });
    }
  },
  
  setupStyles: () => {
    if (!$('#pve-system-styles').length) {
      $('head').append(`
        <style id="pve-system-styles">
          @keyframes countdown { from { width: 100%; } to { width: 0%; } }
          .notification-item { transition: all 0.3s ease; }
          .countdown-bar { transition: width 1.5s linear; }
          .save-notification:hover .countdown-bar { animation-play-state: paused; }
          
          .difficulty-badge-with-count { display: inline-flex; align-items: center; gap: 4px; font-size: 0.65rem; padding: 2px 6px; border-radius: 12px; }
          .nav-pills .nav-link .badge { font-size: 0.6rem; padding: 2px 6px; line-height: 1; margin-left: 4px; }
          .btn-warning.unsaved-changes { animation: pulse-warning 2s infinite; }
          @keyframes pulse-warning { 0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); } }
          
          .template-slot { transition: all 0.2s ease; cursor: move; }
          .template-slot:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-color: #007bff !important; }
          .templates-grid { min-height: 120px; border: 2px dashed #e9ecef; border-radius: 8px; background: #f8f9fa; }
          .sortable-ghost { opacity: 0.4; background: #f8f9fa !important; transform: rotate(5deg); }
          .sortable-chosen { background: #e3f2fd !important; border-color: #2196f3 !important; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3); }
          .sortable-drag { background: #fff !important; box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important; transform: rotate(3deg); border-color: #007bff !important; z-index: 1000; }
          
          .badge-light-secondary { background: rgba(108, 117, 125, 0.1) !important; color: #6c757d !important; border: 1px solid rgba(108, 117, 125, 0.2) !important; }
          .nav-pills .nav-link, .nav-pills .nav-link .tab-title { color: #2d2d2d !important; white-space: nowrap; }
          .nav-pills .nav-link.active { color: #1b1b1b !important; background: #eef5ff !important; }
          
          #save_times_stack { display: flex; flex-direction: column; gap: 4px; }
          #save_times_stack small { display: block; }
          
          .individual-library-container { margin-top: 1rem; animation: fadeIn 0.3s ease; }
          .individual-library-container .card { border: 1px solid #e3f2fd; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
          .individual-library-container .card-header { background: linear-gradient(135deg, #007bff, #0056b3) !important; border-bottom: none; }
          .individual-library-container .library-counter-info { border-left: 3px solid #007bff; background: rgba(0, 123, 255, 0.05) !important; }
          .individual-library-container .alert-info { background: rgba(23, 162, 184, 0.1) !important; border-color: rgba(23, 162, 184, 0.2) !important; color: #155724 !important; }
          
          @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
          
          .countdown-bar { background: linear-gradient(90deg, #28a745 0%, #20c997 100%); border-radius: 0 0 6px 6px; }
          .notification-item { border-left: 4px solid #28a745; background: rgba(40, 167, 69, 0.05) !important; }
          .save-notification { position: relative; overflow: hidden; }
          .notification-content { position: relative; z-index: 2; }
        </style>
      `);
    }
  },
  
  setupEventListeners: () => {
  $('select[name="sid"]').off('change').on('change', function () {
    parameters.pve.params.sid = $(this).val();
    persistentState.save();
    pve.list();
  });

  // $('#pve_list_footer select[name="limit"]').off('change').on('change', function () {
  //   parameters.pve.params.limit = $(this).val();
  //   persistentState.save();
  //   pve.list();
  // });

  $('select[name="type_filter"]').off('change').on('change', function () {
    parameters.pve.params.type = $(this).val();
    persistentState.save();
    pve.list();
  });

  let searchTimeout;
  $('#search').off('input').on('input', function () {
    parameters.pve.params.search = $(this).val();
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      persistentState.save();
      pve.list();
    }, 500);
  });

  $('#difficulty_tabs a[data-bs-toggle="pill"]').off('shown.bs.tab').on('shown.bs.tab', function (e) {
    const difficulty = $(e.target).attr('href').replace('#difficulty_', '');
    pveState.currentDifficulty = difficulty;
    persistentState.save();
    
    if (pveState.currentPve) {
      templates.lazyLoadForDifficulty(difficulty);
    }
    
    setTimeout(() => {
      library.updateIndividualCounter(difficulty);
    }, 100);
  });

  $('a[data-bs-toggle="tab"]').off('shown.bs.tab').on('shown.bs.tab', function (e) {
    const target = $(e.target).attr('href');
    if (target) {
      pveState.currentTab = target.replace('#', '');
      persistentState.save();
    }
    
    if (target === '#pve_templates_tab' && pveState.currentPve) {
      setTimeout(() => {
        templates.lazyLoadForDifficulty(pveState.currentDifficulty);
        library.initAll();
      }, 300);
    }
  });

  $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    if (url) {
      const urlObj = new URL(url, window.location.origin);
      const page = urlObj.searchParams.get('page');
      if (page) {
        parameters.pve.params.page = parseInt(page);
        persistentState.save();
        pve.list(parameters.pve.params.page);
      }
    }
  });

  $(document).off('change', 'input[name="Type"]').on('change', 'input[name="Type"]', function() {
    const newType = parseInt($(this).val());
    if (newType && !isNaN(newType)) {
      const suggestedOrdering = pve.suggestNextOrdering(newType);
      const orderingField = $('input[name="Ordering"]');
      if (!orderingField.val() || orderingField.attr('placeholder').includes('Sugerido:')) {
        orderingField.val(suggestedOrdering);
        orderingField.attr('placeholder', `Sugerido: ${suggestedOrdering}`);
        if (window.showInfoToast) {
          window.showInfoToast(`Ordem sugerida: ${suggestedOrdering} (Tipo ${newType})`, '🔄');
        }
      }
    }
  });

  // Esconder apenas o campo de limite, mantendo a paginação
  $('#pve_list_footer select[name="limit"]').hide();
  // Garantir que a paginação continue visível
  $('#paginator').show();
},

setupBeforeUnloadWarning: () => {
  window.addEventListener('beforeunload', function (e) {
    if (pveState.hasUnsavedChanges) {
      e.preventDefault();
      e.returnValue = 'Você tem alterações não salvas. Tem certeza que deseja sair?';
      return e.returnValue;
    }
  });
}
};

// ====================================
// FUNÇÕES UTILITÁRIAS
// ====================================

window.clearPVECache = function() {
  cacheSystem.clearItemsCache();
  persistentState.clear();
  
  Object.keys(pveState.templatesLoaded).forEach(diff => {
    pveState.templatesLoaded[diff] = false;
  });
  
  pveState.librarySearchCache.clear();
  
  if (window.showSuccessToast) {
    window.showSuccessToast('Cache limpo com sucesso!', '🗑️');
  }
};

window.forceSavePVE = function() {
  if (pveState.currentPve) {
    pve.update();
  }
};

window.forceInitLibrary = function() {
  $('.individual-library-container').remove();
  library.initAll();
};

window.pveSystem = {
  pve,
  templates,
  library,
  controls,
  parameters,
  pveState,
  persistentState,
  cacheSystem,
  unsavedChanges,
  wsdlNotification,
  
  reload: () => pve.list(),
  clearCache: () => clearPVECache(),
  forceSave: () => forceSavePVE(),
  forceInitLibrary: () => forceInitLibrary()
};

// ====================================
// INICIALIZAÇÃO
// ====================================

$(document).ready(function() {
  if (window.location.pathname.includes('pve') || $('#pve_body').length > 0) {
    setTimeout(() => {
      if (typeof helper === 'undefined') {
        window.helper = {
          loader: (element, show) => {
            if (show) {
              $(element).html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>');
            }
          }
        };
      }
      
      if (typeof swMessage === 'undefined') {
        window.swMessage = (type, message) => {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: type === 'warning' ? 'warning' : type === 'error' ? 'error' : 'success',
              title: message,
              timer: 3000,
              showConfirmButton: false,
              toast: true,
              position: 'top-end'
            });
          } else {
            alert(`${type.toUpperCase()}: ${message}`);
          }
        };
      }
      
      controls.init();
    }, 250);
  }
});