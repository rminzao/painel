// page params
const parameters = {
  event: {
    params: {
      sid: null,
      page: 1,
      search: null,
      limit: 5,
      type: 'all',
      filter: 'all',
    },
  },
  conditions: {
    params: {
      sid: null,
      activityId: null,
      giftbagId: null
    }
  },
  rewards: {
    params: {
      sid: null,
      giftId: null,
    }
  }
}

const eventState = {
  activityType: null,
  subActivityType: null,
  currentConditions: null,
  giftbagIdList: null
}

const events = {
  list(page = 1) {
    parameters.event.params.page = page
    loader.init('#events_body')
    axios.get(`${baseUrl}/api/admin/game/gm-activity`, parameters.event).then((results) => {
      this.populate(results.data)
    })
  },
  create: () => {
    const data = $("#md_new_event form").serializeObject();
    data.sid = parameters.conditions.params.sid

    var button = document.querySelector('#md_new_event button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/gm-activity`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        events.list(parameters.event.params.page)
      }
    })
  },
  update: () => {
    const data = $("#event_detail form").serializeObject();
    data.sid = parameters.conditions.params.sid

    var button = document.querySelector('#event_detail button[type="button"]');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/gm-activity`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        events.list(parameters.event.params.page)
      }
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar esse evento? Ao confirmar todos os dados do evento serão apagados (<b>Recompensas</b>, <b>Condições</b> e outros) e essa alteração não pode ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/gm-activity`, {
          params: {
            sid: parameters.rewards.params.sid,
            id: id,
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            events.list(parameters.event.params.page)
          }
        })
      }
    })
  },
  duplicate: (id) => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja duplicar esse evento ?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, duplique isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.get(`${baseUrl}/api/admin/game/gm-activity/duplicate`, {
          params: {
            sid: parameters.event.params.sid,
            id: id,
          }
        }).then((res) => {
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            events.list(parameters.event.params.page)
          }
        })
      }
    })
  },
  reset: () => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja resetar este evento ? Todos os dados serão apagados e não poderá ser desfeito.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, confirme isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const data = $("#md_reset_event form").serializeObject();
        data.sid = parameters.event.params.sid

        if (data.rewarded == null && data.progress == null) {
          swMessage("warning", "Você não selecionou nenhuma opção.");
          return;
        }

        var button = document.querySelector('#md_reset_event button[type="button"]');
        changeButtonState(button, true);

        axios.get(`${baseUrl}/api/admin/game/gm-activity/reset`, {
          params: data
        }).then((res) => {
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          changeButtonState(button, false);
        })
      }
    })
  },
  populate(data) {
    if (!data.state) {
      alert(data.message)
      return
    }

    $('#no_conditions_body').show();
    $('#no_rewards_gift_body').show();
    $('#rewardList').hide();
    $('#conditionsList').hide();

    const list = $('#event_list'),
      paginator = $('#paginator'),
      eventsList = data.data,
      footer = $('#quest_list_footer');

    //clear list and pagination
    list.empty()
    paginator.empty()

    // check if data is not empty
    if (eventsList.length < 1) {
      $('#not_results').show()
      $('#event_list').hide()
      loader.destroy('#events_body')
      footer.hide();
      return;
    }

    //get state type
    var stateType = (state) => {
      switch (state) {
        case 'before':
          return '<span class="text-warning">🚧 Em breve</span>'
        case 'active':
          return '<span class="text-success">🟢 Ativo</span>'
        case 'after':
          return '<span class="text-danger">🔴 Encerrado</span>'
        default:
          return `❓ Desconhecido (${state})`
      }
    }

    var eventItem = (info, last = false) => {
      return `
            <div class="d-flex flex-stack pt-2" id="event-${info.activityId}">
                <div class="d-flex align-items-center">
                    <div>
                        <a href="javascript:;" id="edit-by-name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2 pe-auto">${info.activityName}</a>
                        <div class="text-muted fs-7 mb-1">🎫 Tipo: <span class="text-primary">${activityTypeList[info.activityType]?.name ?? `Desconhecido [${info.activityType}]`}</span></div>
                        <div class="text-muted fs-7 mb-1">${stateType(info.state)}</div>
                    </div>
                </div>
                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="reset" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Resetar">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M8.38 22H21C21.2652 22 21.5196 21.8947 21.7071 21.7072C21.8946 21.5196 22 21.2652 22 21C22 20.7348 21.8946 20.4804 21.7071 20.2928C21.5196 20.1053 21.2652 20 21 20H10L8.38 22Z" fill="currentColor"></path>
                                <path d="M15.622 15.6219L9.855 21.3879C9.66117 21.582 9.43101 21.7359 9.17766 21.8409C8.92431 21.946 8.65275 22 8.37849 22C8.10424 22 7.83268 21.946 7.57933 21.8409C7.32598 21.7359 7.09582 21.582 6.90199 21.3879L2.612 17.098C2.41797 16.9042 2.26404 16.674 2.15903 16.4207C2.05401 16.1673 1.99997 15.8957 1.99997 15.6215C1.99997 15.3472 2.05401 15.0757 2.15903 14.8224C2.26404 14.569 2.41797 14.3388 2.612 14.145L8.37801 8.37805L15.622 15.6219Z" fill="currentColor"></path>
                                <path opacity="0.3" d="M8.37801 8.37805L14.145 2.61206C14.3388 2.41803 14.569 2.26408 14.8223 2.15906C15.0757 2.05404 15.3473 2 15.6215 2C15.8958 2 16.1673 2.05404 16.4207 2.15906C16.674 2.26408 16.9042 2.41803 17.098 2.61206L21.388 6.90198C21.582 7.0958 21.736 7.326 21.841 7.57935C21.946 7.83269 22 8.10429 22 8.37854C22 8.65279 21.946 8.92426 21.841 9.17761C21.736 9.43096 21.582 9.66116 21.388 9.85498L15.622 15.6219L8.37801 8.37805Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="duplicate" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Duplicar">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="edit" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Editar">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-danger w-30px h-30px" id="delete" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Deletar">
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
            ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`;
    }
    $.each(eventsList, (_, event) => {
      list.append(eventItem(event, (_ == eventsList.length - 1)))
      list.find(`#event-${event.activityId} #edit, #event-${event.activityId} #edit-by-name`).on('click', () => {
        eventState.activityType = event.activityType
        eventState.subActivityType = event.activityChildType
        parameters.conditions.params.activityId = event.activityId
        events.detail(event)
        gift.list()
        conditions.list()
      })
      list.find(`#event-${event.activityId} #reset`).on('click', () => {
        $('#md_reset_event form').find('[name="id"]').val(event.activityId)
        $('#md_reset_event').modal('show')
      })
      list.find(`#event-${event.activityId} #delete`).on('click', () => {
        events.delete(event.activityId)
      })
      list.find(`#event-${event.activityId} #duplicate`).on('click', () => {
        events.duplicate(event.activityId)
      })
    })

    $('[data-bs-toggle="tooltip"]').tooltip()
    paginator.html(data.paginator.rendered)
    loader.destroy('#events_body')

    $('#not_results').hide();
    $('#event_list').show();
    footer.show();
  },
  detail: (event) => {
    $('#event_rewards #rewardList').empty()
    $('#no_rewards_gift_body').show()
    $('#event_data_body #event_data').show()
    $('#event_data_body #not_selected').hide()

    $('#md_edit_gift form input[name="activityId"]').val(event.activityId)

    $.each(event, (key, value) => {
      if (key == 'activityType' || key == 'activityChildType' || key == 'getWay') {
        $(`#event_detail form select[name="${key}"]`).val(value).trigger('change')
        return
      }

      if (key == "desc" || key == "rewardDesc") {
        $(`#event_detail form textarea[name="${key}"]`).val(value)
        return
      }

      $(`#event_detail form input[name="${key}"]`).val(value)
    })
  },
  updateOnGame() {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar os items ? ao fazer isso a <b>xml</b> e os <b>emuladores</b> serão atualizados.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, atualize!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        var button = document.querySelector("#update_on_game");
        changeButtonState(button, true);
        axios.get(`${baseUrl}/api/admin/game/gm-activity/update-on-game`, parameters.event).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          changeButtonState(button, false);
        })
      }
    });
  },
}

const conditions = {
  list: () => {
    $('#gift_data #no_conditions_body').show()
    $('#event_gift #gift_data #gift_data_body').addClass('d-none')

    axios.get(`${baseUrl}/api/admin/game/gm-activity/condition`, parameters.conditions).then((res) => {
      eventState.currentConditions = res.data.data
      $('#gift_data select[name="giftbagId"]').trigger('change')
    })
  },
  create: () => {
    const data = $("#md_new_condition form").serializeObject();
    data.activityType = parameters.conditions.params.activityType
    data.sid = parameters.conditions.params.sid

    var button = document.querySelector('#md_new_condition button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/gm-activity/condition`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_new_condition').modal('hide')
        conditions.list()
      }
    })
  },
  update: () => {
    const data = $("#md_edit_condition form").serializeObject();
    data.activityType = parameters.conditions.params.activityType
    data.subActivityType = parameters.conditions.params.subActivityType
    data.sid = parameters.conditions.params.sid
    var button = document.querySelector('#md_edit_condition button[type="button"]');
    changeButtonState(button, true);
    axios.put(`${baseUrl}/api/admin/game/gm-activity/condition`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_edit_condition').modal('hide')
        conditions.list()
      }
    })
  },
  delete: (id, index) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover essa condição? Essa alteração não pode ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/gm-activity/condition`, {
          params: {
            sid: parameters.conditions.params.sid,
            id: id,
            index: index
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            conditions.list()
            $('#kt_tab_condition_rewards').removeClass('active show')
            $('#kt_tab_condition_no_selected').addClass('active show')
          }
        })
      }
    })
  },
  populate: (id) => {
    //find all conditions in list by giftbagId on eventState.currentConditions with filter
    const data = eventState.currentConditions.filter(x => x.giftbagId == id)

    if (data == null || data.length == 0) {
      $('#gift_data #no_conditions_body').show()
      $('#event_gift #gift_data #gift_data_body').addClass('d-none')
      $('#no_conditions_gift_body').show()
      return;
    }

    var conditionItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="condition-${info.conditionIndex}">
                <div class="d-flex align-items-center">
                <div>
                    <a href="javascript:;" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">value ${info.conditionValue}</a>
                    <div class="text-muted fs-7 mb-1">🥊 order: ${info.conditionIndex}</div>
                    </div>
                </div>
                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                        <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path> </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
                        <span class="svg-icon svg-icon-3" id="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
            ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`;
    }

    const list = $('#gift_data_body #conditionsList');

    list.empty()

    $.each(data, (_, condition) => {
      list.append(conditionItem(condition, (_ == data.length - 1)))
      list.find(`#condition-${condition.conditionIndex} #edit`).on('click', () => {
        $('#md_edit_condition').modal('show')
        conditions.detail(condition)
      })
      list.find(`#condition-${condition.conditionIndex} #delete`).on('click', () => {
        conditions.delete(condition.giftbagId, condition.conditionIndex)
      })
    })
    list.show();
    $('#gift_data #no_conditions_body').hide()
    $('#gift_data #no_conditions_gift_body').hide()
    $('#event_gift #gift_data #gift_data_body').removeClass('d-none')
  },
  detail: (data) => {
    $('#md_edit_condition form input[name="originalConditionIndex"]').val(data.conditionIndex)
    $.each(data, (key, value) => {
      $(`#md_edit_condition form input[name="${key}"]`).val(value)
    })
  },
}

const gift = {
  list: () => {
    loader.init('#event_gift')
    $('#not_selected').hide()
    $('#event_data').show()
    axios.get(`${baseUrl}/api/admin/game/gm-activity/gift`, parameters.conditions).then((res) => {
      gift.populate(res.data)
    })
  },
  create: () => {
    const data = $("#md_new_gift form").serializeObject();
    data.sid = parameters.conditions.params.sid
    data.activityId = parameters.conditions.params.activityId

    var button = document.querySelector('#md_new_gift button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/gm-activity/gift`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_new_gift').modal('hide')
        gift.list()
      }
    })
  },
  update: () => {
    const data = $("#md_edit_gift form").serializeObject();
    data.sid = parameters.conditions.params.sid
    data.activityId = parameters.conditions.params.activityId

    var button = document.querySelector('#md_edit_gift button[type="button"]');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/gm-activity/gift`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_edit_gift').modal('hide')
        gift.list()
      }
    })
  },
  delete: () => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja deletar essa giftbag? Essa alteração não pode ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/gm-activity/gift`, {
          params: {
            sid: parameters.conditions.params.sid,
            id: $('#event_gift select[name="giftbagId"]').val(),
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            gift.list()
          }
        })
      }
    })
  },
  populate: (data) => {
    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#gift_data select[name="giftbagId"]'),
      conditionsList = data.data

    eventState.giftbagIdList = conditionsList

    //clear condition list
    list.empty()

    // check if data is not empty
    if (conditionsList.length < 1) {
      $('#event_gift #no_gifts, #event_rewards #no_gifts').show()
      $('#event_gift #gift_data, #event_rewards #gift_data').hide()
      loader.destroy('#event_gift')
      return;
    }

    list.append('<option></option>').trigger('change');
    $.each(conditionsList, (_, condition) => {
      var newOption = new Option(`[${condition.giftbagOrder}] - ${condition.giftbagId}`, condition.giftbagId, false, false);
      list.append(newOption).trigger('change');
    })

    $('#event_gift #no_gifts, #event_rewards #no_gifts').hide()
    $('#event_gift #gift_data, #event_rewards #gift_data').show()

    loader.destroy('#event_gift')
  },
  detail: () => {
    const id = $('#event_gift select[name="giftbagId"]').val()
    const data = eventState.giftbagIdList.find(c => c.giftbagId == id) ?? null

    if (data != null) {
      $('#md_edit_gift form input[name="giftbagOrder"]').val(data.giftbagOrder)
      $('#md_edit_gift form select[name="rewardMark"]').val(data.rewardMark).trigger('change')
    }
  }
}

const rewards = {
  create: () => {
    const data = $("#md_new_reward form").serializeObject();
    data.giftId = parameters.rewards.params.giftId
    data.sid = parameters.rewards.params.sid

    var button = document.querySelector('#md_new_reward button[type="button"]');
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/gm-activity/rewards`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_new_reward #templateId').val('').trigger('change')
        $('#md_new_reward').modal('hide')
        rewards.list()
      }
    })
  },
  update: () => {
    const data = $("#md_edit_reward form").serializeObject();
    data.sid = parameters.rewards.params.sid
    data.giftId = parameters.rewards.params.giftId

    var button = document.querySelector('#md_edit_reward button[type="button"]');
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/gm-activity/rewards`, data).then((results) => {
      var su = results.data;
      swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
      changeButtonState(button, false);
      if (su.state) {
        $('#md_edit_reward').modal('hide')
        rewards.list()
      }
    })
  },
  delete: (giftId, templateId) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja remover esse item? Essa alteração não pode ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/gm-activity/rewards`, {
          params: {
            sid: parameters.rewards.params.sid,
            giftId: giftId,
            templateId: templateId,
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            rewards.list()
          }
        })
      }
    })
  },
  list: () => {
    loader.init('#rewardBody')
    axios.get(`${baseUrl}/api/admin/game/gm-activity/rewards`, parameters.rewards).then((results) => {
      rewards.populate(results.data)
    })
  },
  populate: (data) => {
    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#rewardList'),
      rewardsList = data.data

    //clear reward list
    list.empty()

    // check if data is not empty
    if (rewardsList.length < 1) {
      $('#no_rewards_gift_body').show()
      list.hide()
      loader.destroy('#rewardBody')
      return;
    }

    var rewardItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="reward-${info.templateId}-${info.giftId}">
            <div class="d-flex align-items-center">
                <div class="w-40px h-40px me-3 rounded bg-light">
                    <img src="${info.Icon}"
                        class="w-100">
                </div>
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-1">${info?.Name ?? 'sem nome'}</div>
                        <div class="badge badge-light-primary ms-5 me-2">x${info.count}</div>
                        <div class="badge badge-light-${info.isBind == '0' ? 'success' : 'danger'}">
                        ${info.isBind == '0' ? 'Ilimitado' : 'Limitado'}
                        </div>
                    </div>
                    <div class="text-muted fs-7 mb-1">${info.templateId}</div>
                </div>
            </div>
            <div
                class="d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="currentColor"></path>
                            <path
                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                </button>
                <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" id="delete">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path
                                d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                fill="currentColor"></path>
                            <path opacity="0.5"
                                d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                fill="currentColor"></path>
                            <path opacity="0.5"
                                d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
        ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    }

    $.each(rewardsList, (_, reward) => {
      if (reward?.templateId == null) {
        return
      }
      list.append(rewardItem(reward, (_ == rewardsList.length - 1)))
      list.find(`#reward-${reward.templateId}-${reward.giftId} #edit`).on('click', () => {
        rewards.detail(reward)
      })

      list.find(`#reward-${reward.templateId}-${reward.giftId} #delete`).on('click', () => {
        rewards.delete(reward.giftId, reward.templateId)
      })
    })

    $('#no_rewards_gift_body').hide()
    list.show()

    loader.destroy('#rewardBody')
  },
  detail: (reward) => {
    $('#md_edit_reward').modal('show')
    $("#md-edit-reward-annex-id").html(reward.templateId);
    $("#md-edit-reward-annex-name").html(reward.Name);
    $("#md-edit-reward-annex-pic").attr("src", reward.Icon);

    $.each(reward, (key, value) => {
      const input = $(`#md_edit_reward form`).find(`[name="${key}"]`)

      if (['isBind'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value);

      if (key == 'count') input.attr("max", reward.MaxCount);

      if (['validDate'].includes(key)) input.trigger('change');
    });
  }
}

const loader = {
  init(element) {
    var target = document.querySelector(element);
    var blockUI = new KTBlockUI(target);

    blockUI.destroy();
    blockUI.block();
  },
  destroy() {
    var target = document.querySelector(`.blockui-overlay`);
    target.remove()
  }
}

const init = () => {
  //get server id by select
  parameters.event.params.sid = $('select[name="sid"]').val()
  parameters.conditions.params.sid = $('select[name="sid"]').val()
  parameters.rewards.params.sid = $('select[name="sid"]').val()
  $('#select2-data-eventState_filter').on('change', function () {
    parameters.event.params.filter = $(this).val();
    events.list();
  });
  


  //set sid from select change
  $('select[name="sid"]').on('change', function () {
    parameters.event.params.sid = $(this).val();
    parameters.conditions.params.sid = $(this).val();
    parameters.rewards.params.sid = $(this).val();
    events.list();
  });

  $('#quest_list_footer select[name="limit"]').on('change', function () {
    parameters.event.params.limit = $(this).val();
    events.list();
  });

  $('#events_body select[name="type"]').on('change', function () {
    parameters.event.params.type = $(this).val();
    events.list();
  })

  $('input[name="search"]').on('change', function () {
    parameters.event.params.search = $(this).val();
    events.list();
  })

  $('#eventState_filter').on('change', function () {
    parameters.event.params.filter = $(this).val();
    events.list();
  })

  //date start flatpickr
  $('input[name="beginTime"], input[name="endTime"]').flatpickr({
    enableTime: false,
    enableSeconds: false,
    dateFormat: "Y-m-d H:i:s",
  });

  $('#condition_data #delete').on('click', () => { conditions.delete() })

  //item search
  $('#md_new_reward #templateId').on('change', function () {
    //restore modal to default values
    $('#md-annex-pic, #md-annex-name, #md-annex-id, #md-item-info').hide()

    var itemID = $(this).val()
    $.get(`${baseUrl}/api/admin/item`, {
      sid: parameters.rewards.params.sid,
      search: {
        term: itemID
      }
    }, function (response) {
      if (response.items < 1) {
        return;
      }

      const item = response.items[0];
      var sex = ''
      if (item.NeedSex == "1") {
        sex = '🧢'
      }
      if (item.NeedSex == "2") {
        sex = '🎀'
      }

      $('#md-annex-pic').attr('src', item.Icon)
      $('#md-annex-name').text(`${item.Name} ${sex}`)
      $('#md-annex-id').text(item.TemplateID)
      $("#md-annex-in-max").attr('max', item.MaxCount);
      $('#md_new_reward input[name="remain1"]').val(item.Name);
      $('#md-annex-pic, #md-annex-name, #md-annex-id, #md-item-info').show()
    })
  })

  $('#md_new_reward #templateId').select2({
    minimumInputLength: 2,
    templateResult: (item) => {
      if (!item.id) {
        return item.text;
      }

      var span = document.createElement('span')
      span.innerHTML = `<img src="${item.pic}" class="h-30px me-2" alt="image"/> ${item.text}`;
      return $(span);
    },
    ajax: {
      url: `${baseUrl}/api/admin/item`,
      dataType: 'json',
      type: "GET",
      data: (search) => {
        return {
          sid: parameters.rewards.params.sid,
          search
        };
      },
      processResults: (data) => {
        return {
          results: $.map(data.items, function (item) {
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

  //get subActivityType by activityType
  $('#event_detail select[name="activityType"]').on('change', function () {
    $('#event_detail select[name="activityChildType"]').empty()
    const subTypes = activityTypeList[$(this).val()]?.subActivityType ?? null;

    if (!subTypes) {
      return
    }

    $.each(subTypes, function (key, value) {
      const isSelected = eventState.subActivityType == key ? true : false;
      var newOption = new Option(value, key, isSelected, isSelected);

      $('#event_detail select[name="activityChildType"]').append(newOption).trigger('change');
    })
  })

  $('#md_new_event select[name="activityType"]').on('change', function () {
    $('#md_new_event select[name="activityChildType"]').empty()
    const subTypes = activityTypeList[$(this).val()]?.subActivityType ?? null;

    if (!subTypes) {
      return
    }

    $.each(subTypes, function (key, value) {
      const isSelected = eventState.subActivityType == key ? true : false;
      var newOption = new Option(value, key, isSelected, isSelected);

      $('#md_new_event select[name="activityChildType"]').append(newOption).trigger('change');
    })
  })

  $('body a[href="#event_detail"]').on('click', () => {
    $('#gift_buttons, #condition_buttons').hide();
  })

  $('body a[href="#event_gift"]').on('click', () => {
    $('#gift_buttons').show();
    $('#condition_buttons').hide();
  })

  $('body a[href="#event_cond_and_rewards"]').on('click', () => {
    $('#gift_buttons').hide();
    $('#condition_buttons').show();
  })

  $('#event_gift #gift_data select[name="giftbagId"]').on('change', function () {
    const value = $(this).val()
    if (value == null || value == '') {
      $('#event_gift #gift_data_buttons').hide();
      return
    }

    parameters.conditions.params.giftbagId = value;

    $('#event_gift #gift_data_buttons').show();
    $('#md_edit_gift form input[name="giftbagId"], #md_new_condition form input[name="giftbagId"]').val(value)
    conditions.populate(value)
  })

  $('#event_rewards #gift_data select[name="giftbagId"]').on('change', function () {

    const value = $(this).val()
    if (value == null || value == '') {
      $('#event_rewards #gift_data_buttons').hide();
      return
    }

    parameters.rewards.params.giftId = value;

    $('#event_rewards #gift_data_buttons').show();
    rewards.list()
  })

  events.list()
}

init()
