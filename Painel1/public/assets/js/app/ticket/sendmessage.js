$("form").submit(function (e) {
  e.preventDefault();

  var button = document.querySelector("#button_send_response");
  var form = $(this);
  var data = JSON.stringify(form.serializeObject());

  button.setAttribute("data-kt-indicator", "on");
  button.setAttribute("disabled", "disabled");

  $.ajax({
    url: form.attr("action"),
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    data: data,
    type: "post",
    error: function (load) {
      button.removeAttribute("data-kt-indicator");
      button.removeAttribute("disabled");
      swMessage(
        "error",
        `Ocorreu um erro interno tente novamente,
        se o problema continuar entre em contato com o administrador.`
      );
    },
    success: function (su) {
      if (su.state) {
        if (su.state && su.url) {
          swMessage("success", su.message);
          button.removeAttribute("data-kt-indicator");
          button.removeAttribute("disabled");
        }

        if (su.url) {
          window.setTimeout(function () {
            window.location = su.url;
          }, 1000);
        }
        return;
      }
      swMessage("warning", su.message);
      button.removeAttribute("data-kt-indicator");
      button.removeAttribute("disabled");
    },
  });
});

$(function () {
  $('[id="response"]').map((s, data) => {
    $(data).on("click", () => {
      Swal.fire({
        icon: "error",
        html: "Deletar Comentário?",
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: "Sim, delete isso!",
        cancelButtonText: "Não, cancele!",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-light",
        },
      }).then((result) => {
        // if (result.isConfirmed) {
        //   $.delete($(data).find("#delete").attr("url"), () => {
        //     $(data).hide();
        //   });
        // }
      });
    });
  });
});

$('#ticket-state').on('change', function () {
  $.post(`${baseUrl}/api/ticket/state`, {
    tid: ticket_id,
    status: $('#ticket-state').val()
  }, function (response) {
    if (response.state) {
      swMessage("success", response.message)
      return
    }
    swMessage("warning", response.message)
  })

})
