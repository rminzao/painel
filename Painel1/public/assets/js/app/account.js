const referrals = {
  _ui: {
    main: '#referred_body',
    table: $('#table_referred_list'),
    list: $('#table_referred_list tbody'),
    no_results: $('#no_results'),
    copy: {
      input: document.querySelector("#kt_referral_link_input"),
      button: document.querySelector("#kt_referral_program_link_copy_btn"),
    }
  },

  list: () => {
    helper.loader(referrals._ui.main, true);
    axios
      .get(`${baseUrl}/api/account/referrals`)
      .then(res => referrals.populate(res?.data));
  },

  populate: data => {
    referrals._ui.list.empty();
    helper.loader(referrals._ui.main, false);

    if (!(data?.results >= 1)) {
      referrals._ui.no_results.show();
      referrals._ui.table.hide();
      return;
    }

    const build = (item) => {
      return `<tr>
      <td class="ps-9">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-50px overflow-hidden me-3">
                <img class="h-40px w-40px rounded" src="${item?.referenced?.avatar ?? '❓ Desconhecido'}" alt="">
            </div>
            <div class="d-flex flex-column">
                <span class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                  ${item?.referenced?.name ?? '❓ Desconhecido'}
                </span>
                <span class="fs-7">${item?.created_at}</span>
            </div>
        </div>
      </td>
      <td>${item?.last_update}</td>
      <td class="text-dark">
        <div class="d-flex">
          <div class="d-flex align-items-center highlight p-0 px-3">
            <div class="w-30px h-30px ms-n2 me-1" style=" background-image: url('${baseUrl}/assets/media/others/dDFCykV.png'); background-size: cover; "></div>
            <span class="fw-bolder">${item?.points}</span>
          </div>
        </div>
      </td>
      <td class="text-dark">
        <div class="d-flex">
          <div class="d-flex align-items-center highlight p-0 py-1 px-4">
            <div class="w-20px h-20px ms-n2 me-1" style=" background-image: url('${baseUrl}/assets/media/icons/cupon.png'); background-size: cover; "></div>
            <span class="fw-bolder">${item?.money}</span>
          </div>
        </div>
      </td>
  </tr>`;
    }

    $.each(data?.referenced, (_, info) => {
      referrals._ui.list.append(build(info));
    });

    referrals._ui.table.show();
    referrals._ui.no_results.hide();
  },

  //copy invite link
  copy: () => {
    var e, r;
    e = document.querySelector("#kt_referral_program_link_copy_btn"),
      r = document.querySelector("#kt_referral_link_input"),
      new ClipboardJS(e).on("success", (function (s) {
        var n = e.innerHTML;
        r.classList.add("bg-success"), r.classList.add("text-inverse-success"), e.innerHTML = "Copiado!",
          setTimeout((() => {
            e.innerHTML = n, r.classList.remove("bg-success"), r.classList.remove("text-inverse-success")
          }), 3e3),
          s.clearSelection()
      }))
  },

  init: () => {
    referrals.list();
    referrals.copy();
  }
}

KTUtil.onDOMContentLoaded(() => referrals.init());

