// page params
const parameters = {
    params: {
        sid: null,
        page: 1,
        search: '',
        onclick: 'serverConfig.list',
    }
}

const serverConfig = {
    list: (page = 1) => {
        parameters.params.page = page
        loader.init('#kt_content')
        axios.get(`${baseUrl}/api/admin/game/server/config`, parameters).then((results) => {
            serverConfig.populateList(results.data)
        })
    },
    create: () => {
        const data = $("#md_setting_create form").serializeObject();
        data.sid = parameters.params.sid

        var button = document.querySelector('#md_setting_create button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/game/server/config`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_setting_create').modal('hide')
                serverConfig.list(parameters.params.page)
            }
        })

    },
    update: () => {
        const data = $("#md_setting_edit form").serializeObject();
        data.sid = parameters.params.sid

        var button = document.querySelector('#md_setting_edit form button[type="button"]');
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/game/server/config`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_setting_edit').modal('hide')
                serverConfig.list(parameters.params.page)
            }
        })
    },
    delete: (id) => {
        Swal.fire({
            icon: "error",
            html: "Você tem certeza que deseja apagar esse parâmetro? Essa alteração não pode ser desfeita e pode causa danos irreversíveis ao servidor.",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sim, delete isso!",
            cancelButtonText: "Não, cancele!",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`${baseUrl}/api/admin/game/server/config`, {
                    params: {
                        sid: parameters.params.sid,
                        id: id,
                    }
                }).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    if (su.state) {
                        serverConfig.list(parameters.params.page)
                    }
                })
            }
        })
    },
    populateList: (data) => {
        let configList = $('#settings-list')
        configList.empty()

        $('#item_paginator').empty()

        // check if data is not empty
        if (data.data.length < 1) {
            $('#no-results').show()
            $('#table-settings-list ').hide()
            loader.destroy('#kt_content')
            return;
        }

        var settingItem = (info) => {
            return `<tr id="setting-${info.ID}">
            <td>
                ${info.ID}
            </td>
            <td>
                ${info.Name}
            </td>
            <td>
                ${info.Value}
            </td>
            <td class="text-end">
                <div class="d-flex justify-content-end align-items-center">
                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2" id="edit" data-bs-toggle="modal" data-bs-target="#md_setting_edit">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-light-danger w-30px h-30px" id="delete">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </td>
        </tr>`;
        }

        $.each(data.data, (_, setting) => {
            configList.append(settingItem(setting))
            configList.find(`#setting-${setting.ID} #edit`).click(() => {
                serverConfig.populateEdit(setting)
            })
            configList.find(`#setting-${setting.ID} #delete`).click(() => {
                serverConfig.delete(setting.ID)
            })
        })

        $("#item_paginator").html(data.paginator.rendered)

        $('#no-results').hide()
        $('#table-settings-list ').show()
        loader.destroy('#kt_content')
    },
    populateEdit: (data) => {
        $(`#md_setting_edit input[name="name"]`).val(data.Name)
        $(`#md_setting_edit input[name="value"]`).val(data.Value)
        $(`#md_setting_edit input[name="id"]`).val(data.ID)
    },
    updateOnGame: () => {
        Swal.fire({
            icon: "question",
            html: "Você tem certeza que deseja atualizar as configurações do servidor ? ao fazer isso a <b>xml</b> e os <b>emuladores</b> serão atualizados.",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sim, atualize!",
            cancelButtonText: "Não, cancele!",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-light",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                var button = document.querySelector("#button_update_serverConfig");
                changeButtonState(button, true);
                axios.get(`${baseUrl}/api/admin/game/server/config/update-on-game`, parameters).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    changeButtonState(button, false);
                })
            }
        });
    },
}

const loader = {
    init(element) {
        var target = document.querySelector(element);
        var blockUI = new KTBlockUI(target);

        blockUI.destroy();
        blockUI.block();
    },
    destroy(element) {
        var target = document.querySelector('.blockui-overlay');
        target.remove()
    }
}

const listener = () => {
    parameters.params.sid = $('#sid').val()

    $('input[name="search"]').on('change', () => {
        parameters.params.search = $('input[name="search"]').val()
        serverConfig.list()
    })

    //get server on select
    $('#sid').on('change', () => {
        parameters.params.sid = $('#sid').val()
        serverConfig.list()
    })
}

function init() {
    listener()
    serverConfig.list()
}

init()
