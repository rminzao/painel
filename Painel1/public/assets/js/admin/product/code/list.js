const codeState = {
    params: {
        page: 1,
        limit: 10,
        search: null,
        sid: null,
        id: null,
    }
}

const pCode = {
    list: (page = 1) => {
        codeState.params.page = page;
        helper.loader('#code_body_list')
        axios.get(`${baseUrl}/api/admin/product/code`, codeState).then(res => {
            pCode.populate(res.data)
        })
    },
    create: () => {
        const data = $("#md_code_new form").serializeObject();

        var button = document.querySelector('#md_code_new button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/product/code`, data).then((res) => {
            var su = res.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                pCode.list()
            }
        })
    },
    update: () => {
        const data = $("#code-info form").serializeObject();
        data.id = codeState.params.id;
        var button = document.querySelector('#code-info button[type="button"]');
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/product/code`, data).then((res) => {
            var su = res.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                pCode.list()
            }
        })
    },
    delete: (id) => {
        Swal.fire({
            icon: "error",
            html: "Você tem certeza que deseja apagar esse código? Essa alteração não pode ser desfeita.",
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
                axios.delete(`${baseUrl}/api/admin/product/code`, {
                    params: {
                        id: id,
                    }
                }).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    if (su.state) {
                        pCode.list()
                    }
                })
            }
        })
    },
    populate: (data) => {
        const codeList = $('#code_list'),
            paginator = $('#code_paginator'),
            codeUsedList = $('#code-used-list'),
            no_results = $('#not_results'),
            no_uses = $('#no_code_used');

        if (data.data.length <= 0) {
            codeList.hide();
            no_results.show();
            helper.loader('#code_body_list', false)
            return
        }

        var codeItem = (info, last = false) => {
            var getState = () => {
                if (info.limit != 0 && info.limit < info.used)
                    return '<span class="text-danger">🚩 Limite atingindo</span>'

                switch (info.status) {
                    case 'before':
                        return '<span class="text-warning">🚧 Em breve</span>'
                    case 'active':
                        return '<span class="text-success">🟢 Ativo</span>'
                    case 'after':
                        return '<span class="text-danger">🔴 Expirado</span>'
                    default:
                        return `❓ Desconhecido (${info.status})`
                }
            }

            return `<div class="d-flex flex-stack pt-2" id="code-${info.id}">
                <div class="d-flex flex-column">
                    <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">
                        ${info.code}
                    </a>
                    <span class="text-muted fs-7 mb-1">🎫ID: <span class="text-primary">${info.id}</span> | ${getState()}</span>
                </div>
                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="edit">
                        <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path> </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-danger w-30px h-30px" id="delete">
                        <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                        </svg>
                        </span>
                    </button>
                </div>
            </div>${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
        }

        codeList.empty();
        paginator.empty();


        (data.paginator.rendered == null) ? paginator.hide() : paginator.show();

        $.each(data.data, (_, info) => {
            codeList.append(codeItem(info, data.data.length - 1 == _))

            codeList.find(`#code-${info.id} #edit_name, #code-${info.id} #edit`).on('click', () => {
                pCode.populateDetail(info)
            })

            codeList.find(`#code-${info.id} #delete`).on('click', () => {
                pCode.delete(info.id)
            })
        })

        $("#code_paginator").html(data.paginator.rendered)

        no_results.hide();
        codeList.show();

        helper.loader('#code_body_list', false)
    },
    populateDetail: (data) => {
        codeState.params.id = data.id;

        $('#not_selected').hide()
        $('#code-data').show()

        $.each(data, (key, value) => {
            if (key == 'type' || key == 'sid') {
                $(`#code-info select[name="${key}"]`).val(value).trigger('change')
                return
            }

            if (key == 'state' || key == 'repeat') {
                $(`#code-info input[name="${key}"]`).prop('checked', value == '1' ? true : false).trigger('change')
                return
            }

            $(`#code-info input[name="${key}"]`).val(value)
        })

        const codeUsedListTable = $('#code-used-list'),
              codeUsedList = $('#code-used-list tbody'),
              no_uses = $('#no_code_used')

              codeUsedList.empty();

        if(data.use_list.length <= 0) {
            no_uses.show();
            codeUsedListTable.hide();
            return;
        }

        var useItem = (info) => {
            var getState = () => {
                switch (info.state) {
                    case 'pending':
                        return `<div class="badge badge-light-warning fw-bolder">Pendente</div>`;
                    case 'approved':
                        return `<div class="badge badge-light-success fw-bolder">Aprovado</div>`;
                    default:
                        return `<div class="badge badge-light-danger fw-bolder">${info.state}</div>`;
                }
            }

            return `<tr>
                <td class="ps-9 w-200px w-md-225px">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <img class="h-40px w-40px rounded" src="${info.user.avatar}" alt="">
                        </div>
                        <div class="d-flex flex-column">
                            <a class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex" href="${baseUrl}/admin/users/${info.user.id}" target="_blank">
                                ${info.user.first_name} ${info.user.last_name}
                            </a>
                            <span class="fs-7">#${info.user.id}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <img class="h-40px w-40px rounded"
                                src="${baseUrl}/assets/media/payments/${info.method}.png"
                                alt="${info.method}">
                        </div>

                        <div class="d-flex flex-column">
                            <a class="fs-7 text-gray-800 mb-1 d-flex">
                                R$ <span class="text-primary">${info.value}</span>
                            </a>
                            <span class="fs-7">#${info.reference}</span>
                        </div>
                    </div>
                </td>
                <td>
                    ${getState()}
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end align-items-center text-end mt-1">
                        <button type="button"
                            class="btn btn-icon btn-active-light-primary w-30px h-30px me-6">
                            <span class="svg-icon svg-icon-3" id="edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                        d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3" d="M15 17H9V20H15V17Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </td>
            </tr>`
        }

        $.each(data.use_list, (_, item) => {
            codeUsedList.append(useItem(item));
        })

        no_uses.hide();
        codeUsedListTable.show();
    },
}

const listener = {
    init: () => {
        $('select[name="server_id"]').on('change', function () {
            codeState.params.sid = $(this).val()
            pCode.list()
        })

        $('input[name="code_search"]').on('change', function () {
            codeState.params.search = $(this).val()
            pCode.list()
        })

        $('input[name="start_at"], input[name="expires_at"]').flatpickr({
            enableTime: true,
            enableSeconds: true,
            dateFormat: "Y-m-d H:i:s",
        });

        $('#code-info button[type="button"]').on('click', () => {
            pCode.update()
        })

        $('#md_code_new button[type="button"]').on('click', () => {
            pCode.create()
        })

        $('#code-info select[name="type"]').on('change', function () {
            const type = codeTypes[$(this).val()]

            if (type == null)
                return

            $('#code-info #param1 label').html(type?.param1?.name ?? 'Não definido')
            $('#code-info #param2 label').html(type?.param2?.name ?? 'Não definido')
            $('#code-info #param1 span').html(type?.param1?.prefix ?? '')
            $('#code-info #param2 span').html(type?.param2?.prefix ?? '')
        })

        $('#md_code_new select[name="type"]').on('change', function () {
            const type = codeTypes[$(this).val()]

            if (type == null)
                return

            $('#md_code_new #param1 label').html(type?.param1?.name ?? 'Não definido')
            $('#md_code_new #param2 label').html(type?.param2?.name ?? 'Não definido')
            $('#md_code_new #param1 span').html(type?.param1?.prefix ?? '')
            $('#md_code_new #param2 span').html(type?.param2?.prefix ?? '')
        })

        $('#md_code_new select[name="type"]').trigger('change')
        pCode.list()
    }
}

listener.init()
