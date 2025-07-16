// Função para exibir mensagens de alerta
function swMessage(type, message) {
   // Verifica o tipo de alerta e mostra a mensagem correspondente
   switch (type) {
      case 'warning':
         alert('Aviso: ' + message);  // Exemplo simples com alert()
         break;
      case 'success':
         alert('Sucesso: ' + message);  // Exemplo para mensagens de sucesso
         break;
      case 'error':
         alert('Erro: ' + message);  // Exemplo para mensagens de erro
         break;
      default:
         alert(message);  // Mensagem genérica
   }
}

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

      swMessage('warning', su.message);  // Exibindo mensagem de aviso
      changeButtonState(button, false);

      if ($("div.g-recaptcha").length > 0) {
         grecaptcha.reset();
      }
   });
});
