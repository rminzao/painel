const invoice = {
    list: (page = 1) => {
        axios.get(`${baseUrl}/api/invoice/list`, {
            params: {
                page: page
            }
        }).then((res) => {
            invoice.populate(res.data)
        }).catch(() => {
            helper.loader('#invoice_data', false);
        })
    },
    populate: (data) => {
        helper.loader('#invoice_data');
        var invoices = $("#invoice_list");
        invoices.empty();

        if (data.invoices.length >= 1) {
            $('#no_results').hide()
            $('#invoice_data').show()
        }

        $.each(data.invoices, (_, value) => {
            var getMethod = () => {
                switch (value.method) {
                    case 'picpay':
                        return `<img class="symbol-label" src="${baseUrl}/assets/media/payments/picpay.png" alt=""/>`;
                    case 'mercadopago':
                        return `<img class="symbol-label" src="${baseUrl}/assets/media/payments/mercadopago.png" alt=""/>`;
                    default:
                        return `<div class="symbol-label fs-5 fw-bold text-primary">cps</div>`;
                }
            }

            var getState = () => {
                switch (value.state) {
                    case 'pending':
                        return `<div class="badge badge-light-warning fw-bolder">Pendente</div>`;
                    case 'approved':
                        return `<div class="badge badge-light-success fw-bolder">Aprovado</div>`;
                    default:
                        return `<div class="badge badge-light-danger fw-bolder">${value.state}</div>`;
                }
            }

            invoices.append(`<tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px overflow-hidden me-3">
                                ${getMethod()}
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 mb-1">${value.method}</span>
                                <span>🧾 ${value.reference}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                          <span class="text-gray-800fs-8 mb-1">${value.product.name}</span>
                          <span>
                          💵 R$<span class="text-primary fs-8 mb-1">${value.value}</span>
                          </span>
                      </div>
                    </td>
                    <td>
                      💴 x${value.product.ammount}
                    </td>
                    <td>${getState()}</td>
                    <td>
                        <div class="badge badge-light-${value.sent == '0' ? 'danger' : 'success'} fw-bolder">
                            ${value.sent == '0' ? 'não enviado' : 'enviado'}
                        </div>
                    </td>
                </tr>`);
        });

        $('#invoice_paginator').html(data.paginator.rendered);

        helper.loader('#invoice_data', false);
    }
}
invoice.list();
