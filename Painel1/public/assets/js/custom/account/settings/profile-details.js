"use strict";
var KTAccountSettingsProfileDetails = function () {
  var e, t;
  return {
    init: function () {
      e = document.getElementById("kt_account_profile_details_form"), e.querySelector("#kt_account_profile_details_submit"), t = FormValidation.formValidation(e, {
        fields: {
          fname: {
            validators: {
              notEmpty: {
                message: "Primeiro nome é obrigatório"
              }
            }
          },
          lname: {
            validators: {
              notEmpty: {
                message: "Último nome é obrigatório"
              }
            }
          },
          company: {
            validators: {
              notEmpty: {
                message: "Company name is required"
              }
            }
          },
          phone: {
            validators: {
              notEmpty: {
                message: "Contact phone number is required"
              }
            }
          },
          country: {
            validators: {
              notEmpty: {
                message: "Please select a country"
              }
            }
          },
          timezone: {
            validators: {
              notEmpty: {
                message: "Please select a timezone"
              }
            }
          },
          "communication[]": {
            validators: {
              notEmpty: {
                message: "Please select at least one communication method"
              }
            }
          },
          language: {
            validators: {
              notEmpty: {
                message: "Please select a language"
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger,
          submitButton: new FormValidation.plugins.SubmitButton,
          bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: ".fv-row",
            eleInvalidClass: "",
            eleValidClass: ""
          })
        }
      }), e.querySelector("#kt_account_profile_details_submit").addEventListener("click", (function (n) {
        n.preventDefault(), console.log("click"), t.validate().then((function (n) {
          if (n == 'Valid') {
            sendProfileChange()
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
        $(e.querySelector('[name="country"]')).on("change", (function () {
          t.revalidateField("country")
        })), $(e.querySelector('[name="language"]')).on("change", (function () {
          t.revalidateField("language")
        })), $(e.querySelector('[name="timezone"]')).on("change", (function () {
          t.revalidateField("timezone")
        }))
    }
  }
}();
KTUtil.onDOMContentLoaded((function () {
  KTAccountSettingsProfileDetails.init()
}));

function sendProfileChange() {
  var form = $("#kt_account_profile_details_form");
  var action = form.attr("action");
  // var data = form.serialize();

  var data = new FormData();

  jQuery.each(jQuery('#avatar')[0].files, function (i, file) {
    data.append('avatar[]', file);
  });

  jQuery.each(form.serializeArray(), function (i, file) {
    data.append(file.name, file.value);
  });

  var button = document.querySelector("#kt_account_profile_details_submit");

  changeButtonState(button, true);

  $.ajax({
    url: action,
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: data,
    type: 'post',
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
      if (su.state) {
        if (su.photo) {
          $("#navbar_profile_image, #header_profile_image1, #header_profile_image2").attr("src", su.photo);
        }
      }
    },
  });

  // e.reset(), t.resetForm()
}
