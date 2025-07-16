// page params
const parameters = {
    event: {
        params: {
            sid: null,
            page: 1,
            search: '',
            limit: 100,
        },
    },
    conditions: {
        params: {
            sid: null,
            page: 1,
            activityType: null,
            limit: 100,
            onclick: 'conditions.list',
            subActivityType: null,
        }
    },
    rewards: {
        params: {
            sid: null,
            activityType: null,
            subActivityType: null,
        }
    }
}

const events = {
    list(page = 1) {
        parameters.event.params.page = page
        loader.init('#events_body')
        axios.get(`${baseUrl}/api/admin/game/event/activities`, parameters.event).then((results) => {
            this.populate(results.data)
        })
    },
    populate(data) {
        if (!data.state) {
            alert(data.message)
            return
        }

        const list = $('#event_list'),
            paginator = $('#item_paginator'),
            events = data.data

        //clear list and pagination
        list.empty()
        paginator.empty()

        // check if data is not empty
        if (events.length < 1) {
            $('#not_results').show()
            $('#event_list').hide()
            loader.destroy('#events_body')
            return;
        }

        var eventItem = (info) => {
            return `
            <div class="d-flex flex-stack pt-2" id="event-${info.ActivityType}">
                <div class="d-flex align-items-center">
                  <div>
                    <a href="javascript:;" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2">${info.Title}</a>
                    <div class="text-muted fs-7 mb-1">🎫 Tipo: ${info.ActivityType}</div>
                    </div>
                </div>
                <div class="d-flex align-items-end ms-2">
                  <button type="button" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" id="edit">
                    <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path> <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path> </svg>
                    </span>
                  </button>
                </div>
            </div>
            <div class="pt-2 separator separator-dashed"></div>`;
        }

        $.each(events, (_, event) => {
            list.append(eventItem(event))
            list.find(`#event-${event.ActivityType} #edit`).on('click', () => {
                parameters.conditions.params.activityType = event.ActivityType
                $('#event_selected_title').html(event.Title)
                $('#kt_tab_condition_rewards').removeClass('active show')
                $('#kt_tab_condition_no_selected').addClass('active show')
                conditions.list()
            })
        })

        paginator.html(data.paginator.rendered)
        loader.destroy('#events_body')

        $('#not_results').hide()
        $('#event_list').show()
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
                axios.get(`${baseUrl}/api/admin/game/event/activities/update-on-game`, parameters.event).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    changeButtonState(button, false);
                })
            }
        });
    },
}

const conditions = {
    create: () => {
        const data = $("#md_new_condition form").serializeObject();
        data.activityType = parameters.conditions.params.activityType
        data.sid = parameters.conditions.params.sid
        var button = document.querySelector('#md_new_condition button[type="button"]');
        changeButtonState(button, true);
        axios.post(`${baseUrl}/api/admin/game/event/activities/conditions`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_new_condition').modal('hide')
                conditions.list()
                $('#kt_tab_condition_rewards').removeClass('active show')
                $('#kt_tab_condition_no_selected').addClass('active show')
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
        axios.put(`${baseUrl}/api/admin/game/event/activities/conditions`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_edit_condition').modal('hide')
                conditions.list()
                $('#kt_tab_condition_rewards').removeClass('active show')
                $('#kt_tab_condition_no_selected').addClass('active show')
            }
        })
    },
    delete: () => {
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
                axios.delete(`${baseUrl}/api/admin/game/event/activities/conditions`, {
                    params: {
                        sid: parameters.conditions.params.sid,
                        activityType: parameters.conditions.params.activityType,
                        subActivityType: parameters.conditions.params.subActivityType,
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
    list: (page = 1) => {
        parameters.event.params.page = page
        loader.init('#event_data_body')
        $('#not_selected').hide()
        $('#event_data').show()
        axios.get(`${baseUrl}/api/admin/game/event/activities/conditions`, parameters.conditions).then((results) => {
            conditions.populate(results.data)
        })
    },
    populate: (data) => {
        if (!data.state) {
            alert(data.message)
            return
        }

        const list = $('#condition_data ul'),
            conditionsList = data.data

        //clear condition list
        list.empty()

        // check if data is not empty
        if (conditionsList.length < 1) {
            $('#no_conditions').show()
            $('#condition_data').hide()
            loader.destroy('#event_data_body')
            return;
        }

        var conditionItem = (info) => {
            return `<li class="nav-item w-md-150px me-0" id="condition-${info.SubActivityType}"><a class="nav-link" data-bs-toggle="tab" href="#kt_tab_condition_rewards">Condition ${info.SubActivityType}</a></li>`;
        }

        $.each(conditionsList, (_, condition) => {
            list.append(conditionItem(condition))
            list.find(`#condition-${condition.SubActivityType}`).on('click', () => {
                parameters.conditions.params.activityType = condition.ActivityType
                parameters.rewards.params.activityType = condition.ActivityType
                parameters.conditions.params.subActivityType = condition.SubActivityType
                parameters.rewards.params.subActivityType = condition.SubActivityType

                //set activity type
                var prefix = activityTypes[condition.ActivityType].prefix
                $('#condiction_desc').html(prefix.replace('{0}', `<span class="text-primary">${condition.Condition}</span>`))

                //populate edit modal
                conditions.populateEdit(condition)

                //load rewards list
                rewards.list()
            })
        })

        $('#no_conditions').hide()
        $('#condition_data').show()

        loader.destroy('#event_data_body')
    },
    populateEdit: (data) => {
        var prefix = activityTypes[parameters.conditions.params.activityType].prefix
        $('#md_edit_condition input[name="condition"]').val(data.Condition)
        $('#md_edit_condition #prefix').html(prefix.replace('{0}', `<span class="text-primary">${data.Condition}</span>`))
    },
}

const rewards = {
    create: () => {
        const data = $("#md_new_reward form").serializeObject();
        data.activityType = parameters.rewards.params.activityType
        data.subActivityType = parameters.rewards.params.subActivityType
        data.sid = parameters.rewards.params.sid

        var button = document.querySelector('#md_new_reward button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/game/event/activities/rewards`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_new_reward #itemID').val('').trigger('change')
                $('#md_new_reward').modal('hide')
                rewards.list()
            }
        })
    },
    update: () => {
        const data = $("#md_edit_reward form").serializeObject();
        data.sid = parameters.rewards.params.sid

        var button = document.querySelector('#md_edit_reward button[type="button"]');
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/game/event/activities/rewards`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_edit_reward').modal('hide')
                rewards.list()
            }
        })
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
                axios.delete(`${baseUrl}/api/admin/game/event/activities/rewards`, {
                    params: {
                        sid: parameters.rewards.params.sid,
                        id: id,
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
        loader.init('#rewards_body')
        axios.get(`${baseUrl}/api/admin/game/event/activities/rewards`, parameters.rewards).then((results) => {
            rewards.populate(results.data)
        })
    },
    populate: (data) => {
        if (!data.state) {
            alert(data.message)
            return
        }

        const list = $('#rewards_list'),
            rewardsList = data.data

        //clear reward list
        list.empty()

        // check if data is not empty
        if (rewardsList.length < 1) {
            $('#no_rewards').show()
            list.hide()
            loader.destroy('#rewards_body')
            return;
        }

        var rewardItem = (info) => {
            return `<div class="d-flex flex-stack mb-4" id="reward-${info.TemplateId}">
            <div class="d-flex align-items-center">
                <div class="w-40px h-40px me-3 rounded bg-light">
                    <img src="${info.Icon}"
                        class="w-100">
                </div>
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <div class="text-gray-800 fw-bolder">${info?.Name ?? 'sem nome'}</div>
                        <div class="badge badge-light-primary ms-5 me-2">x${info.Count}</div>
                        <div class="badge badge-light-${info.IsBind == '0' ? 'success' : 'danger'}">
                        ${info.IsBind == '0' ? 'Ilimitado' : 'Limitado'}
                        </div>
                    </div>
                    <div class="text-muted">${info.TemplateId}</div>
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
        </div>`;
        }

        $.each(rewardsList, (_, reward) => {
            list.append(rewardItem(reward))
            list.find(`#reward-${reward.TemplateId} #edit`).on('click', () => {
                rewards.populateEdit(reward)
            })

            list.find(`#reward-${reward.TemplateId} #delete`).on('click', () => {
                rewards.delete(reward.Id)
            })
        })

        $('#no_rewards').hide()
        list.show()

        loader.destroy('#rewards_body')
    },
    populateEdit: (reward) => {
        var restore = function () {
            $("#md-edit-reward-annex-attribute-area").show();
            $("#md-edit-reward-annex-level-area").removeClass("d-none");
            $("#md-edit-reward-annex-amount-area").removeClass("col-12");
            $("#md-edit-reward-annex-amount-area").addClass("col-6");

            if (reward.CanCompose == "0") {
                $("#md-edit-reward-annex-attribute-area").hide();
            }

            if (reward.CanStrengthen == "0") {
                $("#md-edit-reward-annex-level-area").addClass("d-none");
                $("#md-edit-reward-annex-amount-area").removeClass("col-6");
                $("#md-edit-reward-annex-amount-area").addClass("col-12");
            }
        };

        var get = (e) => { return $(`#md_edit_reward ${e}`) };

        $('#md_edit_reward').modal('show')

        restore();

        $("#md-edit-reward-annex-id").html(reward.TemplateId);
        $("#md-edit-reward-annex-name").html(reward.Name);
        $("#md-edit-reward-annex-pic").attr("src", reward.Icon);

        get('input[name="count"]').attr("max", reward.MaxCount);
        get('select[name="validDate"]').val(reward.ValidDate).trigger("change");
        get('select[name="strengthLevel"]').val(reward.StrengthLevel).trigger("change");
        get('input[name="isBind"]').prop("checked", reward.IsBind == "1" ? true : false);

        get('input[name="id"]').val(reward.Id);
        get('input[name="count"]').val(reward.Count);
        get('input[name="attackCompose"]').val(reward.AttackCompose);
        get('input[name="defendCompose"]').val(reward.DefendCompose);
        get('input[name="agilityCompose"]').val(reward.AgilityCompose);
        get('input[name="luckCompose"]').val(reward.LuckCompose);

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

    //set sid from select change
    $('select[name="sid"]').on('change', function () {
        parameters.event.params.sid = $(this).val()
        parameters.conditions.params.sid = $(this).val()
        parameters.rewards.params.sid = $(this).val()
        events.list()
    })

    $('input[name="search"]').on('change', function () {
        parameters.event.params.search = $(this).val()
        events.list()
    })

    $('#md_new_condition input[name="condition"]').on('change', function (e) {
        var prefix = activityTypes[parameters.conditions.params.activityType].prefix
        $('#md_new_condition #prefix').html(prefix.replace('{0}', `<span class="text-primary">${$(this).val()}</span>`))
    })

    $('#md_edit_condition input[name="condition"]').on('change', function (e) {
        var prefix = activityTypes[parameters.conditions.params.activityType].prefix
        $('#md_edit_condition #prefix').html(prefix.replace('{0}', `<span class="text-primary">${$(this).val()}</span>`))
    })

    $('button[data-bs-target="#md_new_condition"]').on('click', function () {
        var prefix = activityTypes[parameters.conditions.params.activityType].prefix
        $('#md_new_condition input[name="condition"]').val('0')
        $('#md_new_condition #prefix').html(prefix.replace('{0}', `<span class="text-primary">0</span>`))
    })

    $('#condition_data #delete').on('click', () => { conditions.delete() })

    //item search
    $('#md_new_reward #itemID').on('change', function () {
        //restore modal to default values
        $('#md-annex-pic, #md-annex-name, #md-annex-id, #md-item-info').hide()
        $('#md-annex-attribute-area').show();
        $('#md-annex-level-area').removeClass('d-none');
        $('#md-annex-amount-area').removeClass('col-12');
        $('#md-annex-amount-area').addClass('col-6');

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

            if (item.CanCompose == '0') {
                $('#md-annex-attribute-area').hide();
            }

            if (item.CanStrengthen == '0') {
                $('#md-annex-level-area').addClass('d-none');
                $('#md-annex-amount-area').removeClass('col-6');
                $('#md-annex-amount-area').addClass('col-12');
            }

            $('#md-annex-pic, #md-annex-name, #md-annex-id, #md-item-info').show()
        })
    })

    $('#md_new_reward #itemID').select2({
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

    events.list()
}

init()
