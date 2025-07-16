function changeButtonState(button, state) {
   if (state) {
      button.disabled = true;  // Desabilitar o botão
      button.innerHTML = "Carregando...";  // Alterar o texto do botão (opcional)
   } else {
      button.disabled = false;  // Habilitar o botão
      button.innerHTML = "Entrar";  // Restaurar o texto do botão (opcional)
   }
}

$("form").submit(function (e) {
   e.preventDefault();

   const data = $(this).serialize();  // Usar .serialize() no lugar de .serializeObject()
   var button = document.querySelector("#auth_form_submit");

   changeButtonState(button, true);

   axios.post($(this).attr("action"), data).then((res) => {
      var su = res.data;
      if (su.state) {
         localStorage.setItem('user_token', su.token)
         document.location.reload(true);
         return;
      }

      swMessage('warning', su.message);
      changeButtonState(button, false);

      if ($("div.g-recaptcha").length > 0) {
         grecaptcha.reset();
      }
   });
});
