const stateItem = {
  sid: null,
  id: null,
  page: null,
  limit: 10,
  list: null,
  data: null,
  category: 0,
  boxRewards: null
}
//
var item = {
  create(info) {
    $('#item_body_list').append(`
    <div class="d-flex flex-stack pt-2" id="item-${info.TemplateID}">
        <div class="d-flex align-items-center">
        <div class="w-40px h-40px me-3 rounded bg-light">
            <img src="${info.Icon}" class="w-100">
        </div>
        <div>
            <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">${helpers.str_limit_words(info.Name, 30)}</a>
            <div class="text-muted fs-7 mb-1">ID: ${info.TemplateID}</div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end ms-2">
            <span class="text-muted fs-7 mb-1"></span>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
              <span class="svg-icon svg-icon-3" id="delete">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path> </svg>
              </span>
          </button>
        </div>
    </div>
    <div class="pt-2 separator separator-dashed"></div>`)
  },
  update() {
    const data = $("#form-item-update").serializeObject();
    var button = document.querySelector("#item-update-form-submit");
    changeButtonState(button, true);

    data.sid = stateItem.sid;

    axios.put(`${baseUrl}/api/admin/item`, data).then((response) => {
      var su = response.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state) {
        //this.list(stateItem.page)
      }
    }).catch((error) => {
      swMessage("error", "erro interno, verifique o console.");
      changeButtonState(button, false);
      console.error(error);
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
        $.get(`${baseUrl}/api/admin/item/game/update`, {
          sid: stateItem.sid,
        }, function (data) {
          changeButtonState(button, false)
          if (!data.state) {
            swMessage("warning", data.message);
            return;
          }

          swMessage("success", data.message);
          return;
        }
        );
      }
    });

  },
  list(page = 1) {
    stateItem.page = page
    stateItem.sid = $("#sid").val()

    loader.init('#items_body')
    itemBox.clear()
    $.get(`${baseUrl}/api/admin/item/list`, {
      sid: stateItem.sid,
      page: stateItem.page,
      search: $('#item_search').val(),
      onclick: 'item.list',
      category: stateItem.category,
      limit: stateItem.limit
    }, function (data) {
      if (data.items.length == 0) {
        item.clear()
        $('#no_items_box').show()
        $('#item-box-list').hide()
        $('#item-box-list').empty()
        loader.destroy('#items_body')
        return;
      }
      item.clear()

      stateItem.list = data.items
      $.each(data.items, function (_, result) {
        item.create(result)
        $("#item_body_list").find(`#item-${result.TemplateID} #edit, #item-${result.TemplateID} #edit_name`).on("click", () => {
          stateItem.data = result
          stateItem.id = result.TemplateID
          item.populateEdit(result)
        });
      });

      $("#item_paginator").html(data.paginator.rendered)
      $("#not_results").hide()
      $('#item_body_list').show()
      loader.destroy('#items_body')
    })
  },
  populateEdit(result) {
    $.each(result, function (val, key) {
      if (val == 'Description') {
        $(`#item-info textarea[name='${val}']`).html(key)
        return;
      }
      if (val == 'Icon') {
        $(`#item-info #pic-show`).attr('src', key)
      }
      $(`#item-info input[name='${val}']`).val(key)
    })


    $('#item-box-list').empty()
    $("#item-box, #item-box-tab").hide()
    if (isPackage(result)) {
      $("#item-box").show()
      $("#item-box-tab").show()
      itemBox.update()
    }
    if (!isPackage(result)) {
        $('a[href="#item-info"]').tab('show')
        $('#box-reward-buttons').hide()
    }
    if (result.CategoryID == '13') {
      $('.equipment-suit').show()
      $('.equipment-suit').css('background-image', `url(${result.Equipment})`)
    }
    $("#not_selected").hide()
    $("#item-data").show()
  },
  clear() {
    $('.equipment-suit').hide()

    stateItem.data = null
    stateItem.id = null

    $('#item_body_list, #item_paginator').empty()
    $('#item_body_list, #item-data').hide()
    $("#not_selected, #not_results").show()
  }
}

const category = {
  list: () => {
    axios.get(`${baseUrl}/api/admin/item/categories`, {
      params: {
        sid: stateItem.sid,
      }
    }).then((res) => {
      category.populate(res.data)
    })
  },
  populate: (data) => {
    if (!data.state) {
      swMessage("warning", data.message)
      return
    }

    const categories = data.categories,
      categories_select = $('#items_body select[name="categoryID"]')

    categories_select.empty()
    categories_select.append(`<option value="0" selected>0 - Todos</option>`);
    $.each(categories, (_, result) => {
      categories_select.append(`<option value="${result.ID}">${result.ID} - ${result.Name}</option>`)
    })
    categories_select.append(`<option value="box">999 - caixas</option>`);
  }
}

const itemBox = {
  update() {
    var createBox = function (info) {
      var isBindState = '<div class="badge badge-light-success me-2">Ilimitado</div>';
      if (info.IsBind != '0') {
        isBindState = '<div class="badge badge-light-danger me-2">Limitado</div>'
      }

      var needSex = ''
      if (info.NeedSex == 1) {
        needSex = '<div class="badge me-n4">🧢</div>'
      } else if (info.NeedSex == 2) {
        needSex = '<div class="badge me-n4">🎀</div>'
      }

      var isSelect = ''
      if (info.IsSelect != '0') {
        isSelect = '<div class="badge">⛏️</div>'
      }

      var isLogs = ''
      if (info.IsLogs != '0') {
        isLogs = '<div class="badge">📃</div>'
      }

      $('#item-box-list').append(`<div class="d-flex flex-stack mb-4">
        <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
                <img src="${info.Icon}"
                    class="w-100">
            </div>
            <div class="me-3">
                <div class="d-flex align-items-center">
                    <div class="text-gray-800 fw-bolder">${info.Name}</div>
                    <div class="badge badge-light-primary ms-5 me-2">x${info.ItemCount}</div>
                    ${isBindState}
                    <div class="badge badge-light-primary">🎲 ${info.Random}</div>
                    ${needSex}
                    ${isSelect}
                    ${isLogs}

                </div>
                <div class="text-muted">${info.TemplateId}</div>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" data-bs-toggle="modal" data-bs-target="#md_box_edit_item" onclick="itemBox.populateEdit(${info.TemplateId})">
        <span class="svg-icon svg-icon-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
      </svg>
      </span>
      </button>
      <button type="button"
      class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger"
      onclick="itemBox.confirmDelete(${info.TemplateId})">
      <span class="svg-icon svg-icon-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none">
            <path
                d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                fill="currentColor"></path>
            <path opacity="0.5"
                d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                fill="currentColor"></path>
            <path opacity="0.5"
                d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                fill="currentColor"></path>
        </svg>
      </span>
      </button>
      </div></div>`)
    }
    stateItem.boxRewards = null
    $.get(`${baseUrl}/api/admin/item/box`, {
      sid: stateItem.sid,
      itemID: stateItem.id,
    }, function (response) {
      itemBox.clear()
      if (response.items.length == 0) {
        $('#item-box-list').hide()
        $('#item-box-list').empty()
        $('#no_items_box').show()
        return
      }

      stateItem.boxRewards = response.items

      $('#item-box-list').show()
      $('#no_items_box').hide()
      $.each(response.items, function (_, result) {
        createBox(result)
      })

    })
  },
  sendCreate() {
    var button = document.querySelector("#btn-send-box-create");
    const data = $("#form-box-reward-send").serializeObject();
    data.sid = stateItem.sid;
    data.itemID = stateItem.id;

    changeButtonState(button, true);
    $.post(`${baseUrl}/api/admin/item/box`, data, function (response) {
      if (response.state) {
        $('#md_box_new_item #templateID').val('').trigger('change')
        itemBox.update()
        swMessage("success", response.message);
        changeButtonState(button, false);
        return;
      }
      swMessage("warning", response.message);
      changeButtonState(button, false);
    });
  },
  sendUpdate() {
    var button = document.querySelector("#btn-box-reward-update");
    changeButtonState(button, true);

    const data = $("#form-box-reward-update").serializeObject();
    data.sid = stateItem.sid;
    data.itemID = stateItem.id;

    if (data.amount > $("#md-edit-reward-annex-in-max").attr('max')) {
      data.amount = $("#md-edit-reward-annex-in-max").attr('max')
    }

    $.ajax({
      type: "PUT",
      url: `${baseUrl}/api/admin/item/box`,
      dataType: "json",
      contentType: "json",
      data: JSON.stringify(data),
      success: function (response) {
        if (response.state) {
          itemBox.update()
          swMessage("success", response.message);
          changeButtonState(button, false);
          return;
        }
        swMessage("warning", response.message);
        changeButtonState(button, false);
      },
    });
  },
  populateEdit(templateID) {

    var reward = stateItem.boxRewards.find((e) => e.ID == stateItem.id && e.TemplateId == templateID);
    console.log(reward)

    var restore = function () {
      $("#md-edit-reward-annex-attribute-area").show();
      $("#md-edit-reward-annex-level-area").removeClass("d-none");
      $("#md-edit-reward-annex-amount-area").removeClass("col-12");
      $("#md-edit-reward-annex-amount-area").addClass("col-6");

      if (reward.CanCompose == "0") {
        $("#md-edit-reward-annex-attribute-area").hide();
      }

      if (reward.CanStrengthen == "0") {
        $("#md-edit-reward-annex-level-area").addClass("d-none");
        $("#md-edit-reward-annex-amount-area").removeClass("col-6");
        $("#md-edit-reward-annex-amount-area").addClass("col-12");
      }
    };

    var get = function (element) {
      return $(`#form-box-reward-update ${element}`);
    };

    restore();

    $("#md-edit-reward-annex-id").html(reward.TemplateId);
    $("#md-edit-reward-annex-name").html(reward.Name);
    $("#md-edit-reward-annex-pic").attr("src", reward.Icon);

    $("#md-edit-reward-annex-in-max").attr("max", reward.MaxCount);

    get('input[name="templateID"]').val(reward.TemplateId);
    get('input[name="amount"]').val(reward.ItemCount);
    get('input[name="attack"]').val(reward.AttackCompose);
    get('input[name="defence"]').val(reward.DefendCompose);
    get('input[name="agility"]').val(reward.AgilityCompose);
    get('input[name="luck"]').val(reward.LuckCompose);
    get('input[name="random"]').val(reward.Random);
    get('select[name="valid"]').val(reward.ItemValid).trigger("change");
    get('select[name="level"]').val(reward.StrengthenLevel).trigger("change");
    get('input[name="isTips"]').prop("checked", reward.IsTips == "1" ? true : false);
    get('input[name="isLogs"]').prop("checked", reward.IsLogs == "1" ? true : false);
    get('input[name="isBind"]').prop("checked", reward.IsBind == "1" ? true : false);
    get('input[name="isSelect"]').prop("checked", reward.IsSelect == "1" ? true : false);
  },
  delete(id) {
    $.delete(`${baseUrl}/api/admin/item/box`,
      JSON.stringify({
        sid: stateItem.sid,
        itemID: stateItem.id,
        templateID: id
      }), (response) => {
        if (response.state) {
          itemBox.update()
          //$(`#item-${stateItem.id} #edit`).trigger( "click" );
          swMessage("success", response.message);
          return;
        }
        swMessage("warning", response.message);
      }, "json");
  },
  confirmDelete(id) {
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
      if (result.isConfirmed) {
        itemBox.delete(id);
      }
    });
  },
  clear() {
    $('#item-box-list').empty()
  }
}

const controls = {
  createListeners() {
    $('#item_search').on('change', () => {
      item.list()
    })

    $('#items_body select[name="categoryID"]').on('change', function () {
      stateItem.category = $(this).val()
      item.list()
    })

    $("#sid").on("change", () => {
      stateItem.sid = $("#sid").val();
      item.list();
      category.list()
    })

    $("#item-box-tab").on("click", () => {
      $('#box-reward-buttons').show()
    })

    $('#item-info-tab').on('click', () => {
      $('#box-reward-buttons').hide()
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
  destroy(element) {
    var target = document.querySelector('.blockui-overlay');
    target.remove()
  }
}

function init() {
  controls.createListeners()
  item.list()
  category.list()
}

init()

//refactor after
$('#md_box_new_item #templateID').on('change', function () {
  restoreDefaultModal()
  var templateID = $(this).val()
  $.get(`${baseUrl}/api/admin/item`, {
    sid: stateItem.sid,
    search: {
      term: templateID
    }
  }, function (response) {
    if (response.items < 1) {
      return;
    }

    const item = response.items[0];
    var sex = ''
    if (item.NeedSex == "1") {
      sex = '🧢'
    }
    if (item.NeedSex == "2") {
      sex = '🎀'
    }

    $('#md-annex-pic').attr('src', item.Icon)
    $('#md-annex-name').text(`${item.Name} ${sex}`)
    $('#md-annex-id').text(item.TemplateID)
    $("#md-annex-in-max").attr('max', item.MaxCount);

    if (item.CanCompose == '0') {
      $('#md-annex-attribute-area').hide();
    }

    if (item.CanStrengthen == '0') {
      $('#md-annex-level-area').addClass('d-none');
      $('#md-annex-amount-area').removeClass('col-6');
      $('#md-annex-amount-area').addClass('col-12');
    }

    $('#md-item-info').show()
    $('#md-annex-pic').show()
    $('#md-annex-name').show()
    $('#md-annex-id').show()
  })
})

// Format options

$('#md_box_new_item #templateID').select2({
  minimumInputLength: 2,
  templateResult: (item) => {
    if (!item.id)
      return item.text;

    return $(`<span><img src="${item.pic}" class="h-30px me-2" alt="image"/> ${item.text}</span>`);
  },
  ajax: {
    url: `${baseUrl}/api/admin/item`,
    dataType: 'json',
    type: "GET",
    data: function (search) {
      return {
        sid: stateItem.sid,
        search
      };
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

function restoreDefaultModal() {
  //hidden item detail
  $('#md-annex-pic').hide()
  $('#md-annex-name').hide()
  $('#md-annex-id').hide()


  $('#md-item-info').hide()
  $('#md-annex-attribute-area').show();
  $('#md-annex-level-area').removeClass('d-none');
  $('#md-annex-amount-area').removeClass('col-12');
  $('#md-annex-amount-area').addClass('col-6');
}
