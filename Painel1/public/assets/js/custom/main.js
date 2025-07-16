function swMessage(
  type,
  message,
  options = {},
) {
  settings = {
    toast: true,
    showCloseButton: true,
    icon: type,
    animation: true,
    title: 'Sistema',
    titleClass: "text-center mb-n1",
    html: message,
    position: 'top-right',
    showConfirmButton: false,
    timer: 6000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  };

  if (options.length > 1) {
    if (typeof options.title !== 'undefined') {
      settings.title = options.title;
    }

    if (typeof options.timer !== 'undefined') {
      settings.timer = options.timer;
      settings.timerProgressBar = true;
      if (!options.timer) {
        delete settings.timer;
        delete settings.timerProgressBar;
      }
    }
  }

  Swal.fire(settings);
  return;
}

function changeButtonState(button, state) {
  if (state) {
    button.setAttribute("data-kt-indicator", "on");
    button.setAttribute("disabled", "disabled");
    return;
  }

  button.removeAttribute("data-kt-indicator");
  button.removeAttribute("disabled");
  return;
}

function in_array(needle, haystack) {
  var array_compare = (a1, a2) => {
    if (a1.length != a2.length) return false;
    var length = a2.length;
    for (var i = 0; i < length; i++) {
      if (a1[i] !== a2[i]) return false;
    }
    return true;
  }

  var length = haystack.length;
  for (var i = 0; i < length; i++) {
    if (typeof haystack[i] == 'object') {
      if (array_compare(haystack[i], needle)) return true;
    } else {
      if (haystack[i] == needle) return true;
    }
  }
  return false;
}

function is_flashplayer() {
  var hasFlash = false;
  try {
      var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
      if (fo)
          hasFlash = true;
  } catch (e) {
      if (navigator.mimeTypes &&
          navigator.mimeTypes['application/x-shockwave-flash'] != undefined &&
          navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin)
          hasFlash = true;
  }
  return hasFlash;
}

const helper = {
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
  dateFormatBr(date) {
    var date = new Date(date)
    const dateString = date.toLocaleString('pt-BR', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      hour12: false,
      timeZone: 'UTC'
    })
    return dateString
  },
  str_limit_words(string, limit, pointer = "...") {
    return string.length > limit ? string.substring(0, limit) + pointer : string;
  },
  loader(element, init = true) {
    if (init) {
      var target = document.querySelector(element);
      var blockUI = new KTBlockUI(target);

      blockUI.destroy();
      blockUI.block();
      return;
    }

    var target = document.querySelector('.blockui-overlay');
    target.remove()
  },
  copyToClipboard(element, text = 'Movido para area de transferência') {
    var copyText = document.querySelector(element);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    try {
      document.execCommand("copy");
      swMessage('success', text);
    } catch (err) {
      console.log('Error while copying to clipboard: ' + err)
      swMessage('error', 'Parece que seu navegador não suporta essa ação, copie manualmente.');
    }
  }
};

const main = {
  axios: () => {
    axios.defaults.headers.common['Authorization'] = AUTH_TOKEN ?? '';
    axios.interceptors.response.use(
      response => {
        return response;
      },
      error => {
        if (error.response.status === 403) {
          swMessage('warning', 'Você não tem permissão para acessar este recurso.');
          return;
        }

        if (error.response.status == 404) {
          swMessage('warning', 'Não foi possível acessar a url solicitada.');
          return;
        }

        if (error.response.status == 405) {
          swMessage('error', 'Método não permitido, verifique as configurações do servidor de hospedagem.');
          return;
        }

        if (error.response.status >= 400 && error.response.status < 500) {
          swMessage('error', error.response.data.message);
          return;
        }

        if (error.response.status >= 500) {
          swMessage('error', 'Ocorreu um erro no servidor.');
          return;
        }
      }
    );
  },
  functions: () => {
    $.fn.serializeObject = function () {
      var o = {};
      var a = this.serializeArray();
      $.each(a, function () {
        if (o[this.name]) {
          if (!o[this.name].push) {
            o[this.name] = [o[this.name]];
          }
          o[this.name].push(this.value || "");
        } else {
          o[this.name] = this.value || "";
        }
      });
      return o;
    };

    $.fn.invisible = function () {
      return this.each(function () {
        $(this).css("visibility", "hidden");
      });
    };

    $.fn.visible = function () {
      return this.each(function () {
        $(this).css("visibility", "visible");
      });
    };
  },
  theme: () => {
    $('#change-theme').prop('checked', KTCookie.get(`user_theme`) == 'dark' ? true : false);

    $('#change-theme').click(function (e) {
      var isDark = $(this).is(":checked");
      var date = new Date(Date.now() + 14 * 24 * 60 * 60 * 1000); // +2 day from now
      var options = {
        expires: date
      };

      KTCookie.set(`user_theme`, isDark ? 'dark' : 'light', options);

      $("#plugins_css").attr("href", `${baseUrl}/assets/plugins/global/plugins.${isDark ? 'dark.' : ''}bundle.css`);
      $("#style_css").attr("href", `${baseUrl}/assets/css/style.${isDark ? 'dark.' : ''}bundle.css`);
    });
  },
  init: () => {
    //console.clear();
    console.log("rmdev");
    main.theme();
    main.functions();
    main.axios();
  }
}

$(function () {
  main.init();
});
