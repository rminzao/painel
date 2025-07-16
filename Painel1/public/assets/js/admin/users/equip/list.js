function getServerList(page = 1) {
  var target = document.querySelector("#kt_block_ui_card_serverlist");

  var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Buscando informações...</div>',
  });

  blockUI.destroy();
  blockUI.block();

  $.getJSON(`${baseUrl}/api/admin/users/equip/list`, {
    page: page
  }, function (response, textStatus, jqXHR) {
    var ranking = $("#server_list");
    ranking.empty();


    $.each(response.users, function (_, value) {
      var userRole = () => {
        if (value.role == 0)
          return '🚧 Tester';

        if (value.role == 1)
          return '💣 Jogador';

        if (value.role == 2)
          return '👑 Administrador';

        if (value.role == 3)
          return '👨‍💻 Desenvolvedor';

        return '👀 Desconhecido';
      }

      var border = ''
      if (value.border != 'none') {
        border = `<div style=" background-image: url(${baseUrl}/assets/media/borders/${value.border}); background-size: cover; width: 120%; height: 120%; position: absolute; margin-top: -10%; margin-left: -10.2%; "> </div>`
      }
      ranking.append(`<tr id="user-${value.id}">
          <td>
            <div class="d-flex align-items-center">
              <div class="symbol symbol-40px overflow-hidden me-3">
                ${border}
                <img class="h-40px w-40px rounded" src="${value.photo}" alt="">
              </div>
              <div class="d-flex flex-column">
                  <a href="${baseUrl}/admin/users/${value.id}" target="_blank"
                      class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                      ${value.first_name} ${value.last_name}
                  </a>
                  <span class="text-muted fs-7">${userRole()}</span>
              </div>
            </div>
          </td>
          <td>
            <div class="d-flex justify-content-end flex-shrink-0">
              <a id="edit" href="javascript:;" data-bs-toggle="modal" data-bs-target="#kt_modal_role_change" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary me-1">
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="currentColor"/>
                <path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="currentColor"/>
                </svg>
                </span>
              </a>
            </div>
            </td>
      </tr>`);

      $(`#user-${value.id}`).find("#edit").on('click', () => {
        $("#kt_modal_role_change #user-role")
          .val(value.role)
          .trigger("change");
        $("#kt_modal_role_change #user-id").val(value.id);
      });
    });

    $('#serverlist_paginator').html(response.paginator.rendered);
    if (response.paginator.rendered == null){
      $('#serverlist_paginator').hide();
    }else{
      $('#serverlist_paginator').show();
    }

    if (blockUI.isBlocked()) {
      blockUI.release();
    }
  })
    .fail(function (jqxhr, settings, ex) {
      // swMessage('warning', 'Ops, ocorreu um erro interno, tente novamente mais tarde.');

    });
}
$(function () {
  getServerList();

  $("form").submit(function (e) {
    e.preventDefault();

    var button = document.querySelector("#button_update_server");
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
         se o problema continuar entre em contacto com o administrador.`
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
          getServerList();
          return;
        }
        swMessage("warning", su.message);
        changeButtonState(button, false);
      },
    });
  });

  $.getJSON(
    `${baseUrl}/api/admin/users/all`,
    function (response, textStatus, jqXHR) {
      response.forEach((element) => {
        $("#kt_modal_role_add_role")
          .find("#user-id")
          .append(
            `<option value="${element.UserID}">[${element.UserID}] ${element.Name}</option>`
          );
      });
    }
  ).fail(function (jqxhr, settings, ex) { });
});

