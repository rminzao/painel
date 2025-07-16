// page params
const parameters = {
    activity: {
        params: {
            sid: null,
            type: 0,
        },
    },
}

const activityState = {
    data: null,
    itemSelected: null,
    quality: null,
}

const activity = {
    list: (type) => {
        parameters.activity.params.type = type
        loader.init('#activity_data_body')
        axios.get(`${baseUrl}/api/admin/game/activity`, parameters.activity).then((results) => {
            activity.populate(results.data, type)
            if (type != null) {
                $(`#condition-${activityState.quality}`).trigger('click')
            }
        })
        $('#event_selected_title').html(activityTypeList[type])

    },
    populate: (data, type) => {
        if (!data.state) {
            alert(data.message)
            return
        }

        const list = $('#shop_list'),
            qualityList = $('#qualities_tab'),
            rewards = data.data,
            qualities = data.qualities

        //append list to state
        activityState.data = rewards

        //clear list and pagination
        list.empty()
        qualityList.empty()

        if (rewards.length == 0) {
            $('#not_selected').show()
            $('#activity_data').hide()
            loader.destroy('#activity_data_body')
            return
        }

        var qualityItem = (quality) => {
            return `<li class="nav-item w-md-150px me-0" id="condition-${quality}">
            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_condition_rewards">
            ${quality}° ${(type == 8) ? 'andar' : 'condição'}
            </a></li>`
        }

        $.each(qualities, function (_, quality) {
            qualityList.append(qualityItem(quality))

            qualityList.find(`#condition-${quality}`).on('click', () => {
                $('#md_new_item input[name="Quality"]').val(quality)
                activityState.quality = quality
                activityReward.list(quality)
            })
        })

        $('#not_selected').hide()
        $('#activity_data').show()
        loader.destroy('#activity_data_body')
        return
    },
    populateEdit: (data) => { },
}

const activityReward = {
    create: () => {
        const data = $("#md_new_item form").serializeObject();
        data.sid = parameters.activity.params.sid
        data.ActivityType = parameters.activity.params.type

        var button = document.querySelector('#md_new_item form button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/game/activity`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_new_item #TemplateID').val('').trigger('change')
                $('#md_new_item').modal('hide')
                activity.list(su.type)
            }
        })
    },
    update: () => {
        const data = $("#md_edit_item form").serializeObject();
        data.sid = parameters.activity.params.sid

        var button = document.querySelector('#md_edit_item form button[type="button"]');
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/game/activity`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_edit_item').modal('hide')
                activity.list(su.type)
            }
        })
    },
    list: (quality) => {
        const rewards = activityState.data.filter(reward => reward.Quality == quality)
        activityReward.populate(rewards)
    },
    delete: (id) => {
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
                axios.delete(`${baseUrl}/api/admin/game/activity`, {
                    params: {
                        sid: parameters.activity.params.sid,
                        id: id,
                    }
                }).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    if (su.state) {
                        activity.list(parameters.activity.params.type)
                    }
                })
            }
        })
    },
    populate: (rewards) => {
        const list = $('#rewards_list')

        //clear list
        list.empty()

        if (rewards.length == 0) {
            list.hide()
            $('#no_rewards').show()
            return
        }

        var rewardItem = (reward) => {
            return `<div class="d-flex flex-stack mb-4" id="reward-${reward.ID}">
            <div class="d-flex align-items-center">
                <div class="w-40px h-40px me-3 rounded bg-light">
                    <img src="${reward.Item?.Icon}" class="w-100">
                </div>
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-8 text-gray-800 fw-bolder">${reward.Item?.Name ?? 'Item desconhecido'}</div>
                        <div class="ms-3">
                            ${reward.Count != '1' ? `<div class="badge badge-light-primary me-2">x${reward.Count}</div>` : ''}
                            <div class="badge badge-light-primary me-2">🎲 ${reward.Probability}</div>
                            ${reward.canRepeat == '0' ? `<div class="badge badge-light-danger me-2">não repete</div>` : ''}
                            <div class="badge badge-light-${reward.IsBind == '0' ? 'success' : 'danger'}">
                                ${reward.IsBind == '0' ? 'Ilimitado' : 'Limitado'}
                            </div>
                        </div>
                    </div>
                    <div class="text-muted">${reward.TemplateID}</div>
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

        $.each(rewards, function (_, reward) {
            list.append(rewardItem(reward))

            list.find(`#reward-${reward.ID} #edit`).click(() => {
                $('#md_edit_item').modal('show')
                activityReward.populateEdit(reward)
            })

            list.find(`#reward-${reward.ID} #delete`).click(() => {
                activityReward.delete(reward.ID)
            })
        })

        list.show()
        $('#no_rewards').hide()
    },
    populateEdit: (reward) => {
        var sex = ''
        if (reward.Item.NeedSex == "1") {
            sex = '🧢'
        }
        if (reward.Item.NeedSex == "2") {
            sex = '🎀'
        }

        $('#md-edit-annex-name').html(`${reward.Item?.Name} ${sex}` ?? '')
        $('#md-edit-annex-pic').attr('src', reward.Item?.Icon ?? '')
        $('#md-edit-annex-id').html(reward.TemplateID ?? '')

        $.each(reward, (key, value) => {
            if (key == 'StrengthLevel') {
                $(`#md_edit_item form select[name="${key}"]`).val(value).trigger('change')
                return
            }

            if (key == 'IsBind' ||
                key == 'canRenew' ||
                key == 'canTransfer' ||
                key == 'canRepeat') {
                $(`#md_edit_item form input[name="${key}"]`).prop('checked', value == '1' ? true : false)
                return
            }

            $(`#md_edit_item form input[name="${key}"]`).val(value)
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
    destroy() {
        var target = document.querySelector(`.blockui-overlay`);
        target.remove()
    }
}

const init = () => {
    //get server id by select
    parameters.activity.params.sid = $('select[name="sid"]').val()

    //set sid from select change
    $('select[name="sid"]').on('change', function () {
        parameters.activity.params.sid = $(this).val()
        activity.list()
    })

    //item search
    $('#md_new_item #TemplateID').select2({
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
                    sid: parameters.activity.params.sid,
                    search
                };
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

    function restoreDefaultModal() {
        $('#md-annex-pic, #md-annex-name, #md-annex-id, #md-item-info').hide()
        $('#md-annex-attribute-area').show();
        $('#md-annex-level-area').removeClass('d-none');
        $('#md-annex-amount-area').removeClass('col-12');
        $('#md-annex-amount-area').addClass('col-6');
    }

    $('#md_new_item #TemplateID').on('change', () => {
        restoreDefaultModal()
        var templateID = $('#md_new_item #TemplateID').val()
        $.get(`${baseUrl}/api/admin/item`, {
            sid: parameters.activity.params.sid,
            search: {
                term: templateID
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

            if (item.CanCompose == '0') {
                $('#md-annex-attribute-area').hide();
            }

            if (item.CanStrengthen == '0') {
                $('#md-annex-level-area').addClass('d-none');
                $('#md-annex-amount-area').removeClass('col-6');
                $('#md-annex-amount-area').addClass('col-12');
            }

            $('#md-item-info').show()
            $('#md-annex-pic').show()
            $('#md-annex-name').show()
            $('#md-annex-id').show()
        })
    })
}

init()
