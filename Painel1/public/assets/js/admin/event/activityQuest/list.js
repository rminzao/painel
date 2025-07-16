const parameters = {
  activity: {
    params: {
      page: 1,
      limit: 5,
      search: '',
      sid: null,
    }
  },
};

const stateActivity = {
  id: null,
}

const activity = {
  list: (page = 1) => {
    helper.loader('#activity_body', true)
    parameters.activity.params.page = page;
    axios.get(`${baseUrl}/api/admin/game/event/activity-quest`, parameters.activity).then(res => {
      activity.populate(res?.data);
    });
  },
  create: () => {
    const data = $("#md_quest_new form").serializeObject();
    data.sid = parameters.activity.params.sid

    var button = document.querySelector("#btn_activity_create");
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/event/activity-quest`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        activity.list(parameters.activity.params.page)
    })
  },
  update: () => {
    const data = $("#activity_data #detail form").serializeObject();
    data.sid = parameters.activity.params.sid

    var button = document.querySelector("#btn_activity_update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/event/activity-quest`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        activity.list(parameters.activity.params.page)
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar essa missão? essa alteração não pode ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/game/event/activity-quest`, {
          params: {
            id: id,
            sid: parameters.activity.params.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            if (id == $('#activity_data #detail input[name="ID"]').val()) {
              $('#no_selected').show();
              $('#activity_data').hide();
            }
            activity.list(parameters.activity.params.page)
          }
        })
      }
    })
  },
  updateOnGame: () => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar o servidor ? ao fazer isso a <b>xml</b> e os <b>emuladores</b> serão atualizados.",
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
        axios.get(`${baseUrl}/api/admin/game/event/activity-quest/update-on-game`, {
          params: { sid: parameters.activity.params.sid }
        }).then(res => {
          changeButtonState(button, false);
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        })
      }
    });
  },
  populate: (data) => {
    const list = $('#activity_list'),
      no_result = $('#no_result'),
      paginator = $('#paginator'),
      footer = $('#activity_list_footer');

    if (data.data.length <= 0) {
      no_result.show();
      list.hide();
      footer.hide();
      helper.loader('#activity_body', false);
      return;
    }

    var activityItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="quest-${info.ID}">
          <div class="d-flex align-items-center">
            <div>
                <span id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary cursor-pointer mb-2">
                  ${info.Title}
                </span>
                <div class="text-muted fs-7">🌍 ID: <span class="text-dark">${info.ID}</span></div>
            </div>
          </div>
          <div class="d-flex align-items-end ms-2">
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
      ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    };

    list.empty();
    paginator.empty();

    (data.paginator.rendered == null) ? paginator.hide() : paginator.show();
    (data.paginator.rendered == null) ? footer.hide() : footer.show();

    $.each(data.data, (_, info) => {
      list.append(activityItem(info, (_ == data.data.length - 1)));

      $(`#quest-${info.ID} #edit_name, #quest-${info.ID} #edit`).click(() => {
        activity.detail(info);
      });

      $(`#quest-${info.ID} #delete`).click(() => {
        activity.delete(info.ID);
      });
    });

    paginator.html(data.paginator.rendered)

    list.show();
    no_result.hide();
    helper.loader('#activity_body', false);
  },
  detail: (data) => {
    $.each(data, (key, value) => {
      const input = $(`#activity_data #detail form`).find(`[name="${key}"]`)
      input.val(value)
    })

    stateActivity.id = data.ID;
    condition.list(data.ID);
    reward.list(data.ID);

    $('#no_selected').hide();
    $('#activity_data').show();
  },
}

const condition = {
  list: (id) => {
    helper.loader('#conditions_body', true);
    axios.get(`${baseUrl}/api/admin/game/event/activity-quest/condition`, {
      params: { sid: parameters.activity.params.sid, id: id }
    }).then(res => {
      condition.populate(res?.data);
    });
  },
  create: () => {
    const data = $("#md_condition_new form").serializeObject();
    data.sid = parameters.activity.params.sid;
    data.QuestID = stateActivity.id;

    var button = document.querySelector("#btn_condition_create");
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/event/activity-quest/condition`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        condition.list(stateActivity.id)
    })
  },
  update: () => {
    const data = $("#md_condition_update form").serializeObject();
    data.sid = parameters.activity.params.sid;

    var button = document.querySelector("#btn_condition_update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/event/activity-quest/condition`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        condition.list(stateActivity.id)
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar essa missão? essa alteração não pode ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/game/event/activity-quest/condition`, {
          params: {
            CondictionID: id,
            QuestID: stateActivity.id,
            sid: parameters.activity.params.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            condition.list(stateActivity.id)
          }
        })
      }
    })
  },
  populate: (data) => {
    const list = $('#conditions_list'),
      no_conditions = $('#no_conditions');

    if (data?.data.length <= 0) {
      no_conditions.show();
      list.hide();
      helper.loader('#conditions_body', false);
      return;
    }

    const conditionItem = (info, last = false) => {
      const conditionType = activityConditions[info.CondictionType];
      return `<div class="d-flex flex-stack pt-2" id="condition-${info.QuestID}_${info.CondictionID}">
          <div class="d-flex align-items-center">
              <div class="me-3">
                  <div class="text-gray-800">${info.CondictionTitle}</div>
                  <div class="text-muted">
                    🎫 Tipo: <span class="text-primary">${conditionType?.name ?? `Desconhecido (${info.CondictionType})`}</span>
                  </div>
                  <div class="text-muted">
                    ${conditionType?.para1?.prefix ?? `Para1`}: <span class="text-primary">${info.Para1}</span>
                  </div>
                  <div class="text-muted">
                    ${conditionType?.para2?.prefix ?? `Para2`}: <span class="text-primary">${info.Para2}</span>
                  </div>
              </div>
          </div>
          <div class="d-flex justify-content-end align-items-center">
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                  <span class="svg-icon svg-icon-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path
                              opacity="0.3"
                              d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                              fill="currentColor"
                          ></path>
                          <path
                              d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                              fill="currentColor"
                          ></path>
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
      ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    }

    list.empty();
    $.each(data.data, (_, info) => {
      list.append(conditionItem(info, (_ == data.data.length - 1)));
      $(`#condition-${info.QuestID}_${info.CondictionID} #edit`).click(() => {
        $('#md_condition_update').modal('show');
        condition.detail(info);
      });
      $(`#condition-${info.QuestID}_${info.CondictionID} #delete`).click(() => {
        condition.delete(info.CondictionID);
      });
    });

    list.show();
    no_conditions.hide();
    helper.loader('#conditions_body', false);
  },
  detail: (data) => {
    $.each(data, (key, value) => {
      const input = $('#md_condition_update form').find(`[name="${key}"]`);

      input.val(value)

      if (['CondictionType'].includes(key))
        input.trigger('change')
    });
  },
}

const reward = {
  list: (id) => {
    helper.loader('#rewards_body', true);
    axios.get(`${baseUrl}/api/admin/game/event/activity-quest/reward`, {
      params: { sid: parameters.activity.params.sid, id: id }
    }).then(res => {
      reward.populate(res?.data);
    });
  },
  create: () => {
    const data = $("#md_reward_create form").serializeObject();
    data.sid = parameters.activity.params.sid;
    data.QuestID = stateActivity.id;

    var button = document.querySelector("#btn_reward_create");
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/event/activity-quest/reward`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        reward.list(stateActivity.id)
    })
  },
  update: () => {
    const data = $("#md_reward_update form").serializeObject();
    data.sid = parameters.activity.params.sid;

    var button = document.querySelector("#btn_reward_update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/event/activity-quest/reward`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        reward.list(stateActivity.id)
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar essa recompensa? essa alteração não pode ser desfeita.",
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
        axios.delete(`${baseUrl}/api/admin/game/event/activity-quest/reward`, {
          params: {
            id: id,
            QuestID: stateActivity.id,
            sid: parameters.activity.params.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            reward.list(stateActivity.id)
          }
        })
      }
    })
  },
  populate: (data) => {
    const list = $('#rewards_list'),
      no_rewards = $('#no_rewards');

    if (data?.data.length <= 0) {
      no_rewards.show();
      list.hide();
      helper.loader('#rewards_body', false);
      return;
    }

    const rewardItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="reward-${info.ID}">
        <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
                <img src="${info.Icon}" class="w-100 h-100">
            </div>
            <div class="me-3">
                <div class="d-flex align-items-center">
                    <div class="text-gray-800 fw-bolder">${info.Name}</div>
                    <div class="badge badge-light-primary ms-5 me-2">x${info.Count}</div>
                    <div class="badge badge-light-${info.IsBinds != '0' ? 'danger' : 'success'} me-2">
                      ${info.IsBinds != '0' ? 'Limitado' : 'Ilimitado'}
                    </div>
                    ${info.NeedSex >= 1 ? `<div class="badge me-n4">${info.NeedSex == 1 ? '🧢' : '🎀'}</div>` : ''}
                </div>
                <div class="text-muted">🎫 ID: ${info.TemplateID}</div>
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
      </div>
      ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    }

    list.empty();
    $.each(data.data, (_, info) => {
      list.append(rewardItem(info, (_ == data.data.length - 1)));
      $(`#reward-${info.ID} #edit`).click(() => {
        reward.detail(info);
      });
      $(`#reward-${info.ID} #delete`).click(() => {
        reward.delete(info.ID);
      });
    });

    list.show();
    no_rewards.hide();

    helper.loader('#rewards_body', false);
  },
  detail: (data) => {
    $("#md_reward_update #attr_area").show();
    $("#md_reward_update #strengthen_area").removeClass("d-none");
    $("#md_reward_update #count_area").removeClass("col-12");
    $("#md_reward_update #count_area").addClass("col-6");

    if (data.CanStrengthen == "0") {
      $("#md_reward_update #strengthen_area").addClass("d-none");
      $("#md_reward_update #count_area").removeClass("col-6");
      $("#md_reward_update #count_area").addClass("col-12");
    }

    if (data.CanCompose == "0")
      $("#md_reward_update #attr_area").hide();

    $('#md_reward_update #item_icon').attr('src', data.Icon)
    $('#md_reward_update #item_name').html(data.Name)
    $('#md_reward_update #item_id').html(data.TemplateID)

    $('#md_reward_update').modal('show')

    $.each(data, (key, value) => {
      const input = $(`#md_reward_update form`).find(`[name="${key}"]`)

      if (['IsBinds'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value)

      if (['StrengthenLevel', 'ValidDate'].includes(key))
        input.trigger('change')

    });

    $('#md_reward_update').modal('show');
  },
}

const controls = {
  listeners: () => {
    parameters.activity.params.sid = $('select[name="sid"]').val();

    $('select[name="sid"]').on('change', function () {
      parameters.activity.params.sid = $(this).val();
      activity.list();
    });

    $('#activity_list_footer select[name="limit"]').on('change', function () {
      parameters.activity.params.limit = $(this).val();
      activity.list();
    });

    $('#search').on('change', function () {
      parameters.activity.params.search = $(this).val();
      activity.list();
    });

    $('#activity_data [href="#detail"]').on('click', () => {
      $('#toolbar_conditions, #toolbar_rewards').hide();
    });

    $('#activity_data [href="#conditions"]').on('click', () => {
      $('#toolbar_rewards').hide();
      $('#toolbar_conditions').show();
    });

    $('#activity_data [href="#rewards"]').on('click', () => {
      $('#toolbar_rewards').show();
      $('#toolbar_conditions').hide();
    });

    var conditionTypeSub = (element, event) => {
      const conditionType = activityConditions[$(event).val()],
        select_para1 = $(element + ' select[name="Para1"]'),
        select_para2 = $(element + ' select[name="Para2"]');

      $(element + ' label[for="Para1"]').html(conditionType?.para1?.prefix ?? '❓ Para1');
      $(element + ' label[for="Para2"]').html(conditionType?.para2?.prefix ?? '❓ Para2');

      if (conditionType.para1.subType) {
        $(element + ' input[name="Para1"]').hide();
        $(element + ' input[name="Para1"]').attr('disabled', 'disabled');
        $(element + ' #para1_select').show();
        select_para1.removeAttr('disabled');
        select_para1.empty();
        $.each(conditionType.para1.subType, (key, value) => {
          select_para1.append(`<option value="${key}">${key} - ${value}</option>`).trigger('change');
        });
      }

      if (conditionType.para2.subType) {
        $(element + ' input[name="Para2"]').hide();
        $(element + ' input[name="Para2"]').attr('disabled', 'disabled');
        $(element + ' #para2_select').show();
        select_para2.removeAttr('disabled');
        select_para2.empty();
        $.each(conditionType.para2.subType, (key, value) => {
          select_para2.append(`<option value="${key}">${value}</option>`).trigger('change');
        });
      }

      if (conditionType.para1.subType || conditionType.para2.subType) {
        return;
      }

      select_para1.attr('disabled', 'disabled');
      select_para2.attr('disabled', 'disabled');

      $(element + ' #para1_select').hide();
      $(element + ' #para2_select').hide();

      $(element + ' input[name="Para1"]').show();
      $(element + ' input[name="Para2"]').show();

      $(element + ' input[name="Para1"]').removeAttr('disabled');
      $(element + ' input[name="Para2"]').removeAttr('disabled');

      $(element + ' input[name="Para1"]').val(conditionType?.para1?.default ?? '0');
      $(element + ' input[name="Para2"]').val(conditionType?.para2?.default ?? '0');
    }

    $('#md_condition_new select[name="CondictionType"]').on('change', function () {
      conditionTypeSub('#md_condition_new', this)
    });

    $('#md_condition_update select[name="CondictionType"]').on('change', function () {
      conditionTypeSub('#md_condition_update', this)
    });

    $('#md_reward_create select[name="TemplateId"]').select2({
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
            sid: parameters.activity.params.sid,
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

    $('#md_reward_create select[name="TemplateId"]').on('change', function () {
      $("#md_reward_create #info_area, #md_reward_create #item_icon, #md_reward_create #item_name, #md_reward_create #item_id").hide();
      axios.get(`${baseUrl}/api/admin/item`, {
        params: {
          sid: parameters.activity.params.sid,
          'search[term]': $(this).val()
        }
      }).then((res) => {
        if (res.data.items.length == 0)
          return

        const data = res.data.items[0];

        $("#md_reward_create #attr_area").show();
        $("#md_reward_create #strengthen_area").removeClass("d-none");
        $("#md_reward_create #count_area").removeClass("col-12");
        $("#md_reward_create #count_area").addClass("col-6");

        if (data.CanStrengthen == "0") {
          $("#md_reward_create #strengthen_area").addClass("d-none");
          $("#md_reward_create #count_area").removeClass("col-6");
          $("#md_reward_create #count_area").addClass("col-12");
        }

        if (data.CanCompose == "0")
          $("#md_reward_create #attr_area").hide();

        $('#md_reward_create #item_icon').attr('src', data.Icon)
        $('#md_reward_create #item_name').html(`${data.Name} ${data.NeedSex == "1" ? '🧢' : ''} ${data.NeedSex == "2" ? '🎀' : ''}`)
        $('#md_reward_create #item_id').html(data.TemplateID)


        $("#md_reward_create #info_area, #md_reward_create #item_icon, #md_reward_create #item_name, #md_reward_create #item_id").show();
      })
    });
  },
  init: () => {
    controls.listeners();
    activity.list();
  }
}

controls.init();
