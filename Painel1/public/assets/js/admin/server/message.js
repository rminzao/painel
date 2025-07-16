function changeButtonState(button, state){
  if(state){
    button.setAttribute("data-kt-indicator", "on");
    button.setAttribute("disabled", "disabled");
    return;
  }

  button.removeAttribute("data-kt-indicator");
  button.removeAttribute("disabled");
  return;
}

//Login Script
$("form").submit(function(e) {
   e.preventDefault();

   var button = document.querySelector("#button_send_ticket");
   var form = $(this);
   var action = form.attr("action");
   var data = form.serialize();
   changeButtonState(button, true);
   $.ajax({
      url: action,
      data: data,
      type: "post",
      dataType: "json",
      error: function(load) {
        changeButtonState(button, false);
         swMessage('error', `Ocorreu um erro interno tente novamente,
         se o problema continuar entre em contato com o administrador.`);
      },
      statusCode: {
        403: function (response) {
          changeButtonState(button, false);
          swMessage('error', `Opss, parece que você não possui permissão para enviar mensagem ao servidor.`);
        },
     },
      success: function(su) {
         if (su.state) {
           swMessage('success', su.message);
           changeButtonState(button, false);
           form[0].reset();
           $('#sid').val(null).trigger('change');
          return;
         }
         swMessage('warning', su.message);
         changeButtonState(button, false);
      }
   });
});
