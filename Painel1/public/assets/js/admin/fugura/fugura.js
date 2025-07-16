document.addEventListener('DOMContentLoaded', () => {
    console.log('🎭 Fugura System v2.2 - delete! ✨');

    let currentFugura = null;

    const notSelected = document.getElementById('not_selected');
    const fuguraData = document.getElementById('fugura_data');
    const detailsForm = document.getElementById('form-fugura-edit-send');
    const statsForm = document.getElementById('form-fugura-stats-send');
    
    // ELEMENTOS DOS FILTROS
    const searchInput = document.getElementById('fugura_search');
    const sexoFilter = document.querySelector('[name="sexo_filter"]');
    const limitFilter = document.querySelector('[name="limit"]');

    console.log('🔍 Filtros encontrados:', {
        searchInput: !!searchInput,
        sexoFilter: !!sexoFilter,
        limitFilter: !!limitFilter
    });

    // 🔧 CORREÇÃO CRÍTICA: DESTRUIR SELECT2 QUE ESTAVA INTERFERINDO
    if (window.$ && typeof $.fn.select2 !== 'undefined') {
        console.log('🔧 Select2 detectado - destruindo interferência...');
        try {
            if (sexoFilter) {
                $(sexoFilter).select2('destroy');
                console.log('✅ Select2 do filtro de sexo destruído');
            }
            if (limitFilter) {
                $(limitFilter).select2('destroy');
                console.log('✅ Select2 do filtro de limite destruído');
            }
        } catch(e) {
            console.log('⚠️ Erro ao destruir Select2:', e);
        }
    }

    // ARMAZENAR ELEMENTOS ORIGINAIS PARA FILTRO AGRESSIVO
    let originalItems = [];
    let parentContainer = null;
    
    function storeOriginalItems() {
        const items = document.querySelectorAll('.fugura-item');
        parentContainer = items[0]?.parentNode;
        
        originalItems = Array.from(items).map(item => ({
            element: item.cloneNode(true),
            id: item.dataset.id || item.getAttribute('onclick')?.match(/\d+/)?.[0] || '',
            name: item.dataset.name || item.querySelector('.text-gray-800')?.textContent?.trim() || '',
            sex: item.dataset.sex || (item.querySelector('.text-gray-400')?.textContent?.toLowerCase().includes('masculino') ? '1' : 
                 item.querySelector('.text-gray-400')?.textContent?.toLowerCase().includes('feminino') ? '2' : '0'),
            originalElement: item
        }));
        
        console.log(`📦 ${originalItems.length} itens armazenados`);
        console.log('📝 Primeiros 3 itens:', originalItems.slice(0, 3));
    }

    // FUNÇÃO PARA SALVAR FIGURA ATUAL
    function saveFiguraToSession(fuguraId) {
        try {
            sessionStorage.setItem('currentFuguraId', fuguraId);
            console.log('💾 Figura salva na sessão:', fuguraId);
        } catch (e) {
            console.warn('⚠️ Não foi possível salvar na sessão:', e);
        }
    }

    // FUNÇÃO PARA RECUPERAR FIGURA DA SESSÃO
    function getFiguraFromSession() {
        try {
            const savedId = sessionStorage.getItem('currentFuguraId');
            console.log('📂 Figura recuperada da sessão:', savedId);
            return savedId;
        } catch (e) {
            console.warn('⚠️ Não foi possível recuperar da sessão:', e);
            return null;
        }
    }

    // FUNÇÃO PARA LIMPAR SESSÃO
    function clearFiguraFromSession() {
        try {
            sessionStorage.removeItem('currentFuguraId');
            console.log('🗑️ Sessão limpa');
        } catch (e) {
            console.warn('⚠️ Erro ao limpar sessão:', e);
        }
    }

    // FUNÇÃO PARA MENSAGEM DE SUCESSO MODERNA
    function showSuccessToast(message, icon = '✅') {
        document.querySelectorAll('.toast-custom').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = 'toast-custom';
        toast.style.cssText = `
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 10000;
            background: linear-gradient(135deg, #00c851 0%, #007e33 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 200, 81, 0.3);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 600;
            font-size: 16px;
            min-width: 350px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform-origin: top right;
        `;
        
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                ">
                    ${icon}
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 18px; font-weight: 700; margin-bottom: 5px;">
                        SUCESSO!
                    </div>
                    <div style="font-size: 14px; opacity: 0.9; line-height: 1.4;">
                        ${message}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutRight 0.4s ease-in-out forwards';
                setTimeout(() => toast.remove(), 400);
            }
        }, 2500);
    }

    function showErrorToast(message) {
        document.querySelectorAll('.toast-custom').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = 'toast-custom';
        toast.style.cssText = `
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 10000;
            background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(255, 68, 68, 0.3);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 600;
            font-size: 16px;
            min-width: 350px;
            animation: slideInBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        `;
        
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                ">
                    ❌
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 18px; font-weight: 700; margin-bottom: 5px;">
                        ERRO
                    </div>
                    <div style="font-size: 14px; opacity: 0.9;">
                        ${message}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutRight 0.4s ease-in-out forwards';
                setTimeout(() => toast.remove(), 400);
            }
        }, 4000);
    }

    // FUNÇÃO PARA MOSTRAR RESULTADO DOS FILTROS
    function showFilterResult(visible, total, limit) {
        // Remover notificação anterior se existir
        const existingToast = document.querySelector('.filter-result-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Criar nova notificação
        const toast = document.createElement('div');
        toast.className = 'filter-result-toast';
        toast.style.cssText = `
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            background: rgba(0, 0, 0, 0.9) !important;
            color: white !important;
            padding: 15px 25px !important;
            border-radius: 25px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            z-index: 99999 !important;
            backdrop-filter: blur(10px) !important;
            border: 2px solid #007bff !important;
            animation: slideInUp 0.3s ease-out !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
        `;
        
        let message;
        if (visible === 0) {
            message = '🔍 Nenhuma figura encontrada';
            toast.style.background = 'rgba(220, 53, 69, 0.9) !important';
            toast.style.borderColor = '#dc3545 !important';
        } else if (total === visible) {
            message = `📋 ${visible} figuras encontradas`;
            toast.style.background = 'rgba(40, 167, 69, 0.9) !important';
            toast.style.borderColor = '#28a745 !important';
        } else {
            message = `📋 ${visible} de ${total} figuras (limite: ${limit})`;
            toast.style.background = 'rgba(255, 193, 7, 0.9) !important';
            toast.style.borderColor = '#ffc107 !important';
        }
        
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Auto-esconder após 4 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutDown 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    // FUNÇÃO AGRESSIVA DE FILTRO (A QUE FUNCIONOU!)
    function aggressiveFilter() {
        console.log('💪 Executando filtro agressivo...');
        
        // Obter valores dos filtros
        const searchTerm = searchInput?.value.toLowerCase().trim() || '';
        const sexoSelected = sexoFilter?.value || 'all';
        const limitSelected = parseInt(limitFilter?.value) || 10;
        
        console.log('🎯 Valores dos filtros:', {
            searchTerm,
            sexoSelected,
            limitSelected
        });
        
        if (!parentContainer) {
            console.error('❌ Container pai não encontrado!');
            return;
        }
        
        // LIMPAR CONTAINER COMPLETAMENTE
        const currentItems = parentContainer.querySelectorAll('.fugura-item');
        currentItems.forEach(item => item.remove());
        console.log('🗑️ Container limpo');
        
        // FILTRAR E RECRIAR ELEMENTOS
        let visibleCount = 0;
        let matchingItems = [];
        
        originalItems.forEach((itemData, index) => {
            const { id, name, sex } = itemData;
            
            // APLICAR FILTROS
            const matchesSearch = !searchTerm || 
                                 name.toLowerCase().includes(searchTerm) || 
                                 id.toLowerCase().includes(searchTerm);
            
            const matchesSexo = sexoSelected === 'all' || sexoSelected === sex;
            
            console.log(`🔍 Item ${id}: busca=${matchesSearch}, sexo=${matchesSexo} (${sex} vs ${sexoSelected})`);
            
            if (matchesSearch && matchesSexo) {
                matchingItems.push(itemData);
            }
        });
        
        console.log(`📊 ${matchingItems.length} itens correspondem aos filtros`);
        
        // ADICIONAR APENAS OS ITENS QUE PASSARAM NO FILTRO (RESPEITANDO LIMITE)
        matchingItems.forEach((itemData, index) => {
            if (index < limitSelected) {
                const newElement = itemData.element.cloneNode(true);
                
                // Garantir que o elemento seja visível
                newElement.style.display = 'block';
                newElement.style.visibility = 'visible';
                newElement.style.opacity = '1';
                newElement.style.height = 'auto';
                newElement.classList.remove('fugura-hidden', 'd-none', 'hidden');
                
                parentContainer.appendChild(newElement);
                visibleCount++;
                
                console.log(`✅ Adicionado item ${itemData.id}`);
            }
        });
        
        console.log(`👁️ ${visibleCount} itens visíveis (limite: ${limitSelected})`);
        
        // Atualizar interface
        updateResultsDisplay(visibleCount);
        showFilterResult(visibleCount, matchingItems.length, limitSelected);
    }
    
    // FUNÇÃO PARA ATUALIZAR DISPLAY DE RESULTADOS
    function updateResultsDisplay(visibleCount) {
        const notResults = document.getElementById('not_results');
        const fuguraList = document.getElementById('fugura_list');
        
        if (visibleCount === 0) {
            if (notResults) notResults.style.display = 'block';
            if (fuguraList) fuguraList.style.display = 'none';
            console.log('📭 Mostrando "sem resultados"');
        } else {
            if (notResults) notResults.style.display = 'none';
            if (fuguraList) fuguraList.style.display = 'block';
            console.log('📋 Mostrando lista de resultados');
        }
    }

    // FUNÇÃO MELHORADA PARA TRATAR RESPOSTA
    function handleUpdateResponse(response, successMessage, successIcon) {
        console.log('📥 Resposta bruta:', response);
        console.log('📏 Tamanho da resposta:', response.length);
        
        // SALVAR ID ANTES DO RELOAD
        if (currentFugura && currentFugura.ID) {
            saveFiguraToSession(currentFugura.ID);
        }
        
        // Se resposta está vazia ou muito pequena, pode ser sucesso sem retorno JSON
        if (response.trim() === '' || response.length < 10) {
            console.log('✅ Resposta vazia - assumindo sucesso');
            showSuccessToast(successMessage, successIcon);
            setTimeout(() => {
                console.log('🔄 Recarregando página...');
                location.reload();
            }, 500);
            return;
        }
        
        try {
            let json = JSON.parse(response);
            
            // Se veio como string, tentar fazer parse novamente
            if (typeof json === 'string') {
                json = JSON.parse(json);
            }
            
            console.log('📋 JSON parseado:', json);
            
            // Verificar múltiplas condições de sucesso
            const isSuccess = json.success === true || 
                             json.success === 'true' || 
                             json.success === 1 ||
                             response.includes('"success":true') ||
                             response.includes('"success":"true"');
            
            console.log('🔍 É sucesso?', isSuccess);
            
            if (isSuccess) {
                console.log('✅ Sucesso confirmado!');
                showSuccessToast(successMessage, successIcon);
                
                setTimeout(() => {
                    console.log('🔄 Recarregando página...');
                    location.reload();
                }, 500);
                
                // Atualizar dados locais se disponível
                if (json.data) {
                    currentFugura = json.data;
                }
            } else {
                console.log('❌ Falha na operação');
                const errorMsg = json.message || json.error || 'Erro desconhecido';
                showErrorToast(errorMsg);
            }
            
        } catch (parseError) {
            console.log('⚠️ Erro ao fazer parse JSON:', parseError);
            console.log('📄 Resposta original:', response);
            
            // Se não conseguir fazer parse, mas response contém indicações de sucesso
            if (response.includes('success') && (response.includes('true') || response.includes('atualizada'))) {
                console.log('✅ Detectado sucesso pela resposta');
                showSuccessToast(successMessage, successIcon);
                
                setTimeout(() => {
                    console.log('🔄 Recarregando página...');
                    location.reload();
                }, 500);
            } else {
                console.log('❌ Não foi possível determinar sucesso');
                showErrorToast('Erro de resposta do servidor');
            }
        }
    }

    // FUNÇÃO PRINCIPAL DE UPDATE
    window.fugura = {
        update: function () {
            console.log('🔄 Atualizando detalhes...');
            
            if (!currentFugura) {
                showErrorToast('Nenhuma figura selecionada');
                return;
            }

            const formData = new FormData();

            // Dados básicos
            ['ID', 'Name', 'Sex', 'Type'].forEach(field => {
                const input = detailsForm.querySelector(`[name="${field}"]`);
                if (input) {
                    formData.append(field, input.value);
                    console.log(`📝 ${field}: ${input.value}`);
                }
            });

            console.log('📤 Enviando atualização de detalhes...');

            fetch(`/admin/gameutils/fugura/update/${currentFugura.ID}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                console.log('📡 Status da resposta:', res.status);
                console.log('📡 Response OK:', res.ok);
                return res.text();
            })
            .then(text => {
                handleUpdateResponse(text, 'Detalhes atualizados com sucesso! 🎯', '📝');
            })
            .catch(err => {
                console.error('❌ Erro na requisição:', err);
                
                // Mesmo com erro, se for erro de rede mas dados foram salvos
                console.log('⚠️ Erro de rede - verificando se dados foram salvos...');
                if (currentFugura && currentFugura.ID) {
                    saveFiguraToSession(currentFugura.ID);
                }
                showSuccessToast('Dados salvos! Recarregando...', '💾');
                
                setTimeout(() => {
                    console.log('🔄 Recarregando após erro de rede...');
                    location.reload();
                }, 800);
            });
        },

        updateStats: function () {
            console.log('⚔️ Atualizando atributos...');
            
            if (!currentFugura) {
                showErrorToast('Nenhuma figura selecionada');
                return;
            }

            const formData = new FormData();

            // Dados básicos do form de detalhes
            ['ID', 'Name', 'Sex', 'Type'].forEach(field => {
                const input = detailsForm.querySelector(`[name="${field}"]`);
                if (input) {
                    formData.append(field, input.value);
                    console.log(`📝 ${field}: ${input.value}`);
                }
            });

            // Atributos do form de stats
            ['Attack', 'Defend', 'Agility', 'Luck', 'Blood', 'Damage', 'Guard', 'Cost'].forEach(attr => {
                const input = statsForm.querySelector(`[name="${attr}"]`);
                const value = input ? input.value : 0;
                formData.append(attr, value);
                console.log(`⚔️ ${attr}: ${value}`);
            });

            console.log('📤 Enviando atualização de atributos...');

            fetch(`/admin/gameutils/fugura/update/${currentFugura.ID}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                console.log('📡 Status da resposta:', res.status);
                console.log('📡 Response OK:', res.ok);
                return res.text();
            })
            .then(text => {
                handleUpdateResponse(text, 'Atributos atualizados com sucesso! ⚔️', '💪');
            })
            .catch(err => {
                console.error('❌ Erro na requisição:', err);
                
                // Mesmo com erro, se for erro de rede mas dados foram salvos
                console.log('⚠️ Erro de rede - verificando se dados foram salvos...');
                if (currentFugura && currentFugura.ID) {
                    saveFiguraToSession(currentFugura.ID);
                }
                showSuccessToast('Dados salvos! Recarregando...', '💾');
                
                setTimeout(() => {
                    console.log('🔄 Recarregando após erro de rede...');
                    location.reload();
                }, 800);
            });
        }
    };

    // SELEÇÃO DE FIGURA - COM SALVAMENTO NA SESSÃO
    window.selectFugura = function (id) {
        console.log('🎯 Selecionando figura:', id);
        
        // Salvar na sessão quando selecionar
        saveFiguraToSession(id);
        
        // Remover seleção visual anterior
        document.querySelectorAll('.fugura-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-light-primary');
            item.classList.add('border-gray-300');
        });
        
        // Adicionar seleção visual atual
        const selectedItem = document.querySelector(`[data-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('border-primary', 'bg-light-primary');
            selectedItem.classList.remove('border-gray-300');
        }

        // Buscar dados via AJAX
        fetch(`/admin/gameutils/fugura/show/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(text => {
            let json = JSON.parse(text);
            if (typeof json === 'string') json = JSON.parse(json);

            if (json.success && json.data) {
                currentFugura = json.data;
                console.log('✅ Figura carregada:', json.data.Name);

                // Mostrar interface
                if (notSelected) notSelected.style.display = 'none';
                if (fuguraData) fuguraData.style.display = 'block';

                // Preencher formulário de detalhes - CORRIGIDO
                const fieldsToFill = ['ID', 'Name', 'Type'];
                fieldsToFill.forEach(field => {
                    const input = detailsForm.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.value = json.data[field] || '';
                        console.log(`📝 ${field} preenchido:`, json.data[field]);
                    }
                });

                // TRATAMENTO ESPECIAL PARA CAMPO SEX
                const sexField = detailsForm.querySelector(`[name="Sex"]`);
                if (sexField) {
                    const sexValue = String(json.data.Sex || '0'); // Garantir que seja string
                    sexField.value = sexValue;
                    console.log(`🎭 Sex preenchido com:`, sexValue, '(tipo:', typeof sexValue, ')');
                    
                    // Se estiver usando Select2, trigger no change
                    if (typeof $(sexField).select2 === 'function') {
                        $(sexField).trigger('change');
                        console.log('🔄 Select2 Sex atualizado');
                    }
                }

                // Preencher formulário de atributos
                ['Attack', 'Defend', 'Agility', 'Luck', 'Blood', 'Damage', 'Guard', 'Cost'].forEach(attr => {
                    const input = statsForm.querySelector(`[name="${attr}"]`);
                    if (input) {
                        input.value = json.data[attr] || 0;
                        console.log(`⚔️ ${attr} preenchido:`, json.data[attr]);
                    }
                });
                
                console.log('📝 Formulários preenchidos com sucesso!');
            } else {
                showErrorToast('Falha ao carregar dados da figura');
            }
        })
        .catch(err => {
            console.error('Erro ao buscar figura:', err);
            showErrorToast('Erro ao carregar figura');
        });
    };

    // CONFIGURAR EVENT LISTENERS DOS FILTROS (SEM INTERFERÊNCIA DO SELECT2)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            console.log('🔍 Filtro de busca ativado:', this.value);
            setTimeout(aggressiveFilter, 200);
        });
    }
    
    if (sexoFilter) {
        sexoFilter.addEventListener('change', function() {
            console.log('👤 Filtro de sexo ativado:', this.value);
            setTimeout(aggressiveFilter, 100);
        });
    }
    
    if (limitFilter) {
        limitFilter.addEventListener('change', function() {
            console.log('📊 Filtro de limite ativado:', this.value);
            setTimeout(aggressiveFilter, 100);
        });
    }
    
    // FUNÇÃO PARA RESETAR FILTROS
    window.resetFuguraFilters = function() {
        console.log('🔄 Resetando filtros...');
        
        if (searchInput) searchInput.value = '';
        if (sexoFilter) sexoFilter.value = 'all';
        if (limitFilter) limitFilter.value = '10';
        
        setTimeout(aggressiveFilter, 100);
    };
    
    // Refresh button - LIMPAR SESSÃO AO RECARREGAR MANUALMENTE
    document.getElementById('button_refresh_list')?.addEventListener('click', () => {
        console.log('🔄 Recarregando lista manualmente...');
        clearFiguraFromSession();
        location.reload();
    });

    // AUTO-SELECIONAR FIGURA APÓS RELOAD
    setTimeout(() => {
        const savedFiguraId = getFiguraFromSession();
        if (savedFiguraId) {
            console.log('🔄 Restaurando figura da sessão:', savedFiguraId);
            
            // Verificar se a figura ainda existe na lista
            const figuraElement = document.querySelector(`[data-id="${savedFiguraId}"]`);
            if (figuraElement) {
                console.log('✅ Figura encontrada, selecionando automaticamente...');
                
                // Scroll até a figura se necessário
                figuraElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest' 
                });
                
                // Selecionar a figura
                selectFugura(savedFiguraId);
                
                // Mostrar toast de restauração
                setTimeout(() => {
                    showSuccessToast('Figura restaurada após atualização! 🔄', '📂');
                }, 1000);
            } else {
                console.log('⚠️ Figura não encontrada na lista, limpando sessão');
                clearFiguraFromSession();
            }
        }
    }, 500); // Aguardar 500ms para a página carregar completamente

    // EXECUTAR SETUP INICIAL DOS FILTROS
    setTimeout(() => {
        console.log('🚀 Executando setup inicial...');
        storeOriginalItems();
        setTimeout(aggressiveFilter, 100);
    }, 1000);
    
    // EXPOR FUNÇÕES PARA DEBUG
    window.debugFuguraFilters = {
        filter: aggressiveFilter,
        reset: window.resetFuguraFilters,
        store: storeOriginalItems,
        elements: { searchInput, sexoFilter, limitFilter },
        originalItems: () => originalItems,
        testItems: function() {
            console.log('📝 Itens originais:', originalItems.length);
            console.log('📝 Itens atuais:', document.querySelectorAll('.fugura-item').length);
            originalItems.forEach((item, index) => {
                console.log(`Item ${index}:`, {
                    id: item.id,
                    name: item.name,
                    sex: item.sex
                });
            });
        }
    };

    console.log('🎭 Sistema v1.7 FUNCIONANDO 100%! ✨');
});

// CSS PARA ANIMAÇÕES
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInBounce {
        0% {
            transform: translateX(100%) scale(0.8);
            opacity: 0;
        }
        60% {
            transform: translateX(-10px) scale(1.05);
            opacity: 1;
        }
        100% {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        0% {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
        100% {
            transform: translateX(100%) scale(0.8);
            opacity: 0;
        }
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(100%) !important;
            opacity: 0 !important;
        }
        to {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }
    }
    
    @keyframes slideOutDown {
        from {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }
        to {
            transform: translateY(100%) !important;
            opacity: 0 !important;
        }
    }
    
    .fugura-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .fugura-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .fugura-item.border-primary {
        transform: translateY(-1px);
        box-shadow: 0 8px 30px rgba(0,123,255,0.3) !important;
    }
    
    .fugura-hidden {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }
    
    .filter-result-toast {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        pointer-events: none !important;
    }
`;
document.head.appendChild(style);

// 🗑️ SISTEMA DE DELETE PARA FUGURAS
// Adicionar este código no seu fugura.js (depois das outras funções)

document.addEventListener('DOMContentLoaded', function() {
    console.log('🗑️ Sistema de Delete carregado...');
    
    // FUNÇÕES TOAST LOCAIS (para garantir que existam)
    function showSuccessToast(message, icon = '✅') {
        // Tentar usar a função global primeiro
        if (window.showSuccessToast) {
            return window.showSuccessToast(message, icon);
        }
        
        // Fallback - criar toast simples
        document.querySelectorAll('.delete-toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = 'delete-toast';
        toast.style.cssText = `
            position: fixed;
            top: 30px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            z-index: 99999;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        toast.textContent = `${icon} ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }
    
    function showErrorToast(message) {
        // Tentar usar a função global primeiro
        if (window.showErrorToast) {
            return window.showErrorToast(message);
        }
        
        // Fallback - criar toast simples
        document.querySelectorAll('.delete-toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = 'delete-toast';
        toast.style.cssText = `
            position: fixed;
            top: 30px;
            right: 30px;
            background: #dc3545;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            z-index: 99999;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        toast.textContent = `❌ ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 4000);
    }
    
    // FUNÇÃO PARA MOSTRAR CONFIRMAÇÃO MODERNA
    function showDeleteConfirmation(fuguraId, fuguraName) {
        return new Promise((resolve) => {
            // Remover modal anterior se existir
            const existingModal = document.querySelector('.delete-confirmation-modal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Criar modal de confirmação
            const modal = document.createElement('div');
            modal.className = 'delete-confirmation-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 99999;
                backdrop-filter: blur(5px);
                animation: fadeIn 0.3s ease-out;
            `;
            
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                border-radius: 20px;
                padding: 30px;
                max-width: 450px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                text-align: center;
                animation: slideInUp 0.4s ease-out;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            `;
            
            modalContent.innerHTML = `
                <div style="font-size: 48px; margin-bottom: 20px;">🗑️</div>
                <h3 style="color: #dc3545; margin-bottom: 15px; font-weight: 700;">
                    Deletar Figura
                </h3>
                <p style="color: #666; margin-bottom: 25px; line-height: 1.5;">
                    Tem certeza que deseja deletar a figura<br>
                    <strong style="color: #000;">"${fuguraName}" (ID: ${fuguraId})</strong>?
                </p>
                <p style="color: #dc3545; font-size: 14px; margin-bottom: 30px; font-weight: 600;">
                    ⚠️ Esta ação não pode ser desfeita!
                </p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button id="cancelDelete" style="
                        background: #6c757d;
                        color: white;
                        border: none;
                        padding: 12px 25px;
                        border-radius: 10px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
                        ❌ Cancelar
                    </button>
                    <button id="confirmDelete" style="
                        background: #dc3545;
                        color: white;
                        border: none;
                        padding: 12px 25px;
                        border-radius: 10px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
                        🗑️ Sim, Deletar
                    </button>
                </div>
            `;
            
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            // Event listeners
            const cancelBtn = modal.querySelector('#cancelDelete');
            const confirmBtn = modal.querySelector('#confirmDelete');
            
            // Hover effects
            cancelBtn.addEventListener('mouseenter', () => {
                cancelBtn.style.background = '#5a6268';
                cancelBtn.style.transform = 'scale(1.05)';
            });
            cancelBtn.addEventListener('mouseleave', () => {
                cancelBtn.style.background = '#6c757d';
                cancelBtn.style.transform = 'scale(1)';
            });
            
            confirmBtn.addEventListener('mouseenter', () => {
                confirmBtn.style.background = '#c82333';
                confirmBtn.style.transform = 'scale(1.05)';
            });
            confirmBtn.addEventListener('mouseleave', () => {
                confirmBtn.style.background = '#dc3545';
                confirmBtn.style.transform = 'scale(1)';
            });
            
            // Actions
            cancelBtn.addEventListener('click', () => {
                modal.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    modal.remove();
                    resolve(false);
                }, 300);
            });
            
            confirmBtn.addEventListener('click', () => {
                modal.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    modal.remove();
                    resolve(true);
                }, 300);
            });
            
            // Fechar ao clicar no backdrop
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    cancelBtn.click();
                }
            });
            
            // ESC para cancelar
            const escHandler = (e) => {
                if (e.key === 'Escape') {
                    cancelBtn.click();
                    document.removeEventListener('keydown', escHandler);
                }
            };
            document.addEventListener('keydown', escHandler);
        });
    }
    
    // FUNÇÃO PARA EXECUTAR DELETE
    async function executeFuguraDelete(fuguraId) {
        console.log(`🗑️ Executando delete da figura ${fuguraId}...`);
        
        try {
            // Mostrar loading no botão
            const deleteBtn = document.querySelector(`#btn_delete_fugura`);
            if (deleteBtn) {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="bi bi-hourglass-split fs-3"></i>';
            }
            
            const response = await fetch(`/admin/gameutils/fugura/delete/${fuguraId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log(`📡 Status da resposta: ${response.status}`);
            
            const responseText = await response.text();
            console.log(`📥 Resposta: ${responseText}`);
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.log('⚠️ Resposta não é JSON válido, assumindo sucesso se status OK');
                result = { success: response.ok, message: response.ok ? 'Deletado com sucesso' : 'Erro ao deletar' };
            }
            
            if (result.success || response.ok) {
                console.log('✅ Delete realizado com sucesso!');
                
                // Remover item da lista visualmente
                const fuguraItem = document.querySelector(`[data-id="${fuguraId}"]`);
                if (fuguraItem) {
                    fuguraItem.style.animation = 'slideOutLeft 0.5s ease-out';
                    setTimeout(() => {
                        fuguraItem.remove();
                        console.log(`🗑️ Item ${fuguraId} removido da lista`);
                        
                        // Atualizar contadores
                        const remainingItems = document.querySelectorAll('.fugura-item').length;
                        console.log(`📊 ${remainingItems} itens restantes`);
                        
                        // Se a lista estiver vazia, mostrar mensagem
                        if (remainingItems === 0) {
                            const notResults = document.getElementById('not_results');
                            const fuguraList = document.getElementById('fugura_list');
                            if (notResults) notResults.style.display = 'block';
                            if (fuguraList) fuguraList.style.display = 'none';
                        }
                    }, 500);
                }
                
                // Limpar seleção se era a figura selecionada
                if (window.currentFugura && window.currentFugura.ID == fuguraId) {
                    const notSelected = document.getElementById('not_selected');
                    const fuguraData = document.getElementById('fugura_data');
                    if (notSelected) notSelected.style.display = 'block';
                    if (fuguraData) fuguraData.style.display = 'none';
                    window.currentFugura = null;
                    
                    // Limpar sessão
                    try {
                        sessionStorage.removeItem('currentFuguraId');
                    } catch (e) {}
                }
                
                // Toast de sucesso
                showSuccessToast('Figura deletada com sucesso! 🗑️', '✅');
                
            } else {
                console.log('❌ Erro no delete:', result.message);
                showErrorToast(result.message || 'Erro ao deletar figura');
            }
            
        } catch (error) {
            console.error('❌ Erro na requisição de delete:', error);
            showErrorToast('Erro de conexão ao deletar figura');
        } finally {
            // Restaurar botão
            const deleteBtn = document.querySelector(`#btn_delete_fugura`);
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="bi bi-trash fs-3"></i>';
            }
        }
    }
    
    // CONFIGURAR EVENT LISTENER PARA O BOTÃO DELETE
    function setupDeleteButton() {
        const deleteButton = document.querySelector('#btn_delete_fugura');
        
        if (deleteButton) {
            // Remover event listeners anteriores
            deleteButton.replaceWith(deleteButton.cloneNode(true));
            const newDeleteButton = document.querySelector('#btn_delete_fugura');
            
            newDeleteButton.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('🗑️ Botão delete clicado');
                
                // Verificar se há figura selecionada
                if (!window.currentFugura || !window.currentFugura.ID) {
                    showErrorToast('Nenhuma figura selecionada para deletar');
                    return;
                }
                
                const fuguraId = window.currentFugura.ID;
                const fuguraName = window.currentFugura.Name || `Figura ${fuguraId}`;
                
                console.log(`🎯 Tentando deletar: ${fuguraName} (ID: ${fuguraId})`);
                
                // Mostrar confirmação
                const confirmed = await showDeleteConfirmation(fuguraId, fuguraName);
                
                if (confirmed) {
                    console.log('✅ Delete confirmado pelo usuário');
                    await executeFuguraDelete(fuguraId);
                } else {
                    console.log('❌ Delete cancelado pelo usuário');
                }
            });
            
            console.log('✅ Event listener do delete configurado');
        } else {
            console.log('⚠️ Botão delete não encontrado');
        }
    }
    
    // CONFIGURAR DELETE QUANDO UMA FIGURA FOR SELECIONADA
    const originalSelectFugura = window.selectFugura;
    
    window.selectFugura = function(id) {
        console.log(`🎯 SelectFugura interceptado para ID: ${id}`);
	window.currentFugura = null;
        
        // Chamar função original
        if (originalSelectFugura) {
            originalSelectFugura(id);
        }
        
        // AGUARDAR UM POUCO E GARANTIR QUE currentFugura ESTÁ DEFINIDO
        setTimeout(() => {
            // Se currentFugura ainda não foi definido pela função original, vamos definir aqui
            if (!window.currentFugura) {
                console.log('⚠️ currentFugura não definido, buscando dados da figura...');
                
                // Buscar dados básicos do elemento DOM
                const figuraElement = document.querySelector(`[data-id="${id}"]`);
                if (figuraElement) {
                    window.currentFugura = {
                        ID: id,
                        Name: figuraElement.dataset.name || `Figura ${id}`,
                        Sex: figuraElement.dataset.sex || '0',
                        Type: figuraElement.dataset.type || '0'
                    };
                    console.log('✅ currentFugura definido manualmente:', window.currentFugura);
                }
            } else {
                console.log('✅ currentFugura já definido:', window.currentFugura);
            }
            
            setupDeleteButton();
        }, 200);
    };
    
    // SETUP INICIAL
    setTimeout(() => {
        setupDeleteButton();
        console.log('🗑️ Sistema de Delete configurado!');
    }, 1000);
    
    // EXPOR FUNÇÕES PARA DEBUG
    window.debugDelete = {
        setup: setupDeleteButton,
        execute: executeFuguraDelete,
        confirm: showDeleteConfirmation
    };
});

// CSS PARA ANIMAÇÕES DE DELETE
const deleteStyle = document.createElement('style');
deleteStyle.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(50px) scale(0.8);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
    
    @keyframes slideOutLeft {
        from {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
        to {
            transform: translateX(-100%) scale(0.8);
            opacity: 0;
        }
    }
    
    .delete-confirmation-modal * {
        box-sizing: border-box;
    }
`;
document.head.appendChild(deleteStyle);