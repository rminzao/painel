@extends('layouts.app')

@section('title', 'Instâncias')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">☃️ Instâncias (PVE)</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Instâncias (PVE)</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                {{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="pve.updateOnGame()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path opacity="0.3"
                                    d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z"
                                    fill="currentColor" />
                                <path
                                    d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        atualizar
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
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="card card-flush" id="events_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome da instancia" id="search" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <select name="type_filter" class="form-select form-select-sm form-select-solid w-60"
                                            data-hide-search="true" data-control="select2"
                                            data-placeholder="Status da missão">
                                            <option value="0" selected>🧁 todos</option>
                                            @foreach ($pveTypes as $type => $item)
                                                <option value="{{ $type }}">
                                                    {{ $item['name'] != '' ? $item['name'] : '❓ ' . $item['prefix'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_pve_create">
                                    adicionar pve
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7" id="pve_body">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhuma instancia encontrada',
                                ])
                            </div>
                            <div id="pve_list"></div>
                            <div id="pve_list_footer" style="display:none;">
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
                                'message' => 'clique em uma instância para continuar',
                            ])
                        </div>
                        <div id="pve_data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <span>📃 Detalhes</span>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" id="btn_pve_update"
                                        onclick="pve.update()">
                                        <span class="indicator-label">Aplicar alterações</span>
                                        <span class="indicator-progress">
                                            aplicando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form>
                                    <input type="hidden" name="OriginalID">
                                    <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                        <div class="row">
                                            <div class="fv-row mb-7 col-2">
                                                <label class="fs-6 fw-bold form-label mb-2">🎫 ID</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid" name="ID"
                                                    value="" />
                                            </div>
                                            <div class="fv-row mb-7 col-10">
                                                <label class="fs-6 fw-bold form-label mb-2">📜 Nome</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid" name="Name"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row mb-7 col-12">
                                                <label for="" class="form-label">🎫 Descrição</label>
                                                <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Description"
                                                    data-kt-autosize="true"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📝 Fácil</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="SimpleGameScript" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📝 Normal</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="NormalGameScript" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📝 Dificil</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="HardGameScript" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📝 Avançado</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="TerrorGameScript" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📦 Fácil</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="SimpleTemplateIds" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📦 Normal</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="NormalTemplateIds" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📦 Dificil</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="HardTemplateIds" value="" />
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column col-3">
                                                <div class="fv-row mb-7">
                                                    <label for="" class="form-label">📦 Avançado</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="TerrorTemplateIds" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row mb-7 col-4">
                                                <label for="QuestID" class="form-label">🕋 Type</label>
                                                <input type="number"
                                                    class="form-control form-control-sm form-control-solid" name="Type"
                                                    value="" />
                                            </div>
                                            <div class="fv-row mb-7 col-4">
                                                <label for="" class="form-label">🕋 LevelLimits</label>
                                                <input type="number"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="LevelLimits" value="" />
                                            </div>
                                            <div class="fv-row mb-7 col-4">
                                                <label for="" class="form-label">🦽 Pic</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid" name="Pic"
                                                    value="" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row mb-7 col-6">
                                                <label for="QuestID" class="form-label">🕋 Ordering</label>
                                                <input type="number"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="Ordering" value="" />
                                            </div>
                                            <div class="fv-row mb-7 col-6">
                                                <label for="" class="form-label">🕋 AdviceTips</label>
                                                <input type="text"
                                                    class="form-control form-control-sm form-control-solid"
                                                    name="AdviceTips" value="" />
                                            </div>
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
@endsection

@section('modals')
    <div class="modal fade" id="md_pve_create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">☃️ Nova instancia</h3>
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
                            <div class="row">
                                <div class="fv-row mb-7 col-2">
                                    <label class="fs-6 fw-bold form-label mb-2">🎫 ID</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="ID" value="" />
                                </div>
                                <div class="fv-row mb-7 col-10">
                                    <label class="fs-6 fw-bold form-label mb-2">📜 Nome</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="Name" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-12">
                                    <label for="" class="form-label">🎫 Descrição</label>
                                    <textarea class="form-control form-control-solid overflow-hidden" rows="3" name="Description"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📝 Fácil</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="SimpleGameScript" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📝 Normal</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="NormalGameScript" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📝 Dificil</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="HardGameScript" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📝 Avançado</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="TerrorGameScript" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📦 Fácil</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="SimpleTemplateIds" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📦 Normal</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="NormalTemplateIds" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📦 Dificil</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="HardTemplateIds" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-column col-3">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📦 Avançado</label>
                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            name="TerrorTemplateIds" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label for="QuestID" class="form-label">🕋 Type</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Type" value="" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🕋 LevelLimits</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="LevelLimits" value="" />
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label for="" class="form-label">🦽 Pic</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="Pic" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label for="QuestID" class="form-label">🕋 Ordering</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        name="Ordering" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label for="" class="form-label">🕋 AdviceTips</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="AdviceTips" value="" />
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-primary w-100" id="btn_pve_create"
                                onclick="pve.create()">
                                <span class="indicator-label">Adicionar instancia</span>
                                <span class="indicator-progress">
                                    adicionando...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
    <script src="{{ url() }}/assets/js/admin/game/pve/list.js"></script>
@endsection
