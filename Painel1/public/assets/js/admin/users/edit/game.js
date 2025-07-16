const parameters = {
  game: {
    messages: {
      params: {
        sid: null,
        uid: null,
        page: null,
        search: null,
        limit: null,
      }
    },
    bag: {
      params: {
        sid: null,
        uid: null,
        page: null,
        search: null,
        limit: 10,
        type: 'all',
        category: 'all',
      }
    },
  }
}

const stateGame = {
  message: {
    page: null,
    data: null,
    current: null,
  },
  bag: {
    data: null,
  }
}

const message = {
  list(page = 1, reload = false) {
    parameters.game.messages.params.page = reload ? stateGame.message.page : page
    loader.init('#tab_user_person_messages .card-body');
    axios.get(`${baseUrl}/api/admin/user/game/message`, parameters.game.messages).then((res) => {
      stateGame.message.data = res.data;
      message.populate(res.data);
    });
  },
  populate(data) {
    $('#tab_user_person_messages #no_results').show();
    $('#tab_user_person_messages .table-responsive').hide();
    $('#tab_user_person_messages #email_limit_area').hide();

    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#messages-list'),
      paginator = $('#email_paginator'),
      messageList = data.data

    list.empty()
    paginator.empty()

    if (messageList.length < 1) {
      $('#tab_user_person_messages #no_results').show();
      $('#tab_user_person_messages .table-responsive').hide();
      $('#tab_user_person_messages #email_limit_area').hide();
      loader.destroy('#tab_user_person_messages .card-body');
      return;
    }

    stateGame.message.page = data?.paginator?.current ?? 1

    $('#tab_user_person_messages #no_results').hide();
    $('#tab_user_person_messages .table-responsive').show();
    $('#tab_user_person_messages #email_limit_area').show();

    var emailItem = (data) => {
      return `<tr id="mail-${data.ID}">
            <td class="ps-9 w-200px w-md-225px">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px me-3">
                        <div class="symbol-label bg-light-danger">
                            <span class="text-danger">A</span>
                        </div>
                    </div>
                <div>
                <div>
                    <a href="javascript:;" id="edit_name" class="fs-8 text-gray-900 text-hover-primary mb-2">${helper.str_limit_words(data.Title, 15)}</a>
                    <div class="text-muted fs-7 mb-1">🎫 ID: ${data.ID}</div>
                </div>
            </td>
            <td>
                <div class="text-dark mb-1">
                    <a class="text-dark">
                        <span class="text-muted fs-7">${helper.str_limit_words(data.Content, 70)}</span>
                    </a>
                </div>
                <div class="text-muted fs-7">
                    Enviado por:
                    <span class="text-primary">
                        ${data.Sender}
                    </span>
                </div>
            </td>
            <td class="w-100px text-end fs-7 pe-9"></td>
            <td>
                <div class="d-flex justify-content-end align-items-center text-end mt-1">
                    <button class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary me-9" id="detail">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z" fill="currentColor"></path>
                                <path opacity="0.3" d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z" fill="currentColor"></path>
                                <path opacity="0.3" d="M15 17H9V20H15V17Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </td>
        </tr>`
    }

    $.each(messageList, (_, item) => {
      //populate invoice list
      list.append(emailItem(item))

      //find click edit button
      $(`#messages-list #mail-${item.ID} #detail`).click(() => {
        //prevent close on click outside
        $('#md_game_mail_edit').modal({ backdrop: "static ", keyboard: false }).modal('show')

        stateGame.message.current = item

        message.populateDetail(item)
      })
    });

    paginator.html(data.paginator.rendered)

    loader.destroy('#tab_user_person_messages .card-body');
  },
  populateDetail: (data) => {
    const attachments = $('#md_game_mail_edit #mail_annex_list')

    $('#md_game_mail_edit #mail_title').html(data.Title)
    $('#md_game_mail_edit #mail_sender').html(data.Sender)
    $('#md_game_mail_edit #mail_time_ago').html(data?.TimeAgo ?? 'desconhecido')
    $('#md_game_mail_edit #mail_time_send').html(helper.dateFormatBr(data.SendDate))
    $('#md_game_mail_edit #mail_content').html(data.Content)

    $('#md_game_mail_edit #mail_states').html(`
            <span class="badge badge-light-${data.IsDelete == '0' ? 'primary' : 'danger'} my-1 me-2">
                ${data.IsDelete == '0' ? 'Visível' : 'Deletado'}
            </span>
            <span class="badge badge-light-${data.IsRead == '1' ? 'primary' : 'danger'} my-1">
                ${data.IsRead == '1' ? 'Lido' : 'Não lido'}
            </span>
        `)

    //check if annex list is empty
    if (data.annexList == null || data.annexList.length < 1) {
      $('#md_game_mail_edit #mail_annex').hide()
      return
    }

    attachments.empty()

    $.each(data.annexList, (index, item) => {
      attachments.append(`
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 mt-6" id="attachment-${index}">
                <div class="symbol symbol-50 me-1">
                    <span class="bg-light symbol-label" data-toggle="popover" data-html="true" data-id="${item.ItemID}" data-type="message" style="background-image: url(${item.Icon});"></span>
                    <button class="btn btn-light-danger btn-icon btn-sm" id="delete" style="position: absolute; bottom: 2px;right: 1px;width: 25px;height: 25px;">
                        <span class="svg-icon svg-icon-muted svg-icon-1hx">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.2166 8.50002L10.5166 7.80007C10.1166 7.40007 10.1166 6.80005 10.5166 6.40005L13.4166 3.50002C15.5166 1.40002 18.9166 1.50005 20.8166 3.90005C22.5166 5.90005 22.2166 8.90007 20.3166 10.8001L17.5166 13.6C17.1166 14 16.5166 14 16.1166 13.6L15.4166 12.9C15.0166 12.5 15.0166 11.9 15.4166 11.5L18.3166 8.6C19.2166 7.7 19.1166 6.30002 18.0166 5.50002C17.2166 4.90002 16.0166 5.10007 15.3166 5.80007L12.4166 8.69997C12.2166 8.89997 11.6166 8.90002 11.2166 8.50002ZM11.2166 15.6L8.51659 18.3001C7.81659 19.0001 6.71658 19.2 5.81658 18.6C4.81658 17.9 4.71659 16.4 5.51659 15.5L8.31658 12.7C8.71658 12.3 8.71658 11.7001 8.31658 11.3001L7.6166 10.6C7.2166 10.2 6.6166 10.2 6.2166 10.6L3.6166 13.2C1.7166 15.1 1.4166 18.1 3.1166 20.1C5.0166 22.4 8.51659 22.5 10.5166 20.5L13.3166 17.7C13.7166 17.3 13.7166 16.7001 13.3166 16.3001L12.6166 15.6C12.3166 15.2 11.6166 15.2 11.2166 15.6Z" fill="currentColor"></path>
                                <path opacity="0.3" d="M5.0166 9L2.81659 8.40002C2.31659 8.30002 2.0166 7.79995 2.1166 7.19995L2.31659 5.90002C2.41659 5.20002 3.21659 4.89995 3.81659 5.19995L6.0166 6.40002C6.4166 6.60002 6.6166 7.09998 6.5166 7.59998L6.31659 8.30005C6.11659 8.80005 5.5166 9.1 5.0166 9ZM8.41659 5.69995H8.6166C9.1166 5.69995 9.5166 5.30005 9.5166 4.80005L9.6166 3.09998C9.6166 2.49998 9.2166 2 8.5166 2H7.81659C7.21659 2 6.71659 2.59995 6.91659 3.19995L7.31659 4.90002C7.41659 5.40002 7.91659 5.69995 8.41659 5.69995ZM14.6166 18.2L15.1166 21.3C15.2166 21.8 15.7166 22.2 16.2166 22L17.6166 21.6C18.1166 21.4 18.4166 20.8 18.1166 20.3L16.7166 17.5C16.5166 17.1 16.1166 16.9 15.7166 17L15.2166 17.1C14.8166 17.3 14.5166 17.7 14.6166 18.2ZM18.4166 16.3L19.8166 17.2C20.2166 17.5 20.8166 17.3 21.0166 16.8L21.3166 15.9C21.5166 15.4 21.1166 14.8 20.5166 14.8H18.8166C18.0166 14.8 17.7166 15.9 18.4166 16.3Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>`);

      $(`#attachment-${index} #delete`).on('click', () => {
        message.deleteAttachment(index)
      })
    })

    controls.popover()

    $('#md_game_mail_edit #mail_annex').show()
  },
  deleteAttachment: (id) => {
    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja remover este anexo ? Essa alteração não poderá ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, remova isso !",
      cancelButtonText: "Não, cancele !",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((res) => {
      if (res.isConfirmed) {
        $(`#attachment-${id}`).remove()
      }
    });
  }
}

const bag = {
  list: (page = 1, reload = false) => {
    parameters.game.bag.params.page = reload ? parameters.game.bag.params.page : page
    loader.init('#tab_user_person_bag .card-body');
    axios.get(`${baseUrl}/api/admin/user/game/bag`, parameters.game.bag).then(res => {
      stateGame.bag.data = res.data?.data;
      bag.populate(res.data);
    });
  },
  update: () => { },
  delete: () => { },
  categories: () => {
    axios.get(`${baseUrl}/api/admin/item/categories?sid=${state.sid}`).then(res => {
      if (res.data?.categories?.length <= 0) {
        return;
      }
      var select = $('select[name="bag_category"]');
      select.empty();
      select.append(`<option value="all">Todos</option>`)
      $.each(res.data.categories, (_, category) => {
        select.append(`<option value="${category.ID}">${category.Name}</option>`)
      })
      select.trigger('change');
    });
  },
  populate: (data) => {
    $('#tab_user_person_bag #no_results').show();
    $('#tab_user_person_bag .table-responsive').hide();
    $('#tab_user_person_bag #email_limit_area').hide();

    if (!data.state) {
      alert(data.message)
      return
    }

    const list = $('#bag-list'),
      paginator = $('#bag_paginator'),
      bagData = data.data

    list.empty()
    paginator.empty()

    if (bagData.length < 1) {
      $('#tab_user_person_bag #no_results').show();
      $('#tab_user_person_bag .table-responsive').hide();
      $('#tab_user_person_bag #bag_limit_area').hide();
      loader.destroy('#tab_user_person_bag .card-body');
      return;
    }

    stateGame.message.page = data?.paginator?.current ?? 1

    $('#tab_user_person_bag #no_results').hide();
    $('#tab_user_person_bag .table-responsive').show();
    $('#tab_user_person_bag #bag_limit_area').show();

    var bagItem = (data, last = false) => {
      return `
      <div class="d-flex flex-stack pt-2" id="item-${data.ItemID}">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-40px w-40px h-40px me-3 bg-light" data-toggle="popover" data-html="true" data-id="${data.ItemID}" data-type="bag">
                    <img src="${data.Icon}" onerror="this.src='${baseUrl}/assets/media/icons/original.png';" class="w-100 h-100 rounded">
                    <div style="text-shadow: 0px 0px 3px #000000;" class="position-absolute me-1 end-0 bottom-0 text-white">${data.Count}</div>
                </div>
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-8 text-gray-900 text-hover-primary mb-1">${data?.Name ?? '❓ Desconhecido'}</div>
                    </div>
                    <div class="text-muted fs-7 mb-1">🌍 ID: ${data.ItemID}</div>
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

    $.each(bagData, (_, item) => {
      //populate invoice list
      list.append(bagItem(item, (_ == bagData.length - 1)))

      //find click edit button
      $(`#bag-list #mail-${item.ID} #detail`).click(() => {
        //prevent close on click outside
        //$('#md_game_mail_edit').modal({ backdrop: "static ", keyboard: false }).modal('show')
        //message.populateDetail(item)
      })
    });
    controls.popover();
    paginator.html(data.paginator.rendered);

    loader.destroy('#tab_user_person_bag .card-body');
  },
  detail: (data) => { },
}

const others = {
  updateNickname: () => {
    const data = $("#kt_user_game_nick_update_form").serializeObject();
    var button = document.querySelector("#kt_user_game_nick_update_submit");
    data.id = state.character.id;
    data.sid = state.sid;
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/user/game/nick`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      user.detail()
    });
  },
  completeQuests: () => {
    //check if option is selected
    if ($('#md_enable_mission form select[name="type"]').val() == "") {
      swMessage("warning", "Você não selecionou nenhum tipo de missão para completar.");
      return;
    }

    Swal.fire({
      icon: "question",
      html: "Você tem certeza que deseja <span class=\"text-primary\">completar todas</span> as missões de <span class=\"text-primary\">" + $('#md_enable_mission form select[name="type"] option:selected').text() + "</span>? Essa alteração não poderá ser desfeita.",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, complete tudo !",
      cancelButtonText: "Não, cancele !",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed) return;

      var button = document.querySelector("#btn_submit_quest_complete");
      changeButtonState(button, true);

      axios.post(`${baseUrl}/api/admin/game/user/quest/complete`, {
        uid: state.character.id,
        sid: state.sid,
        type: $('#md_enable_mission form').find('[name="type"]').val()
      }).then((res) => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message);
        changeButtonState(button, false);
      })
    });
  },
  completeLaboratory: () => {
    Swal.fire({
      icon: "question",
      html: `
            Você tem certeza que deseja <span class="text-primary">completar todas</span> as fases do
            <span class="text-primary">laboratório para este personagem</span>?
            `,
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, complete tudo !",
      cancelButtonText: "Não, cancele !",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed) return;

      var button = document.querySelector("#btn_submit_laboratory_complete");
      changeButtonState(button, true);

      axios.post(`${baseUrl}/api/admin/game/user/laboratory/complete`, {
        uid: state.character.id,
        sid: state.sid,
      }).then((res) => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message);
        changeButtonState(button, false);
      })
    });
  },
  banPerson: () => {
    Swal.fire({
      icon: "question",
      html: `Você tem certeza que deseja <span class="text-danger">banir</span> esse jogador?`,
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, banir !",
      cancelButtonText: "Não, cancele !",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (!result.isConfirmed) return;

      const data = $("#md_person_ban form").serializeObject();
      data.id = state.character.id;
      data.sid = state.sid;

      var button = document.querySelector("#btn_submit_person_ban");
      changeButtonState(button, true);

      axios.post(`${baseUrl}/api/admin/user/game/forbid`, data).then((res) => {
        var su = res.data;
        swMessage(su.state ? "success" : "warning", su.message);
        changeButtonState(button, false);
      })
    });
  }
}

const controls = {
  listeners: () => {
    $('#person_id').on('change', function () {
      var server = $('option:selected', this).attr('data-server');
      state.sid = server;
      parameters.game.messages.params.sid = server;
      parameters.game.bag.params.sid = server;



      if (state.sid != undefined) {
        bag.categories();
      }


      $('#s_name').html($('option:selected', this).attr('data-server-name'));

      state.character.id = $(this).val()
      parameters.game.messages.params.uid = $(this).val();
      parameters.game.bag.params.uid = $(this).val();

      if (state.character.id == '') {
        return
      }

      //find all tab and remove active
      $('.menu-link.active').trigger('click');


      //find character info by id from state.user.characters
      const currentCharacter = state.user.characters.find(function (element) {
        return element.UserID == state.character.id
      })

      if (currentCharacter == undefined) {
        $('#character-preview').hide();
        return
      }

      $('#character-passwordTwo').html(currentCharacter?.PasswordTwo ?? 'sem senha');

      $('#md_person_ban input[name="forbid"]').val(currentCharacter.ForbidDate)

      $('.i_grade').css('background-image', `url(${baseUrl}/assets/media/levels/${currentCharacter.Grade}.png)`)

      const objectBuilder = (type, width, height) => {
        var vars = '';
        var src = '';

        const circleOrSimpleImage = (level) => {
          level = parseInt(level)
          if (level >= 5 && level <= 9) {
            return 1
          }
          if (level >= 9 && level <= 11) {
            return 2
          }
          if (level >= 11 && level <= 12) {
            return 3
          }
          if (level >= 12 && level <= 15) {
            return 4
          }
        }

        if (type == 1) {
          vars = `CircleLight=${currentCharacter.server.resource}/image/equip/circlelight`;
          vars += `&CLightNum=${circleOrSimpleImage(currentCharacter?.equipment?.arm?.data?.StrengthenLevel)}`;
          src = `${baseUrl}/assets/media/game/loaders/container_c.swf`
        }

        if (type == 2) {
          vars = `SircleLight=${currentCharacter.server.resource}/image/equip/sinplelight`;
          vars += `&SLightNum=${circleOrSimpleImage(currentCharacter?.equipment?.cloth?.data?.StrengthenLevel)}`;
          src = `${baseUrl}/assets/media/game/loaders/container_s.swf`
        }

        if (type == 3) {
          vars = `Wing=${currentCharacter.server.resource}/image/equip/wing/${currentCharacter?.equipment?.wing?.data?.Pic}`;
          vars += `&WingId=${currentCharacter?.equipment?.wing?.data?.TemplateID}`;
          src = `${baseUrl}/assets/media/game/loaders/container_w.swf`
        }

        return `<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="7road-ddt-game"
          codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
          name="Main" width="${width}" height="${height}" >
            <embed wmode="transparent"
                  flashVars="${vars}"
                  src="${src}" width="${width}"
                  height="${height}" align="middle" quality="hight"
                  name="Main" allowscriptaccess="always"
                  type="application/x-shockwave-flash"
                  pluginspage="http://www.macromedia.com/go/getflashplayer" />
        </object>`;
      }

      if (is_flashplayer()) {
        $('.p_circlelightc').html(objectBuilder(1, 272, 173));
        $('.p_sinplelight').html(objectBuilder(2, 272, 200));
        $('.f_wing').html(objectBuilder(3, 300, 200));
      }

      //set character equipment image
      $.each(currentCharacter.equipment, (index, value) => {
        if (index == 'hair' && !currentCharacter.equipment.head.image.includes('default'))
          value.image = value.image.replace('/b/', '/a/');

        $(`.f_${index} img`).attr('src', value?.image);
      })

      //show character info
      $('#character-preview').show();

      //set link to access account in game
      $('#play_in_account').attr('onclick', `window.open('${baseUrl}/admin/jogar/${state.user.id}/${state.sid}', '_blank')`);

      //message.list()
      //oculta a tab de mensagens
    });


    $('select[name="bag_limit"]').on('change', function () {
      parameters.game.bag.params.limit = $(this).val();
      bag.list();
    });

    $('select[name="bag_type"]').on('change', function () {
      parameters.game.bag.params.type = $(this).val();
      bag.list();
    });

    $('select[name="bag_category"]').on('change', function () {
      parameters.game.bag.params.category = $(this).val();
      bag.list();
    });

    $('input[name="bag_search"]').on('change', function () {
      parameters.game.bag.params.search = $(this).val();
      bag.list();
    });

    //buttons events
    $('a[href="#tab_user_person_messages"').click(() => {
      message.list();
    });
    $('a[href="#tab_user_person_bag"').click(() => {
      bag.list();
    });

    $("#kt_user_game_nick_update_submit").click(() => {
      others.updateNickname();
    });
    $("#disconnect_account").click(() => {
      user.disconnectAccount(state.character.id);
    });

    $('#tab_user_person_messages input[name="search"], #tab_user_person_messages select[name="email_limit"]').on('change', () => {
      parameters.game.messages.params.search = $('#tab_user_person_messages input[name="search"]').val();
      parameters.game.messages.params.limit = $('#tab_user_person_messages select[name="email_limit"]').val();
      message.list();
    });

    $('#person_effect').on('change', function () {
      if ($(this).is(':checked')) {
        $('.p_circlelightc, .f_wing, .p_sinplelight').show();
        return;
      }
      $('.p_circlelightc, .f_wing, .p_sinplelight').hide();
    });

    $('input[name="forbid"]').flatpickr({
      enableTime: true,
      enableSeconds: true,
      dateFormat: "Y-m-d H:i:s",
    });
  },
  popover: () => {
    const _data = (type, id) => {
      if (type == 'message') {
        return stateGame.message.current.annexList[id];
      }
      if (type == 'bag') {
        return stateGame.bag.data.find(item => item.ItemID == id);
      }
    }

    $('[data-toggle="popover"]').popover({
      trigger: 'hover',
      html: true,
      title: function () {
        switch ($(this).data('type')) {
          case 'bag':

            break;

          default:
            break;
        }
        const data = _data($(this).data('type'), $(this).data('id'));
        return `[<span class="text-primary">${data.TemplateID}</span>] ${data.NeedSex != '0' ? `${data.NeedSex == '1' ? '🧢' : '🎀'}` : ''} ${data?.Name}` ?? 'Sem Nome';
      },
      content: function () {
        const data = _data($(this).data('type'), $(this).data('id'));
        const latentEnergy = data.LatentEnergyCurStr.split(',');
        return `<div class='d-flex align-items-center mb-5'>
              <div class='w-40px h-40px me-3 rounded bg-light'>
                  <img class='w-100 h-100' src='${data.Icon}' />
              </div>
              <div class='me-5'>
                <div class='text-muted'>
                  Categoria: <span class='text-gray-800'>${data.CategoryID}</span>
                </div>
                <div class='text-muted'>
                  ${data.CategoryID == '7'
                    ? `Dano: <span class='text-gray-800'>${data.Property7}</span>`
                    : `Count: <span class='text-gray-800'>${data.Count}</span>`}
                </div>
              </div>
              <div class="d-flex flex-column">

              ${data.StrengthenLevel != '0'
                ? `<div class="mb-3"><img src="${baseUrl}/assets/media/game/strengthen/${data.StrengthenLevel}.png" width="91" /></div>`
                : ''}
                <span class="badge badge-light-${data.IsBinds != '0' ? 'danger' : 'success'}">
                  ${data.IsBinds != '0' ? 'Limitado' : 'Ilimitado'}
                </span>
              </div>
            </div>

            ${(data.Attack != '0' || data.Defence != '0' || data.Agility != '0' || data.Luck != '0')
            ? ` <div class='fs-7 text-warning'>Ataque: ${data.Attack} ${data.AttackCompose != '0' ? `(+${data.AttackCompose})` : ''}</div>
                  <div class='fs-7 text-warning'>Defesa: ${data.Defence} ${data.DefendCompose != '0' ? `(+${data.DefendCompose})` : ''}</div>
                  <div class='fs-7 text-warning'>Agilidade: ${data.Agility} ${data.AgilityCompose != '0' ? `(+${data.AgilityCompose})` : ''}</div>
                  <div class='fs-7 text-warning'>Sorte: ${data.Luck} ${data.LuckCompose != '0' ? `(+${data.LuckCompose})` : ''}</div>`
            : ''}

            ${data.Description != '' ? `
                <div class='flex-stack mt-3 mb-3'>
                    <div class='fs-8 fw-bolder'>Descrição:</div>
                    <div class='text-start fs-8 text-gray-800'>${data.Description}</div>
                </div>` : ''}

            ${latentEnergy[0] != '0' ? `
              <div class='fs-7 text-success'>
                <img class="rounded me-1" src="${baseUrl}/assets/media/game/others/potential.png" width="15" height="15" />
                Ataque +${latentEnergy[0]}
              </div>
              <div class='fs-7 text-success'>
                <img class="rounded me-1" src="${baseUrl}/assets/media/game/others/potential.png" width="15" height="15" />
                Defesa +${latentEnergy[1]}
              </div>
              <div class='fs-7 text-success'>
                <img class="rounded me-1" src="${baseUrl}/assets/media/game/others/potential.png" width="15" height="15" />
                Agilidade +${latentEnergy[2]}
              </div>
              <div class='fs-7 text-success'>
                <img class="rounded me-1" src="${baseUrl}/assets/media/game/others/potential.png" width="15" height="15" />
                Sorte +${latentEnergy[3]}
              </div>` : ''}

            <div class="mt-5 mb-n2 fs-8">
              ${data.ValidDate != '0'
            ? `Validade ${data.ValidDate} dia(s)`
            : '<span class="text-warning">Terá efeito permanente</span>'}
            </div>
        `;
      }
    })
  },
  init: () => {
    controls.listeners();

    $('#person_id').trigger('change');
  }
}

controls.init();
