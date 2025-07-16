const parameters = {
  map: {
    params: {
      sid: '',
      page: 1,
      limit: 10,
      sort: 'ID',
      order: 'asc',
      search: '',
    }
  }
}

const stateMap = {
  positions: [],
  background: {
    x: 0,
    y: 0,
  },
  sound: null
}

const map = {
  list: (page = 1) => {
    parameters.map.params.page = page;
    helper.loader('#map_list_body', true)
    axios.get(`${baseUrl}/api/admin/game/map`, parameters.map).then(res => {
      map.populate(res.data)
    })
  },
  create: () => { },
  update: () => {
    const data = $("#tab_detail form").serializeObject();
    data.sid = parameters.map.params.sid;

    let teams = [];
    stateMap.positions.forEach(position => {
      if (teams.indexOf(position.team) === -1) {
        teams.push(position.team);
      }
    })

    let positions = [];
    teams.forEach(team => {
      let teamPositions = [];
      stateMap.positions.forEach(position => {
        if (position.team === team) {
          teamPositions.push(`${position.x},${position.y}`);
        }
      })
      positions.push(teamPositions.join('|'));
    })

    data.PosX = positions[0];
    data.PosX1 = positions[1];

    var button = document.querySelector("#button_map_update");
    changeButtonState(button, true);

    axios.put(`${baseUrl}/api/admin/game/map`, data).then((res) => {
      var su = res.data;
      swMessage(su.state ? "success" : "warning", su.message);
      changeButtonState(button, false);
      if (su.state)
        map.list(parameters.map.params.page);
    })
  },
  delete: (id) => { },
  populate: (data) => {
    const { maps, pagination } = data;

    const mapList = $('#map_list'),
      not_result = $('#not_results'),
      paginator = $('#paginator'),
      footer = $('#map_list_footer');

    if (maps.length <= 0) {
      not_result.show();
      mapList.hide();
      paginator.hide();
      footer.hide();
      helper.loader('#map_list_body', false);
    }

    const mapItem = (info, last = false) => {
      return `<div class="d-flex flex-stack pt-2" id="mapItem-${info.ID}">
                <div class="d-flex align-items-center">
                    <div class="w-60px h-40px me-3 rounded bg-light">
                        <img src="${info.bg}" class="w-100 h-100 rounded">
                    </div>
                    <div>
                        <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">${info.Name} <span class="text-primary"></span></a>
                        <div class="text-muted fs-7 mb-1">ID: ${info.ID}</div>
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
            ${!last ? '<div class="pt-2 separator separator-dashed"></div>' : ''}`
    }

    mapList.empty();

    $.each(maps, (index, value) => {
      mapList.append(mapItem(value, (index == maps.length - 1)));

      $(`#mapItem-${value.ID} #edit`).on('click', () => {
        map.populateDetail(value);
      })
    });

    paginator.html(pagination.render)

    not_result.hide();
    mapList.show();
    paginator.show();
    footer.show();
    helper.loader('#map_list_body', false);
  },
  populateDetail: (data) => {
    $(`#map_list #edit_name span`).html('');
    $(`#mapItem-${data.ID} #edit_name span`).html(` editando...`);

    $("#map_foreground, #map_deadground").hide();

    $("#map_foreground").attr('src', null);
    $("#map_deadground").attr('src', null);

    $.each(data, (index, value) => {
      $('#tab_detail').find(`[name="${index}"]`).val(value);
    })

    controls.soundMap(false)

    stateMap.sound = data.backgroundMusic;
    stateMap.positions = [];
    const populateTeam = (positions, team, isDead = false) => {
      if (positions.length == 0) {
        return
      }

      $(`#team${team}_playerList`).empty();

      $.each(positions.split('|'), (_, value) => {
        if (
          (team == 1 && stateMap.positions.length >= 4) ||
          (team == 2 && stateMap.positions.length >= 8)
        ) return;

        var currentId = stateMap.positions.length + 1;
        const position = value.split(',');
        stateMap.positions.push({
          x: position[0],
          y: position[1],
          team: team,
          id: currentId
        })

        var x = (position[0] / data.ForegroundWidth) * 100;
        var y = (position[1] / data.ForegroundHeight) * 100;

        stateMap.background.x = data.ForegroundWidth;
        stateMap.background.y = data.ForegroundHeight;

        var player = $(`
          <div id="player_pos_${currentId}"
            class="position-absolute team_player${team} w-40px h-40px mt-n10 ms-n6 rounded"
            style="z-index:3;"
            draggable="true"
            ondragstart="drag.start(event)"
            onmouseover="player.mouseOver(this)"
            onmouseout="player.mouseOut(this)">
              <span class="position-absolute badge badge-light fs-9 me-auto px-2 py-1 ms-n2 mt-n6" style="transform:scaleX(${(position[0] > (data.ForegroundWidth / 2) ? '-1' : '1')});">
                Player ${currentId}
              </span>
          </div>`);

        player.css({
          'left': x + '%',
          'top': y + '%',
          'transform': 'scaleX(' + (position[0] > (data.ForegroundWidth / 2) ? '-1' : '1') + ')',
        });

        $("#player_positions").append(player);

      });

      $(`#not_players_t${team}`).hide();
    }

    $("#map_background").attr("src", data.bg);

    if (data.ForePic != null) {
      $("#map_foreground").attr("src", data.fg)
      $("#map_foreground").show()
    }

    if (data.DeadPic != null) {
      $("#map_deadground").attr("src", data.dg)
      $("#map_deadground").show()
    }

    $("#player_positions, #team1_playerList, #team2_playerList").empty();

    populateTeam(data?.PosX ?? '', 1);
    populateTeam(data?.PosX1 ?? '', 2);

    player.populateList()

    $('#not_selected').hide();
    $('#area_map').show();
  },
}

const controls = {
  cursor: (e) => {
    var pos = $(e.target).offset();
    var x = e.pageX - pos.left;
    var y = e.pageY - pos.top;

    //Position ratio
    const xPos = x / $(e.target).width();
    const yPos = y / $(e.target).height();

    let _position = `<span class="badge badge-light fw-bolder me-auto px-4 py-3">x: ${Math.ceil(xPos * stateMap.background.x)} y: ${Math.ceil(yPos * stateMap.background.y)}</span>`;

    const infoElement = document.getElementById('mouse_pointer_current');
    infoElement.innerHTML = _position;
    infoElement.style.top = y + "px";
    infoElement.style.left = x + 20 + "px";

    $('#area_map #current_position').html(`
      posição atual
      <span class="text-dark">
      x:[<span class="text-primary">${Math.ceil(xPos * stateMap.background.x)}</span>]
      y:[<span class="text-primary">${Math.ceil(yPos * stateMap.background.y)}</span>]
      </span>
    `);
  },
  listeners: () => {
    parameters.map.params.sid = $('select[name="sid"]').val()

    $('select[name="sid"]').on('change', function () {
      parameters.map.params.sid = $(this).val()
      map.list()
    })

    $('input[name="search"]').on('change', function () {
      parameters.map.params.search = $(this).val()
      map.list()
    })

    $('#map_list_footer select[name="limit"]').on('change', function () {
      parameters.map.params.limit = $(this).val()
      map.list()
    })
  },
  popover: () => {
    $('[data-toggle="popover"]').popover({
      trigger: 'hover',
      html: true,
      title: function () {
        const data = stateMap.positions.find(x => x.id == $(this).data('id'));
        return `Jogador ${data.id}`;
      },
      content: function () {
        const data = stateMap.positions.find(x => x.id == $(this).data('id'));
        return `
            <div class='d-flex flex-stack mb-3'>
                <div class='fw-bold pe-10 text-gray-600 fs-7'>x:</div>
                <div class='text-end fw-bolder fs-6 text-gray-800'>${data.x}</div>
            </div>
            <div class='d-flex flex-stack mb-3'>
                <div class='fw-bold pe-10 text-gray-600 fs-7'>y:</div>
                <div class='text-end fw-bolder fs-6 text-gray-800'>${data.y}</div>
            </div>
        `;
      }
    })
  },
  addTeam: (team) => {
    if (stateMap.positions.length >= 8) {
      swMessage('warning', 'Não é possível adicionar mais de 8 jogadores');
      return;
    }

    //check if current team have more than 4 players
    const currentTeam = stateMap.positions.filter(x => x.team == team);
    if (currentTeam.length >= 4) {
      swMessage('warning', `Não é possível adicionar mais de <b>4 jogadores</b> no <b>time ${team}</b>`);
      return;
    }

    //get disponible id for new player
    let id = 1;
    while (stateMap.positions.find(x => x.id == id)) {
      id++;
    }

    const currentId = id;
    const position = [10, 10];

    stateMap.positions.push({
      id: currentId,
      team: team,
      x: position[0],
      y: position[1],
    })

    var player = $(`
      <div id="player_pos_${currentId}"
        class="position-absolute team_player${team} w-40px h-40px mt-n10 ms-n6 rounded"
        style="z-index:3;"
        draggable="true"
        ondragstart="drag.start(event)"
        onmouseover="player.mouseOver(this)"
        onmouseout="player.mouseOut(this)">
          <span class="position-absolute badge badge-light fs-9 me-auto px-2 py-1 ms-n2 mt-n6" style="transform:scaleX(${(position[0] > (stateMap.background.x / 2) ? '-1' : '1')});">
            Player ${currentId}
          </span>
      </div>`);

    player.css({
      'left': position[0] + '%',
      'top': position[1] + '%',
      'transform': 'scaleX(' + (position[0] > (stateMap.background.x / 2) ? '-1' : '1') + ')',
    });

    $("#player_positions").append(player);

    $(`#not_players_t${team}`).hide();

    //populate player list
    $(team == 1 ? "#team1_playerList" : "#team2_playerList").append(`<div class="d-flex flex-stack" id="list_player${currentId}">
          <div class="d-flex align-items-center">
              <div class="w-40px h-40px me-3 rounded bg-light">
                  <img src="${baseUrl}/assets/media/others/player${team}.png" class="w-100 h-100 rounded">
              </div>
              <div>
                  <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">Player ${currentId}</a>
                  <div class="text-muted fs-7 mb-1" id="positions">x: ${position[0]} y:${position[1]}</div>
              </div>
          </div>
          <div class="d-flex align-items-end ms-2">
              <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" onclick="controls.removeTeam(${currentId})">
                  <span class="svg-icon svg-icon-3">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                          <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                      </svg>
                  </span>
              </button>
          </div>
      </div>`);
  },
  removeTeam: (id) => {
    const player = stateMap.positions.find(x => x.id == id);
    const index = stateMap.positions.indexOf(player);
    stateMap.positions.splice(index, 1);

    $(`#list_player${id}`).remove();
    $(`#player_pos_${id}`).remove();

    //check if current player team not have players
    const currentTeam = stateMap.positions.filter(x => x.team == player.team);
    if (currentTeam.length == 0) {
      $(`#not_players_t${player.team}`).show();
    }

    swMessage('success', `Jogador ${id} removido com sucesso do time ${player.team}`);
  },
  soundMap: (play = true) => {
    var player = flvjs.createPlayer({
      type: 'flv',
      url: stateMap.sound
    });

    player.attachMediaElement(document.getElementById('videoElement'));

    if (play) {
      player.load();
      player.play();
      return;
    }

    player.pause();
    player.unload();
    player.detachMediaElement();
    player.destroy();
    player = null;
    return;

  },
  init: () => {
    controls.listeners()
    map.list()
  }
}

const drag = {
  start: (event) => {
    var style = window.getComputedStyle(event.target, null);
    var str = (parseInt(style.getPropertyValue("left")) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top")) - event.clientY) + ',' + event.target.id;
    event.dataTransfer.setData("Text", str);
  },
  drop: (event) => {
    var offset = event.dataTransfer.getData("Text").split(',');
    var playerObject = document.getElementById(offset[2]);

    posX = event.clientX + parseInt(offset[0], 10);
    posY = event.clientY + parseInt(offset[1], 10);

    playerObject.style.left = posX + 'px';
    playerObject.style.top = posY + 'px';

    var xPos = (posX / $(playerObject).parent().width()) * stateMap.background.x;
    var yPos = (posY / $(playerObject).parent().parent().height()) * stateMap.background.y;

    var player_pos_id = playerObject.id.split('_')[2];
    var playerIndex = stateMap.positions.findIndex(x => x.id == player_pos_id);

    stateMap.positions[playerIndex].x = Math.ceil(xPos);
    stateMap.positions[playerIndex].y = Math.ceil(yPos);

    $(`#list_player${player_pos_id} #positions`).html(`x: ${Math.ceil(xPos)} y:${Math.ceil(yPos)}`);

    playerObject.style.transform = 'scaleX(' + (Math.ceil(xPos) > (stateMap.background.x / 2) ? '-1' : '1') + ')';

    $(`#${offset[2]} span`).css('transform', `scaleX(${(Math.ceil(xPos) > (stateMap.background.x / 2) ? '-1' : '1')})`)
    event.preventDefault();
    return false;
  },
  end: (event) => {
    event.preventDefault();
    return false;
  }
}

const player = {
  mouseOver: (event) => {
    $('#mouse_pointer_current').hide();
    $(event).css('z-index', '1000');
    $(event).addClass('border border-primary');
    $('#mouse_pointer_current').hide()
  },
  mouseOut: (event) => {
    $('#mouse_pointer_current').show();
    $(event).css('z-index', '3');
    $(event).removeClass('border border-primary');
    $('#mouse_pointer_current').show()
  },
  populateList: () => {
    //reorder array by id stateMap.positions
    var list = stateMap.positions;

    $.each(list, (_, data) => {
      $(`#team${data.team}_playerList`).append(`<div class="d-flex flex-stack" id="list_player${data.id}">
        <div class="d-flex align-items-center">
            <div class="w-40px h-40px me-3 rounded bg-light">
                <img src="${baseUrl}/assets/media/others/player${data.team}.png" class="w-100 h-100 rounded">
            </div>
            <div>
                <a href="javascript:;" id="edit_name" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">Player ${data.id}</a>
                <div class="text-muted fs-7 mb-1" id="positions">x: ${data.x} y:${data.y}</div>
            </div>
        </div>
        <div class="d-flex align-items-end ms-2">
            <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger" onclick="controls.removeTeam(${data.id})">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>`);
    });
  }
}

controls.init()
