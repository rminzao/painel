const state = {
  sid: null,
  server: null,
  page: 1,
  limit: 10,
  users: [],
}

const user = {
  list(page = state.page) {
    loader.init('#kt_content .card-body')

    axios.get(`${baseUrl}/api/admin/game/user`, {
      params: {
        sid: state.sid,
        page: page,
        limit: state.limit,
        search: $('input[name="search"]').val(),
        state: $('select[name="state"]').val(),
      }
    }).then(res => {
      state.users = res.data.users
      state.page = res.data.paginator.current

      //populate list
      this.populate()

      //populate pagination
      $('#item_paginator').html(res.data.paginator.rendered)
    });
  },
  populate() {
    $('#item_paginator').empty()
    $('#user_list').empty()
    if (state.users.length < 1) {
      $('#no_results').show()
      $('#table_user_list').hide()
      loader.destroy('#kt_content .card-body')
      return;
    }

    var userCreate = (info) => {
      return `<tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px me-3" ${info?.web?.id != null ? `onclick="window.open('${baseUrl}/admin/users/${info.web.id}', '_blank')"` : ''}>
                        <img class="h-40px w-40px rounded" src="${info?.web?.avatar ?? baseUrl + '/assets/media/avatars/default.png'}" alt="" />
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-0 bg-${info.State == '0' ? 'danger' : 'success'} rounded-circle border border-1 border-light h-10px w-10px" style="border-color: var(--bs-body-bg)!important;"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <a ${info?.web?.id != null ? `href="${baseUrl}/admin/users/${info.web.id}" target="_blank"` : ''} class="fs-8 text-gray-800 text-hover-primary mb-1 d-flex cursor-pointer">
                            ${info.NickName} - ${info.UserID}
                        </a>
                        <span class="text-muted fs-7 mb-1">${info.web?.id != null ? `🌍 ID: ${info.web?.id}` : '<span class="text-danger">Não possui conta no site</span>'} </span>
                    </div>
                </div>
            </td>
            <td>
                <div class="badge badge-light-${info.State == '1' ? 'success' : 'danger'}">
                    ${info.State == '1' ? 'Conectado' : 'Desconectado'}
                </div>
            </td>
            <td>
                ${info.LastDate}
            </td>
            <td class="text-end">
              <div class="d-flex align-items-end float-end ms-2">
                <button title="Detalhes" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                  data-bs-placement="top" class="btn btn-icon btn-${info?.web?.id != null ? 'active-light-primary' : 'light-danger disabled'} w-30px h-30px me-2">
                  <span class="svg-icon svg-icon-3" id="edit">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z" fill="currentColor"/>
                          <path opacity="0.3" d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z" fill="currentColor"/>
                          <path opacity="0.3" d="M15 17H9V20H15V17Z" fill="currentColor"/>
                      </svg>
                  </span>
                </button>
                <button type="button" title="Editar" ${info?.web?.id != null ? `onclick="window.open('${baseUrl}/admin/users/${info.web.id}', '_blank')"` : ''}
                  data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top"
                  class="btn btn-icon btn-${info?.web?.id != null ? 'active-light-primary' : 'light-danger disabled'} w-30px h-30px me-2">
                  <span class="svg-icon svg-icon-3" id="edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path opacity="0.3" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z" fill="currentColor"/>
                      <path d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z" fill="currentColor"/>
                    </svg>
                  </span>
                </button>
                <button title="Acessar conta" ${info?.web?.id != null ? `onclick="window.open('${baseUrl}/admin/jogar/${info.web.id}/${info.server}', '_blank')"` : ''}
                  data-bs-toggle="tooltip" data-bs-trigger="hover"
                  data-bs-dismiss="click" data-bs-placement="top" class="btn btn-icon btn-${info?.web?.id != null ? 'active-light-primary' : 'light-danger disabled'} w-30px h-30px">
                  <span class="svg-icon svg-icon-3" id="edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M16.9 10.7L7 5V19L16.9 13.3C17.9 12.7 17.9 11.3 16.9 10.7Z" fill="currentColor"/>
                    </svg>
                  </span>
                </button>
              </div>
            </td>
        </tr>`;
    }

    state.users.forEach(user => {
      $('#table_user_list tbody').append(userCreate(user))
    })

    $('[data-bs-toggle="tooltip"]').tooltip();

    $('#no_results').hide()
    $('#table_user_list').show()

    loader.destroy('#kt_content .card-body')
  },
  updateRanking: () => {
    Swal.fire({
      icon: "question",
      html: `Você tem certeza que deseja atualizar ranking do servidor <span class="fw-bolder text-primary">${state.server}</span> ?`,
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "Sim, atualize isso!",
      cancelButtonText: "Não, cancele!",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        var button = document.querySelector("#button_update_ranking");
        changeButtonState(button, true);
        axios.get(`${baseUrl}/api/admin/user/ranking-update`, {
          params: {
            sid: state.sid
          }
        }).then(res => {
          changeButtonState(button, false);
          var su = res.data;
          swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
        })
      }
    });
  }
}

const controls = {
  createListeners() {
    $('select[name="sid"]').on('change', () => {
      state.sid = $('select[name="sid"]').val()
      state.server = $('select[name="sid"] option:selected').text()
      user.list()
    })

    $('select[name="state"]').on('change', () => {
      user.list()
    })

    $('input[name="search"]').on('change', () => {
      user.list()
    })
  }
}

const loader = {
  init(element) {
    var target = document.querySelector(element);
    var blockUI = new KTBlockUI(target);

    blockUI.destroy();
    blockUI.block();
  },
  destroy(element) {
    var target = document.querySelector('.blockui-overlay');
    target.remove()
  }
}

function init() {
  state.sid = $('select[name="sid"]').val()
  state.server = $('select[name="sid"] option:selected').text()
  controls.createListeners()
  user.list()
}

init()
