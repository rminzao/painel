@extends('layouts.app')

@section('title', 'Servidores')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🌍 Servidores</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Servidores</li>
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
                    <div class="card card-flush">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Nome/id do servidor" name="search">
                                        </div>
                                    </div>
                                    <select name="statusServer" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-hide-search="true" data-placeholder="Servidor">
                                        <option value="all" selected>Todos</option>
                                        <option value="true">Ativado</option>
                                        <option value="false">Desativado</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_new_server">
                                    novo servidor
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-4 ps-7" id="list_body">
                            <div id="no_result">
                                @include('components.default.notfound', [
                                    'title' => 'Sem servidores',
                                    'message' => 'nenhum servidor encontrado',
                                ])
                            </div>
                            <div id="server_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>
                            <div id="list_footer" style="display:none;">
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
                                'message' => 'clique em um servidor para continuar',
                            ])
                        </div>
                        <div id="server_data" style="display:none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#detail">
                                                    📝 Detalhes
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#database">
                                                    📲 Banco de dados
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#flash">
                                                    ⚡ Flash
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#others">
                                                    📐 Outros
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" onclick="server.update()"
                                        id="apply_changed">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.3" x="2" y="2" width="20"
                                                    height="20" rx="5" fill="currentColor"></rect>
                                                <rect x="10.8891" y="17.8033" width="12" height="2"
                                                    rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                    fill="currentColor"></rect>
                                                <rect x="6.01041" y="10.9247" width="12" height="2"
                                                    rx="1" fill="currentColor"></rect>
                                            </svg>
                                        </span>
                                        Aplicar alterações
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="detail" role="tabpanel">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <input type="hidden" name="id">
                                                <div class="row mb-5">
                                                    <div class="fv-row col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🔪 Nome</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="name" value="">
                                                    </div>
                                                    <div class="fv-row col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">💾 Versão</label>
                                                        <input type="string"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="version" value="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">✂️ Descrição</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" name="description" rows="2"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row col-6 mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">🔗 quest</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="quest" value="">
                                                    </div>
                                                    <div class="fv-row col-6 mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">🔗 flash</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="flash" value="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row col-6 mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎨 resource</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="resource" value="">
                                                    </div>
                                                    <div class="fv-row col-6 mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">🌍 wsdl</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="wsdl" value="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row  mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">🚪 areaID</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="setting_areaid" value="">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="database" role="tabpanel">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-5">
                                                    <div class="fv-row col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                            📇 dbData
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="dbData" value="">
                                                    </div>
                                                    <div class="fv-row col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">
                                                            📇 dbUser
                                                        </label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="dbUser" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="flash" role="tabpanel">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">

                                                <div class="row">
                                                    <div class="fv-row col-6 mb-5">
                                                        <label class="fs-6 fw-bold form-label mb-2">💻 lang</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm form-control-solid"
                                                            name="lang" value="">
                                                    </div>
                                                    <div class="fv-row col-6 mb-5">
                                                        <label for="setting_flash_quality" class="form-label required">📺
                                                            Qualidade
                                                            Flash</label>
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-hide-search="true"
                                                            name="setting_flash_quality">
                                                            <option value="best" selected>Best</option>
                                                            <option value="autohigh">Auto High</option>
                                                            <option value="high">High</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="low">Low</option>
                                                            <option value="autolow">Auto Low</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-5 d-flex flex-stack">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">🔐 MD5</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Habilita a hash da flash
                                                        </div>
                                                    </div>
                                                    <label
                                                        class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                                            name="setting_md5" value="1" checked>
                                                    </label>
                                                </div>



                                                <div class="accordion accordion-icon-toggle" id="kt_accordion_2">
                                                    <div class="mb-5">
                                                        <div class="accordion-header py-3 d-flex collapsed"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#kt_accordion_2_item_1" aria-expanded="false">
                                                            <span class="accordion-icon">
                                                                <span class="svg-icon svg-icon-4">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24"
                                                                        fill="none">
                                                                        <rect opacity="0.5" x="18"
                                                                            y="13" width="13" height="2"
                                                                            rx="1" transform="rotate(-180 18 13)"
                                                                            fill="currentColor"></rect>
                                                                        <path
                                                                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                            <h3 class="fs-6 fw-bold mb-0 ms-4">Opções do jogo</h3>
                                                        </div>
                                                        <div id="kt_accordion_2_item_1"
                                                            class="fs-6 collapse p-5 highlight"
                                                            data-bs-parent="#kt_accordion_2">
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">👕
                                                                        Conjunto</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o conjunto será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_suit"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🔩 Embelezar
                                                                        PET</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o embelezar(pet) será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_pets_eat"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- BATISMO - NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🤲 Batismo</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o batismo será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_batismo"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🧙‍ Templo</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o templo será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_templo"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">👔 Fugura</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a fugura será <span
                                                                            class="text-danger">desabilitada</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_fugura"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🤺 Passe de Batalha</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o passe de batalha será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_passe"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">👨‍🌾 Fazenda</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a fazenda será <span
                                                                            class="text-danger">desabilitada</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_fazenda"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">📕 Manual</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o manual do explorador será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_manual"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <!-- NEW FUNCTION -->
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🧲 Cabine Mágica</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a cabine mágica será <span
                                                                            class="text-danger">desabilitada</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_cabine"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">⚒️ Totem</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado o totem será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_totem"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">📙
                                                                        Potencia</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a potência será <span
                                                                            class="text-danger">desabilitada</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_latent_energy"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🦾
                                                                        Avanço</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a avanço será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_advance"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                            <div class="mb-5 d-flex flex-stack">
                                                                <div class="me-5">
                                                                    <label class="fs-6 fw-bold form-label">🟣 Espírito de
                                                                        luta</label>
                                                                    <div class="fs-7 fw-bold text-muted">
                                                                        Se desmarcado a espírito de luta será <span
                                                                            class="text-danger">desabilitado</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                    <input class="form-check-input h-20px w-30px"
                                                                        type="checkbox" name="setting_gemstone"
                                                                        value="1" checked>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="others" role="tabpanel">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="mb-5 d-flex flex-stack">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">🎯 Status</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Se desmarcado o servidor ficará <span
                                                                class="text-danger">indisponível</span>
                                                        </div>
                                                    </div>
                                                    <label
                                                        class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                                            name="active" value="1" checked>
                                                    </label>
                                                </div>
                                                <div class="row" id="status_area" style="display:none;">
                                                    <div class="fv-row mb-5">
                                                        <label for="status" class="form-label required">🖖
                                                            Motivo</label>
                                                        <select class="form-select form-select-sm form-select-solid"
                                                            data-control="select2" data-hide-search="true"
                                                            name="status">
                                                            <option value="maintenance">🚧 Manutenção</option>
                                                            <option value="comming_soon">🚀 Em Breve</option>
                                                            <option value="not_visible">👁‍🗨 Não visível</option>
                                                            <option value="offline">🔴 Desligado</option>
                                                        </select>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Motivo do servidor está desativado
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-5 d-flex flex-stack">
                                                    <div class="me-5">
                                                        <label class="fs-6 fw-bold form-label">🦄 Navbar</label>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Se desmarcado a navbar ficará <span
                                                                class="text-danger">oculta</span>
                                                        </div>
                                                    </div>
                                                    <label
                                                        class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                                            name="setting_navbar" value="1" checked>
                                                    </label>
                                                </div>
                                                <div class="mb-5 d-flex flex-stack">
                                                  <div class="me-5">
                                                      <label class="fs-6 fw-bold form-label">👁‍🗨 Visibilidade</label>
                                                      <div class="fs-7 fw-bold text-muted">
                                                          Se desamarcado este servidor não irá aparecer em nenhuma lista
                                                          no site, mesmo em
                                                          funções administrativas.
                                                      </div>
                                                  </div>
                                                  <label
                                                      class="form-check form-switch form-check-custom form-check-solid">
                                                      <input class="form-check-input h-20px w-30px" type="checkbox"
                                                          name="visible" value="1" checked>
                                                  </label>
                                              </div>
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
    <div class="modal fade" id="md_new_server" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">🌍 Adicionar servidor</h4>
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
                            <div class="row mb-5">
                                <div class="fv-row col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🔪 Nome</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="name" value="">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">💾 Versão</label>
                                    <input type="string" class="form-control form-control-sm form-control-solid"
                                        name="version" value="">
                                </div>
                            </div>
                            <div class="fv-row mb-5">
                                <label class="fs-6 fw-bold form-label mb-2">🕵 Descrição</label>
                                <textarea class="form-control form-control-sm form-control-solid" name="description" rows="4"></textarea>
                            </div>
                            <div class="row mb-5">
                                <div class="fv-row col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🏴󠁳󠁮󠁤󠁢󠁿 dbData</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="dbData" value="">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🏴󠁳󠁮󠁤󠁢󠁿 dbUser</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="dbUser" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row col-6 mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🔗 flash</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="flash" value="">
                                </div>
                                <div class="fv-row col-6 mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🔗 quest</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="quest" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row col-6 mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🎨 resource</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="resource" value="">
                                </div>
                                <div class="fv-row col-6 mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🌍 wsdl</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="wsdl" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row col-6 mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🚩 lang</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="lang" value="">
                                </div>
                                <div class="fv-row col-6 mb-5">
                                    <label for="setting_flash_quality" class="form-label required">📺 Qualidade
                                        Flash</label>
                                    <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                        data-hide-search="true" name="setting_flash_quality">
                                        <option value="best" selected>Best</option>
                                        <option value="autohigh">Auto High</option>
                                        <option value="high">High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                        <option value="autolow">Auto Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row  mb-5">
                                    <label class="fs-6 fw-bold form-label mb-2">🚪 areaID</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        name="setting_areaid" value="">
                                </div>
                            </div>
                            <div class="mb-5 d-flex flex-stack">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🦄 Navbar</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado a navbar ficará <span class="text-danger">oculta</span>
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="setting_navbar"
                                        value="1" checked>
                                </label>
                            </div>
                            <div class="mb-5 d-flex flex-stack">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🎯 Status</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado o servidor ficará <span class="text-danger">indisponível</span>
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="active"
                                        value="1" checked>
                                </label>
                            </div>
                            <div class="row" id="status_area" style="display:none;">
                                <div class="fv-row mb-5">
                                    <label for="status" class="form-label required">🖖 Motivo</label>
                                    <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                        data-hide-search="true" name="status">
                                        <option value="maintenance">🚧 Manutenção</option>
                                        <option value="comming_soon">🚀 Em Breve</option>
                                        <option value="not_visible">👁‍🗨 Não visível</option>
                                        <option value="offline">🔴 Desligado</option>
                                    </select>
                                    <div class="fs-7 fw-bold text-muted">
                                        Motivo do servidor está desativado
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5 d-flex flex-stack">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🔐 MD5</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Habilita a hash da flash
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="setting_md5"
                                        value="1" checked>
                                </label>
                            </div>
                            <div class="mb-5 d-flex flex-stack">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">👁‍🗨 Visibilidade</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desamarcado este servidor não irá aparecer em nenhuma lista no site, mesmo em
                                        funções administrativas.
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="visible"
                                        value="1" checked>
                                </label>
                            </div>

                            <div class="accordion accordion-icon-toggle" id="kt_accordion_2">
                                <div class="mb-5">
                                    <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#kt_accordion_2_item_1" aria-expanded="false">
                                        <span class="accordion-icon">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                            <span class="svg-icon svg-icon-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="18" y="13" width="13"
                                                        height="2" rx="1" transform="rotate(-180 18 13)"
                                                        fill="currentColor"></rect>
                                                    <path
                                                        d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <h3 class="fs-6 fw-bold mb-0 ms-4">Opções avançadas</h3>
                                    </div>
                                    <div id="kt_accordion_2_item_1" class="fs-6 collapse p-5 highlight"
                                        data-bs-parent="#kt_accordion_2">
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">👕 Conjunto</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado o conjunto será <span
                                                        class="text-danger">desabilitado</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_suit" value="1" checked>
                                            </label>
                                        </div>
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">🔩 Embelezar PET</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado o embelezar(pet) será <span
                                                        class="text-danger">desabilitado</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_pets_eat" value="1" checked>
                                            </label>
                                        </div>
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">⚒️ Totem</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado o totem será <span
                                                        class="text-danger">desabilitado</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_totem" value="1" checked>
                                            </label>
                                        </div>
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">📙 Potencia</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado a potência será <span
                                                        class="text-danger">desabilitada</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_latent_energy" value="1" checked>
                                            </label>
                                        </div>
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">🦾 Avanço</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado a avanço será <span
                                                        class="text-danger">desabilitado</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_advance" value="1" checked>
                                            </label>
                                        </div>
                                        <div class="mb-5 d-flex flex-stack">
                                            <div class="me-5">
                                                <label class="fs-6 fw-bold form-label">🟣 Espírito de luta</label>
                                                <div class="fs-7 fw-bold text-muted">
                                                    Se desmarcado a espírito de luta será <span
                                                        class="text-danger">desabilitado</span>
                                                </div>
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input h-20px w-30px" type="checkbox"
                                                    name="setting_gemstone" value="1" checked>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center pt-5">
                                <button type="button" onclick="server.create()" class="btn btn-primary w-100">
                                    <span class="indicator-label">Adicionar servidor</span>
                                    <span class="indicator-progress">
                                        adicionando...
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
    <script src="{{ url() }}/assets/js/admin/server/list.js"></script>
@endsection
