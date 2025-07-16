
$("#sid").change(function () {
  $("#annex-list").empty();
  $("#category").val(0).trigger("change");

  loadListUsers();
  getItemList();
  loadCategoryList();
});

$("#category").change(function () {
  getItemList();
});


$("#search").on("change", () => {
  getItemList()
});

$("#clear-annex-button").click("on", function () {
  $('#annex-list').empty()
  if ($('#annex-list').children().length == 0) {
    $('#not_annex').show();
  }
});

$("#annex-list").on("click", "#destroy", function () {
  $(this).closest(".d-flex.flex-stack").remove();
  if ($('#annex-list').children().length == 0) {
    $('#not_annex').show();
  }
});

function appendAttachments() {
  $('#not_annex').hide();
  var name = $("#md-annex-in-name").val(),
    pic = $("#md-annex-in-pic").val(),
    id = $("#md-annex-in-id").val(),
    cat = $("#md-annex-in-cat").val(),
    amount = $("#md-annex-in-max").val(),
    level = $("#md-annex-in-level").val(),
    maxAmount = $("#md-annex-in-max").attr("max"),
    CanStrengthen = $("#md-annex-in-strengthen").val(),
    validate = $("#md-annex-in-validate").val(),
    isbinds = 0,
    CanCompose = $("#md-annex-in-compose").val();

  if ($('#md-annex-in-isbinds').is(':checked')) {
    isbinds = 1;
  }

  var attribute = {
    attack: $("#md-annex-in-attack").val(),
    defence: $("#md-annex-in-defence").val(),
    agility: $("#md-annex-in-agility").val(),
    luck: $("#md-annex-in-luck").val(),
  };

  var content = null,
    currentLevel = 0;

  //check if have compose
  if (CanCompose != "0") {
    content = `<div class='d-flex flex-stack mb-3'> <div class='fw-bold pe-10 text-gray-600 fs-7'>Ataque:
                  </div> <div class='text-end fw-bolder fs-6 text-gray-800'>${attribute.attack}</div> </div>
                  <div class='d-flex flex-stack mb-3'> <div class='fw-bold pe-10 text-gray-600 fs-7'>Defesa:</div>
                  <div class='text-end fw-bolder fs-6 text-gray-800'>${attribute.defence}</div> </div>
                  <div class='d-flex flex-stack mb-3'> <div class='fw-bold pe-10 text-gray-600 fs-7'>Agilidade:</div>
                  <div class='text-end fw-bolder fs-6 text-gray-800'>${attribute.agility}</div> </div>
                  <div class='d-flex flex-stack mb-3'> <div class='fw-bold pe-10 text-gray-600 fs-7'>Sorte:</div>
                  <div class='text-end fw-bolder fs-6 text-gray-800'>${attribute.luck}</div> </div>`;
  }

  //check if have level
  if (CanStrengthen != "0") {
    currentLevel = (level == null) ? 0 : level;
  }

  //check max count
  if (maxAmount < amount) {
    $(".fv-plugins-message-container.invalid-feedback").text(`A quantidade maxima é ${maxAmount}`);
    return;
  }

  //append item data to annex list
  $("#annex-list").append(`
      <div class="d-flex flex-stack mb-3">
          <div class="d-flex align-items-center">
              <div class="w-40px h-40px me-3 rounded bg-light" data-toggle="popover" data-html="true"
              data-pic="${pic}" data-category="${cat}" data-id="${id}" data-content="${content}"
              data-desc="" data-maxcount="${amount}" data-level="${currentLevel}"
              data-bs-original-title="${name}" >
                  <img src="${pic}" class="w-100" />
              </div>
              <div class="me-3">
                  <div class="d-flex align-items-center">
                      <div class="text-gray-800 fw-bolder">${name}</div>
                      <div class="badge badge-light-primary ms-5">x${amount}</div>
                  </div>
                  <div class="text-muted">${id}</div>
              </div>
          </div>
          <input type="hidden" name="attachments[TemplateID][]" value="${id}"/>
          <input type="hidden" name="attachments[CategoryID][]" value="${cat}"/>
          <input type="hidden" name="attachments[Count][]" value="${amount}"/>
          <input type="hidden" name="attachments[StrengthenLevel][]" value="${currentLevel}"/>
          <input type="hidden" name="attachments[Attack][]" value="${attribute.attack}"/>
          <input type="hidden" name="attachments[Defence][]" value="${attribute.defence}"/>
          <input type="hidden" name="attachments[Agility][]" value="${attribute.agility}"/>
          <input type="hidden" name="attachments[Luck][]" value="${attribute.luck}"/>
          <input type="hidden" name="attachments[IsBinds][]" value="${isbinds}"/>
          <input type="hidden" name="attachments[Valid][]" value="${validate}"/>
          <div class="d-flex justify-content-end align-items-center">
              <button type="button"
                  class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="destroy">
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
          </div>
      </div>`);

  //start popover
  loadPopover();

  //reset values
  $("#md-annex-in-attack").val(0);
  $("#md-annex-in-defence").val(0);
  $("#md-annex-in-agility").val(0);
  $("#md-annex-in-luck").val(0);

  //clear amount error message
  $(".fv-plugins-message-container.invalid-feedback").empty();

  //close modal
  $("#kt_modal_append_mail").modal("toggle");
}

function loadCategoryList() {
  var sid = $("#sid").val();

  $.get(
    `${baseUrl}/api/admin/item/categories`, {
    sid: sid,
  },
    function (data) {
      if (!data.state) {
        swMessage("warning", data.message);
        return;
      }

      $("#category").empty();
      $("#category").append(`<option value="0" selected>Todos</option>`);
      $.each(data.categories, function (key, item) {
        $("#category").append(`<option value="${item.ID}">${item.Name} - ${item.ID}</option>`);
      });
    }
  );
}

function getItemList(page = 1) {
  var target = document.querySelector("#kt_block_ui_item_list");
  var blockUI = new KTBlockUI(target);

  blockUI.destroy();
  blockUI.block();

  var sid = $("#sid").val(),
    category = $("#category").val(),
    search = $("#search").val();

  $.get(
    `${baseUrl}/api/admin/item/list`, {
    page: page,
    sid: sid,
    category: category,
    search: search,
  },
    function (data) {
      if (!data.state) {
        swMessage("warning", data.message);
        return;
      }

      $("#item-list").empty();
      $("#item_paginator").empty();
      if (data.items.length < 1) {
        $("#item-list").html(`<div data-kt-search-element="empty" class="text-center">
        <div class="pt-10 pb-5">
            <span class="svg-icon svg-icon-4x opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="currentColor"></path>
                    <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="currentColor"></path>
                    <rect x="13.6993" y="13.6656" width="4.42828" height="1.73089" rx="0.865447" transform="rotate(45 13.6993 13.6656)" fill="currentColor"></rect>
                    <path
                        d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z"
                        fill="currentColor"
                    ></path>
                </svg>
            </span>
        </div>

        <div class="pb-15 fw-bold">
            <h3 class="text-gray-600 fs-5 mb-2">Nenhum resultado encontrado</h3>
            <div class="text-muted fs-7">Tente novamente com outro nome ou id</div>
        </div>
    </div>
    `);
      }
      $.each(data.items, function (key, item) {
        var dataContent = '';

        if (item.CanCompose != "0" || item.CategoryID >= 1 && item.CategoryID <= 9 || item.CategoryID >= 13 && item.CategoryID <= 17 || (item.CategoryID >= 50 && item.CategoryID <= 52)) {
          dataContent =
            `<div class='d-flex flex-stack mb-3'>
              <div class='fw-bold pe-10 text-gray-600 fs-7'>Ataque:</div>
              <div class='text-end fw-bolder fs-6 text-gray-800'>${item.Attack}</div>
          </div>
          <div class='d-flex flex-stack mb-3'>
              <div class='fw-bold pe-10 text-gray-600 fs-7'>Defesa:</div>
              <div class='text-end fw-bolder fs-6 text-gray-800'>${item.Defence}</div>
          </div>
          <div class='d-flex flex-stack mb-3'>
              <div class='fw-bold pe-10 text-gray-600 fs-7'>Agilidade:</div>
              <div class='text-end fw-bolder fs-6 text-gray-800'>${item.Agility}</div>
          </div>
          <div class='d-flex flex-stack mb-3'>
              <div class='fw-bold pe-10 text-gray-600 fs-7'>Sorte:</div>
              <div class='text-end fw-bolder fs-6 text-gray-800'>${item.Luck}</div>
          </div>`;
        }

        $("#item-list").append(`
                      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 mb-6" data-bs-toggle="modal"
                      data-bs-target="#kt_modal_append_mail"
                          onclick="loadAppendEdit({
                            TemplateID: '${item.TemplateID}',
                            Name: '${item.Name.replaceAll("'", "\\'")}',
                            Pic: '${item.Pic}',
                            Icon: '${item.Icon}',
                            MaxCount: '${item.MaxCount}',
                            CategoryID: '${item.CategoryID}',
                            CanStrengthen: '${item.CanStrengthen}',
                            NeedSex: '${item.NeedSex}',
                            CanCompose: '${item.CanCompose}'
                          })" data-toggle="popover" data-html="true" title="${item.Name}" data-pic="${item.Icon}"
                          data-category="${item.CategoryID}" data-id="${item.TemplateID}" data-content="${dataContent}"
                          data-maxcount="${item.MaxCount}" data-needsex="${item.NeedSex}" data-desc="${item.Description}"
                          data-canequip="${item.CanEquip}">
                              <div class="w-60px h-60px rounded bg-light item-icon-custom">
                                <img
                                  class="w-60px h-60px"
                                  src="${item.Icon}"
                                  onerror="if (this.src != '${baseUrl}/assets/media/icons/original.png') this.src = '${baseUrl}/assets/media/icons/original.png';">
                              </div>
                          </div>`);
        $("#item_paginator").html(data.paginator.rendered);
      });

      if (blockUI.isBlocked()) {
        blockUI.release();
      }

      loadPopover()
    }
  );
}

function loadPopover() {
  $('[data-toggle="popover"]').popover({
    trigger: 'hover',
    html: true,
    title: 'Toolbox',
    content: function () {
      var desc = '',
        level = '';

      if ($(this).data('desc') != "") {
        desc =
          `<div class='flex-stack mb-3'>
            <div class='fw-bold pe-10 text-gray-600 fs-7'>Descrição:</div>
            <div class='text-start fw-bolder fs-6 text-gray-800'>${$(this).data('desc')}</div>
          </div>`;
      }

      if ($(this).data('level') != null) {
        level =
          `<div class='d-flex flex-stack mb-3'>
              <div class='fw-bold pe-10 text-gray-600 fs-7'>Level:</div>
              <div class='text-start fw-bolder fs-6 text-gray-800'>${$(this).data('level')}</div>
          </div>`;
      }
      var sex = '';
      if ($(this).data('canequip') == '1') {
        if ($(this).data('needsex') == '1') {
          sex = `<span class="badge badge-light-primary">Homem</span>`;
        } else if ($(this).data('needsex') == '2') {
          sex = `<span class="badge badge-light-danger">Mulher</span>`;
        }
      }

      return `<div class='d-flex align-items-center mb-5'>
          <div class='w-40px h-40px me-3 rounded bg-light'>
              <img
                class="w-100"
                src="${$(this).data('pic')}" />
          </div>
          <div class='me-3'>
              <div class='text-muted'>
                  Categoria
                  <span class='text-gray-800 fw-bolder'>${$(this).data('category')}</span>
              </div>
              <div class='text-muted'>
                  Id
                  <span class='text-gray-800 fw-bolder'>${$(this).data('id')}</span>
              </div>
          </div>
          ${sex}
      </div>
      ${$(this).data('content')}
      <div class='d-flex flex-stack mb-3'>
          <div class='fw-bold pe-10 text-gray-600 fs-7'>Quantidade:</div>
          <div class='text-end fw-bolder fs-6 text-gray-800'>${$(this).data('maxcount')}</div>
      </div>
      ${desc}
      ${level}`;
    }
  })
}

function loadListUsers() {
  var sid = $("#sid").val();

  var target = document.querySelector("#kt_block_ui_mail_detail");
  var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Buscando usuarios...</div>',
  });

  blockUI.destroy();
  blockUI.block();

  $.post(`${baseUrl}/api/admin/server/${sid}/users`, function (data) {
    if (data.state) {
      $("#uid").empty().trigger("change");
      $.each(data.users, function (index, value) {
        $("#uid").append(
          `<option value="${value.UserID}">(${value.UserID}) ${value.NickName}</option>`
        ).trigger("change");
      });

      if (blockUI.isBlocked()) {
        blockUI.release();
      }
    }
  });
}

function loadAppendEdit(attributes) {
  restoreDefaultModal()

  if (attributes.CanCompose == '0') {
    $('#md-annex-attribute-area').hide();
  }

  if (attributes.CanStrengthen == '0') {
    $('#md-annex-level-area').addClass('d-none');
    $('#md-annex-amount-area').removeClass('col-6');
    $('#md-annex-amount-area').addClass('col-12');
  }

  if ((attributes.CategoryID >= 1 && attributes.CategoryID <= 7)) {
    restoreDefaultModal()
  }

  var needSex = ''
  if (attributes.NeedSex == 1) {
    needSex = '🧢'
  } else if (attributes.NeedSex == 2) {
    needSex = '🎀'
  }

  //show current item info
  $("#md-annex-pic").attr('src', attributes.Icon);
  $("#md-annex-name").text(`${needSex} ${attributes.Name}`);
  $("#md-annex-id").text(`${attributes.TemplateID}`);

  //set attributes to current item
  $("#md-annex-in-id").val(attributes.TemplateID);
  $("#md-annex-in-pic").val(attributes.Icon);
  $("#md-annex-in-name").val(attributes.Name);
  $("#md-annex-in-cat").val(attributes.CategoryID);
  $("#md-annex-in-max").attr('max', attributes.MaxCount);
  $("#md-annex-in-max").val('1');
  $("#md-annex-in-level").val(0).trigger("change");
  $("#md-annex-in-strengthen").val(attributes.CanStrengthen);
  $("#md-annex-in-compose").val(attributes.CanCompose);


  function restoreDefaultModal() {
    $('#md-annex-attribute-area').show();
    $('#md-annex-level-area').removeClass('d-none');
    $('#md-annex-amount-area').removeClass('col-12');
    $('#md-annex-amount-area').addClass('col-6');
  }
}

//send form
$("form").submit(function (e) {
  e.preventDefault();

  var button = document.querySelector("#button_send_item");
  var form = $(this);
  var action = form.attr("action");
  var data = form.serialize();
  changeButtonState(button, true);
  $.ajax({
    url: action,
    data: data,
    type: "post",
    dataType: "json",
    error: function (load) {
      changeButtonState(button, false);
      swMessage(
        "error",
        `Ocorreu um erro interno tente novamente,
         se o problema continuar entre em contato com o administrador.`
      );
    },
    statusCode: {
      403: function (response) {
        changeButtonState(button, false);
        swMessage(
          "error",
          `Opss, parece que você não possui permissão para enviar mensagem ao servidor.`
        );
      },
    },
    success: function (su) {
      if (su.state) {
        swMessage("success", su.message);
        changeButtonState(button, false);
        //form[0].reset();
        //$('#sid').val(null).trigger('change');
        return;
      }
      swMessage("warning", su.message);
      changeButtonState(button, false);
    },
  });
});

//load page data
$(function () {
  getItemList();
  loadCategoryList();
  loadListUsers();
});

$('input[name="isOnline"]').on('change', function () {
  $('#uid').prop('disabled', $(this).is(':checked') ? true : false);
})

function setMaxCount() {
  const input = $('#md-annex-in-max');
  input.val(input.attr('max'));
}
