// page params
const parameters = {
  shop: {
    params: {
      sid: null,
      page: 1,
      search: null,
      type: 0,
      limit: 10,
    },
  },
  shopShow: {
    params: {
      sid: null,
      id: null,
    },
  },
}

const shopState = {
  id: null,
}

const shop = {
  list: (page = 1) => {
    parameters.shop.params.page = page
    loader.init('#shop_body')
    axios.get(`${baseUrl}/api/admin/game/shop`, parameters.shop).then((results) => {
      shop.populate(results.data)
    })
  },
  create: () => {
    const data = $("#md_new_shop form").serializeObject();
    data.sid = parameters.shop.params.sid

    var button = document.querySelector('#md_new_shop button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/shop`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_new_shop').modal('hide')
        shop.list()
      }
    })
  },
  update: button => {
    const data = $("#shop_data form").serializeObject();
    data.sid = parameters.shop.params.sid

    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/shop`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_edit_event').modal('hide')
        shop.list(parameters.shop.params.page)
      }
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar esse conjunto? Essa alteração não pode ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/game/shop`, {
          params: {
            sid: parameters.shop.params.sid,
            id: id,
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            shop.list(parameters.shop.params.page)
            if (shopState.id == id) {
              shopState.id = null
              $('#not_selected').show()
              $('#shop_data').hide()
            }
          }
        })
      }
    })
  },
  populate: (data) => {
    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#shop_list'),
      showList = $('#shop_show_list'),
      paginator = $('#item_paginator'),
      shopList = data.data

    //clear list and pagination
    list.empty()
    paginator.empty()

    // check if data is not empty
    if (shopList.length < 1) {
      $('#not_results').show()
      list.hide()
      loader.destroy('#shop_body')
      return;
    }

    //get shopType
    var moneyType = (id) => {
      if (moneyTypeList[id] === undefined) {
        return `Desconhecido (${id})`
      }
      return moneyTypeList[id];
    }

    var shopItem = (info, last = false) => {
      var needSex = ''
      if (info.Item?.NeedSex == 1) {
        needSex = '<div class="badge me-n4">🧢</div>'
      } else if (info.Item?.NeedSex == 2) {
        needSex = '<div class="badge me-n4">🎀</div>'
      }

      return `<div class="d-flex flex-stack pt-2" id="shop-${info.ID}">
                <div class="d-flex align-items-center">
                    <div class="w-40px h-40px me-3 rounded bg-light">
                        <img src="${info.Item?.Icon}"
                            onerror="this.src='${baseUrl}/assets/media/icons/original.png';"
                            class="w-100">
                    </div>
                    <div class="me-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <a href="javascript:;" class="fs-8 text-gray-900 text-hover-primary" id="by-name">
                                  ${info.ID} - ${info.Item?.Name ?? 'Item desconhecido'} ${needSex}
                                </a>
                                <div class="text-muted fs-7">💾 Tipo: <span class="text-primary">${moneyType(parseInt(info.ShopID))}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                        <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path> </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
                        <span class="svg-icon svg-icon-3" id="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
            ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`;
    }

    $.each(shopList, (_, event) => {
      list.append(shopItem(event, (_ == shopList.length - 1)))

      list.find(`#shop-${event.ID} #edit, #shop-${event.ID} #by-name`).on('click', () => {
        shop.populateEdit(event)
        shopState.id = event.ID
        $('#not_selected').hide()
        $('#shop_data').show()

        parameters.shopShow.params.id = event.ID
        shopShow.list()
      })

      list.find(`#shop-${event.ID} #delete`).on('click', () => {
        shop.delete(event.ID)
      })
    })

    paginator.html(data.paginator.rendered)
    loader.destroy('#shop_body')

    $('#not_results').hide()
    $('#shop_list').show()
  },
  populateEdit: (data) => {
    $('#md_new_shop_show input[name="shopID"]').val(data.ID);
    $.each(data, (key, val) => {
      var value = val;
      if (key != 'StartDate' && key != 'EndDate') {
        var value = parseInt(val);
      }

      //checkbox
      if (key == 'IsCheap' || key == 'IsVouch' || key == 'IsBind' || key == 'IsContinue') {
        $(`#shop_detail input[name="${key}"]`).prop("checked", value == 1 ? true : false);
        return;
      }

      if (key == 'BuyType' || key == 'Label') {
        $(`#shop_detail  select[name="${key}"]`).val(value).trigger('change');
        return;
      }

      //select
      if (key == 'ShopID' || key == 'APrice1' || key == 'APrice2' || key == 'APrice3' || key == 'BPrice1' || key == 'BPrice2' || key == 'BPrice3' || key == 'CPrice1' || key == 'CPrice2' || key == 'CPrice3') {
        if ($(`#shop_detail select[name="${key}"] option[value="${value}"]`).val()) {
          $(`#shop_detail  select[name="${key}"]`).val(value).trigger('change');
          return;
        }

        var newOption = new Option(`Desconhecido (${value})`, value, true, true);
        $(`#shop_detail select[name="${key}"]`).append(newOption).trigger('change');
        return;
      }

      $(`#shop_detail input[name="${key}"]`).val(value)
      return;
    })
  },
  updateOnGame: () => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar o shop ? ao fazer isso a <b>xml</b> e os <b>emuladores</b> serão atualizados.",
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
        var button = document.querySelector("#update_on_game");
        changeButtonState(button, true);
        axios.get(`${baseUrl}/api/admin/game/shop/update-on-game`, parameters.shop).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          changeButtonState(button, false);
        })
      }
    });
  },
}

const shopShow = {
  list: () => {
    loader.init('#show_body')
    axios.get(`${baseUrl}/api/admin/game/shop/show`, parameters.shopShow).then((results) => {
      shopShow.populate(results.data)
    })
  },
  create: () => {
    const data = $("#md_new_shop_show form").serializeObject();
    data.sid = parameters.shop.params.sid

    var button = document.querySelector('#md_new_shop_show button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/shop/show`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_new_shop_show').modal('hide')
        shopShow.list()
      }
    })
  },
  update: () => {
    const data = $("#md_edit_shop_show form").serializeObject();
    data.sid = parameters.shop.params.sid

    var button = document.querySelector('#md_edit_shop_show button[type="button"]');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/shop/show`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_edit_shop_show').modal('hide')
        shopShow.list()
      }
    })
  },
  delete: (id, type) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover esse item da loja ? Essa alteração não pode ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/game/shop/show`, {
          params: {
            sid: parameters.shop.params.sid,
            id: id,
            type: type
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            shopShow.list()
          }
        })
      }
    })
  },
  populate: (data) => {
    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#shop_show_list'),
      shopList = data.data

    //clear list and pagination
    list.empty()
    list.hide()

    // check if data is not empty
    if (shopList.length < 1) {
      $('#no_shows').show()
      list.hide()
      loader.destroy('#show_body')
      return;
    }

    //get shopType on shopTypeList by key
    var shopType = (id) => {
      return shopTypeList[id]?.name ? shopTypeList[id].name : (shopTypeList[id]?.prefix ?? `Desconhecido - ${id}`)
    }

    var shopShowItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="shopShow-${info.ShopId}${info.Type}">
            <div class="fw-bold mb-0">
                <div class="d-flex mb-1">
                  <div class="fs-7 text-muted me-3">
                    🎟️ shopID: <span class="fs-7 text-dark">${info.ShopId}</span>
                  </div>
                </div>
                <div class="fs-7 text-muted">
                  🛒 Tipo: <span class="text-dark">${shopType(info.Type)}</span> - <span class="text-dark">${info.Type}</span>
                </div>
            </div>
            <div class="d-flex">
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" data-bs-toggle="modal" data-bs-target="#md_edit_shop_show" id="edit">
                <span class="svg-icon svg-icon-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                  </svg>
                </span>
              </button>
              <button type="button" id="delete" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger">
                  <span class="svg-icon svg-icon-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                      </svg>
                  </span>
              </button>
            </div>
        </div>
        ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`;
    }

    // populate list
    $.each(shopList, (_, item) => {
      list.append(shopShowItem(item, (_ == shopList.length - 1)))

      list.find(`#shopShow-${item.ShopId}${item.Type} #edit, #shopShow-${item.ShopId}${item.Type} #by-name`).on('click', () => {
        shopShow.populateEdit(item)
      })

      list.find(`#shopShow-${item.ShopId}${item.Type} #delete`).on('click', () => {
        shopShow.delete(item.ShopId, item.Type)
      })
    })

    list.show()
    $('#no_shows').hide()
    loader.destroy('#show_body')
  },
  populateEdit: (data) => {
    $('#md_edit_shop_show input[name="shopID"]').val(data.ShopId);
    $('#md_edit_shop_show input[name="originalType"]').val(data.Type);
    $('#md_edit_shop_show select[name="type"]').val(data.Type).trigger('change');
  },
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

const init = () => {
  //get server id by select
  parameters.shop.params.sid = $('select[name="sid"]').val()
  parameters.shopShow.params.sid = $('select[name="sid"]').val()

  //set sid from select change
  $('select[name="sid"]').on('change', function () {
    parameters.shop.params.sid = $(this).val()
    parameters.shopShow.params.sid = $(this).val()
    shop.list()
    //shopShow.list()
  })

  $('#shop_body select[name="type"]').on('change', function () {
    parameters.shop.params.type = $(this).val()
    shop.list()
    //shopShow.list()
  })

  $('#shop_body input[name="search"]').on('change', function () {
    parameters.shop.params.search = $(this).val()
    shop.list()
  })

  $('a[href="#shop_show_detail"]').on('click', () => {
    $('#detail_buttons').hide()
    $('#parts_buttons').show()
  })

  $('a[href="#shop_detail"]').on('click', () => {
    $('#parts_buttons').hide()
    $('#detail_buttons').show()
  })

  $('input[name="StartDate"], input[name="EndDate"]').flatpickr({
    enableTime: false,
    enableSeconds: false,
    dateFormat: "Y-m-d H:i:s",
  });

  //item search
  $('#md_new_shop #itemID').select2({
    minimumInputLength: 2,
    templateResult: (item) => {
      if (!item.id) {
        return item.text;
      }

      var span = document.createElement('span')
      span.innerHTML = `<img src="${item.pic}" class="h-30px me-2" alt="image"/> ${item.text}`;
      return $(span);
    },
    ajax: {
      url: `${baseUrl}/api/admin/item`,
      dataType: 'json',
      type: "GET",
      data: (search) => {
        return {
          sid: parameters.shop.params.sid,
          search
        };
      },
      processResults: (data) => {
        return {
          results: $.map(data.items, function (item) {
            var sex = ''
            if (item.NeedSex == "1") {
              sex = '🧢'
            }
            if (item.NeedSex == "2") {
              sex = '🎀'
            }
            return {
              text: `[${item.TemplateID}] - ${item.Name} ${sex}`,
              id: item.TemplateID,
              pic: item.Icon,
              sex: sex,
              data: item
            }
          })
        };
      }

    }
  });

  $('#shop_detail select[name="BuyType"]').on('change', function () {
    $('body #shop_detail #unit_label .text-primary').html(this.value == '1' ? 'Quantidade' : 'Dias')
  })

  $('#md_new_shop form select[name="BuyType"]').on('change', function () {
    $('body #md_new_shop #unit_label .text-primary').html(this.value == '1' ? 'Quantidade' : 'Dias')
  })

  shop.list()
  //shopShow.list()
}

init()
