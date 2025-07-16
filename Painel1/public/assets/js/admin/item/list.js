const parameters = {
  item: {
    params: {
      sid: null,
      page: 1,
      limit: 10,
      search: null,
      category: 0,
      onclick: 'item.list'
    }
  }
}

const item = {
  list: (page = 1) => {
    parameters.item.params.page = page
    helper.loader('#items_body')
    axios.get(`${baseUrl}/api/admin/item/list`, parameters.item).then(res => {
      item.populate(res.data)
    })
  },
  create: () => { },
  update: () => {
    const data = $("#form-item-update").serializeObject();
    data.sid = parameters.item.params.sid

    var button = document.querySelector("#item-update-form-submit");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/item`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        item.list(parameters.item.params.page)
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar este item ? essa alteração não pode ser desfeita e poderá acarretar <span class=\"text-danger\">erros imprevistos</span> no servidor.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      axios.delete(`${baseUrl}/api/admin/item`, {
        params: {
          sid: parameters.item.params.sid,
          id: id
        }
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state) {
          item.list(parameters.item.params.page)

          if (id == $('#item-info input[name="TemplateID"]').val()) {
            $("#not_selected").show()
            $("#item-data").hide()
          }
        }
      })
    });
  },
  duplicate: (id) => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que <br>deseja <span class=\"text-primary\">duplicar</span> este item ?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, duplique isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      axios.post(`${baseUrl}/api/admin/item/duplicate`, {
        sid: parameters.item.params.sid,
        id: id
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state)
          item.list(parameters.item.params.page)
      })
    });
  },
  updateOnGame() {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar os items ? ao fazer isso a <b>xml</b> e os <b>emuladores</b> serão atualizados.",
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
        var button = document.querySelector("#button_update_game_item");
        changeButtonState(button, true);
        axios.get(`${baseUrl}/api/admin/item/game/update`, {
          params: {
            sid: parameters.item.params.sid
          }
        }).then(res => {
          changeButtonState(button, false);
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        })
      }
    });

  },
  populate: (data) => {
    if (data.items.length <= 0) {
      helper.loader('#items_body', false)
      $('#not_results').show()
      $('#item_body_list').hide()
      return
    }

    const items = data.items,
      itemList = $('#item_body_list'),
      paginator = $('#item_paginator')

    var itemRow = (info, last = false) => {
      return `
      <div class="d-flex flex-stack pt-2" id="item-${info.TemplateID}">
          <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
                <img src="${info.Icon}" onerror="this.src='${baseUrl}/assets/media/icons/original.png';" class="w-100">
            </div>
            <div>
                <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">${helpers.str_limit_words(info.Name, 30)}</a>
                <div class="text-muted fs-7 mb-1">ID: ${info.TemplateID}</div>
            </div>
          </div>
          <div class="d-flex align-items-end ms-2">
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="duplicate">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"></path>
                </svg>
              </span>
            </button>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                <span class="svg-icon svg-icon-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                  </svg>
                </span>
            </button>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
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
      ${last ? '<div class="pt-2 separator separator-dashed"></div>' : ''}`
    }

    itemList.empty()

    $.each(items, (_, info) => {
      itemList.append(
        itemRow(info, items.length - 1 == _ ? false : true)
      )

      $(`#item_body_list #item-${info.TemplateID} #edit, #item_body_list #item-${info.TemplateID} #edit_name`).click(() => {
        itemBox.list(info.TemplateID)
        item.populateDetail(info)
      })

      $(`#item_body_list #item-${info.TemplateID} #duplicate`).click(() => {
        item.duplicate(info.TemplateID)
      })

      $(`#item_body_list #item-${info.TemplateID} #delete`).click(() => {
        item.delete(info.TemplateID)
      })
    });

    (data.paginator.rendered == null) ? paginator.hide() : paginator.show();

    paginator.html(data.paginator.rendered)

    $('#not_results').hide()
    $('#item_body_list').show()
    helper.loader('#items_body', false)
  },
  populateDetail: (data) => {
    $("#not_selected").hide()
    $("#item-data").show()

    $.each(data, (key, value) => {
      if (key == 'Description') {
        $(`#item-info textarea[name='${key}']`).val(value)
        return;
      }

      $(`#item-info input[name='${key}']`).val(value)
    })

    if (data.CategoryID == '11' && (data.Property1 == '6' || data.Property1 == '114')) {
      $('#item-box-tab').show()
      return
    }

    $('#box-reward-buttons').hide()
    $('#item_toolbar_default').show()

    $('a[href="#item-info"]').tab('show')
    $('#item-box-tab, #box-reward-buttons').hide()
  },
}

const itemBox = {
  list: (id) => {
    helper.loader('#item_box_body')
    axios.get(`${baseUrl}/api/admin/item/box`, {
      params: {
        sid: parameters.item.params.sid,
        itemID: id,
      }
    }).then(res => { itemBox.populate(res.data) })
    $('#md_box_new_item input[name="ID"]').val(id)
  },
  create: () => {
    const data = $("#md_box_new_item form").serializeObject();
    data.sid = parameters.item.params.sid

    var button = document.querySelector("#btn-send-box-create");
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/item/box`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        $("#md_box_new_item form")[0].reset();
      $('#md_box_new_item select[name="TemplateId"]').val('').trigger('change')
      itemBox.list(data.ID)
    })
  },
  update: () => {
    const data = $("#md_box_edit_item form").serializeObject();
    data.sid = parameters.item.params.sid

    var button = document.querySelector("#btn-box-reward-update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/item/box`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        itemBox.list(data.ID)
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover o item dessa caixa ? essa alteração não tem como ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      const itemID = $('#item-info input[name="TemplateID"]').val()
      axios.delete(`${baseUrl}/api/admin/item/box`, {
        params: {
          sid: parameters.item.params.sid,
          itemID: itemID, //id da caixa
          templateID: id //id do item
        }
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state)
          itemBox.list(itemID)
      })
    });
  },
  populate: (data) => {
    if (data.items.length <= 0) {
      $('#item_box_list').hide()
      $('#item_box_list').empty()
      $('#no_items_box').show()
      helper.loader('#item_box_body', false)
      return
    }

    const items = data.items,
      boxList = $('#item_box_list');

    var itemRow = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="item-box-${info.TemplateId}">
        <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
                <img src="${info.Icon}" onerror="this.src='${baseUrl}/assets/media/icons/original.png';" class="w-100">
            </div>
            <div class="me-3">
                <div class="d-flex align-items-center">
                  <div class="text-gray-800 fw-bolder">${info?.Name ?? '❓ Desconhecido'}</div>
                  <div class="badge badge-light-primary ms-5 me-2">x${info.ItemCount}</div>
                  <div class="badge badge-light-${info.IsBind != '0' ? 'danger' : 'success'} me-2">
                    ${info.IsBind != '0' ? 'Limitado' : 'Ilimitado'}
                  </div>
                  <div class="badge badge-light-primary"> 🎲 ${info.Random} </div>
                  ${info.NeedSex >= 1 ? `<div class="badge me-n4">${info.NeedSex == 1 ? '🧢' : '🎀'}</div>` : ''}
                  ${info.IsSelect != '0' ? '<div class="badge">🤚</div>' : ''}
                  ${info.IsLogs != '0' ? '<div class="badge">📃</div>' : ''}
                  ${info.IsTips != '0' ? '<div class="badge">📢</div>' : ''}
                </div>
                <div class="text-muted">${info.TemplateId}</div>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center">
          <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
            <span class="svg-icon svg-icon-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
              </svg>
            </span>
          </button>
          <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
            <span class="svg-icon svg-icon-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none">
                  <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                  <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                  <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
              </svg>
            </span>
          </button>
        </div>
      </div>
      ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    }

    $('#item_box_list').empty()

    $.each(items, (_, box) => {
      boxList.append(itemRow(box, (_ == items.length - 1)))

      $(`#item_box_list #item-box-${box.TemplateId} #edit`).click(() => {
        itemBox.populateDetail(box)
      })

      $(`#item_box_list #item-box-${box.TemplateId} #delete`).click(() => {
        itemBox.delete(box.TemplateId)
      })
    })

    $('#item_box_list').show()
    $('#no_items_box').hide()

    helper.loader('#item_box_body', false)
  },
  populateDetail: (data) => {
    $("#md_box_edit_item #attr_area").show();
    $("#md_box_edit_item #strengthen_area").removeClass("d-none");
    $("#md_box_edit_item #count_area").removeClass("col-12");
    $("#md_box_edit_item #count_area").addClass("col-6");

    if (data.CanStrengthen == "0") {
      $("#md_box_edit_item #strengthen_area").addClass("d-none");
      $("#md_box_edit_item #count_area").removeClass("col-6");
      $("#md_box_edit_item #count_area").addClass("col-12");
    }

    if (data.CanCompose == "0")
      $("#md_box_edit_item #attr_area").hide();

    $('#md_box_edit_item #item_icon').attr('src', data.Icon)
    $('#md_box_edit_item #item_name').html(data.Name)
    $('#md_box_edit_item #item_id').html(data.TemplateId)

    $('#md_box_edit_item').modal('show')

    $.each(data, (key, value) => {
      const input = $(`#md_box_edit_item form`).find(`[name="${key}"]`)

      if (['IsTips', 'IsLogs', 'IsBind', 'IsSelect'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value)

      if (['StrengthenLevel', 'ItemValid'].includes(key))
        input.trigger('change')

    })
  }
}

const category = {
  list: () => {
    axios.get(`${baseUrl}/api/admin/item/categories`, parameters.item).then((res) => {
      category.populate(res.data)
    })
  },
  populate: (data) => {
    if (!data.state) {
      swMessage("warning", data.message)
      return
    }

    const categories = data.categories,
      categoryList = $('#items_body select[name="categoryID"]')

    categoryList.empty()

    categoryList.append(`<option value="0" selected>Todos</option>`);

    $.each(categories, (_, result) => {
      categoryList.append(`<option value="${result.ID}">${result.Name} - ${result.ID}</option>`)
    })

    categoryList.trigger('change')
  }
}

const controls = {
  listeners: () => {
    $('#items_body select[name="categoryID"]').on('change', function () {
      parameters.item.params.category = $(this).val()
      item.list()
    })

    $('#items_body select[name="limit"]').on('change', function () {
      parameters.item.params.limit = $(this).val()
      item.list()
    })

    $("#sid").on('change', () => {
      parameters.item.params.sid = $("#sid").val()
      item.list()
      category.list()
    })

    $('#item_search').on('change', () => {
      parameters.item.params.search = $('#item_search').val()
      item.list()
    })

    $('a[href="#item_box_body"]').on('click', () => {
      $('#box-reward-buttons').show()
      $('#item_toolbar_default').hide()
    })

    $('a[href="#item-info"]').on('click', () => {
      $('#box-reward-buttons').hide()
      $('#item_toolbar_default').show()
    })

    $('#md_box_new_item select[name="TemplateId"]').select2({
      minimumInputLength: 1,
      language: {
        searching: () => {
          return "Buscando aguarde...";
        },
        inputTooShort: () => {
          return 'Insira o nome ou id do item';
        }
      },
      templateResult: (item) => {
        if (!item.id)
          return item.text;

        return $(`<span><img src="${item.pic}" onerror="this.src='${baseUrl}/assets/media/icons/original.png';" class="h-30px me-2" alt="image"/> ${item.text}</span>`);
      },
      ajax: {
        url: `${baseUrl}/api/admin/item`,
        dataType: 'json',
        type: "GET",
        data: (search) => {
          return {
            sid: parameters.item.params.sid,
            search
          }
        },
        processResults: (data) => {
          return {
            results: $.map(data.items, (item) => {
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

    $('#md_box_new_item select[name="TemplateId"]').on('change', function () {
      $("#md_box_new_item #info_area, #md_box_new_item #item_icon, #md_box_new_item #item_name, #md_box_new_item #item_id").hide();
      axios.get(`${baseUrl}/api/admin/item`, {
        params: {
          sid: parameters.item.params.sid,
          'search[term]': $(this).val()
        }
      }).then((res) => {
        if (res.data.items.length == 0)
          return

        const data = res.data.items[0];

        $("#md_box_new_item #attr_area").show();
        $("#md_box_new_item #strengthen_area").removeClass("d-none");
        $("#md_box_new_item #count_area").removeClass("col-12");
        $("#md_box_new_item #count_area").addClass("col-6");

        if (data.CanStrengthen == "0") {
          $("#md_box_new_item #strengthen_area").addClass("d-none");
          $("#md_box_new_item #count_area").removeClass("col-6");
          $("#md_box_new_item #count_area").addClass("col-12");
        }

        if (data.CanCompose == "0")
          $("#md_box_new_item #attr_area").hide();

        $('#md_box_new_item #item_icon').attr('src', data.Icon)
        $('#md_box_new_item #item_name').html(`${data.Name} ${data.NeedSex == "1" ? '🧢' : ''} ${data.NeedSex == "2" ? '🎀' : ''}`)
        $('#md_box_new_item #item_id').html(data.TemplateID)


        $("#md_box_new_item #info_area, #md_box_new_item #item_icon, #md_box_new_item #item_name, #md_box_new_item #item_id").show();


        // $('#md-annex-pic').attr('src', item.Icon)
        // $('#md-annex-name').text(`${item.Name} ${sex}`)
        // $('#md-annex-id').text(item.TemplateID)
        // $("#md-annex-in-max").attr('max', item.MaxCount);

        // if (item.CanCompose == '0') {
        //   $('#md-annex-attribute-area').hide();
        // }

        // if (item.CanStrengthen == '0') {
        //   $('#md-annex-level-area').addClass('d-none');
        //   $('#md-annex-amount-area').removeClass('col-6');
        //   $('#md-annex-amount-area').addClass('col-12');
        // }

        // $('#md-item-info').show()
        // $('#md-annex-pic').show()
        // $('#md-annex-name').show()
        // $('#md-annex-id').show()
      })
    });
  },
  init: () => {
    parameters.item.params.sid = $("#sid").val()
    item.list()
    category.list()
    controls.listeners()
  }
}

controls.init()



