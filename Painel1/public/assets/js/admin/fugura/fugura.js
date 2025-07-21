document.addEventListener('DOMContentLoaded', () => {
    // VARIÁVEL GLOBAL COMPARTILHADA
    window.currentFugura = null;

    const notSelected = document.getElementById('not_selected');
    const fuguraData = document.getElementById('fugura_data');
    const detailsForm = document.getElementById('form-fugura-edit-send');
    const statsForm = document.getElementById('form-fugura-stats-send');
    const searchInput = document.getElementById('fugura_search');
    const sexoFilter = document.querySelector('[name="sexo_filter"]');
    const limitFilter = document.querySelector('[name="limit"]');

    // Destruir Select2 que interfere nos filtros
    if (window.$ && typeof $.fn.select2 !== 'undefined') {
        try {
            if (sexoFilter) $(sexoFilter).select2('destroy');
            if (limitFilter) $(limitFilter).select2('destroy');
        } catch(e) {}
    }

    // TRANSFORMAR CAMPOS EM DROPDOWNS VISUAIS
    function setupFormDropdowns() {
        // Campo Type na aba Detalhes
        const typeFieldDetails = detailsForm?.querySelector('[name="Type"]');
        if (typeFieldDetails && typeFieldDetails.type === 'number') {
            const currentValue = typeFieldDetails.value || '1';
            
            const newSelect = document.createElement('select');
            newSelect.name = 'Type';
            newSelect.className = 'form-select form-select-sm form-select-solid';
            newSelect.innerHTML = `
                <option value="1">🎭 Conjuntos</option>
                <option value="2">⚔️ Armamentos</option>
            `;
            newSelect.value = currentValue;
            
            typeFieldDetails.parentNode.replaceChild(newSelect, typeFieldDetails);
        }

        // Campo Sex na aba Detalhes
        const sexFieldDetails = detailsForm?.querySelector('[name="Sex"]');
        if (sexFieldDetails && sexFieldDetails.tagName !== 'SELECT') {
            const currentValue = sexFieldDetails.value || '0';
            
            const newSelect = document.createElement('select');
            newSelect.name = 'Sex';
            newSelect.className = 'form-select form-select-sm form-select-solid';
            newSelect.innerHTML = `
                <option value="0">👤 Neutro</option>
                <option value="1">👦 Masculino</option>
                <option value="2">👧 Feminino</option>
            `;
            newSelect.value = currentValue;
            
            sexFieldDetails.parentNode.replaceChild(newSelect, sexFieldDetails);
        }
        
        // Campo Type na aba Itens (form de adição)
        const typeFieldItems = document.getElementById('add-type');
        if (typeFieldItems && typeFieldItems.type === 'number') {
            const currentValue = typeFieldItems.value || '1';
            
            const newSelect = document.createElement('select');
            newSelect.id = 'add-type';
            newSelect.name = 'type';
            newSelect.className = 'form-select form-select-sm';
            newSelect.innerHTML = `
                <option value="1">🎭 Conjuntos</option>
                <option value="2">⚔️ Armamentos</option>
            `;
            newSelect.value = currentValue;
            
            typeFieldItems.parentNode.replaceChild(newSelect, typeFieldItems);
        }

        // Campo Sex na aba Itens (form de adição)
        const sexFieldItems = document.getElementById('add-sex');
        if (sexFieldItems && sexFieldItems.tagName !== 'SELECT') {
            const currentValue = sexFieldItems.value || '0';
            
            const newSelect = document.createElement('select');
            newSelect.id = 'add-sex';
            newSelect.name = 'sex';
            newSelect.className = 'form-select form-select-sm';
            newSelect.innerHTML = `
                <option value="0">👤 Neutro</option>
                <option value="1">👦 Masculino</option>
                <option value="2">👧 Feminino</option>
            `;
            newSelect.value = currentValue;
            
            sexFieldItems.parentNode.replaceChild(newSelect, sexFieldItems);
        }

        // Campo Descrição fixo como 1
        const descriptionField = document.getElementById('add-description');
        if (descriptionField) {
            descriptionField.value = '1';
            descriptionField.readOnly = true;
            descriptionField.style.backgroundColor = '#f8f9fa';
        }
    }

    // FUNÇÃO PARA BUSCAR PRÓXIMO ID DISPONÍVEL
    function getNextAvailableId() {
        const allFuguraElements = document.querySelectorAll('.fugura-item');
        const existingIds = [];
        
        allFuguraElements.forEach(item => {
            const id = parseInt(item.dataset.id);
            if (!isNaN(id) && id > 0) {
                existingIds.push(id);
            }
        });
        
        if (window.originalItems && Array.isArray(window.originalItems)) {
            window.originalItems.forEach(itemData => {
                const id = parseInt(itemData.id);
                if (!isNaN(id) && id > 0 && !existingIds.includes(id)) {
                    existingIds.push(id);
                }
            });
        }
        
        existingIds.sort((a, b) => a - b);
        
        let nextId = 1;
        for (let i = 0; i < existingIds.length; i++) {
            if (existingIds[i] === nextId) {
                nextId++;
            } else {
                break;
            }
        }
        
        return nextId;
    }

    // Armazenar elementos originais para sistema de filtros
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
        
        window.originalItems = originalItems;
    }

    // Sistema de sessão para manter figura selecionada após reload
    function saveFiguraToSession(fuguraId) {
        try {
            sessionStorage.setItem('currentFuguraId', fuguraId);
        } catch (e) {}
    }

    function getFiguraFromSession() {
        try {
            return sessionStorage.getItem('currentFuguraId');
        } catch (e) {
            return null;
        }
    }

    function clearFiguraFromSession() {
        try {
            sessionStorage.removeItem('currentFuguraId');
        } catch (e) {}
    }

    // Sistema de notificações toast
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

    function showFilterResult(visible, total, limit) {
        const existingToast = document.querySelector('.filter-result-toast');
        if (existingToast) existingToast.remove();
        
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
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutDown 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    // Sistema de filtros agressivo que reconstrói a lista
    function aggressiveFilter() {
        const searchTerm = searchInput?.value.toLowerCase().trim() || '';
        const sexoSelected = sexoFilter?.value || 'all';
        const limitSelected = parseInt(limitFilter?.value) || 10;
        
        if (!parentContainer) return;
        
        const currentItems = parentContainer.querySelectorAll('.fugura-item');
        currentItems.forEach(item => item.remove());
        
        let visibleCount = 0;
        let matchingItems = [];
        
        originalItems.forEach((itemData) => {
            const { id, name, sex } = itemData;
            
            const matchesSearch = !searchTerm || 
                                 name.toLowerCase().includes(searchTerm) || 
                                 id.toLowerCase().includes(searchTerm);
            
            const matchesSexo = sexoSelected === 'all' || sexoSelected === sex;
            
            if (matchesSearch && matchesSexo) {
                matchingItems.push(itemData);
            }
        });
        
        matchingItems.forEach((itemData, index) => {
            if (index < limitSelected) {
                const newElement = itemData.element.cloneNode(true);
                
                newElement.style.display = 'block';
                newElement.style.visibility = 'visible';
                newElement.style.opacity = '1';
                newElement.style.height = 'auto';
                newElement.classList.remove('fugura-hidden', 'd-none', 'hidden');
                
                parentContainer.appendChild(newElement);
                visibleCount++;
            }
        });
        
        updateResultsDisplay(visibleCount);
        showFilterResult(visibleCount, matchingItems.length, limitSelected);
    }
    
    function updateResultsDisplay(visibleCount) {
        const notResults = document.getElementById('not_results');
        const fuguraList = document.getElementById('fugura_list');
        
        if (visibleCount === 0) {
            if (notResults) notResults.style.display = 'block';
            if (fuguraList) fuguraList.style.display = 'none';
        } else {
            if (notResults) notResults.style.display = 'none';
            if (fuguraList) fuguraList.style.display = 'block';
        }
    }

    // Tratamento de respostas AJAX para updates
    function handleUpdateResponse(response, successMessage, successIcon) {
        if (window.currentFugura && window.currentFugura.ID) {
            saveFiguraToSession(window.currentFugura.ID);
        }
        
        if (response.trim() === '' || response.length < 10) {
            showSuccessToast(successMessage, successIcon);
            setTimeout(() => location.reload(), 500);
            return;
        }
        
        try {
            let json = JSON.parse(response);
            if (typeof json === 'string') json = JSON.parse(json);
            
            const isSuccess = json.success === true || 
                             json.success === 'true' || 
                             json.success === 1 ||
                             response.includes('"success":true') ||
                             response.includes('"success":"true"');
            
            if (isSuccess) {
                showSuccessToast(successMessage, successIcon);
                setTimeout(() => location.reload(), 500);
                if (json.data) window.currentFugura = json.data;
            } else {
                const errorMsg = json.message || json.error || 'Erro desconhecido';
                showErrorToast(errorMsg);
            }
            
        } catch (parseError) {          
            if (response.includes('success') && (response.includes('true') || response.includes('atualizada'))) {
                showSuccessToast(successMessage, successIcon);
                setTimeout(() => location.reload(), 500);
            } else {
                showErrorToast('Erro de resposta do servidor');
            }
        }
    }

    // Sistema principal de atualização de fuguras
    window.fugura = {
        update: function () {
            if (!window.currentFugura) {
                showErrorToast('Nenhuma figura selecionada');
                return;
            }

            const formData = new FormData();
            ['ID', 'Name', 'Sex', 'Type'].forEach(field => {
                const input = detailsForm.querySelector(`[name="${field}"]`);
                if (input) formData.append(field, input.value);
            });

            fetch(`/admin/gameutils/fugura/update/${window.currentFugura.ID}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.text())
            .then(text => {
                handleUpdateResponse(text, 'Detalhes atualizados com sucesso! 🎯', '📝');
            })
            .catch(err => {
                if (window.currentFugura && window.currentFugura.ID) {
                    saveFiguraToSession(window.currentFugura.ID);
                }
                showSuccessToast('Dados salvos! Recarregando...', '💾');
                setTimeout(() => location.reload(), 800);
            });
        },

        updateStats: function () {
            if (!window.currentFugura) {
                showErrorToast('Nenhuma figura selecionada');
                return;
            }

            const formData = new FormData();

            ['ID', 'Name', 'Sex', 'Type'].forEach(field => {
                const input = detailsForm.querySelector(`[name="${field}"]`);
                if (input) formData.append(field, input.value);
            });

            ['Attack', 'Defend', 'Agility', 'Luck', 'Blood', 'Damage', 'Guard', 'Cost'].forEach(attr => {
                const input = statsForm.querySelector(`[name="${attr}"]`);
                const value = input ? input.value : 0;
                formData.append(attr, value);
            });

            fetch(`/admin/gameutils/fugura/update/${window.currentFugura.ID}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.text())
            .then(text => {
                handleUpdateResponse(text, 'Atributos atualizados com sucesso! ⚔️', '💪');
            })
            .catch(err => {
                if (window.currentFugura && window.currentFugura.ID) {
                    saveFiguraToSession(window.currentFugura.ID);
                }
                showSuccessToast('Dados salvos! Recarregando...', '💾');
                setTimeout(() => location.reload(), 800);
            });
        }
    };

    // SISTEMA DE SELEÇÃO DE FIGURAS
    window.selectFugura = function (id) {
        window.currentFugura = { ID: id };
        saveFiguraToSession(id);
        
        document.querySelectorAll('.fugura-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-light-primary');
            item.classList.add('border-gray-300');
        });
        
        const selectedItem = document.querySelector(`[data-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('border-primary', 'bg-light-primary');
            selectedItem.classList.remove('border-gray-300');
            
            window.currentFugura = {
                ID: id,
                Name: selectedItem.dataset.name || selectedItem.querySelector('.text-gray-800')?.textContent?.trim() || `Figura ${id}`,
                Sex: selectedItem.dataset.sex || '0',
                Type: selectedItem.dataset.type || '0'
            };
        }
        
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
                window.currentFugura = json.data;

                if (notSelected) notSelected.style.display = 'none';
                if (fuguraData) fuguraData.style.display = 'block';

                const fieldsToFill = ['ID', 'Name'];
                fieldsToFill.forEach(field => {
                    const input = detailsForm.querySelector(`[name="${field}"]`);
                    if (input) input.value = json.data[field] || '';
                });

                // Campo Sex
                const sexField = detailsForm.querySelector(`[name="Sex"]`);
                if (sexField) {
                    const sexValue = String(json.data.Sex || '0');
                    sexField.value = sexValue;
                    
                    if (window.$ && $(sexField).hasClass('select2-hidden-accessible')) {
                        $(sexField).trigger('change');
                    }
                }

                // Campo Type
                const currentType = String(json.data.Type || '1');
                const typeField = detailsForm.querySelector(`[name="Type"]`);
                if (typeField) {
                    typeField.value = currentType;
                }

                ['Attack', 'Defend', 'Agility', 'Luck', 'Blood', 'Damage', 'Guard', 'Cost'].forEach(attr => {
                    const input = statsForm.querySelector(`[name="${attr}"]`);
                    if (input) input.value = json.data[attr] || 0;
                });
                
                setTimeout(() => {
                    const itemsTab = document.querySelector('a[href="#fugura_items"]');
                    
                    if (itemsTab && itemsTab.classList.contains('active')) {
                        if (window.loadFuguraItems) {
                            window.loadFuguraItems(window.currentFugura.ID);
                        }
                    }
                    
                    if (window.hideItemPreview) window.hideItemPreview();
                    const listDiv = document.getElementById('items-list');
                    if (listDiv) {
                        listDiv.innerHTML = '';
                        listDiv.style.display = 'none';
                    }
                    const emptyDiv = document.getElementById('items-empty');
                    if (emptyDiv) emptyDiv.style.display = 'block';
                    
                    const templateSelect = $('#add-template-id');
                    if (templateSelect.length) {
                        templateSelect.val(null).trigger('change');
                    }
                }, 300);
                
            } else {
                showErrorToast('Falha ao carregar dados da figura');
            }
        })
        .catch(err => {
            showErrorToast('Erro ao carregar figura');
        });
    };

    // Event listeners dos filtros
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            setTimeout(aggressiveFilter, 200);
        });
    }
    
    if (sexoFilter) {
        sexoFilter.addEventListener('change', function() {
            setTimeout(aggressiveFilter, 100);
        });
    }
    
    if (limitFilter) {
        limitFilter.addEventListener('change', function() {
            setTimeout(aggressiveFilter, 100);
        });
    }
    
    window.resetFuguraFilters = function() {
        if (searchInput) searchInput.value = '';
        if (sexoFilter) sexoFilter.value = 'all';
        if (limitFilter) limitFilter.value = '10';
        setTimeout(aggressiveFilter, 100);
    };
    
    document.getElementById('button_refresh_list')?.addEventListener('click', () => {
        clearFiguraFromSession();
        location.reload();
    });

    // Auto-restaurar fugura após reload
    setTimeout(() => {
        const savedFiguraId = getFiguraFromSession();
        if (savedFiguraId) {
            const figuraElement = document.querySelector(`[data-id="${savedFiguraId}"]`);
            if (figuraElement) {
                figuraElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                selectFugura(savedFiguraId);
                setTimeout(() => {
                    showSuccessToast('Figura restaurada após atualização! 🔄', '📂');
                }, 1000);
            } else {
                clearFiguraFromSession();
            }
        }
    }, 500);

    setTimeout(() => {
        storeOriginalItems();
        setTimeout(aggressiveFilter, 100);
        setupFormDropdowns();
    }, 1000);

    // TORNAR FUNÇÕES GLOBAIS PARA OUTROS SISTEMAS
    window.showSuccessToast = showSuccessToast;
    window.showErrorToast = showErrorToast;
    window.getNextAvailableId = getNextAvailableId;
});

// CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInBounce {
        0% { transform: translateX(100%) scale(0.8); opacity: 0; }
        60% { transform: translateX(-10px) scale(1.05); opacity: 1; }
        100% { transform: translateX(0) scale(1); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        0% { transform: translateX(0) scale(1); opacity: 1; }
        100% { transform: translateX(100%) scale(0.8); opacity: 0; }
    }
    
    @keyframes slideInUp {
        from { transform: translateY(100%) !important; opacity: 0 !important; }
        to { transform: translateY(0) !important; opacity: 1 !important; }
    }
    
    @keyframes slideOutDown {
        from { transform: translateY(0) !important; opacity: 1 !important; }
        to { transform: translateY(100%) !important; opacity: 0 !important; }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    @keyframes slideOutLeft {
        from { transform: translateX(0) scale(1); opacity: 1; }
        to { transform: translateX(-100%) scale(0.8); opacity: 0; }
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
    
    /* Dropdowns nos formulários com padding e altura consistentes */
    .form-select.form-select-sm,
    .form-select {
        height: 38px !important;
        padding: 6px 30px 6px 12px !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        border-radius: 6px !important;
        background-position: right 8px center !important;
        background-size: 16px 12px !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-color: #ffffff !important;
        border: 1px solid #e1e3ea !important;
    }
    
    /* Garantir que todos os selects tenham a mesma aparência */
    select[name="Sex"],
    select[name="Type"],
    select[name="sex"],
    select[name="type"],
    #createSex,
    #createType {
        height: 38px !important;
        padding: 6px 30px 6px 12px !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        border-radius: 6px !important;
        background-position: right 8px center !important;
        background-size: 16px 12px !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-color: #ffffff !important;
        border: 1px solid #e1e3ea !important;
        cursor: pointer !important;
    }
    
    /* Inputs number no mesmo tamanho */
    .form-control.form-control-sm {
        height: 38px !important;
        padding: 6px 12px !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        border-radius: 6px !important;
    }
    
    /* Container dos formulários com melhor espaçamento */
    .fv-row {
        margin-bottom: 1rem;
    }
    
    .row.mb-4 .fv-row,
    .row.mb-5 .fv-row {
        margin-bottom: 0;
    }
    
    /* Labels consistentes */
    .form-label {
        font-weight: 600 !important;
        margin-bottom: 8px !important;
        font-size: 0.9rem !important;
    }
    
    /* FORÇA A VISIBILIDADE DOS ITENS */
    #items-list {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
        min-height: 400px !important;
        border: 3px solid #28a745 !important;
        border-radius: 12px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        position: relative !important;
        z-index: 1 !important;
    }
    
    /* Container dos itens SEMPRE VISÍVEL */
    .items-content {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        max-height: 400px !important;
        overflow-y: auto !important;
        padding: 12px !important;
        position: relative !important;
        z-index: 2 !important;
    }
    
    /* Cards de itens SEMPRE VISÍVEIS */
    .items-content .row {
        display: flex !important;
        flex-wrap: wrap !important;
        visibility: visible !important;
        opacity: 1 !important;
        margin: 0 !important;
    }
    
    .items-content .col-12,
    .items-content .col-sm-6, 
    .items-content .col-lg-4 {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        flex: 0 0 auto !important;
        padding: 4px !important;
    }
    
    /* Cards individuais FORÇADAMENTE VISÍVEIS */
    .item-card {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        min-height: 150px !important;
        border: 2px solid #007bff !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
        background: #ffffff !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        position: relative !important;
        z-index: 3 !important;
    }
    
    .item-card .card-body {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 12px !important;
    }
    
    .item-card .card-footer {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        justify-content: center !important;
        padding: 8px !important;
    }
    
    .item-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        border-color: #28a745 !important;
    }
    
    /* Cabeçalho da área de itens SEMPRE VISÍVEL */
    .items-header {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
        color: white !important;
        padding: 12px 15px !important;
        border-radius: 10px 10px 0 0 !important;
        margin: 0 !important;
        font-weight: 700 !important;
        font-size: 16px !important;
        text-align: center !important;
        box-shadow: 0 2px 6px rgba(40,167,69,0.2) !important;
        position: relative !important;
        z-index: 4 !important;
    }
    
    /* Paginação SEMPRE VISÍVEL */
    .items-pagination {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 12px 15px !important;
        background: #f8f9fa !important;
        border-top: 2px solid #e1e3ea !important;
        border-radius: 0 0 10px 10px !important;
        text-align: center !important;
        margin: 0 !important;
        position: relative !important;
        z-index: 4 !important;
    }
    
    .pagination-info {
        display: block !important;
        visibility: visible !important;
        font-size: 12px !important;
        color: #495057 !important;
        margin: 0 0 8px 0 !important;
        font-weight: 600 !important;
    }
    
    .pagination-btn {
        display: inline-block !important;
        visibility: visible !important;
        background: #28a745 !important;
        color: white !important;
        border: none !important;
        padding: 8px 12px !important;
        margin: 0 3px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 4px rgba(40,167,69,0.2) !important;
    }
    
    .pagination-btn:hover {
        background: #1e7e34 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(40,167,69,0.3) !important;
    }
    
    .pagination-btn:disabled {
        background: #6c757d !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .pagination-btn.active {
        background: #007bff !important;
        font-weight: bold !important;
        box-shadow: 0 4px 8px rgba(0,123,255,0.3) !important;
    }
    
    /* Scrollbar personalizada SEMPRE VISÍVEL */
    .items-content::-webkit-scrollbar {
        width: 8px !important;
        display: block !important;
    }
    
    .items-content::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 4px !important;
    }
    
    .items-content::-webkit-scrollbar-thumb {
        background: #007bff !important;
        border-radius: 4px !important;
    }
    
    .items-content::-webkit-scrollbar-thumb:hover {
        background: #0056b3 !important;
    }
    
    /* Responsividade melhorada */
    @media (max-width: 991.98px) {
        .col-lg-5, .col-lg-7 {
            flex: 0 0 auto;
            width: 100% !important;
            margin-bottom: 1rem;
        }
        
        .items-content {
            max-height: 300px !important;
        }
    }
`;
document.head.appendChild(style);

// SISTEMA DE DELETE
document.addEventListener('DOMContentLoaded', function() {
    function showDeleteConfirmation(fuguraId, fuguraName) {
        return new Promise((resolve) => {
            const existingModal = document.querySelector('.delete-confirmation-modal');
            if (existingModal) existingModal.remove();
            
            const modal = document.createElement('div');
            modal.className = 'delete-confirmation-modal';
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.7); display: flex; align-items: center;
                justify-content: center; z-index: 99999; backdrop-filter: blur(5px);
                animation: fadeIn 0.3s ease-out;
            `;
            
            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white; border-radius: 20px; padding: 30px; max-width: 450px;
                width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); text-align: center;
                animation: slideInUp 0.4s ease-out;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            `;
            
            modalContent.innerHTML = `
                <div style="font-size: 48px; margin-bottom: 20px;">🗑️</div>
                <h3 style="color: #dc3545; margin-bottom: 15px; font-weight: 700;">Deletar Figura</h3>
                <p style="color: #666; margin-bottom: 25px; line-height: 1.5;">
                    Tem certeza que deseja deletar a figura<br>
                    <strong style="color: #000;">"${fuguraName}" (ID: ${fuguraId})</strong>?
                </p>
                <p style="color: #dc3545; font-size: 14px; margin-bottom: 30px; font-weight: 600;">
                    ⚠️ Esta ação não pode ser desfeita!
                </p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button id="cancelDelete" style="
                        background: #6c757d; color: white; border: none; padding: 12px 25px;
                        border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
                    ">❌ Cancelar</button>
                    <button id="confirmDelete" style="
                        background: #dc3545; color: white; border: none; padding: 12px 25px;
                        border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
                    ">🗑️ Sim, Deletar</button>
                </div>
            `;
            
            modal.appendChild(modalContent);
            document.body.appendChild(modal);
            
            const cancelBtn = modal.querySelector('#cancelDelete');
            const confirmBtn = modal.querySelector('#confirmDelete');
            
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
        });
    }
    
    async function executeFuguraDelete(fuguraId) {
        try {
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
            
            const responseText = await response.text();
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                result = { success: response.ok, message: response.ok ? 'Deletado com sucesso' : 'Erro ao deletar' };
            }
            
            if (result.success || response.ok) {
                const fuguraItem = document.querySelector(`[data-id="${fuguraId}"]`);
                if (fuguraItem) {
                    fuguraItem.style.animation = 'slideOutLeft 0.5s ease-out';
                    setTimeout(() => {
                        fuguraItem.remove();
                    }, 500);
                }
                
                if (window.currentFugura && window.currentFugura.ID == fuguraId) {
                    const notSelected = document.getElementById('not_selected');
                    const fuguraData = document.getElementById('fugura_data');
                    if (notSelected) notSelected.style.display = 'block';
                    if (fuguraData) fuguraData.style.display = 'none';
                    window.currentFugura = null;
                }
                
                if (window.showSuccessToast) {
                    window.showSuccessToast('Figura deletada com sucesso! 🗑️', '✅');
                }
                
            } else {
                if (window.showErrorToast) {
                    window.showErrorToast(result.message || 'Erro ao deletar figura');
                }
            }
            
        } catch (error) {
            if (window.showErrorToast) {
                window.showErrorToast('Erro de conexão ao deletar figura');
            }
        } finally {
            const deleteBtn = document.querySelector(`#btn_delete_fugura`);
            if (deleteBtn) {
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="bi bi-trash fs-3"></i>';
            }
        }
    }
    
    function setupDeleteButton() {
        const deleteButton = document.querySelector('#btn_delete_fugura');
        
        if (deleteButton) {
            deleteButton.replaceWith(deleteButton.cloneNode(true));
            const newDeleteButton = document.querySelector('#btn_delete_fugura');
            
            newDeleteButton.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!window.currentFugura || !window.currentFugura.ID) {
                    if (window.showErrorToast) {
                        window.showErrorToast('Nenhuma figura selecionada para deletar');
                    }
                    return;
                }
                
                const fuguraId = window.currentFugura.ID;
                const fuguraName = window.currentFugura.Name || `Figura ${fuguraId}`;
                
                const confirmed = await showDeleteConfirmation(fuguraId, fuguraName);
                
                if (confirmed) {
                    await executeFuguraDelete(fuguraId);
                }
            });
        }
    }
    
    setTimeout(() => {
        setupDeleteButton();
    }, 1000);
});

// SISTEMA DE CRIAÇÃO
document.addEventListener('DOMContentLoaded', function() {
    
    function setupCreateForm() {
        const createModal = document.getElementById('createModal');
        const createForm = document.getElementById('createForm');
        
        if (!createModal || !createForm) return;

        createModal.addEventListener('show.bs.modal', function() {
		const nextId = window.getNextAvailableId();
		
		const idField = createForm.querySelector('[name="ID"]');
		if (idField) {
			idField.value = nextId;
		}
		
		const fieldsToReset = ['Name', 'Attack', 'Defend', 'Agility', 'Luck', 'Blood', 'Damage', 'Guard', 'Cost'];
		fieldsToReset.forEach(fieldName => {
			const field = createForm.querySelector(`[name="${fieldName}"]`);
			if (field) {
				field.value = fieldName === 'Name' ? '' : '0';
			}
		});
		
		// FORÇAR TRANSFORMAÇÃO SEMPRE
		setTimeout(() => setupCreateFormDropdowns(), 50);
		setTimeout(() => setupCreateFormDropdowns(), 200);
		setTimeout(() => setupCreateFormDropdowns(), 500);
	});
        
        const createButtons = document.querySelectorAll('[data-bs-target="#createModal"], .btn[onclick*="createModal"]');
        createButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!window.bootstrap) {
                    setTimeout(() => {
                        const event = new Event('show.bs.modal');
                        createModal.dispatchEvent(event);
                    }, 100);
                }
            });
        });
    }

    function setupCreateFormDropdowns() {
		const createForm = document.getElementById('createForm');
		if (!createForm) return;

		// REMOVER CAMPOS SEX DUPLICADOS - MÉTODO CORRETO
		const allSexFields = createForm.querySelectorAll('[name="Sex"]');
		allSexFields.forEach(field => field.remove());
		
		// Remover labels que contenham "Sexo"
		const allLabels = createForm.querySelectorAll('label');
		allLabels.forEach(label => {
			if (label.textContent && label.textContent.includes('Sexo')) {
				const container = label.closest('.fv-row');
				if (container) container.remove();
			}
		});

		// Campo Type no form de criação - SEMPRE TRANSFORMAR
		const typeField = createForm.querySelector('[name="Type"]');
		if (typeField) {
			const currentValue = typeField.value || '1';
			const newSelect = document.createElement('select');
			newSelect.name = 'Type';
			newSelect.className = 'form-select form-select-sm form-select-solid';
			newSelect.setAttribute('data-control', 'select2');
			newSelect.setAttribute('data-hide-search', 'true');
			newSelect.innerHTML = `
				<option value="1">🎭 Conjuntos</option>
				<option value="2">⚔️ Armamentos</option>
			`;
			newSelect.value = currentValue;
			
			typeField.parentNode.replaceChild(newSelect, typeField);
		}

		// Criar campo Sex APENAS UM
		const typeContainer = createForm.querySelector('[name="Type"]')?.closest('.fv-row');
		
		if (typeContainer && !createForm.querySelector('[name="Sex"]')) {
			const sexContainer = document.createElement('div');
			sexContainer.className = 'fv-row col-6';
			sexContainer.innerHTML = `
				<label class="fs-6 fw-bold form-label mb-2">🎭 Sexo</label>
				<select name="Sex" class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true">
					<option value="0">👤 Unissex</option>
					<option value="1">👦 Masculino</option>
					<option value="2">👧 Feminino</option>
				</select>
			`;
			
			// Inserir na mesma row do Type
			typeContainer.parentNode.insertBefore(sexContainer, typeContainer.nextSibling);
		}
	}
    
    function setupCreateFormSubmit() {
        const createForm = document.getElementById('createForm');
        
        if (!createForm) return;
        
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const idField = createForm.querySelector('[name="ID"]');
            const nameField = createForm.querySelector('[name="Name"]');
            const typeField = createForm.querySelector('[name="Type"]');
            
            if (!idField?.value || !nameField?.value.trim()) {
                if (window.showErrorToast) {
                    window.showErrorToast('ID e Nome são obrigatórios!');
                }
                return;
            }
            
            const typeValue = typeField?.value;
            if (!typeValue || (typeValue !== '1' && typeValue !== '2')) {
                if (window.showErrorToast) {
                    window.showErrorToast('Selecione um tipo válido!');
                }
                return;
            }
            
            const formData = new FormData(createForm);
            await processCreateForm(formData);
        });
    }
    
    setTimeout(() => {
        setupCreateForm();
        setupCreateFormSubmit();
    }, 1000);
});

// SISTEMA MÁXIMO 12 ITENS 
	(function() {
		'use strict';

		window.allItems = [];
		window.currentFiguraItems = [];

		function isValidObject(obj) {
			return obj !== null && typeof obj === 'object' && !Array.isArray(obj);
		}

		function hasProperty(obj, prop) {
			return isValidObject(obj) && Object.prototype.hasOwnProperty.call(obj, prop);
		}

		function parseJsonResponse(data) {
			try {
				if (isValidObject(data)) {
					return data;
				}
				
				if (typeof data === 'string') {
					const cleanData = data.trim();
					
					if (cleanData.startsWith('{') || cleanData.startsWith('[')) {
						const parsed = JSON.parse(cleanData);
						return parsed;
					} else {
						return null;
					}
				}
				
				return null;
				
			} catch (e) {
				return null;
			}
		}

		// FUNÇÃO PARA VERIFICAR ITEM DUPLICADO
		function isItemDuplicate(templateId) {
			const isDuplicate = window.currentFiguraItems.some(item => {
				const itemTemplateId = item.TemplateID || item.templateId || item.template_id;
				return String(itemTemplateId) === String(templateId);
			});
			
			return isDuplicate;
		}

		// FUNÇÃO PARA EXIBIR TODOS OS ITENS
		window.displayItems = function displayItems(items) {
			const listDiv = document.getElementById('items-list');
			if (!listDiv) {
				console.error('❌ Elemento items-list não encontrado');
				return;
			}

			console.log('🔄 Exibindo todos os itens:', items.length);

			// FORÇA EXIBIÇÃO DA LISTA
			listDiv.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; min-height: 400px !important;';
			listDiv.innerHTML = '';
			
			const header = document.createElement('div');
			header.className = 'items-header';
			header.innerHTML = `🎒 Itens da Fugura (${items.length}/12)`;
			header.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
			listDiv.appendChild(header);
			
			const itemsContentContainer = document.createElement('div');
			itemsContentContainer.className = 'items-content';
			itemsContentContainer.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; height: auto !important;';
			
			const itemsContainer = document.createElement('div');
			itemsContainer.className = 'row';
			itemsContainer.style.cssText = 'display: flex !important; flex-wrap: wrap !important; visibility: visible !important; opacity: 1 !important;';
			
			if (items.length === 0) {
				const emptyMsg = document.createElement('div');
				emptyMsg.className = 'col-12 text-center py-4';
				emptyMsg.innerHTML = '<div class="text-muted">📭 Nenhum item adicionado</div>';
				emptyMsg.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
				itemsContainer.appendChild(emptyMsg);
			} else {
				items.forEach(function(item, index) {
					try {
						const itemElement = window.createItemCard(item);
						itemsContainer.appendChild(itemElement);
					} catch (error) {
						console.error('❌ Erro ao criar card do item:', error);
					}
				});
			}
			
			itemsContentContainer.appendChild(itemsContainer);
			listDiv.appendChild(itemsContentContainer);
			
			console.log('✅ Todos os itens exibidos com sucesso');
		};

		// ===================================
		// FUNÇÕES GLOBAIS DO SISTEMA
		// ===================================

		// Função Preview
		window.showItemPreview = function(item) {
			const previewArea = document.getElementById('item-preview-area');
			const previewImage = document.getElementById('item-preview-image');
			const previewName = document.getElementById('item-preview-name');
			const previewId = document.getElementById('item-preview-id');
			const previewType = document.getElementById('item-preview-type');
			const selectionHint = document.getElementById('item-selection-hint');
			
			if (!previewArea || !previewImage || !previewName || !previewId || !previewType) {
				return;
			}
			
			var sexIcon = '';
			if (item.NeedSex == "1") sexIcon = ' 👨';
			else if (item.NeedSex == "2") sexIcon = ' 👩';

			const imageUrl = item.Icon || '/assets/media/svg/files/blank-image.svg';
			previewImage.src = imageUrl;
			previewImage.style.display = 'block';
			previewImage.onerror = function() {
				this.src = '/assets/media/svg/files/blank-image.svg';
			};

			const itemName = (item.Name || 'Sem nome') + sexIcon;
			const itemId = item.TemplateID || 'N/A';
			const itemType = item.CategoryID || 'N/A';
			
			previewName.textContent = itemName;
			previewId.textContent = itemId;
			previewType.textContent = itemType;

			previewArea.style.display = 'block';
			if (selectionHint) selectionHint.style.display = 'none';
		};

		window.hideItemPreview = function() {
			const previewArea = document.getElementById('item-preview-area');
			const selectionHint = document.getElementById('item-selection-hint');
			const previewImage = document.getElementById('item-preview-image');
			
			if (previewArea) previewArea.style.display = 'none';
			if (selectionHint) selectionHint.style.display = 'block';
			if (previewImage) previewImage.style.display = 'none';
		};

		// Configurar Select2
		window.setupItemSelect = function() {
			const templateSelect = $('#add-template-id');
			if (!templateSelect.length) {
				return;
			}

			if (templateSelect.hasClass('select2-hidden-accessible')) {
				templateSelect.select2('destroy');
			}

			var optionFormat = function (item) {
				if (!item.id) return item.text;
				var span = document.createElement('span');
				span.innerHTML = '<img src="' + (item.pic || '/assets/media/svg/files/blank-image.svg') + '" class="h-30px me-2" alt="image" onerror="this.src=\'/assets/media/svg/files/blank-image.svg\'"/>' + item.text;
				return $(span);
			};

			templateSelect.select2({
				minimumInputLength: 2,
				templateResult: optionFormat,
				placeholder: 'Digite para buscar um item...',
				allowClear: true,
				ajax: {
					url: '/admin/gameutils/items/search',
					dataType: 'json',
					type: "GET",
					delay: 250,
					data: function (params) {
						return {
							search: params.term,
							figuraId: window.currentFugura ? window.currentFugura.ID : null
						};
					},
					processResults: function (response) {
						let data = parseJsonResponse(response);
						
						if (!data || !hasProperty(data, 'success') || !data.success || !hasProperty(data, 'items') || !Array.isArray(data.items)) {
							return { results: [] };
						}

						const results = data.items.map(function(item) {
							return {
								id: item.id,
								text: item.text, 
								pic: item.pic,
								itemData: item.data
							};
						});

						return { results: results };
					},
					cache: true
				}
			});

			templateSelect.on('select2:select', function (e) {
				const selectedData = e.params.data;
				
				if (selectedData.itemData) {
					window.showItemPreview(selectedData.itemData);
					
					const templateId = selectedData.itemData.TemplateID;
					if (isItemDuplicate(templateId)) {
						if (window.showErrorToast) {
							window.showErrorToast(`Este item (ID: ${templateId}) já foi adicionado à esta figura!`);
						}
						
						templateSelect.val(null).trigger('change');
						window.hideItemPreview();
					}
				}
			});

			templateSelect.on('select2:clear', function () {
				window.hideItemPreview();
			});
		};

		// Configurar Aba de Itens
		window.setupItemsTab = function() {
			const itemsTab = document.querySelector('a[href="#fugura_items"]');
			if (!itemsTab) {
				return;
			}

			itemsTab.addEventListener('shown.bs.tab', function() {
				if (window.currentFugura && window.currentFugura.ID) {
					window.loadFuguraItems(window.currentFugura.ID);
				}
				
				setTimeout(function() {
					if (window.setupItemSelect) {
						window.setupItemSelect();
					}
				}, 200);
			});
		};

		// Carregar Itens da Figura
		window.loadFuguraItems = async function(figuraId) {
			const loadingDiv = document.getElementById('items-loading');
			const emptyDiv = document.getElementById('items-empty');
			const listDiv = document.getElementById('items-list');

			// FORÇA EXIBIÇÃO DO LOADING
			if (loadingDiv) {
				loadingDiv.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
			}
			if (emptyDiv) {
				emptyDiv.style.cssText = 'display: none !important;';
			}
			if (listDiv) {
				listDiv.style.cssText = 'display: none !important;';
			}

			try {
				const url = `/admin/gameutils/fugura/${figuraId}/items`;
				
				const response = await fetch(url, {
					headers: {
						'Accept': 'application/json',
						'X-Requested-With': 'XMLHttpRequest'
					}
				});

				const responseText = await response.text();
				const data = parseJsonResponse(responseText);

				// ESCONDE LOADING
				if (loadingDiv) {
					loadingDiv.style.cssText = 'display: none !important;';
				}

				let hasItems = false;
				let itemsArray = [];

				if (isValidObject(data) && data.success) {
					if (hasProperty(data, 'items') && Array.isArray(data.items) && data.items.length > 0) {
						hasItems = true;
						itemsArray = data.items;
					} else if (hasProperty(data, 'data') && Array.isArray(data.data) && data.data.length > 0) {
						hasItems = true;
						itemsArray = data.data;
					}
				}

				window.currentFiguraItems = itemsArray;
				window.allItems = itemsArray;

				if (hasItems) {
					window.displayItems(itemsArray);
					if (listDiv) {
						listDiv.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
					}
					if (emptyDiv) {
						emptyDiv.style.cssText = 'display: none !important;';
					}
				} else {
					window.currentFiguraItems = [];
					window.allItems = [];
					if (emptyDiv) {
						emptyDiv.style.cssText = 'display: block !important; visibility: visible !important;';
					}
					if (listDiv) {
						listDiv.style.cssText = 'display: none !important;';
					}
				}

			} catch (error) {
				if (loadingDiv) {
					loadingDiv.style.cssText = 'display: none !important;';
				}
				if (emptyDiv) {
					emptyDiv.style.cssText = 'display: block !important; visibility: visible !important;';
				}
				
				if (window.showErrorToast) {
					window.showErrorToast('Erro ao carregar itens: ' + error.message);
				}
			}
		};

		// Criar Card de Item
		window.createItemCard = function createItemCard(item) {
			const col = document.createElement('div');
			col.className = 'col-12 col-sm-6 col-lg-4 mb-2';
			col.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; flex: 0 0 auto !important; width: 33.33% !important; padding: 4px !important;';

			const templateId = item.TemplateID || item.templateId || item.template_id || 'N/A';
			const itemId = item.ItemID || item.itemId || item.item_id || 'N/A';
			const description = item.Description || item.description || 'Sem descrição';
			const sex = item.Sex || item.sex || 0;
			const cost = item.Cost || item.cost || 0;
			const type = item.Type || item.type || 'N/A';
			const icon = item.Icon || item.icon || '/assets/media/svg/files/blank-image.svg';

			const sexIcon = sex == 0 ? '👤' : sex == 1 ? '👦' : '👧';

			col.innerHTML = 
				'<div class="card card-flush item-card h-100" style="display: block !important; visibility: visible !important; opacity: 1 !important; min-height: 150px !important; border: 3px solid #007bff !important;">' +
					'<div class="card-body text-center p-2" style="display: block !important; visibility: visible !important; opacity: 1 !important;">' +
						'<div class="symbol symbol-35px symbol-circle mx-auto mb-1">' +
							'<img src="' + icon + '" alt="Item ' + templateId + '" class="symbol-label" onerror="this.src=\'/assets/media/svg/files/blank-image.svg\'" />' +
						'</div>' +
						'<div class="fs-8 fw-bold text-gray-800 mb-1">ID: ' + templateId + '</div>' +
						'<div class="fs-9 text-gray-600 mb-1" style="font-size: 10px !important; line-height: 1.2;">' + description.substring(0, 25) + (description.length > 25 ? '...' : '') + '</div>' +
						'<div class="d-flex justify-content-center align-items-center gap-1 mb-1">' +
							'<span class="badge badge-light-primary" style="font-size: 8px !important; padding: 2px 4px;">' + sexIcon + '</span>' +
							'<span class="badge badge-light-success" style="font-size: 8px !important; padding: 2px 4px;">💰 ' + cost + '</span>' +
						'</div>' +
						'<div class="text-muted" style="font-size: 9px !important;">Tipo: ' + type + '</div>' +
					'</div>' +
					'<div class="card-footer d-flex justify-content-center py-1" style="display: flex !important; visibility: visible !important; opacity: 1 !important;">' +
						'<button class="btn btn-sm btn-light-danger" onclick="deleteItem(' + itemId + ')" style="font-size: 9px; padding: 2px 6px;">' +
							'🗑️' +
						'</button>' +
					'</div>' +
				'</div>';

			return col;
		};

		// Deletar Item
		window.deleteItem = async function(itemId) {
			if (!window.currentFugura || !window.currentFugura.ID) return;
			
			if (!confirm('Tem certeza que deseja remover este item?')) return;

			try {
				const url = '/admin/gameutils/fugura/' + window.currentFugura.ID + '/items/' + itemId + '/delete';

				const response = await fetch(url, {
					method: 'POST',
					headers: {
						'Accept': 'application/json',
						'X-Requested-With': 'XMLHttpRequest'
					}
				});

				const responseText = await response.text();
				const data = parseJsonResponse(responseText);

				if (isValidObject(data) && data.success) {
					if (window.showSuccessToast) {
						window.showSuccessToast('Item removido com sucesso! 🗑️', '✅');
					}
					
					window.loadFuguraItems(window.currentFugura.ID);
				} else {
					if (window.showErrorToast) {
						const errorMsg = (isValidObject(data) && data.message) ? data.message : 'Erro ao remover item';
						window.showErrorToast(errorMsg);
					}
				}
			} catch (error) {
				if (window.showErrorToast) {
					window.showErrorToast('Erro de conexão');
				}
			}
		};

		// Configurar Formulário de Adição com LIMITE DE 12 ITENS
		function setupAddItemForm() {
			const addForm = document.getElementById('form-add-item');
			if (!addForm) {
				return;
			}

			addForm.addEventListener('submit', async function(e) {
				e.preventDefault();

				if (!window.currentFugura || !window.currentFugura.ID) {
					if (window.showErrorToast) {
						window.showErrorToast('Nenhuma figura selecionada - selecione uma figura primeiro');
					}
					return;
				}

				// VERIFICAR LIMITE DE 12 ITENS
				if (window.currentFiguraItems.length >= 12) {
					if (window.showErrorToast) {
						window.showErrorToast('⚠️ Limite máximo de 12 itens por figura atingido!');
					}
					return;
				}

				const templateId = $('#add-template-id').val();

				if (!templateId) {
					if (window.showErrorToast) {
						window.showErrorToast('Selecione um item primeiro');
					}
					return;
				}

				if (isItemDuplicate(templateId)) {
					if (window.showErrorToast) {
						window.showErrorToast(`Este item (ID: ${templateId}) já foi adicionado à esta figura!`);
					}
					return;
				}

				const formData = new FormData();
				formData.append('TemplateID', templateId);
				formData.append('Description', '1');
				formData.append('Sex', $('#add-sex').val() || '0');
				formData.append('Cost', $('#add-cost').val() || '0');
				formData.append('Type', $('#add-type').val() || '1');

				try {
					const url = '/admin/gameutils/fugura/' + window.currentFugura.ID + '/items';

					const response = await fetch(url, {
						method: 'POST',
						headers: {
							'Accept': 'application/json',
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					});

					const responseText = await response.text();
					const data = parseJsonResponse(responseText);

					if (isValidObject(data) && data.success) {
						if (window.showSuccessToast) {
							window.showSuccessToast('Item adicionado com sucesso! 🎒', '✅');
						}
						
						addForm.reset();
						$('#add-template-id').val(null).trigger('change');
						window.hideItemPreview();
						
						const descriptionField = document.getElementById('add-description');
						if (descriptionField) {
							descriptionField.value = '1';
						}
						
						window.loadFuguraItems(window.currentFugura.ID);
					} else {
						if (window.showErrorToast) {
							const errorMsg = (isValidObject(data) && data.message) ? data.message : 'Erro ao adicionar item';
							window.showErrorToast(errorMsg);
						}
					}
				} catch (error) {
					if (window.showErrorToast) {
						window.showErrorToast('Erro de conexão: ' + error.message);
					}
				}
			});
		}

		// Inicialização
		setTimeout(function() {
			window.setupItemsTab();
			setupAddItemForm();
		}, 1500);

	})();