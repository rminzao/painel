@extends('layouts.app')

@section('title', 'Lista de usuários')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">😱 Lista de usuários</h1>
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
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card card-flush" id="user_body">
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
                                placeholder="nome/id do usuário" />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="w-100 mw-150px">
                            <select class="form-select form-select-solid" name="filter" data-control="select2"
                                data-hide-search="true">
                                <option value="all" selected>🧁 Todos</option>
                                <option value="team">👑 Equipe</option>
                                <option value="banned">👎 Banidos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="no_result">
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
                    <div id="user_list_footer" style="display: none;">
                      <div class="d-flex justify-content-between mt-5">
                          <div>
                              <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                  data-control="select2" data-hide-search="true">
                                  <option value="5" selected>5</option>
                                  <option value="10">10</option>
                                  <option value="25">25</option>
                                  <option value="50">50</option>
                                  <option value="100">100</option>
                              </select>
                          </div>
                          <div id="paginator"></div>
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
    <script src="{{ url() }}/assets/js/admin/users/list.js"></script>
@endsection
