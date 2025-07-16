// page params
const parameters = {
    event: {
        params: {
            sid: null,
            page: 1,
            search: '',
            limit: 10,
        },
    },
    rewards: {
        params: {
            sid: null,
            activeID: null,
        }
    }
}

const events = {
    list(page = 1) {
        parameters.event.params.page = page
        loader.init('#events_body')
        axios.get(`${baseUrl}/api/admin/game/event/activity`, parameters.event).then((results) => {
            this.populate(results.data)
        })
    },
    create: () => {
        const data = $("#md_new_event form").serializeObject();
        data.activeID = parameters.rewards.params.activeID
        data.sid = parameters.rewards.params.sid

        var button = document.querySelector('#md_new_event button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/game/event/activity`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                //$('#md_new_event #itemID').val('').trigger('change')
                $('#md_new_event').modal('hide')
                events.list()
            }
        })
    },
    update: () => {
        const data = $("#event_data form").serializeObject();
        data.sid = parameters.rewards.params.sid
        data.activeID = parameters.rewards.params.activeID

        var button = document.querySelector('#event_data form button[type="button"]');
        changeButtonState(button, true);

        axios.put(`${baseUrl}/api/admin/game/event/activity`, data).then((results) => {
            var su = results.data;
            swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
            changeButtonState(button, false);
            if (su.state) {
                $('#md_edit_event').modal('hide')
                events.list()
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
                axios.delete(`${baseUrl}/api/admin/game/event/activity`, {
                    params: {
                        sid: parameters.event.params.sid,
                        id: id,
                    }
                }).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    if (su.state) {
                        events.list()
                    }
                })
            }
        })
    },
    duplicate: (id) => {
        Swal.fire({
            icon: "question",
            html: "Você tem certeza que deseja duplicar esse evento ?",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sim, duplique isso!",
            cancelButtonText: "Não, cancele!",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-light",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`${baseUrl}/api/admin/game/event/activity/duplicate`, {
                    sid: parameters.event.params.sid,
                    id: id,
                }).then((res) => {
                    var su = res.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    if (su.state) {
                        events.list()
                    }
                })
            }
        })
    },
    reset: (id) => {
        Swal.fire({
            icon: "question",
            html: "Você tem certeza que deseja <span class=\"text-danger\">resetar</span> esse evento ? Todos os usuários que já coletaram este evento poderão coletar <span class=\"text-primary\">novamente</span>.",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Sim, resete isso!",
            cancelButtonText: "Não, cancele!",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-light",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`${baseUrl}/api/admin/game/event/activity/reset`, {
                    sid: parameters.event.params.sid,
                    id: id,
                }).then((res) => {
                    var su = res.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                })
            }
        })
    },
    populate: (data) => {
        if (!data.state) {
            alert(data.message)
            return
        }

        const list = $('#event_list'),
            paginator = $('#item_paginator'),
            eventsList = data.data

        //clear list and pagination
        list.empty()
        paginator.empty()

        // check if data is not empty
        if (eventsList.length < 1) {
            $('#not_results').show()
            $('#event_list').hide()
            loader.destroy('#events_body')
            return;
        }

        var eventItem = (info) => {
            return `
            <div class="d-flex flex-stack pt-2" id="event-${info.ActiveID}">
                <div class="d-flex align-items-center">
                  <div>
                    <a href="javascript:;" class="fs-8 fw-bolder text-gray-900 text-hover-primary mb-2" id="by-name">${info.Title}</a>
                    <div class="text-muted fs-7 mb-1">🎫 ID: ${info.ActiveID}</div>
                    </div>
                </div>
                <div class="d-flex align-items-end ms-2">
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="reset" data-bs-toggle="tooltip" data-bs-placement="top" title="Resetar coletados">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M8.38 22H21C21.2652 22 21.5196 21.8947 21.7071 21.7072C21.8946 21.5196 22 21.2652 22 21C22 20.7348 21.8946 20.4804 21.7071 20.2928C21.5196 20.1053 21.2652 20 21 20H10L8.38 22Z" fill="currentColor"/>
                                <path d="M15.622 15.6219L9.855 21.3879C9.66117 21.582 9.43101 21.7359 9.17766 21.8409C8.92431 21.946 8.65275 22 8.37849 22C8.10424 22 7.83268 21.946 7.57933 21.8409C7.32598 21.7359 7.09582 21.582 6.90199 21.3879L2.612 17.098C2.41797 16.9042 2.26404 16.674 2.15903 16.4207C2.05401 16.1673 1.99997 15.8957 1.99997 15.6215C1.99997 15.3472 2.05401 15.0757 2.15903 14.8224C2.26404 14.569 2.41797 14.3388 2.612 14.145L8.37801 8.37805L15.622 15.6219Z" fill="currentColor"/>
                                <path opacity="0.3" d="M8.37801 8.37805L14.145 2.61206C14.3388 2.41803 14.569 2.26408 14.8223 2.15906C15.0757 2.05404 15.3473 2 15.6215 2C15.8958 2 16.1673 2.05404 16.4207 2.15906C16.674 2.26408 16.9042 2.41803 17.098 2.61206L21.388 6.90198C21.582 7.0958 21.736 7.326 21.841 7.57935C21.946 7.83269 22 8.10429 22 8.37854C22 8.65279 21.946 8.92426 21.841 9.17761C21.736 9.43096 21.582 9.66116 21.388 9.85498L15.622 15.6219L8.37801 8.37805Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="duplicate" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicar">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.5" d="M18 2H9C7.34315 2 6 3.34315 6 5H8C8 4.44772 8.44772 4 9 4H18C18.5523 4 19 4.44772 19 5V16C19 16.5523 18.5523 17 18 17V19C19.6569 19 21 17.6569 21 16V5C21 3.34315 19.6569 2 18 2Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7857 7.125H6.21429C5.62255 7.125 5.14286 7.6007 5.14286 8.1875V18.8125C5.14286 19.3993 5.62255 19.875 6.21429 19.875H14.7857C15.3774 19.875 15.8571 19.3993 15.8571 18.8125V8.1875C15.8571 7.6007 15.3774 7.125 14.7857 7.125ZM6.21429 5C4.43908 5 3 6.42709 3 8.1875V18.8125C3 20.5729 4.43909 22 6.21429 22H14.7857C16.5609 22 18 20.5729 18 18.8125V8.1875C18 6.42709 16.5609 5 14.7857 5H6.21429Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-primary w-30px h-30px" id="edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </button>
                    <button type="button" class="btn btn-icon btn-active-color-danger w-30px h-30px" id="delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar">
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
            <div class="pt-2 separator separator-dashed"></div>`;
        }

        $.each(eventsList, (_, event) => {
            list.append(eventItem(event));

            list.find(`#event-${event.ActiveID} #reset`).on('click', () => {
                events.reset(event.ActiveID)
            });

            list.find(`#event-${event.ActiveID} #duplicate`).on('click', () => {
                events.duplicate(event.ActiveID)
            });

            list.find(`#event-${event.ActiveID} #edit, #event-${event.ActiveID} #by-name`).on('click', () => {
                parameters.rewards.params.activeID = event.ActiveID
                rewards.list()
                events.populateEdit(event)
                $('#not_selected').hide()
                $('#event_data').show()
            });

            list.find(`#event-${event.ActiveID} #delete`).on('click', () => {
                events.delete(event.ActiveID)
            });
        })

        $('[data-bs-toggle="tooltip"]').tooltip()

        paginator.html(data.paginator.rendered)
        loader.destroy('#events_body')

        $('#not_results').hide()
        $('#event_list').show()
    },
    populateEdit: (data) => {
        $('#event_data form').trigger("reset");

        $.each(data, (key, value) => {
            const input = $(`#event_data #event_detail form`).find(`[name="${key}"]`);
            input.val(value);

            if (['IsAdvance', 'IsOnly', 'IsShow'].includes(key)) {
                input.prop('checked', value == '1' ? true : false);
                return;
            }

            if (['HasKey'].includes(key)) input.trigger('change');
        })
    },
    updateOnGame: () => {
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
                axios.get(`${baseUrl}/api/admin/game/event/activity/update-on-game`, parameters.event).then((results) => {
                    var su = results.data;
                    swMessage(su.state ? "success" : "warning", su.message ?? 'mensagem desconhecida');
                    changeButtonState(button, false);
                })
            }
        });
    },
}

const rewards = {
    create: () => {
        const data = $("#md_new_reward form").serializeObject();
        data.activeID = parameters.rewards.params.activeID
        data.sid = parameters.rewards.params.sid

        var button = document.querySelector('#md_new_reward button[type="button"]');
        changeButtonState(button, true);

        axios.post(`${baseUrl}/api/admin/game/event/activity/rewards`, data).then((results) => {
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

        axios.put(`${baseUrl}/api/admin/game/event/activity/rewards`, data).then((results) => {
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
                axios.delete(`${baseUrl}/api/admin/game/event/activity/rewards`, {
                    params: {
                        sid: parameters.rewards.params.sid,
                        activeID: parameters.rewards.params.activeID,
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
        axios.get(`${baseUrl}/api/admin/game/event/activity/rewards`, parameters.rewards).then((results) => {
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
            return `<div class="d-flex flex-stack mb-4" id="reward-${info.ItemID}">
            <div class="d-flex align-items-center">
                <div class="w-40px h-40px me-3 rounded bg-light">
                    <img src="${info.Icon}"
                        class="w-100">
                </div>
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <div class="text-gray-800 fw-bolder">${info?.Name ?? 'sem nome'}</div>
                        <div class="badge badge-light-primary ms-5 me-2">x${info.Count}</div>
                        <div class="badge badge-light-primary">
                        📅 ${info.ValidDate == '0' ? ' Permanente' : ` ${info.ValidDate} dias`}
                        </div>
                    </div>
                    <div class="text-muted">${info.ItemID}</div>
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
            list.find(`#reward-${reward.ItemID} #edit`).on('click', () => {
                rewards.populateEdit(reward)
            })

            list.find(`#reward-${reward.ItemID} #delete`).on('click', () => {
                rewards.delete(reward.ID)
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

        $("#md-edit-reward-annex-id").html(reward.ItemID);
        $("#md-edit-reward-annex-name").html(reward.Name);
        $("#md-edit-reward-annex-pic").attr("src", reward.Icon);

        get('input[name="count"]').attr("max", reward.MaxCount);
        get('select[name="validDate"]').val(reward.ValidDate).trigger("change");
        get('select[name="strengthLevel"]').val(reward.StrengthenLevel).trigger("change");

        get('input[name="id"]').val(reward.ID);
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
    parameters.rewards.params.sid = $('select[name="sid"]').val()

    //set sid from select change
    $('select[name="sid"]').on('change', function () {
        parameters.event.params.sid = $(this).val()
        parameters.rewards.params.sid = $(this).val()
        events.list()
    })

    $('input[name="search"]').on('change', function () {
        parameters.event.params.search = $(this).val()
        events.list()
    })

    $('a[href="#event_rewards"]').on('click', () => {
        $('#reward_buttons').show()
    })

    $('a[href="#event_detail"]').on('click', () => {
        $('#reward_buttons').hide()
    })

    //date start flatpickr
    $('input[name="StartDate"], input[name="EndDate"]').flatpickr({
        enableTime: false,
        enableSeconds: false,
        dateFormat: "Y-m-d H:i:s",
    });

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
