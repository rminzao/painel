const user = {
    detail: () => {
        axios.get(`${baseUrl}/api/admin/user/${state.user.id}/detail`).then((res) => {
            const uData = res.data

            user.populateCharacter(uData.characters)

            state.user.characters = uData.characters

            $('#person_id').empty().trigger('change')

            $.each(uData.characters, (i, character) => {
                $('#person_id').append(`
                <option value="${character.UserID}" data-server="${character.server_id}" data-server-name="${character.server_name}">
                    ${character.NickName}
                </option>`)
            })

            $('#person_id').val(uData.characters[0].UserID).trigger('change')
        })
    },
    populateCharacter: (data) => {
    },
    update() {
        const data = $("#kt_user_info_update_form").serializeObject();
        var button = document.querySelector("#kt_user_info_update_submit");
        data.id = state.user.id;
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/user`, data).then((response) => {
            var su = response.data;
            swMessage(su.state ? "success" : "warning", su.message);
            changeButtonState(button, false);
        }).catch((error) => {
            swMessage("error", "erro interno, verifique o console.");
            changeButtonState(button, false);
            console.error(error);
        });
    },
    updatePassword() {
        const data = $("#kt_user_password_update_form").serializeObject();
        var button = document.querySelector("#kt_user_password_update_submit");
        data.id = state.user.id;
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/user/password`, data).then((response) => {
            var su = response.data;
            swMessage(su.state ? "success" : "warning", su.message);
            changeButtonState(button, false);
        }).catch((error) => {
            swMessage("error", "erro interno, verifique o console.");
            changeButtonState(button, false);
            console.error(error);
        });
    },
    updateEmail() {
        const data = $("#kt_user_email_update_form").serializeObject();
        var button = document.querySelector("#kt_user_email_update_submit");
        data.id = state.user.id;
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/user/email`, data).then((response) => {
            var su = response.data;
            swMessage(su.state ? "success" : "warning", su.message);
            changeButtonState(button, false);
        }).catch((error) => {
            swMessage("error", "erro interno, verifique o console.");
            changeButtonState(button, false);
            console.error(error);
        });
    },
    disconnectAccount: (uid) => {
        Swal.fire({
            icon: "question",
            html: "Você tem certeza que deseja desconectar este jogador ?",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sim, Desconecte !",
            cancelButtonText: "Não, cancele !",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light",
            },
        }).then((res) => {
            if (res.isConfirmed) {
                var button = document.querySelector("#disconnect_account");
                changeButtonState(button, true);

                axios.get(`${baseUrl}/api/admin/game/user/disconnect`, {
                    params: {
                        uid: uid,
                        sid: state.sid
                    }
                }).then((res) => {
                    const su = res.data;

                    changeButtonState(button, false);
                    swMessage(su.state ? "success" : "warning", su.message);
                });
            }
        });


    }
}

const invoice = {
    list() {
        $('#tab_user_invoice #no_results').show();
        $('#tab_user_invoice .table-responsive').hide();
        loader.init('#tab_user_invoice .card-body');
        axios.get(`${baseUrl}/api/admin/invoice`, {
            params: {
                page: 1,
                uid: state.user.id,
                limit: 999
            }
        }).then((response) => {
            var su = response.data;
            $('#invoice_list').empty();
            if (su.invoices.length == 0) {
                $('#tab_user_invoice #no_results').show();
                $('#tab_user_invoice .table-responsive').hide();
                loader.destroy('#tab_user_invoice .card-body');
                return;
            }

            $('#tab_user_invoice #no_results').hide();
            $('#tab_user_invoice .table-responsive').show();

            $.each(su.invoices, function (_, item) {
                //populate invoice list
                invoice.populate(item);
            });
            loader.destroy('#tab_user_invoice .card-body');
        }).catch((error) => {
            swMessage("error", "erro interno, verifique o console.");
            console.error(error);
            loader.destroy('#tab_user_invoice .card-body');
        });
    },
    populate(data) {
        var getInvoiceState = () => {
            switch (data.state) {
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
                    return `<div class="badge badge-light-primary">${data.state}</div>`
            }
        }

        var sentState = '<div class="badge badge-light-danger fw-bolder">não enviado</div>';
        if (data.sent == '1') {
            sentState = '<div class="badge badge-light-success fw-bolder">prêmio enviado</div> '
        }

        $('#invoice_list').append(`<tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="symbol symbol-40px overflow-hidden me-3">
                    <img class="symbol-label" src="${baseUrl}/assets/media/payments/${data.method}.png" alt="" />
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-8 fw-bolder text-gray-900 mb-1">${data.method}</span>
                    <span class="text-muted fs-7 mb-1">🧾 ${data.reference}</span>
                </div>
              </div>
            </td>
            <td>
                <div class="d-flex flex-column">
                    <a class="text-gray-800 text-hover-primary fs-8 mb-1">Recarga</a>
                    <span>
                    💵 R$<span class="text-primary fs-8 mb-1">${data.value}</span>
                    </span>
                </div>
            </td>
            <td>
              💴 x${data.product?.ammount}
            </td>
            <td>
                ${getInvoiceState()}
            </td>
            <td>
                ${sentState}
            </td>
            <td class="text-center">
            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px me-2">
                <span class="svg-icon svg-icon-3" id="edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z" fill="currentColor"></path>
                        <path opacity="0.3" d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z" fill="currentColor"></path>
                        <path opacity="0.3" d="M15 17H9V20H15V17Z" fill="currentColor"></path>
                    </svg>
                </span>
            </button>
            </td>
        </tr>`);
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

const webListener = {
    init() {
        user.detail();

        //capture user update
        $("#kt_user_info_update_submit").click(() => {
            user.update();
        });

        //capture user password update
        $("#kt_user_password_update_submit").click(() => {
            user.updatePassword();
        });

        $("#kt_user_email_update_submit").click(() => {
            user.updateEmail();
        });

        $('a[href="#tab_user_invoice"]').click(() => {
            invoice.list();
        });
    }
}

webListener.init();
