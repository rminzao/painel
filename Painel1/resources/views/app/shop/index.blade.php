@extends('layouts.app')

@section('title', 'Loja')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🔥 Loja</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">
                            {{ $_ENV['APP_NAME'] }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Aplicação</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Loja</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="items_body">
                        <div class="card-body pt-4 ps-7">
                            <div class="menu-content d-flex align-items-center px-0 mb-5">
                                <span class="symbol symbol-50px me-3">
                                    <img alt="Logo" src="{{ image_avatar($user->photo, 50, 50) }}">
                                </span>
                                <div class="d-flex flex-column">
                                    <div class="text-dark fs-8">
                                        {{ $user->fullName() }}
                                    </div>
                                    <span class="text-muted fs-8 d-inline-block text-truncate mw-150px">
                                        {{ $user->mail(true) }}
                                    </span>
                                    <span class="text-muted fs-8">
                                        🌍 <span id="s_name">s1 - Terra das chamas</span>
                                    </span>

                                </div>

                                <div class="position-absolute end-0 mt-n15 me-n5 mw-150px">
                                    <img src="https://marketplace.thetanarena.com/3697f5f6f364ac71fb6a242c7e130871.png"
                                        class="w-100">
                                </div>
                            </div>
                            <div class="mt-1 mb-2">
                                <select class="form-select form-select-sm form-select-solid px-6" id="sid"
                                    data-hide-search="true" data-control="select2" data-placeholder="Selecione um servidor">
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->id }}" {{ $loop->first ? 'selected' : '' }}>
                                            {{ $server->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-1 mb-2">
                                <select class="form-select form-select-sm form-select-solid px-6" id="person_id"
                                    data-hide-search="true" data-control="select2"
                                    data-placeholder="Selecione um personagem">
                                    <option></option>
                                    @foreach ($user->characters() as $char)
                                        <option value="{{ $char['UserID'] }}" data-sid="{{ $char['_server']['id'] }}">
                                            [{{ $char['UserID'] }}] - {{ $char['NickName'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="bg-gray-100 rounded">
                                <div class="p-4">
                                    <span class="mb-1 text-warning fw-bolder fs-6">Atenção</span> <br>
                                    O item comprado será enviado diretamente ao correio do personagem selecionado.
                                </div>

                                <div class="position-relative p_picture" id="character-preview">
                                    <div class="f_face">
                                        <img data-current="0"
                                            src="http://192.168.1.109:1008/image/equip/f/face/face174/1/show.png"
                                            style="transform: translateX(0px);">
                                    </div>
                                    <div class="f_hair">
                                        <img src="http://192.168.1.109:1008/image/equip/f/hair/hair242/1/a/show.png">
                                    </div>
                                    <div class="f_head">
                                        <img src="http://192.168.1.109:1008/image/equip/f/head/head114/1/show.png">
                                    </div>
                                    <div class="f_cloth">
                                        <img src="http://192.168.1.109:1008/image/equip/f/cloth/cloth55/1/show.png">
                                    </div>
                                    <div class="f_arm">
                                        <img src="http://192.168.1.109:1008/image/arm/Sbrick4/1/0/show.png">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="kt_chat_messenger">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um item para continuar',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/item/helper.js"></script>
    <script>
        function faceAnmite() {
            var faceObj = $('.f_face img');
            var current = faceObj.data('current');
            var faceTrans = [0, 397, 264.8, 397];
            current++;

            if (current == 4) {
                current = 0;
            }

            faceObj.data('current', current);
            faceObj.css('transform', 'translateX(-' + faceTrans[current] + 'px)');

            setTimeout(faceAnmite, current > 0 ? 100 : 2000);
        }
        setTimeout(faceAnmite, 400);

        const shop = {
            _ui: {
                sid: $('#sid')
            },
            _params: {
                sid: null
            },

            characters: () => {
                axios.get(url,{params: {
                    sid: shop._params.sid
                }})
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    console.error(err); 
                })
            }, 

            listeners: () => {
                shop._params.sid = shop._ui.sid.val();
                shop._ui.sid.on('change', () => shop._params.sid = shop._ui.sid.val());
            },

            init: () => {
                shop.listeners();
                shop.characters();
            }
        };

        shop.init();
    </script>
@endsection
