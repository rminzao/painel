@extends('layouts.app')

@section('title', 'Shop')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📰 Anuncios</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Anuncios</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select form-select-solid" data-control="select2"
                        data-hide-search="true" data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>{{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card" id="shop_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome do item" name="search" />
                                        </div>
                                    </div>
                                    <select name="type" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2">
                                        <option value="all" selected>Todos</option>
                                        <option value="enable">🟢 Ativo</option>
                                        <option value="disable">🔴 Expirado</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_new_announcement">
                                    novo anuncio
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7" id="announcement_body">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum anuncio encontrado',
                                ])
                            </div>
                            <div id="announcement_list"></div>
                            <div id="announcement_list_footer" style="display:none;">
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

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card">
                        <div id="no_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um anuncio para continuar',
                            ])
                        </div>
                        <div id="announcement_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#detail">
                                                    📃 Detalhes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm"
                                        onclick="announcement.update()" id="btn_update_announcement">
                                        <span class="indicator-label">Aplicar alterações</span>
                                        <span class="indicator-progress">
                                            aplicando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
                                        <form>
                                            <input type="hidden" name="ID" />
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="fv-row mb-5">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Título</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        name="Title" value="" />
                                                </div>
                                                <div class="fv-row mb-5">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎲 Descrição</label>
                                                    <textarea class="form-control form-control form-control-solid" name="Text" rows="7"
                                                        data-kt-autosize="true"></textarea>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">📆 Data inicial</label>
                                                        <input class="form-control form-control-solid" name="BeginDate"
                                                            value="{{ date('d/m/Y H:i:s.v') }}" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">📆 Data final</label>
                                                        <input class="form-control form-control-solid" name="EndDate"
                                                            value="{{ date('d/m/Y H:i:s.v') }}" />
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-stack mb-7">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">IsExist</label>
                                                        <div class="fs-7 fw-bold text-muted"> Desconhecido
                                                        </div>
                                                    </div>
                                                    <label
                                                        class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                                            name="IsExist" value="1" checked />
                                                        <span class="form-check-label fw-bold text-muted"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_new_announcement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Novo anúncio</h3>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="fv-row mb-5">
                                <label class="fs-6 fw-bold form-label mb-2">🏷️ Título</label>
                                <input type="text" class="form-control form-control-solid" name="Title"
                                    value="" />
                            </div>
                            <div class="fv-row mb-5">
                                <label class="fs-6 fw-bold form-label mb-2">🎲 Descrição</label>
                                <textarea class="form-control form-control form-control-solid" name="Text" rows="7"
                                    data-kt-autosize="true"></textarea>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📆 Data inicial</label>
                                    <input class="form-control form-control-solid" name="BeginDate"
                                        value="{{ date('d/m/Y H:i:s.v') }}" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📆 Data final</label>
                                    <input class="form-control form-control-solid" name="EndDate"
                                        value="{{ date('2050-m-d H:i:s.v') }}" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-7">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">IsExist</label>
                                    <div class="fs-7 fw-bold text-muted"> Desconhecido
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsExist"
                                        value="1" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="text-center">
                              <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="announcement.create()">
                                  <span class="indicator-label">Criar anuncio</span>
                                  <span class="indicator-progress">
                                      criando...
                                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                  </span>
                              </button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/game/announcements/list.js"></script>
@endsection
