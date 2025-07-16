$('form').submit(function (e) {
  e.preventDefault();

  var button = document.querySelector("#button_send_ticket");
  var form = $(this);
  var data = new FormData();

  jQuery.each(jQuery('#attachments')[0].files, function (i, file) {
    data.append('file[]', file);
  });

  jQuery.each(form.serializeArray(), function (i, file) {
    data.append(file.name, file.value);
  });

  button.setAttribute("data-kt-indicator", "on");
  button.setAttribute("disabled", "disabled");

  $.ajax({
    url: form.attr("action"),
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: data,
    type: 'post',
    error: function (load) {
      button.removeAttribute("data-kt-indicator");
      button.removeAttribute("disabled");
      swMessage('error', `Ocorreu um erro interno tente novamente,
        se o problema continuar entre em contato com o administrador.`);
    },
    success: function (su) {
      if (su.state) {
        if (su.state && su.url) {
          swMessage('success', su.message);
          button.removeAttribute("data-kt-indicator");
          button.removeAttribute("disabled");
        }

        if (su.url) {
          window.setTimeout(function () {
            window.location = su.url;
          }, 5000);
        }
        return;
      }
      swMessage('warning', su.message);
      button.removeAttribute("data-kt-indicator");
      button.removeAttribute("disabled");
    }
  });
});
