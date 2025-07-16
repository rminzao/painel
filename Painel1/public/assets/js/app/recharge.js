const stateRecharge = {
    pid: null,
    code: null,
}

const recharge = {
    detail: (id) => {
        $('#kt_modal_product_detail').modal('show');
        $('#md_product_detail #rewards').hide()
        $('#product_old_value, #product_discount').hide()
        $('#content_md').invisible()
        loader.init('#md_product_detail')

        axios.get(`${baseUrl}/api/product/detail/${id}`).then((res) => {
            recharge.populateDetail(res.data)
        })
    },
    populateDetail: (data) => {
        stateRecharge.pid = null
        stateRecharge.code = null
        $('input[name="promotion-code"]').val('')
        if (!data.state) {
            swMessage('warning', data.message);
            loader.destroy()
            return;
        }
        $('#cupon_info').show();
        $('#pix_alert').show();
        $('#laboratory_info').hide();

        if (data.data.type == '3') {
            $('#laboratory_info').show();
            $('#cupon_info').hide();
            $('#pix_alert').hide();
        }

        stateRecharge.pid = data.data.id

        const rewards = data.data?.rewards,
            rewardsList = $("#md_product_detail #rewards-list")

        rewardsList.empty()

        if (rewards) {
            $('#md_product_detail #rewards').show()
            $.each(rewards, (_, item) => {
                rewardsList.append(`
                <div class="symbol symbol-50px me-5 mb-5" data-toggle="popover" data-html="true">
                    <div style="position: absolute; right: 0; bottom: 0; background-color: #000000a3; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; color: #fff;width: 100%; text-align: center;">x${item.ItemCount}</div>
                    <img src="${item.Pic}" alt="">
                </div>`);

                var boxList = '';
                if (item?.box?.length > 0) {
                    $.each(item?.box, (_, box) => {
                        boxList += `<div class="w-30px h-30px rounded bg-light m-1">
                        <img class='w-100 h-100' 
                                src='${box?.Pic}' 
                                onerror="this.src='${baseUrl}/assets/media/icons/original.png';" />
                    </div>`;
                    });
                }
                $('[data-toggle="popover"]').popover({
                    trigger: 'hover',
                    html: true,
                    title: () => {
                        return `${item?.NeedSex != '0' ? `${item?.NeedSex == '1' ? '🧢' : '🎀'}` : ''} ${item?.Name}` ?? '❓ Desconhecido';
                    },
                    content: () => {
                        return `<div class='d-flex align-items-center mb-5'>
                            <div class='w-40px h-40px me-3 rounded bg-secondary'>
                                <img class='w-100 h-100' 
                                src='${item?.Pic}' 
                                onerror="this.src='${baseUrl}/assets/media/icons/original.png';" /> 
                            </div>
                            <div class='me-5'>
                              <div class='fs-8'>
                                Categoria: <span class='text-gray-800'>${item?.CategoryID}</span>
                              </div>
                              <div class='fs-8'>
                                ${[1, 5, 7, 27].includes(parseInt(item?.CategoryID))
                                ? `${[7, 27].includes(parseInt(item?.CategoryID)) ? 'Dano' : 'Armadura'}: <span class='text-gray-800'>${item?.Property7}</span>`
                                : `Count: <span class='text-gray-800'>${item.ItemCount}</span>`}
                              </div>
                            </div>
                          </div>
                            <div class='flex-stack mt-3 mb-3'>
                                <div class='fs-8 fw-bolder'>Descrição:</div>
                                <div class='text-start fs-8 text-gray-800'>
                                    ${item?.Description != ''
                                ? `${item?.Description}`
                                : 'não possui descrição'}
                                </div>
                            </div>
                            ${boxList != '' ? `
                            <div class='flex-stack mt-3 mb-3'>
                                <div class='fs-8 fw-bolder'>${boxList != '' ? 'Recompensas:' : ''}</div>
                                <div class='d-flex flex-wrap justify-content-center rounded highlight p-0 py-4'>
                                    ${boxList}
                                </div>
                            </div> ` : ''}
                      `;
                    }
                })
            });
        }

        $('#md_product_detail #product_name').html(data.data.name)
        $('#md_product_detail #product_value').html(data.data.value)
        $('#md_product_detail #product_amount').html(data.data.ammount)
        $('#md_product_detail #btn_mp').attr('onclick', `recharge.buy('mercadopago', ${data.data.id})`);
        $('#md_product_detail #btn_pix').attr('onclick', `recharge.buy('mercadopago', ${data.data.id})`);
        $('#md_product_detail #btn_picpay').attr('onclick', `recharge.buy('picpay', ${data.data.id})`);

        loader.destroy()
        $('#content_md').visible()
    },
    checkCode: () => {
        var button = document.querySelector("#btn_check_code");
        changeButtonState(button, true);
        const code = $('input[name="promotion-code"]').val()
        $('#content_md').invisible()
        loader.init('#md_product_detail')
        axios.post(`${baseUrl}/api/product/checkCode`, {
            code: code,
            pid: stateRecharge.pid
        }).then((res) => {
            if (!res.data.state) {
                swMessage('warning', res.data.message);
            }

            if (res.data.state) {
                stateRecharge.code = code
                $('#product_old_value, #product_discount').show()
                $('#product_old_value del').html(res.data.data.original)
                $('#product_value').html(res.data.data.value)
                $('#product_discount span').html(res.data.data.discount)

                swMessage('success', res.data.message);
            }

            $('#content_md').visible()
            loader.destroy()
            changeButtonState(button, false);

            return;
        })
    },
    buy: (method, id) => {
        $('#content_md').invisible()
        loader.init('#md_product_detail')
        axios.get(`${baseUrl}/api/invoice/create`, {
            params: {
                method: method,
                id: id,
                code: stateRecharge.code
            }
        }).then((res) => {
            if (res.data.state) {
                window.location.href = res.data.paymentUrl;
            }
        })
    }
}

const loader = {
    init(element) {
        var target = document.querySelector(element);
        var blockUI = new KTBlockUI(target);

        blockUI.destroy();
        blockUI.block();
    },
    destroy() {
        var target = document.querySelector(`.blockui-overlay`);
        target.remove()
    }
}
