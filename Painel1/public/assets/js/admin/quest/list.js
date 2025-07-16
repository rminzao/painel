const parameters = {
  quest: {
    params: {
      page: 1,
      limit: 5,
      search: null,
      state: null,
      type: null,
      sid: null,
    }
  }
}

const stateQuest = {
  id: null,
  version: null
}

const quest = {
  list: (page = 1) => {
    parameters.quest.params.page = page;
    helper.loader('#quest_body', true);
    axios.get(`${baseUrl}/api/admin/game/quest`, parameters.quest).then(res => {
      quest.populate(res.data);
    })
  },
  create: () => {
    const data = $(`#md_quest_create form[data-require-version="${stateQuest.version}"]`).serializeObject();
    data.sid = parameters.quest.params.sid

    var button = document.querySelector(`form[data-require-version="${stateQuest.version}"] #btn_quest_create`);
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/quest`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        quest.list(parameters.quest.params.page)
    })
  },
  update: () => {
    const data = $(`#quest_data`).find(`form[data-require-version="${stateQuest.version}"]`).serializeObject();
    data.sid = parameters.quest.params.sid

    var button = document.querySelector(`form[data-require-version="${stateQuest.version}"] #btn_quest_update`);
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/quest`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        quest.list(parameters.quest.params.page)
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
        axios.delete(`${baseUrl}/api/admin/game/quest`, {
          params: {
            id: id,
            sid: parameters.quest.params.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            if (id == stateQuest.id) {
              $('#no_selected').show();
              $('#quest_data').hide();
            }
            quest.list(parameters.quest.params.page)
          }
        })
      }
    })
  },
  updateOnGame: () => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja atualizar as missões? os emuladores e a request será atualizados.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, atualize isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-sm btn-light-primary",
        cancelButton: "btn btn-sm btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        var button = document.querySelector(`#btn_quest_game_update`);
        changeButtonState(button, true);

        axios.get(`${baseUrl}/api/admin/game/quest/update-on-game`, parameters.quest).then(res => {
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          changeButtonState(button, false);
        });
      }
    })
  },
  populate: (data) => {
    const list = $('#quest_list'),
      no_result = $('#no_result'),
      paginator = $('#paginator'),
      footer = $('#quest_list_footer');

    if (data.data.length <= 0) {
      no_result.show();
      list.hide();
      footer.hide();
      helper.loader('#quest_body', false);
      return;
    }

    var questItem = (info, last = false) => {
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
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="duplicate">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"/>
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
      ${last ? '' : '<div class="pt-2 separator separator-dashed"></div>'}`
    };

    list.empty();
    paginator.empty();

    (data.paginator.rendered == null) ? paginator.hide() : paginator.show();
    (data.paginator.rendered == null) ? footer.hide() : footer.show();

    $.each(data.data, (_, info) => {
      list.append(questItem(info, (_ == data.data.length - 1)));

      $(`#quest-${info.ID} #duplicate`).click(() => {
        quest.duplicate(info.ID);
      });

      $(`#quest-${info.ID} #edit_name, #quest-${info.ID} #edit`).click(() => {
        controls.version_checker();
        quest.detail(info);
      });

      $(`#quest-${info.ID} #delete`).click(() => {
        quest.delete(info.ID);
      });
    });

    paginator.html(data.paginator.rendered)

    list.show();
    no_result.hide();
    helper.loader('#quest_body', false);
  },
  detail: (data) => {
    const version = $('select[name="sid"] option:selected').attr('data-version');
    var object = $(`div[id="version_server"][data-require-version="${version}"], form[id="version_server"][data-require-version="${version}"]`);

    if (object.length == 0) return;

    $.each(data, (key, value) => {
      const input = $(`#quest_data #detail form`).find(`[name="${key}"]`)

      if (['CanRepeat','TimeMode'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return;
      }

      input.val(value);

      if (['QuestID'].includes(key))
        input.trigger('change')
    })

    stateQuest.id = data.ID;
    condition.list(data.ID);
    reward.list(data.ID);

    $('#no_selected').hide();
    $('#quest_data').show();
    $('#unbearable, #md_quest_create #unbearable').hide();
  },
  duplicate: (id) => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja duplicar essa missão?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, duplique isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-sm btn-light-primary",
        cancelButton: "btn btn-sm btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.get(`${baseUrl}/api/admin/game/quest/duplicate`, {
          params: {
            id: id,
            sid: parameters.quest.params.sid
          }
        }).then((results) => {
          var su = results.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            if (id == stateQuest.id) {
              $('#no_selected').show();
              $('#quest_data').hide();
            }
            quest.list(parameters.quest.params.page)
          }
        })
      }
    })
  }
}

const condition = {
  list: (id) => {
    helper.loader('.tab-content #condition', true);
    axios.get(`${baseUrl}/api/admin/game/quest/condition`, {
      params: {
        sid: parameters.quest.params.sid,
        id: id
      }
    }).then(res => {
      condition.populate(res.data)
    })
  },
  create: () => {
    const data = $(`#md_condition_create form`).serializeObject();
    data.sid = parameters.quest.params.sid
    data.QuestID = stateQuest.id;

    var button = document.querySelector(`#btn_condition_create`);
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/quest/condition`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        condition.list(stateQuest.id);
    })
  },
  update: () => {
    const data = $(`#md_condition_update form`).serializeObject();
    data.sid = parameters.quest.params.sid

    var button = document.querySelector(`#btn_condition_update`);
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/quest/condition`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        condition.list(stateQuest.id);
    })
  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar essa condição?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-sm btn-light-danger",
        cancelButton: "btn btn-sm btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/quest/condition`, {
          params: {
            CondictionID: id,
            QuestID: stateQuest.id,
            sid: parameters.quest.params.sid
          }
        }).then(res => {
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            condition.list(stateQuest.id);
          }
        });
      }
    })

  },
  populate: (data) => {
    const list = $('#condition_list'),
      no_conditions = $('#no_condition');

    if (data?.data.length <= 0) {
      no_conditions.show();
      list.hide();
      helper.loader('.tab-content #condition', false);
      return;
    }

    const conditionItem = (info, last = false) => {
      const conditionType = questConditionTypes[info.CondictionType];
      return `<div class="d-flex flex-stack pt-2" id="condition-${info.QuestID}_${info.CondictionID}">
          <div class="d-flex align-items-center">
              <div class="me-3">
                  <div class="text-gray-800">${info.CondictionTitle} ${info.isOpitional != '0' ? ' | 👊 <span class="text-warning fw-bolder">Opcional</span>' : ''}</div>
                  <div class="text-muted">
                    🎫 Tipo: <span class="text-primary">${conditionType?.Name ?? `Desconhecido (${info.CondictionType})`}</span>
                  </div>
                  <div class="text-muted">
                    ${conditionType?.Para1?.Name ?? `❓ Para1`}: <span class="text-primary">${info.Para1}</span>
                  </div>
                  <div class="text-muted">
                    ${conditionType?.Para2?.Name ?? `❓ Para2`}: <span class="text-primary">${info.Para2}</span>
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
    helper.loader('.tab-content #condition', false);
  },
  detail: (data) => {
    $.each(data, (key, value) => {
      const input = $(`#md_condition_update form`).find(`[name="${key}"]`)

      if (['isOpitional'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value)

      if (['CondictionType'].includes(key))
        input.trigger('change')
    })
  },
}

const reward = {
  list: (id) => {
    helper.loader('.tab-content #reward', true);
    axios.get(`${baseUrl}/api/admin/game/quest/reward`, {
      params: { sid: parameters.quest.params.sid, id: id }
    }).then(res => {
      reward.populate(res?.data);
    });
  },
  create: () => {
    const data = $(`#md_reward_create form[data-require-version="${stateQuest.version}"]`).serializeObject();
    data.sid = parameters.quest.params.sid;
    data.QuestID = stateQuest.id;

    var button = document.querySelector(`form[data-require-version="${stateQuest.version}"] #btn_reward_create`);
    changeButtonState(button, true);

    axios.post(`${baseUrl}/api/admin/game/quest/reward`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state) {
        reward.list(stateQuest.id)
      }
    })
  },
  update: () => {
    const data = $(`#md_reward_update form[data-require-version="${stateQuest.version}"]`).serializeObject();
    data.sid = parameters.quest.params.sid;
    data.QuestID = stateQuest.id;

    var button = document.querySelector(`form[data-require-version="${stateQuest.version}"] #btn_reward_update`);
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/quest/reward`, data).then(res => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state) {
        reward.list(stateQuest.id)
      }
    })

  },
  delete: (id) => {
    Swal.fire({
      icon: "error",
      html: "Você tem certeza que deseja apagar essa recompensa?",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, delete isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-sm btn-light-danger",
        cancelButton: "btn btn-sm btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${baseUrl}/api/admin/game/quest/reward`, {
          params: {
            RewardItemID: id,
            QuestID: stateQuest.id,
            sid: parameters.quest.params.sid
          }
        }).then(res => {
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
          if (su.state) {
            reward.list(stateQuest.id);
          }
        });
      }
    })
  },
  populate: (data) => {
    const list = $('#reward_list'),
      no_rewards = $('#no_reward');

    if (data?.data.length <= 0) {
      no_rewards.show();
      list.hide();
      helper.loader('.tab-content #reward', false);
      return;
    }

    const rewardItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="reward-${info.QuestID}-${info.RewardItemID}">
        <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
              <img src="${info.Icon}"
                onerror="this.src='${baseUrl}/assets/media/icons/original.png';"
                class="w-100 h-100">
            </div>
            <div class="me-3">
                <div class="d-flex align-items-center">
                    <div class="text-gray-800 fw-bolder">${info?.Name ?? '❓ Desconhecido'}</div>
                    <div class="badge badge-light-primary ms-5 me-2">x${info.RewardItemCount1 ?? info?.RewardItemCount}</div>
                    <div class="badge badge-light-${info.IsBind != '0' ? 'danger' : 'success'} me-2">
                      ${info?.IsBind != '0' ? 'Limitado' : 'Ilimitado'}
                    </div>
                    ${info?.IsSelect != '0' ? '🤚' : ''}
                    ${info?.NeedSex >= 1 ? `<div class="badge me-n4">${info?.NeedSex == 1 ? '🧢' : '🎀'}</div>` : ''}
                </div>
                <div class="text-muted">🎫 ID: ${info.RewardItemID}</div>
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
      $(`#reward-${info.QuestID}-${info.RewardItemID} #edit`).click(() => {
        $("#md_reward_update").modal('show');
        reward.detail(info);
      });
      $(`#reward-${info.QuestID}-${info.RewardItemID} #delete`).click(() => {
        reward.delete(info.RewardItemID);
      });
    });

    list.show();
    no_rewards.hide();

    helper.loader('.tab-content #reward', false);
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

      if (['IsBind', 'IsCount', 'IsSelect'].includes(key)) {
        input.prop("checked", value == "1" ? true : false);
        return
      }

      input.val(value)

      if (['StrengthenLevel', 'RewardItemValid'].includes(key))
        input.trigger('change')

    });

    $('#md_reward_update').modal('show');
  },
}

const controls = {
  version_checker: () => {
    const version = $('select[name="sid"] option:selected').attr('data-version');
    var object = $(`div[id="version_server"][data-require-version="${version}"], form[id="version_server"][data-require-version="${version}"]`);

    stateQuest.version = version;

    $(`div[id="version_server"], form[id="version_server"]`).hide();

    if (object.length == 0) {
      swMessage(
        'warning',
        'Esta função não da suporte para versão do servidor <span class="text-warning">' + $('option:selected', this).text() + '</span>'
      );
      $('#md_quest_create #modal_data').hide();
      $('#quest_data, #no_selected').hide();
      $('#unbearable').show();
      $('#md_quest_create #unbearable').show();
      return;
    }

    $('#md_quest_create #modal_data').show();
    $('#md_quest_create #unbearable').hide();

    $('#no_selected').show();
    $('#unbearable, #quest_data').hide();

    object.show();
  },
  listeners: () => {
    parameters.quest.params.sid = $('select[name="sid"]').val();
    stateQuest.version = $('select[name="sid"] option:selected').attr('data-version');
    controls.reward_select();

    parameters.quest.params.type = $('select[name="questType_filter"]').val();
    parameters.quest.params.state = $('select[name="questState_filter"]').val();

    $('select[name="sid"]').on('change', function () {
      parameters.quest.params.sid = $(this).val();
      quest.list();
      controls.version_checker();

      const form = $(`form[data-require-version="${stateQuest.version}"]`);
      form.find('#info_area').hide();
      form.find('select[name="RewardItemID"]').trigger('change');
      $('#item_icon').hide();
      $('#item_name').hide();
      $('#item_id').hide();
    });

    $('select[name="limit"]').on('change', function () {
      parameters.quest.params.limit = $(this).val();
      quest.list();
    });

    $('input[name="search"]').on('change', function () {
      parameters.quest.params.search = $(this).val();
      quest.list();
    });

    $('select[name="questType_filter"]').on('change', function () {
      parameters.quest.params.type = $(this).val();
      quest.list();
    });

    $('select[name="questState_filter"]').on('change', function () {
      parameters.quest.params.state = $(this).val();
      quest.list();
    });

    $('input[name="StartDate"], input[name="EndDate"]').flatpickr({
      enableTime: false,
      enableSeconds: false,
      dateFormat: "Y-m-d H:i:s",
    });

    $('a[href="#detail"]').on('click', () => {
      $('#condition_toolbar').hide();
      $('#reward_toolbar').hide();
    });

    $('a[href="#condition"]').on('click', () => {
      $('#condition_toolbar').show();
      $('#reward_toolbar').hide();
    });

    $('a[href="#reward"]').on('click', () => {
      $('#condition_toolbar').hide();
      $('#reward_toolbar').show();
    });

    $('#md_condition_create select[name="CondictionType"]').on('change', function () {
      const conditionType = questConditionTypes[$(this).val()];
      $('#md_condition_create #para1').html(`${conditionType?.Para1?.Name ?? `❓ Para1`}`);
      $('#md_condition_create #para2').html(`${conditionType?.Para2?.Name ?? `❓ Para2`}`);
    });

    $('#md_condition_update select[name="CondictionType"]').on('change', function () {
      const conditionType = questConditionTypes[$(this).val()];
      $('#md_condition_update #para1').html(`${conditionType?.Para1?.Name ?? `❓ Para1`}`);
      $('#md_condition_update #para2').html(`${conditionType?.Para2?.Name ?? `❓ Para2`}`);
    });

    $('#md_reward_create input[name="IsMultipleCount"], #md_reward_update input[name="IsMultipleCount"]').on('change', function () {
      if ($(this).is(':checked')) {
        $('#md_reward_create #multiple_count').show();
        $('#md_reward_update #multiple_count').show();
        return;
      }

      $('#md_reward_create #multiple_count').hide();
      $('#md_reward_update #multiple_count').hide();
    });
  },
  reward_select: () => {
    $('#md_reward_create select[name="RewardItemID"]').select2({
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
            sid: parameters.quest.params.sid,
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

    $('#md_reward_create select[name="RewardItemID"]').on('change', function () {
      $("#md_reward_create #info_area, #md_reward_create #item_icon, #md_reward_create #item_name, #md_reward_create #item_id").hide();
      axios.get(`${baseUrl}/api/admin/item`, {
        params: {
          sid: parameters.quest.params.sid,
          'search[term]': $(this).val()
        }
      }).then((res) => {
        if (res.data.items.length == 0)
          return

        const data = res.data.items[0];

        $("#md_reward_create #attr_area").show();
        $("#md_reward_create #strengthen_area").removeClass("d-none");
        $("#md_reward_create #valid_area").removeClass("col-12");
        $("#md_reward_create #valid_area").addClass("col-6");

        if (data.CanStrengthen == "0") {
          $("#md_reward_create #strengthen_area").addClass("d-none");
          $("#md_reward_create #valid_area").removeClass("col-6");
          $("#md_reward_create #valid_area").addClass("col-12");
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
    quest.list();
  }
}

controls.init();
