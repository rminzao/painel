"use strict";
var KTAccountSettingsSigninMethods = {
  init: function () {
    var t, e;
    ! function () {
      var t = document.getElementById("kt_signin_email"),
        e = document.getElementById("kt_signin_email_edit"),
        n = document.getElementById("kt_signin_password"),
        o = document.getElementById("kt_signin_password_edit"),
        i = document.getElementById("kt_signin_email_button"),
        s = document.getElementById("kt_signin_cancel"),
        r = document.getElementById("kt_signin_password_button"),
        a = document.getElementById("kt_password_cancel");
      i.querySelector("button").addEventListener("click", (function () {
        l()
      })), s.addEventListener("click", (function () {
        l()
      })), r.querySelector("button").addEventListener("click", (function () {
        d()
      })), a.addEventListener("click", (function () {
        d()
      }));
      var l = function () {
        t.classList.toggle("d-none"), i.classList.toggle("d-none"), e.classList.toggle("d-none")
      },
        d = function () {
          n.classList.toggle("d-none"), r.classList.toggle("d-none"), o.classList.toggle("d-none")
        }
    }(), e = document.getElementById("kt_signin_change_email"), t = FormValidation.formValidation(e, {
      fields: {
        emailaddress: {
          validators: {
            notEmpty: {
              message: "Email é obrigatório"
            },
            emailAddress: {
              message: "Não é um email válido"
            }
          }
        },
        confirmemailpassword: {
          validators: {
            notEmpty: {
              message: "Senha é obrigatória"
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger,
        bootstrap: new FormValidation.plugins.Bootstrap5({
          rowSelector: ".fv-row"
        })
      }
    }), e.querySelector("#kt_signin_submit").addEventListener("click", (function (n) {
      n.preventDefault(), console.log("click"), t.validate().then((function (n) {
        if (n == 'Valid') {
          sendMailChange()
        } else {
          swal.fire({
            text: "Desculpe, parece que alguns erros foram detectados nos dados informados, verifique e tente novamente.",
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn font-weight-bold btn-light-primary"
            }
          })
        }
      }))
    })),
      function (t) {
        var e, n = document.getElementById("kt_signin_change_password");
        e = FormValidation.formValidation(n, {
          fields: {
            currentpassword: {
              validators: {
                notEmpty: {
                  message: "Senha atual é obrigatória"
                }
              }
            },
            newpassword: {
              validators: {
                notEmpty: {
                  message: "Nova senha é obrigatória"
                }
              }
            },
            confirmpassword: {
              validators: {
                notEmpty: {
                  message: "Confirmar nova senha é obrigatória"
                },
                identical: {
                  compare: function () {
                    return n.querySelector('[name="newpassword"]').value
                  },
                  message: "A senha e sua confirmação não são iguais"
                }
              }
            }
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger,
            bootstrap: new FormValidation.plugins.Bootstrap5({
              rowSelector: ".fv-row"
            })
          }
        }), n.querySelector("#kt_password_submit").addEventListener("click", (function (t) {
          t.preventDefault();
          e.validate().then((function (t) {
            if (t == 'Valid') {
              sendPassChange()
            } else {
              swal.fire({
                text: "Desculpe, parece que alguns erros foram detectados nos dados informados, verifique e tente novamente.",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn font-weight-bold btn-light-primary"
                }
              })
            }
          }))

        }))
      }()
  }
};
KTUtil.onDOMContentLoaded((function () {
  KTAccountSettingsSigninMethods.init()
}));

function sendMailChange() {
  var form = $("#kt_signin_change_email");
  var action = form.attr("action");
  var data = form.serialize();
  var button = document.querySelector("#kt_signin_submit");

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
          `Opss, parece que você não possui permissão para alterar esses dads.`
        );
      },
    },
    success: function (su) {
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
    },
  });

  // e.reset(), t.resetForm()
}

function sendPassChange() {
  var form = $("#kt_signin_change_password");
  var action = form.attr("action");
  var data = form.serialize();
  var button = document.querySelector("#kt_password_submit");

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
          `Opss, parece que você não possui permissão para alterar esses dads.`
        );
      },
    },
    success: function (su) {
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
    },
  });

  //n.reset(), e.resetForm()
}
