const parameters = {
  product: {
    params: {
      sid: 0,
      page: 1,
      limit: 5,
      search: '',
      onclick: 'product.list'
    }
  }
}

const product = {
  list: (page = 1) => {
    parameters.product.params.page = page
    helper.loader('#product_body')
    axios.get(`${baseUrl}/api/admin/product`, parameters.product).then(res => {
      product.populate(res.data)
    })
  },
  create: () => {
    const data = $("#md_product_new form").serializeObject();
    var button = document.querySelector('#button_product_create');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/product`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        product.list(parameters.product.params.page);
    })
  },
  update: () => {
    const data = $("#product_data form").serializeObject();
    var button = document.querySelector('#button_product_update');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/product`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        product.list(parameters.product.params.page);
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja <span class=\"text-danger\">deletar</span> esse produto ?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-light-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      axios.delete(`${baseUrl}/api/admin/product`, {
        params: {
          id: id
        }
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state) {
          product.list(parameters.product.params.page);

          if (id == $('#product_data').find('[name="id"]').val()) {
            $('#not_selected').show()
            $('#product_data').hide()
          }
        }
      })
    });
  },
  duplicate: (id) => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que <br>deseja <span class=\"text-primary\">duplicar</span> este produto ?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, duplique isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      axios.post(`${baseUrl}/api/admin/product/duplicate`, {
        id: id
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state)
          product.list(parameters.product.params.page)
      })
    });
  },
  populate: (data) => {
    const products = data?.data,
      productList = $('#product_list'),
      paginator = $('#product_paginator');

    if (products.length <= 0) {
      $('#product_footer').hide();
      productList.hide();
      $('#not_results').show();
      helper.loader('#product_body', false);
      return
    }

    var productItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="product-${info.id}">
          <div class="d-flex align-items-center">
            <div>
                <span id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary cursor-pointer mb-2">${info.name}</span>
                <div class="text-muted fs-7">🌍 ${info?.server?.name ?? '❓ Desconhecido'}</div>
                <div class="text-muted fs-7">💵 R$<span class="text-primary">${info.value}</span> | 💴 x${info.ammount}</div>
            </div>
          </div>
          <div class="d-flex align-items-end ms-2">
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="duplicate">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"></path>
                </svg>
              </span>
            </button>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                <span class="svg-icon svg-icon-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                  </svg>
                </span>
            </button>
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                    <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                </svg>
              </span>
            </button>
          </div>
      </div>
      ${last ? '<div class="pt-2 separator separator-dashed"></div>' : ''}`;
    }

    productList.empty()
    $.each(products, (_, info) => {
      productList.append(productItem(info, products.length - 1 == _ ? false : true));

      $(`#product-${info.id} #duplicate`).click(() => {
        product.duplicate(info.id);
      })

      $(`#product-${info.id} #edit, #product-${info.id} #edit_name`).click(() => {
        reward.list(info.id);
        product.populateEdit(info);
      })

      $(`#product-${info.id} #delete`).click(() => {
        product.delete(info.id);
      })
    });

    (data.paginator.rendered == null) ? paginator.hide() : paginator.show();

    paginator.html(data.paginator.rendered)

    $('#not_results').hide()
    productList.show()
    helper.loader('#product_body', false)
  },
  populateEdit: (data) => {
    $('#not_selected').hide()
    $('#product_data').show()

    $.each(data, (key, value) => {
      const input = $(`#product_data form`).find(`[name="${key}"]`)

      if (['reward', 'active'].includes(key)) {
        input.prop("checked", value == "1" ? true : false).trigger('change');
        return
      }

      input.val(value)

      if (['sid', 'type'].includes(key))
        input.trigger('change')
    })
  }
}

const reward = {
  list: (id = null) => {
    if (id == null)
      id = $('#product_data').find('[name="id"]').val()

    helper.loader('#rewards_body .highlight')
    axios.get(`${baseUrl}/api/admin/product/reward`, {
      params: {
        id: id
      }
    }).then(res => {
      reward.populate(res.data)
    })
  },
  create: () => {
    const data = $("#md_product_reward_new form").serializeObject();
    data.pid = $('#product_data').find('[name="id"]').val()
    var button = document.querySelector('#md_product_reward_new button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/product/reward`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        reward.list()
    })
  },
  update: () => {
    const data = $("#md_product_reward_edit form").serializeObject();
    var button = document.querySelector('#md_product_reward_edit button[type="button"]');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/product/reward`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        reward.list()
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja <span class=\"text-danger\">remover</span> esse item ?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-light-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed)
        return

      axios.delete(`${baseUrl}/api/admin/product/reward`, {
        params: {
          id: id
        }
      }).then(res => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        if (su.state) {
          reward.list()
        }

      })
    });
  },
  populate: (data) => {
    const rewardList = $('#rewards_list'),
      rewards = data.data;

    if (data.data.length <= 0) {
      rewardList.hide()
      $('#no_rewards').show()
      helper.loader('#rewards_body .highlight', false)
      return
    }

    var rewardItem = (info) => {
      return `<div class="d-flex flex-stack mb-4" id="reward-${info.id}">
          <div class="d-flex align-items-center">
              <div class="w-40px h-40px me-3 rounded bg-light">
                  <img src="${info.item.Icon}" class="w-100">
              </div>
              <div class="me-3">
                  <div class="d-flex align-items-center">
                      <div class="text-gray-800 fw-bolder">${info.item.Name}</div>
                      <div class="badge badge-light-primary ms-5 me-2">x${info.ItemCount}</div>
                      <div class="badge badge-light-${info.IsBind == '1' ? 'danger' : 'success'}">
                        ${info.IsBind == '1' ? 'limitado' : 'ilimitado'}
                      </div>
                  </div>
                  <div class="text-muted">${info.TemplateID}</div>
              </div>
          </div>
          <div class="d-flex justify-content-end align-items-center">
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                  <span class="svg-icon svg-icon-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                          <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                      </svg>
                  </span>
              </button>
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
                  <span class="svg-icon svg-icon-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                      </svg>
                  </span>
              </button>
          </div>
      </div>`
    }

    rewardList.empty()

    $.each(rewards, (_, info) => {
      rewardList.append(rewardItem(info))

      $(`#reward-${info.id} #edit`).click(() => {
        reward.populateDetail(info)
      })

      $(`#reward-${info.id} #delete`).click(() => {
        reward.delete(info.id)
      })
    });

    rewardList.show()
    $('#no_rewards').hide()
    helper.loader('#rewards_body .highlight', false)
  },
  populateDetail: (data) => {
    $("#md_product_reward_edit #attr_area").show();
    $("#md_product_reward_edit #strengthen_area").removeClass("d-none");
    $("#md_product_reward_edit #count_area").removeClass("col-12");
    $("#md_product_reward_edit #count_area").addClass("col-6");

    if (data.item.CanStrengthen == "0") {
      $("#md_product_reward_edit #strengthen_area").addClass("d-none");
      $("#md_product_reward_edit #count_area").removeClass("col-6");
      $("#md_product_reward_edit #count_area").addClass("col-12");
    }

    if (data.item.CanCompose == "0")
      $("#md_product_reward_edit #attr_area").hide();

    $('#md_product_reward_edit #item_icon').attr('src', data.item.Icon)
    $('#md_product_reward_edit #item_name').html(data.item.Name)
    $('#md_product_reward_edit #item_id').html(data.TemplateID)

    $('#md_product_reward_edit').modal('show')

    $.each(data, (key, value) => {
      const input = $(`#md_product_reward_edit form`).find(`[name="${key}"]`)

      if (['IsBind'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value)

      if (['StrengthenLevel', 'ItemValid'].includes(key))
        input.trigger('change')

    })
  }
}

const controls = {
  listeners: () => {
    $('#product_body input[name="search"]').on('change', function () {
      parameters.product.params.search = $(this).val();
      product.list();
    })

    $('#product_body_list select[name="limit"]').on('change', function () {
      parameters.product.params.limit = $(this).val();
      product.list();
    })

    $('#product_body select[name="sid"]').on('change', function () {
      parameters.product.params.sid = $(this).val();
      product.list();
    })

    $('#product_data form input[name="reward"]').on('change', function () {
      const rewardBody = $('#product_data form #rewards_body')
      $(this).is(':checked') ? rewardBody.show() : rewardBody.hide()
    })

    $('#md_product_new form input[name="reward"]').on('change', function () {
      const alertProduct = $('#product_create_alert')
      $(this).is(':checked') ? alertProduct.show() : alertProduct.hide()
    })

    $('#md_product_reward_new select[name="TemplateID"]').select2({
      minimumInputLength: 1,
      language: {
        searching: () => {
          return "Buscando aguarde...";
        },
        inputTooShort: () => {
          return 'Insira o nome ou id do item';
        }
      },
      templateResult: (item) => {
        if (!item.id)
          return item.text;

        return $(`<span><img src="${item.pic}" class="h-30px me-2" alt="image"/> ${item.text}</span>`);
      },
      ajax: {
        url: `${baseUrl}/api/admin/item`,
        dataType: 'json',
        type: "GET",
        data: (search) => {
          return {
            sid: id = $('#product_data').find('[name="sid"]').val(),
            search
          }
        },
        processResults: (data) => {
          return {
            results: $.map(data.items, (item) => {
              var sex = ''
              if (item.NeedSex == "1") {
                sex = '🧢'
              }
              if (item.NeedSex == "2") {
                sex = '🎀'
              }

              return {
                text: `[${item.TemplateID}] - ${item.Name} ${sex}`,
                id: item.TemplateID,
                pic: item.Icon,
                sex: sex,
                data: item
              }
            })
          };
        }
      }
    });

    $('#md_product_reward_new select[name="TemplateID"]').on('change', function () {
      $("#md_product_reward_new #info_area, #md_product_reward_new #item_icon, #md_product_reward_new #item_name, #md_product_reward_new #item_id").hide();
      axios.get(`${baseUrl}/api/admin/item`, {
        params: {
          sid: id = $('#product_data').find('[name="sid"]').val(),
          'search[term]': $(this).val()
        }
      }).then((res) => {
        if (res.data.items.length == 0)
          return

        const data = res.data.items[0];

        $("#md_product_reward_new #attr_area").show();
        $("#md_product_reward_new #strengthen_area").removeClass("d-none");
        $("#md_product_reward_new #count_area").removeClass("col-12");
        $("#md_product_reward_new #count_area").addClass("col-6");

        if (data.CanStrengthen == "0") {
          $("#md_product_reward_new #strengthen_area").addClass("d-none");
          $("#md_product_reward_new #count_area").removeClass("col-6");
          $("#md_product_reward_new #count_area").addClass("col-12");
        }

        if (data.CanCompose == "0")
          $("#md_product_reward_new #attr_area").hide();

        $('#md_product_reward_new #item_icon').attr('src', data.Icon)
        $('#md_product_reward_new #item_name').html(`${data.Name} ${data.NeedSex == "1" ? '🧢' : ''} ${data.NeedSex == "2" ? '🎀' : ''}`)
        $('#md_product_reward_new #item_id').html(data.TemplateID)


        $("#md_product_reward_new #info_area, #md_product_reward_new #item_icon, #md_product_reward_new #item_name, #md_product_reward_new #item_id").show();
      })
    })
  },
  init: () => {
    controls.listeners()
    product.list()
  }
}

controls.init()
