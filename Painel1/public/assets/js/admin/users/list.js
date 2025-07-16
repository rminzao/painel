const parameters = {
  user: {
    params: {
      filter: 'all',
      search: null,
      limit: 5,
      page: 1,
    }
  }
};

const user = {
  list: (page = 1) => {
    helper.loader('#user_body', true);
    parameters.user.params.page = page;
    axios.get(`${baseUrl}/api/admin/web/user`, parameters.user).then(res => {
      user.populate(res.data);
    });
  },
  delete: (id) => { },
  populate: (data) => {
    const list = $('#user_list'),
      no_result = $('#no_result'),
      paginator = $('#paginator'),
      footer = $('#user_list_footer');

    if (data?.data?.length <= 0) {
      no_result.show();
      list.hide();
      footer.hide();
      helper.loader('#user_body', false);
      $('#table_user_list').hide();
      return;
    }

    var userItem = (info) => {
      return `<tr>
      <td>
          <div class="d-flex align-items-center">
              <div class="symbol symbol-40px me-3" onclick="window.open('${baseUrl}/admin/users/${info.id}', '_blank')">
                  <img class="h-40px w-40px rounded" src="${info?.avatar ?? baseUrl + '/assets/media/avatars/default.png'}" alt="" />
                  <div class="position-absolute translate-middle bottom-0 start-100 mb-0 bg-${info.active == '0' ? 'danger' : 'success'} rounded-circle border border-1 border-light h-10px w-10px" style="border-color: var(--bs-body-bg)!important;"></div>
              </div>
              <div class="d-flex flex-column">
                  <a href="${baseUrl}/admin/users/${info.id}" target="_blank" class="fs-8 text-gray-800 text-hover-primary mb-1 d-flex cursor-pointer">
                      ${info.first_name} - ${info.last_name}
                  </a>
                  <span class="text-muted fs-7 mb-1">${info?.id != null ? `🌍 ID: ${info?.id}` : '<span class="text-danger">Não possui conta no site</span>'} </span>
              </div>
          </div>
      </td>
      <td>
          <div class="badge badge-light-${info.active == '1' ? 'success' : 'danger'}">
              ${info.active == '1' ? 'Ativo' : 'Banido'}
          </div>
      </td>
      <td>
          ${info.created_at}
      </td>
      <td class="text-end">
        <div class="d-flex align-items-end float-end ms-2">
          <button type="button" title="Editar" ${info?.id != null ? `onclick="window.open('${baseUrl}/admin/users/${info.id}', '_blank')"` : ''}
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top"
            class="btn btn-icon btn-${info?.id != null ? 'active-light-primary' : 'light-danger disabled'} w-30px h-30px me-2">
            <span class="svg-icon svg-icon-3" id="edit">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z" fill="currentColor"/>
                <path d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z" fill="currentColor"/>
              </svg>
            </span>
          </button>
        </div>
      </td>
  </tr>`;
    };

    list.empty();
    paginator.empty();

    (data.paginator.rendered == null) ? paginator.hide() : paginator.show();
    (data.paginator.rendered == null) ? footer.hide() : footer.show();

    $.each(data.data, (_, info) => {
      list.append(userItem(info));
    });

    paginator.html(data.paginator.rendered)
    footer.show();
    list.show();
    no_result.hide();
    helper.loader('#user_list', false);
    $('#table_user_list').show();
  },
}

const controls = {
  listeners: () => {
    $('input[name="search"]').on('change', function () {
      parameters.user.params.search = $(this).val();
      user.list();
    });
    $('select[name="filter"]').on('change', function () {
      parameters.user.params.filter = $(this).val();
      user.list();
    });
    $('select[name="limit"]').on('change', function () {
      parameters.user.params.limit = $(this).val();
      user.list();
    });
  },
  init: () => {
    controls.listeners();
    user.list();
  },
}

controls.init();
