const stateRanking = {
    sid: null,
    page: 1,
};

const ranking = {
    list: (page = 1) => {
        helper.loader('#ranking_body')
        axios.get(`${baseUrl}/api/ranking`, {
            params: {
                page: page,
                sid: stateRanking.sid,
            }
        }).then(res => {
            ranking.populate(res.data); 
        })
    },
    populate: (data) => {
        var ranking = $("#ranking_list tbody");

        var rankingItem = (value) => {
            var rankingIcon = () => {
                switch (value.position) {
                    case 1:
                        return `<div class="icon-ranking gold" style="width: 24px;height: 20px;"></div>`;
                    case 2:
                        return `<div class="icon-ranking silver" style="width: 24px;height: 20px;"></div>`;
                    case 3:
                        return `<div class="icon-ranking bronze" style="width: 24px;height: 20px;"></div>`;
                    default:
                        return `<div class="fs-6 fw-bold"> <span class="text-primary">${value.position}</span> -</div>`;
                }
            };

            return `<tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                        ${value?.border ? `
                            <div style=" background-image: url(${baseUrl}/assets/media/borders/${value.border}); background-size: cover; width: 120%; height: 120%; position: absolute; margin-top: -10%; margin-left: -10.2%; "></div>` : ''}
                            <img src="${value?.avatar ?? baseUrl + '/assets/media/avatars/default.png'}" onerror="this.src='${baseUrl}/assets/media/icons/original.png';" alt="" />
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-0 bg-${value.State == '0' ? 'danger' : 'success'} rounded-circle border border-1 border-light h-10px w-10px" style="border-color: var(--bs-body-bg)!important;"></div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="${(role >= 2 && value.id) ? baseUrl + '/admin/users/' + value?.id : 'javascript:;'}" ${(role >= 2 && value.id) ? 'target="_blank"' : ''} class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                                <div class="symbol symbol-20px overflow-hidden me-2">
                                    ${rankingIcon()}
                                </div>
                                ${value.NickName}
                            </a>
                            <span fs-7>nv. ${value.Grade}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex;">
                        <div
                            style="
                                margin-right: 6%;
                                width: 13px;
                                height: 17px;
                                background-size: cover !important;
                                background: url(data:image/png;base64,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);
                            "
                        ></div>
                        ${value.FightPower}
                    </div>
                </td>
                <td class="text-end">
                    <div class="d-flex flex-column w-100 me-2">
                        <div class="d-flex flex-stack mb-2">
                            <span class="text-muted me-2 fs-7 fw-bold">${value.WinRate}%</span>
                        </div>
                        <div class="progress h-6px w-100">
                            <div class="progress-bar bg-primary" role="progressbar" style="width:${value.WinRate}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </td>
            </tr>`;
        };

        if(data?.data.length <= 0){
          ranking.hide();
          $('#no_result').show();
          helper.loader('#ranking_body', false);
          $('#ranking_paginator').hide();
          $('#ranking_body .table-responsive').hide();
          return;
        }

        ranking.empty();
        $.each(data.data, (_, player) => {
            ranking.append(rankingItem(player));
        });

        $('#ranking_paginator').html(data.paginator.rendered);
        $('#ranking_body .table-responsive').show();
        $('#no_result').hide();
        $('#ranking_paginator').show();
        helper.loader('#ranking_body', false);
    }
}

const init = () => {
    stateRanking.sid = $('select[name="sid"]').val();

    $('select[name="sid"]').on('change', () => {
        stateRanking.sid = $('select[name="sid"]').val()
        ranking.list(1)
    })

    ranking.list();
}

init();
