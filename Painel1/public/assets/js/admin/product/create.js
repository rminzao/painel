function makeid(length) {
    var result = "";
    var characters =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(
        Math.floor(Math.random() * charactersLength)
      );
    }
    return result;
  }

  $("table").on("click", "#DeleteButton", function () {
    $(this).closest("tr").remove();
  });

  $("#createNewRow").on("click", function (e) {
    e.preventDefault();
    $('.fv-plugins-message-container').empty();
    var id = $("#reward_id").val(),
      attack = $("#reward_attack").val(),
      defence = $("#reward_defence").val(),
      agility = $("#reward_agility").val(),
      luck = $("#reward_luck").val(),
      ammount = $("#reward_ammount").val(),
      level = $("#reward_level").val(),
      valid = $("#reward_valid").val(),
      hash = makeid(6);
    if (id == '') {
      $('.fv-plugins-message-container').text('TemplateID é obrigatório');
      return;
    }

    $("table tbody")[0].innerHTML += `
        <tr id="${hash}">
          <td>${id}<input type="hidden" name="_item[templateid][]" value="${id}"></td>
          <td>${attack}<input type="hidden" name="_item[attack][]" value="${attack}"></td>
          <td>${defence}<input type="hidden" name="_item[defence][]" value="${defence}"></td>
          <td>${agility}<input type="hidden" name="_item[agility][]" value="${agility}"></td>
          <td>${luck}<input type="hidden" name="_item[luck][]" value="${luck}"></td>
          <td>${ammount}<input type="hidden" name="_item[ammount][]" value="${ammount}"></td>
          <td>${level}<input type="hidden" name="_item[level][]" value="${level}"></td>
          <td>${valid}<input type="hidden" name="_item[valid][]" value="${valid}"></td>
          <td>
          <a href="javascript:;" id="DeleteButton" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                  <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                  <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                </svg>
              </span>
            </a>
          </td>
        </tr>
      `;

    $('#kt_modal_new_target').modal('hide');
  });

  $("#kt_serverlist_select").change(function () {
    if ($(this).val() == "true") {
      $("#rewards-card").show();
      return;
    }
    $("#rewards-card").hide();
  });

  //Login Script
  $("form").submit(function (e) {
    e.preventDefault();

    var button = document.querySelector("#button_create_recharge");
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
          form[0].reset();
          $('#sid').val(null).trigger('change');
          return;
        }
        swMessage("warning", su.message);
        changeButtonState(button, false);
      },
    });
  });
