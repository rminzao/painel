@extends('layouts.app')

@section('title', 'Lista de usuários')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🎮 Lista de jogadores</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Administração</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Lista de jogadores</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-4">
                    <select id="sid" name="sid" class="form-select form-select-solid" data-control="select2"
                        data-hide-search="true" data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>{{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary" id="button_update_ranking"
                    onclick="user.updateRanking()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span>
                        atualizar ranking
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                        transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="currentColor" />
                                </svg>
                            </span>

                            <input type="text" name="search" class="form-control form-control-solid w-250px ps-14"
                                placeholder="Buscar - Nick/UserID" />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="w-100 mw-150px">
                            <select class="form-select form-select-solid" name="state" data-control="select2"
                                data-hide-search="true" data-placeholder="Status">
                                <option></option>
                                <option value="all" selected>Todos</option>
                                <option value="1">Online</option>
                                <option value="0">Offline</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="no_results">
                        @include('components.default.notfound', [
                            'title' => 'Nada por aqui',
                            'message' => 'nenhum usuário encontrado',
                        ])
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3" id="table_user_list"
                            style="display:none;">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Info</th>
                                    <th>Status</th>
                                    <th>Ult. Login</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600" id="user_list"></tbody>
                        </table>
                    </div>
                    <div class="mt-5" id="item_paginator"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/game/users/list.js"></script>
@endsection
