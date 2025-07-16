@extends('layouts.app')

@section('title', 'Códigos promocionais')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🪙 Códigos promocionais</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Produtos</li>
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
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Código do cupom" name="code_search" />
                                        </div>
                                    </div>
                                    <select name="server_id" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-hide-search="true" data-placeholder="Servidor">
                                        <option value="0" selected>Todos</option>
                                        @foreach ($servers as $server)
                                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#md_code_new">
                                    novo cupom
                                </button>
                            </div>
                        </div>

                        <div class="card-body pt-4 ps-7" id="code_body_list">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Nada por aqui',
                                    'message' => 'nenhum cupom encontrado',
                                ])
                            </div>

                            <div id="code_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>

                            <div class="mt-10" id="code_paginator"></div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um cupom para continuar',
                            ])
                        </div>
                        <div id="code-data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item" id="item-info-tab">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#code-info">
                                                    🏷️ Informações
                                                </a>
                                            </li>
                                            <li class="nav-item" id="item-box-tab">
                                                <a class="nav-link" data-bs-toggle="tab" href="#code-used">
                                                    📦 Usados
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="tab-content">
                                    <div class="tab-pane p-8 fade show active" id="code-info" role="tabpanel">
                                        <form>
                                            <div class="row">
                                                <div class="fv-row mb-7 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Código</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid" name="code"
                                                        readonly />
                                                </div>
                                                <div class="fv-row mb-7 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🌍 Servidor</label>
                                                    <select name="sid"
                                                        class="form-select form-select-sm form-select-solid w-60"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="Servidor">
                                                        <option value="0" selected>Todos</option>
                                                        @foreach ($servers as $server)
                                                            <option value="{{ $server->id }}">{{ $server->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row mb-7 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">📙 Tipo</label>
                                                    <select name="type"
                                                        class="form-select form-select-sm form-select-solid w-60"
                                                        data-control="select2" data-hide-search="true"
                                                        data-placeholder="Tipo de cupom">
                                                        @foreach ($codeTypes as $key => $type)
                                                            <option value="{{ $key }}">{{ $type['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-7 col-4" id="param1">
                                                    <label class="fs-6 fw-bold form-label mb-2">❓ param1</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="param1" />
                                                    <span class="text-muted"></span>
                                                </div>
                                                <div class="fv-row mb-7 col-4" id="param2">
                                                    <label class="fs-6 fw-bold form-label mb-2">❓ param2</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="param2" />
                                                    <span class="text-muted"></span>
                                                </div>
                                                <div class="fv-row mb-7 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🧽 Limite</label>
                                                    <input type="number"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="limit" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-7 col-6">
                                                    <label for="" class="form-label">📅 Data inicial</label>
                                                    <input class="form-control form-control-sm form-control-solid" name="start_at"
                                                        value="" />
                                                </div>
                                                <div class="fv-row mb-7 col-6">
                                                    <label for="" class="form-label">📅 Data de término</label>
                                                    <input class="form-control form-control-sm form-control-solid" name="expires_at"
                                                        value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-stack mb-7">
                                                <div class="me-5">
                                                    <label class="fs-6 fw-bold form-label">💱 Repetível</label>
                                                    <div class="fs-7 fw-bold text-muted">se marcado o código <span
                                                            class="text-primary">poderá</span> ser usado repetidamente pelo mesmo jogador.
                                                    </div>
                                                </div>
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input h-20px w-30px" type="checkbox"
                                                        name="repeat" value="1" checked="">
                                                </label>
                                            </div>

                                            <div class="d-flex flex-stack">
                                                <div class="me-5">
                                                    <label class="fs-6 fw-bold form-label">Ativo</label>
                                                    <div class="fs-7 fw-bold text-muted">se desmarcado o código <span
                                                            class="text-danger">não poderá</span> ser utilizado.
                                                    </div>
                                                </div>
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input h-20px w-30px" type="checkbox"
                                                        name="state" value="1" checked="">
                                                </label>
                                            </div>
                                            <div class="text-center pt-5">
                                                <button type="button" class="btn btn-primary w-100">
                                                    <span class="indicator-label">Aplicar alterações</span>
                                                    <span class="indicator-progress">
                                                        processando aguarde...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="code-used" role="tabpanel">
                                        <div id="no_code_used">
                                            @include('components.default.notfound', [
                                                'title' => 'Nada por aqui',
                                                'message' => 'ninguem usou esse cupom',
                                            ])
                                        </div>
                                        <div class="table-responsive overflow-auto mh-600px">
                                            <table class="table align-middle fs-6 gy-3 table-row-dashed my-0 no-footer" id="code-used-list" style="display:none;">
                                                <tbody class="fw-bold text-gray-600"></tbody>
                                            </table>
                                        </div>
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
    <div class="modal fade" id="md_code_new" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo cupom</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body scroll-y">
                    <form>
                        <div class="row">
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🏷️ Código</label>
                                <input type="text" class="form-control form-control-sm form-control-solid" name="code"/>
                            </div>
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🌍 Servidor</label>
                                <select name="sid"
                                    class="form-select form-select-sm form-select-solid w-60"
                                    data-control="select2" data-hide-search="true"
                                    data-placeholder="Servidor">
                                    <option value="0" selected>Todos</option>
                                    @foreach ($servers as $server)
                                        <option value="{{ $server->id }}">{{ $server->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">📙 Tipo</label>
                                <select name="type"
                                    class="form-select form-select-sm form-select-solid w-60"
                                    data-control="select2" data-hide-search="true"
                                    data-placeholder="Tipo de cupom">
                                    @foreach ($codeTypes as $key => $type)
                                        <option value="{{ $key }}">{{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row mb-7 col-6">
                                <label class="fs-6 fw-bold form-label mb-2">🧽 Limite (padrão <span class="text-primary">0</span>)</label>
                                <input type="number"
                                    class="form-control form-control-sm form-control-solid"
                                    name="limit" value="0" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row mb-7 col-6" id="param1">
                                <label class="fs-6 fw-bold form-label mb-2">❓ param1</label>
                                <input type="number"
                                    class="form-control form-control-sm form-control-solid"
                                    name="param1" value="0" />
                                <span class="text-muted"></span>
                            </div>
                            <div class="fv-row mb-7 col-6" id="param2">
                                <label class="fs-6 fw-bold form-label mb-2">❓ param2</label>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid"
                                    name="param2" />
                                <span class="text-muted"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row mb-7 col-6">
                                <label for="" class="form-label">📅 Data inicial</label>
                                <input class="form-control form-control-sm form-control-solid" name="start_at"
                                    value="{{ date('d/m/Y 00:00:00') }}" />
                            </div>
                            <div class="fv-row mb-7 col-6">
                                <label for="" class="form-label">📅 Data de término</label>
                                <input class="form-control form-control-sm form-control-solid" name="expires_at"
                                    value="{{ date('d/m/Y H:i:s', strtotime('+1 year')) }}" />
                            </div>
                        </div>
                        <div class="d-flex flex-stack mb-7">
                            <div class="me-5">
                                <label class="fs-6 fw-bold form-label">💱 Repetível</label>
                                <div class="fs-7 fw-bold text-muted">se marcado o código <span
                                        class="text-primary">poderá</span> ser usado repetidamente pelo mesmo jogador.
                                </div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                    name="repeat" value="1" checked>
                            </label>
                        </div>
                        <div class="d-flex flex-stack">
                            <div class="me-5">
                                <label class="fs-6 fw-bold form-label">Ativo</label>
                                <div class="fs-7 fw-bold text-muted">se desmarcado o código <span
                                        class="text-danger">não poderá</span> ser utilizado.
                                </div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                    name="state" value="1" checked="">
                            </label>
                        </div>
                        <div class="text-center pt-10">
                            <button type="button" class="btn btn-primary w-100">
                                <span class="indicator-label">Criar código</span>
                                <span class="indicator-progress">
                                    criando...
                                    <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        const codeTypes = @json($codeTypes);
    </script>
    <script src="{{ url() }}/assets/js/admin/product/code/list.js"></script>
@endsection
