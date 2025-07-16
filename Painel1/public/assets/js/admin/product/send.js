$('#sid').on('change', function () {

  if (this.value == 0) {
    return;
  }

  var target = document.querySelector("#kt_block_ui_4_target");
  var blockUI = new KTBlockUI(target);
  blockUI.destroy();
  blockUI.block();

  $.post(`${baseUrl}/api/admin/server/${this.value}/users`, function (data) {
    if (data.state) {
      $('#uid').empty().trigger('change');
      $.each(data.users, function (index, value) {
        $('#uid').append(
          `<option value="${value.UserID}">(${value.UserID}) ${value.NickName}</option>`
        ).trigger('change');
      });
      blockUI.release();
    }
  });
});
$('#sid').trigger('change');
//Login Script
$("form").submit(function (e) {
  e.preventDefault();

  var button = document.querySelector("#button_send_recharge");
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
          `Opss, parece que você não possui permissão para enviar recargas ao servidor.`
        );
      },
    },
    success: function (su) {
      if (su.state) {
        swMessage("success", su.message);
        changeButtonState(button, false);
        //form[0].reset();
        //$('#sid').val(null).trigger('change');
        //$('#uid').val(null).trigger('change');
        //$('#uid').empty();
        return;
      }
      swMessage("warning", su.message);
      changeButtonState(button, false);
    },
  });
});
