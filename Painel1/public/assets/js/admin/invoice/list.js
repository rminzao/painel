// page params
const parameters = {
    params: {
        sid: null,
        uid: null,
        page: 1,
        state: 0,
        search: '',
        onclick: 'invoice.list',
    }
}

const stateInvoice = {
    data: null
}

const invoice = {
    list(page = 1) {
        parameters.params.page = page
        loader.init('#kt_content')
        axios.get(`${baseUrl}/api/admin/invoice`, parameters).then((results) => {
            this.populateList(results.data)
        })
    },
    update(){
        const data = $("#form-invoice-update").serializeObject();
        var button = document.querySelector("#invoice-update-form-submit");
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/invoice`, data).then((response) => {
            var su = response.data;
            swMessage(su.state ? "success" : "warning", su.message);
            changeButtonState(button, false);
            this.list(parameters.params.page)
        }).catch((error) => {
            swMessage("error", "erro interno, verifique o console.");
            changeButtonState(button, false);
            console.error(error);
        });
    },
    loadUsers() {
        axios.get(`${baseUrl}/api/admin/user`, parameters).then((results) => {
            this.populateUser(results.data)
        })
    },
    populateList(data) {
        let invoiceList = $('#invoice-list')
        invoiceList.empty()

        $('#item_paginator').empty()

        // check if data is not empty
        if (data.invoices.length < 1) {
            $('#no-results').show()
            $('#table-invoice-list ').hide()
            loader.destroy('#kt_content')
            return;
        }

        var invoiceItem = (info) => {
            var getInvoiceState = () => {

                switch (info.state) {
                    case 'approved':
                        return `<div class="badge badge-light-success">Aprovado</div>`
                    case 'pending':
                        return `<div class="badge badge-light-warning">Pendente</div>`
                    case 'rejected':
                            return `<div class="badge badge-light-danger">Recusado</div>`
                    case 'refounded':
                        return `<div class="badge badge-light-danger">Reembolsado</div>`
                    case 'cancelled':
                        return `<div class="badge badge-light-danger">Cancelado</div>`
                    default:
                        return`<div class="badge badge-light-primary">${info.state}</div>`
                }
            }

            return `<tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px overflow-hidden me-3">
                        <img class="h-40px w-40px rounded" src="${info?.user?.avatar ?? baseUrl + '/assets/media/avatars/default.png'}" alt="" />
                    </div>

                    <div class="d-flex flex-column">
                        <a href="${baseUrl}/admin/users/${info?.user?.id}" target="_blank" class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                            ${info?.user?.first_name ?? 'sem '} ${info?.user?.last_name ?? 'nome'}
                        </a>
                        <span class="fs-7">#${info?.uid ?? 'sem id'}</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px overflow-hidden me-3">
                        <img class="h-40px w-40px rounded" src="${baseUrl}/assets/media/payments/${info.method}.png" alt="${info.method}" />
                    </div>

                    <div class="d-flex flex-column">
                        <a href="javascript:;" class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                            R$ <span class="text-primary">${info.value}</span>
                        </a>
                        <span class="fs-7">#${info.reference}</span>
                    </div>
                </div>
            </td>
            <td>
                ${getInvoiceState()}
            </td>
            <td>
                ${info.created_at}
            </td>
            <td>
                ${info.updated_at}
            </td>
            <td>
                ${info?.paid_at ?? 'não pago'}
            </td>
            <td class="text-end">
                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2">
                        <span class="svg-icon svg-icon-3" id="edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z" fill="currentColor"/>
                                <path opacity="0.3" d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z" fill="currentColor"/>
                                <path opacity="0.3" d="M15 17H9V20H15V17Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </button>
                    <button type="button" onclick="invoice.populateEdit(${info.id})" data-bs-toggle="modal" data-bs-target="#md_invoice_edit" class="btn btn-icon btn-active-light-primary w-30px h-30px">
                        <span class="svg-icon svg-icon-3" id="edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </td>
        </tr>`;
        }

        stateInvoice.data = data.invoices;

        $.each(data.invoices, function (_, invoice) {
            invoiceList.append(invoiceItem(invoice))
        })

        $("#item_paginator").html(data.paginator.rendered)

        $('#no-results').hide()
        $('#table-invoice-list ').show()
        loader.destroy('#kt_content')
    },
    populateUser(data) {
        let userList = $('#user-list')
        userList.empty()
        userList.append(`<option value="0">Todos jogadores</option>`);
        $.each(data.users, function (_, user) {
            //check is user character is not null
            if (user.character != null) {
                userList.append(`<option value="${user.app_id}" data-kt-select2-user="${user.avatar}">${user.character?.NickName}</option>`)
            }
        })
    },
    populateEdit(id){
        let invoice = stateInvoice.data.find(invoice => invoice.id == id)

        var get = (element) => {
            return $(`#md_invoice_edit ${element}`)
        }

        get('input[name="sent"]').prop('disabled', false)
        get('input[name="sent"]').prop('checked', false)
        get('textarea[name="note"]').empty()

        if (invoice) {
            get('input[name="id"').val(invoice.id)
            get('#md-edit-reference').html('#' + invoice.reference)
            get('#md-edit-user-name').html(invoice.user.first_name)
            get('#md-edit-method-pic').attr('src', `${baseUrl}/assets/media/payments/${invoice.method}.png`)
            get('select[name="state"]').val(invoice.state).trigger('change')
            get('textarea[name="note"]').html(invoice.note)
            if(invoice.sent == 1){
                get('input[name="sent"]').prop('checked', true)
                get('input[name="sent"]').prop('disabled', true)
            }
        }
    }
}

// create listeners
const listener = {
    init() {
        //get server id by select
        parameters.params.sid = $('select[name="sid"]').val()

        //set sid from select change
        $('select[name="sid"]').on('change', function () {
            parameters.params.sid = $(this).val()
            invoice.list()
        })

        //set uid from select change
        $('select[name="uid"]').on('change', function () {
            parameters.params.uid = $(this).val()
            invoice.list()
        })

        //load invoice list from select state change
        $('#state-filter').on('change', function () {
            parameters.params.state = $(this).val()
            invoice.list()
        })

        $('input[name="search"]').on('change', function () {
            parameters.params.search = $(this).val()
            invoice.list()
        })


        invoice.list()
        invoice.loadUsers()
    }
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

//start invoice page functions
$(document).ready(function () {
    listener.init()
})
