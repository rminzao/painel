const stateDrop = {
  id: null,
  sid: null,
  page: null,
  data: null,
  rewards: null,
}

const drop = {
  list(page = 1) {
    const itemCreate = (item, last = false) => {
      $('#drop_list').append(`
        <div class="d-flex flex-stack pt-2" id="item-${item?.DropID}">
            <div class="d-flex align-items-center">
                <div>
                    <a href="javascript:;" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">${item?.Name != null && item.Name != "" ? item.Name : 'Sem nome'}</a>
                    <div class="fs-7 text-muted">🎫 ID: <span class="text-dark">${item?.DropID}</span></div>
                    <div class="fs-7 text-muted">🎯 nível: <span class="text-dark">${helpers.str_limit_words(item?.Detail ?? 'nenhum', 10)}</span></div>
                </div>
            </div>
            <div class="d-flex align-items-end">
                <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="currentColor"
                            ></path>
                            <path
                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                </button>
                <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" onclick="drop.delete(${item?.DropID})">
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
        ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`)
    }

    loader.init('#drop_body')

    //get list of drop
    $.get(`${baseUrl}/api/admin/drop`, {
      sid: stateDrop.sid,
      page: page,
      search: $('#drop_search').val(),
      type: $('select[name="dropType_filter"]').val(),
      limit: $('select[name="limit"]').val() ?? 10,
      onclick: 'drop.list',
    }, (data) => {
      loader.destroy('#drop_body')
      drop.clear()
      //drop.reset()
      //dropReward.clear()

      if (data.items.length == 0) {
        $('#drop_footer').hide();
        return
      }

      $('#drop_list').show()
      $('#not_results').hide()
      $('#drop_footer').show();

      stateDrop.page = data.paginator.current;

      $("#item_paginator").html(data.paginator.rendered)

      $.each(data.items, function (_, result) {
        itemCreate(result, (_ == data.items.length - 1));

        $("#drop_list").find(`#item-${result.DropID} #edit`).on("click", () => {
          stateDrop.data = result
          $('#drop_info img').hide()
          $('#detail_image_icon').css('background-image', '')
          if (result.Icon) {
            $('#detail_image_icon').css('background-image', `url(${result.Icon})`)
          }
          if (result.Pic) {
            $('#drop_info img').show()
            $('#drop_info img').attr('src', result.Pic)
          }
          $('#drop_data').show()
          $('#not_selected').hide()
          stateDrop.id = result.DropID
          dropReward.list()
          drop.populate()
        });
      });
    })
  },
  create() {
    const data = $("#form_drop_condition_create").serializeObject();
    var button = document.querySelector("#form_drop_condition_submit");
    data.sid = stateDrop.sid;
    changeButtonState(button, true);
    axios.post(
      `${baseUrl}/api/admin/drop`, data
    ).then((response) => {
      var su = response.data;
      if (su.state) {
        drop.list(stateDrop.page);
      }
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
    }).catch((error) => {
      swMessage("error", "erro interno, verifique o console.");
      changeButtonState(button, false);
      console.error(error);
    });
  },
  update() {
    var button = document.querySelector("#btn_drop_condition_update");
    const data = $("#form-drop-condtion-edit-send").serializeObject();
    data.sid = stateDrop.sid;
    data.did = stateDrop.id;

    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/drop`, data).then(res => {
      if (res.data.state) drop.list(stateDrop.page);
      swMessage(res.data.state ? "success" : "warning", res.data.message);
      changeButtonState(button, false);
    });
  },
  delete(id) {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover esse drop ? todos os drops dessa instância serão apagos. Essa alteração não tem como ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/drop`, {
          params: {
            sid: stateDrop.sid,
            dropID: id
          }
        }).then(response => {
          if (response.data.state) {
            $('#drop_data').hide()
            $('#not_selected').show()
            swMessage("success", response.data.message);
            drop.list(stateDrop.page)
            return;
          }

          swMessage("warning", response.data.message);
        }).catch(error => {
          swMessage("error", 'erro verifique o console.');
          console.log(error)
        })
      }
    });
  },
  PVEImport: () => {
    const data = $("#md_drop_import_item form").serializeObject();
    var button = document.querySelector("#btn_drop_import");
    data.sid = stateDrop.sid;
    data.id = stateDrop.id;
    changeButtonState(button, true);
    axios.post(
      `${baseUrl}/api/admin/drop/pve-import`, data).then((res) => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message);
        changeButtonState(button, false);
        if (su.state)
          dropReward.list();
        $('#md_drop_import_item').modal('hide');
      })
  },
  import: () => {
    const data = $("#md_drop_import form").serializeObject();
    data.sid = stateDrop.sid;
    data.id = stateDrop.id;

    var button = document.querySelector("#btn_modal_import");
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/drop/import`, data).then(res => {
      if (res.data.state) {
        dropReward.list();
        $('#md_drop_import').modal('hide');
        $('#md_drop_import form').find('[name="hash"]').val(null);
      }

      swMessage(res.data.state ? "success" : "warning", res.data.message);
      changeButtonState(button, false);
    });
  },
  export: () => {
    var button = document.querySelector("#reward_buttons #menu_utils");
    changeButtonState(button, true);
    axios.get(`${baseUrl}/api/admin/drop/export`, {
      params: {
        sid: stateDrop.sid,
        id: stateDrop.id
      }
    }).then(res => {
      if (res.data.state) {
        $('#md_drop_export').modal({ backdrop: "static ", keyboard: false }).modal('show');
        $('#md_drop_export').find('[name="content"]').val(res.data.content);
      }
      swMessage(res.data.state ? "success" : "warning", res.data.message);
      changeButtonState(button, false);
    });
  },
  populate() {
    var data = stateDrop.data
    var get = (element) => {
      return $(`#drop_info ${element}`)
    }

    get('input[name="name"]').val(data?.Name)
    get('input[name="detail"]').val(data?.Detail)
    get('input[name="dropID"]').val(data.DropID)
    get('select[name="condictionType"]').val(data.CondictionType).trigger('change')
    get('input[name="para1"]').val(data.Para1)
    get('input[name="para2"]').val(data.Para2)
  },
  updateOnGame() {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar os drops ? ao fazer isso os <b>emuladores</b> serão atualizados.",
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
        var button = document.querySelector("#button_update_quest");
        changeButtonState(button, true);
        axios.get(`${baseUrl}/api/admin/drop/update-on-game`, {
          params: {
            sid: stateDrop.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          changeButtonState(button, false);
        })
      }
    });
  },
  clear() {
    $('#drop_list').empty()
    $('#drop_list').hide()
    $('#not_results').show()
  },
  reset() {
    $('#drop_data').hide()
    $('#not_selected').show()
  },
}

const dropReward = {
  list() {
    //append item to drop list
    const itemCreate = (info, last = false) => {
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

      var isTips = ''
      if (info.IsTips != '0') {
        isTips = '<div class="badge me-n4">⛏️</div>'
      }

      var isLogs = ''
      if (info.IsLogs != '0') {
        isLogs = '<div class="badge me-n4">📃</div>'
      }

      var itemCount = `x${info.BeginData}-${info.EndData}`
      if (info.BeginData == info.EndData) {
        itemCount = `x${info.BeginData}`
      }

      $('#rewards_list').append(`<div class="d-flex flex-stack pt-2">
              <div class="d-flex align-items-center">
                  <div class="w-40px h-40px me-3 rounded bg-light">
                      <img src="${info.Pic}"
                      onerror="this.src='${baseUrl}/assets/media/icons/original.png';"
                      class="w-100">
                  </div>
                  <div class="me-3">
                      <div class="d-flex align-items-center">
                          <div class="text-gray-800 fw-bolder">${info?.Name ?? '❓ Desconhecido'}</div>
                          <div class="badge badge-light-primary ms-5 me-2">${itemCount}</div>
                          ${isBindState}
                          <div class="badge badge-light-primary">🎲 ${info.Random}</div>
                          ${needSex}
                          ${isTips}
                          ${isLogs}
                      </div>
                      <div class="text-muted">🌍 ID: ${info.ItemId}</div>
                  </div>
              </div>
              <div class="d-flex justify-content-end align-items-center">
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" data-bs-toggle="modal" data-bs-target="#md_drop_edit_item" onclick="dropReward.populate(${info.Id})">
              <span class="svg-icon svg-icon-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
              <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
            </svg>
            </span>
            </button>
            <button type="button"
            class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger"
            onclick="dropReward.confirmDelete(${info.Id})">
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
            </div></div>
            ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`)
    }

    loader.init('#drop_reward')

    //get list of drop
    $.get(`${baseUrl}/api/admin/drop/reward`, {
      sid: stateDrop.sid,
      did: stateDrop.id,
    }, (data) => {
      loader.destroy('#drop_reward')
      dropReward.clear()

      if (data.items.length == 0) {
        return
      }

      $('#rewards_list').show()
      $('#no_rewards').hide()

      stateDrop.rewards = data.items;

      $.each(data.items, function (_, result) {
        itemCreate(result, (_ == data.items.length - 1))
      });
    })
  },
  create() {
    var button = document.querySelector("#btn_drop_create");
    const data = $("#form-drop-reward-send").serializeObject();
    data.sid = stateDrop.sid;
    data.did = stateDrop.id;

    if (data.amount > $("#md-annex-in-max").attr('max')) {
      data.amount = $("#md-annex-in-max").attr('max')
    }

    changeButtonState(button, true);
    $.post(`${baseUrl}/api/admin/drop/reward`, data, function (response) {
      if (response.state) {
        dropReward.list()
        swMessage("success", response.message);
        changeButtonState(button, false);
        return;
      }
      swMessage("warning", response.message);
      changeButtonState(button, false);
    });
  },
  update() {
    var button = document.querySelector("#btn_drop_edit");
    const data = $("#form-drop-reward-edit-send").serializeObject();
    data.sid = stateDrop.sid;

    var maxCount = $('#form-drop-reward-edit-send input[name="endData"]').attr('max')
    if (data.beginData > maxCount) {
      data.beginData = maxCount
    }
    if (data.endData > maxCount) {
      data.endData = maxCount
    }

    changeButtonState(button, true);
    axios.put(`${baseUrl}/api/admin/drop/reward`, data).then(res => {
      if (res.data.state) dropReward.list();
      swMessage(res.data.state ? "success" : "warning", res.data.message);
      changeButtonState(button, false);
    });
  },
  delete: (id) => {
    axios.delete(`${baseUrl}/api/admin/drop/reward`, {
      params: {
        sid: stateDrop.sid,
        did: stateDrop.id,
        id: id,
      }
    }).then(res => {
      if (res.data.state) dropReward.list();
      swMessage(res.data.state ? "success" : "warning", res.data.message);
    })
  },
  populate: (id) => {
    var reward = stateDrop.rewards.find((e) => e.Id == id)

    var get = function (element) {
      return $(`#form-drop-reward-edit-send ${element}`)
    };

    $("#md-edit-reward-id").html(reward.ItemId)
    $("#md-edit-reward-name").html(reward.Name)
    $("#md-edit-reward-pic").attr("src", reward.Pic)

    get('input[name="id"]').val(reward.Id)
    get('input[name="beginData"]').val(reward.BeginData)
    get('input[name="endData"]').val(reward.EndData)
    get('input[name="endData"], input[name="beginData"]').attr("max", reward.MaxCount)
    get('input[name="random"]').val(reward.Random)
    get('select[name="valid"]').val(reward.ValueDate).trigger("change")
    get('input[name="isLogs"]').prop("checked", reward.IsLogs == "1" ? true : false)
    get('input[name="isBind"]').prop("checked", reward.IsBind == "1" ? true : false)
    get('input[name="isTips"]').prop("checked", reward.IsTips == "1" ? true : false)

  },
  confirmDelete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover esse's item's ? essa alteração não tem como ser desfeita.",
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
        dropReward.delete(id);
      }
    });
  },
  clear() {
    $('#rewards_list').empty()
    $('#rewards_list').hide()
    $('#no_rewards').show()
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
  stateDrop.sid = $('#sid').val();
  drop.list();
}

const helpers = {
  fixDate(date) {
    if (date !== "") {
      var dateVal = new Date(date);
      var day = dateVal.getDate().toString().padStart(2, "0");
      var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
      var hour = dateVal.getHours().toString().padStart(2, "0");
      var minute = dateVal.getMinutes().toString().padStart(2, "0");
      var inputDate = dateVal.getFullYear() + "-" + month + "-" + day + "T" + hour + ":" + minute;

      return inputDate;
    }
    return date;
  },
  str_limit_words(string, limit, pointer = "...") {
    return string.length > limit ? string.substring(0, limit) + pointer : string;
  },
};

init();

//refactor after
$('#sid').on('change', function () {
  stateDrop.sid = $('#sid').val();
  drop.list()
})

$('#drop_search, select[name="dropType_filter"], select[name="limit"]').on('change', function () {
  drop.list()
})

$('#drop_data [href="#drop_info"]').on("click", () => {
  $('#reward_buttons').hide()
  $('#detail_image_icon').show()
})

$('#drop_data [href="#drop_reward"]').on('click', () => {
  $('#reward_buttons').show()
  $('#detail_image_icon').hide()
})

$('#copy_import_clipboard').on('click', (e) => {
  e.preventDefault();
  helper.copyToClipboard('#md_drop_export form textarea[name="content"]', 'Chave de importação movida para area de transferência.')
})


function restoreDefaultModal() {
  //hidden item detail
  $('#md-annex-pic').hide()
  $('#md-annex-name').hide()
  $('#md-annex-id').hide()


  $('#md-item-info').hide()
  $('#md-annex-attribute-area').show();
}

$('#md_drop_new_item #itemID').on('change', function () {
  restoreDefaultModal()
  var itemID = $(this).val()
  $.get(`${baseUrl}/api/admin/item`, {
    sid: stateDrop.sid,
    search: {
      term: itemID
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


    $('#md-item-info').show()
    $('#md-annex-pic').show()
    $('#md-annex-name').show()
    $('#md-annex-id').show()
  })
})

var optionFormat = function (item) {
  if (!item.id) {
    return item.text;
  }

  var span = document.createElement('span')
  var template = ''


  template += '<img src="' + item.pic + '" class="h-30px me-2" alt="image"/>';
  template += item.text;

  span.innerHTML = template;

  return $(span);
}

$('#md_drop_new_item #itemID').select2({
  minimumInputLength: 2,
  templateResult: optionFormat,
  ajax: {
    url: `${baseUrl}/api/admin/item`,
    dataType: 'json',
    type: "GET",
    data: function (search) {
      return {
        sid: stateDrop.sid,
        search
      };
    },
    processResults: function (data) {
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
