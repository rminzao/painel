@extends('layouts.app')

@section('title', 'Itens')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🔥 Itens</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">
                            {{ $_ENV['APP_NAME'] }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Administração</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Itens</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-4">
                    <select id="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary" id="button_update_game_item"
                    onclick="item.updateOnGame()">
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
                    <div class="card card-flush" id="items_body">
                        <div class="p-5 pt-7 pb-0">
                            <div class="d-flex">
                                <div class="w-100 me-3">
                                    <div class="w-100 position-relative d-flex mb-3" autocomplete="off">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="ID/Nome do item" id="item_search" />
                                        </div>
                                    </div>
                                    <select name="categoryID" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-placeholder="Categoria da loja">
                                        <option value="0" selected>Todos</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" href="javascript:;"
                                    title="Em breve" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                    data-bs-dismiss="click" data-bs-placement="bottom">
                                    adicionar item
                                </button>
                            </div>
                        </div>

                        <div class="card-body pt-4 ps-7">
                            <div id="not_results">
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum item encontrado',
                                ])
                            </div>

                            <div id="item_body_list" class="scroll-y me-n5 h-lg-auto" style="display: none;"></div>

                            <div class="d-flex justify-content-between mt-5">
                                <div>
                                    <select name="limit" class="form-select form-select-sm form-select-solid w-60"
                                        data-control="select2" data-hide-search="true">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div id="item_paginator"></div>
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
                        <div id="item-data" style="display: none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item" id="item-info-tab">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#item-info">📝
                                                    Detalhes</a>
                                            </li>
                                            <li class="nav-item" id="item-box-tab">
                                                <a class="nav-link" data-bs-toggle="tab" href="#item_box_body">📦
                                                    Pacote</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div id="item_toolbar_default">
                                        <button type="button" onclick="item.update()" id="item-update-form-submit"
                                            class="btn btn-sm btn-light-primary w-100">
                                            <span class="indicator-label">Aplicar alterações</span>
                                            <span class="indicator-progress">
                                                processando aguarde...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                    <div id="box-reward-buttons" style="display: none;">
                                        <button type="button" onclick="itemBox.delete(0)"
                                            class="btn btn-light-danger btn-sm me-3">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
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
                                            Remover todas
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_box_new_item"
                                            class="btn btn-light-primary btn-sm">
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
                                            Adicionar recompensa
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="item-info" role="tabpanel">
                                    <div class="card-body">
                                        <form id="form-item-update">
                                            <div class="row">
                                                <input type="hidden" class="form-control form-control-solid disabled"
                                                    name="TemplateID" readonly />
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏷️ Nome</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Name" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🖼️ Imagem</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Pic" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">📙 Categoria</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CategoryID" />
                                                </div>
                                            </div>
                                            <div class="fv-row mb-5 col-12">
                                                <label class="fs-6 fw-bold form-label mb-2">📝 Descrição</label>
                                                <textarea class="form-control form-control form-control-solid" name="Description" data-kt-autosize="true"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🔴 Ataque</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Attack" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🔵 Defesa</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Defence" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🟢 Agilidade</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Agility" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🟡 Sorte</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Luck" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🆙 Level</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Level" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🔢 Qnt. Máxima</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="MaxCount" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">👫 NeedSex</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="NeedSex" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🆙 NeedLevel</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="NeedLevel" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">💪 CanStrengthen</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanStrengthen" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">🏗️ CanCompose</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanCompose" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">📦 CanDrop</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanDrop" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">⚙️ CanEquip</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanEquip" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">📈 CanUse</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanUse" />
                                                </div>
                                                <div class="fv-row mb-5 col-4">
                                                    <label class="fs-6 fw-bold form-label mb-2">❌ CanDelete</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanDelete" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">📜 Script</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Script" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">📊 Data</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Data" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎨 Colors</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Colors" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">👌 Quality</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Quality" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property1</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property1" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property2</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property2" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property3</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property3" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property4</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property4" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property5</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property5" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property6</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property6" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property7</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property7" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Property8</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Property8" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">📅 AddTime</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="AddTime" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">BindType</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="BindType" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">FusionType</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="FusionType" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">FusionRate</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="FusionRate" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">FusionNeedRate</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="FusionNeedRate" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">Hole</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="Hole" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">RefineryLevel</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="RefineryLevel" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">CanRecycle</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanRecycle" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">ReclaimValue</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="ReclaimValue" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">ReclaimType</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="ReclaimType" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">FloorPrice</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="FloorPrice" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">🎩 SuitId</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="SuitId" />
                                                </div>
                                                <div class="fv-row mb-5 col-3">
                                                    <label class="fs-6 fw-bold form-label mb-2">CanTransfer</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm form-control-solid"
                                                        name="CanTransfer" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="item_box_body" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="no_items_box">
                                            @include('components.default.notfound', [
                                                'title' => 'Sem items',
                                                'message' => 'essa box não possui nenhum item',
                                            ])
                                        </div>
                                        <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px" id="item_box_list"
                                            style="display: none;"></div>
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
    <div class="modal fade" id="md_box_new_item" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="item_icon" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="item_name"></span>
                            <span id="item_id" class="text-gray-800 text-hover-primary mb-1"></span>
                        </div>
                    </div>
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
                        <input type="hidden" name="ID">
                        <div class="row">
                            <label class="form-label">📦 Item</label>
                            <select class="form-select form-select-sm form-select-solid"
                                data-dropdown-parent="#md_box_new_item" data-placeholder="Selecione um item"
                                data-allow-clear="true" name="TemplateId">
                            </select>
                        </div>
                        <div id="info_area" class="mt-5" style="display: none">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row">
                                    <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                                        <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                            Level
                                        </label>
                                        <select class="form-select form-select-sm form-select-solid mb-2"
                                            data-control="select2" data-hide-search="true" data-placeholder="Nível"
                                            name="StrengthenLevel">
                                            <option></option>
                                            <option value="0">Sem level</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">Nível {{ $i }}</option>
                                            @endfor
                                            <option value="13">Avanço 1</option>
                                            <option value="14">Avanço 2</option>
                                            <option value="15">Avanço 3</option>
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-12" id="count_area">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>⏳ Quantidade</span>
                                        </label>
                                        <input type="number" id="md-edit-reward-annex-in-max"
                                            class="form-control form-control-sm form-control-solid" min="1"
                                            max="1" name="ItemCount" value="1" />

                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex flex-column mb-5 fv-row col-6" id="md-annex-amount-area">
                                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                            <span>🎲 Probabilidade</span>
                                        </label>
                                        <input type="number" id="md-annex-in-max"
                                            class="form-control form-control-sm form-control-solid" placeholder=""
                                            step="1" min="1" name="Random" value="1" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-6"
                                        id="md-edit-reward-annex-valid-area">
                                        <label for="kt_ecommerce_add_category_store_template"
                                            class="form-label required">📅 Validade</label>
                                        <select class="form-select form-select-sm form-select-solid mb-2"
                                            data-control="select2" data-hide-search="true" name="ItemValid">
                                            <option value="0" selected>Permanente</option>
                                            <option value="1">1 Dia</option>
                                            <option value="3">3 Dias</option>
                                            <option value="7">7 Dias</option>
                                            <option value="15">15 Dias</option>
                                            <option value="30">30 Dias</option>
                                            <option value="365">365 Dias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="attr_area">
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="AttackCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="DefendCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="AgilityCompose" value="0" />
                                    </div>
                                    <div class="d-flex flex-column mb-5 fv-row col-3">
                                        <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                            placeholder="Enter card number" name="LuckCompose" value="0" />
                                    </div>
                                </div>
                                <div class="d-flex flex-stack mb-5">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">💾 Log</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Exibe no chat quando o item for dropado.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsTips"
                                            value="1" checked />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-5">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">❓ isLogs</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            desconhecido.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsLogs"
                                            value="1" />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-5">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">🟢 Ilimitado</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se desmarcado o item <span class="text-success">poderá ser enviado</span>.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind"
                                            value="1" checked="checked" />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">🤚 Garantido</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se marcado o item <span class="text-success">será garantido</span>
                                            ao abrir o pacote.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsSelect"
                                            value="1" />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="text-center mt-5">
                                    <button type="button" onclick="itemBox.create()" id="btn-send-box-create"
                                        class="btn btn-sm btn-light-primary w-100">
                                        <span class="indicator-label">Adicionar</span>
                                        <span class="indicator-progress">
                                            adicionando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_box_edit_item" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label fs-2 fw-bold text-success">
                                <img id="item_icon" class="w-100" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span id="item_name"></span>
                            <span id="item_id" class="text-gray-800 text-hover-primary mb-1"></span>
                        </div>
                    </div>
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
                        <input type="hidden" name="ID">
                        <input type="hidden" name="DataId">
                        <input type="hidden" name="TemplateId">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                                    <label for="kt_ecommerce_add_category_store_template" class="form-label required">
                                        Level
                                    </label>
                                    <select class="form-select form-select-sm form-select-solid mb-2"
                                        data-control="select2" data-hide-search="true" data-placeholder="Nível"
                                        name="StrengthenLevel">
                                        <option></option>
                                        <option value="0">Sem level</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">Nível {{ $i }}</option>
                                        @endfor
                                        <option value="13">Avanço 1</option>
                                        <option value="14">Avanço 2</option>
                                        <option value="15">Avanço 3</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-12" id="count_area">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span>⏳ Quantidade</span>
                                    </label>
                                    <input type="number" id="md-edit-reward-annex-in-max"
                                        class="form-control form-control-sm form-control-solid" min="1"
                                        max="1" name="ItemCount" value="1" />

                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="d-flex flex-column mb-5 fv-row col-6" id="md-annex-amount-area">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span>🎲 Probabilidade</span>
                                    </label>
                                    <input type="number" id="md-annex-in-max"
                                        class="form-control form-control-sm form-control-solid" placeholder=""
                                        step="1" min="1" name="Random" value="1" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-6" id="md-edit-reward-annex-valid-area">
                                    <label for="kt_ecommerce_add_category_store_template" class="form-label required">📅
                                        Validade</label>
                                    <select class="form-select form-select-sm form-select-solid mb-2"
                                        data-control="select2" data-hide-search="true" name="ItemValid">
                                        <option value="0" selected>Permanente</option>
                                        <option value="1">1 Dia</option>
                                        <option value="3">3 Dias</option>
                                        <option value="7">7 Dias</option>
                                        <option value="15">15 Dias</option>
                                        <option value="30">30 Dias</option>
                                        <option value="365">365 Dias</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="attr_area">
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔴 Atq.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="AttackCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔵 Def.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="DefendCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟢 Agl.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="AgilityCompose" value="0" />
                                </div>
                                <div class="d-flex flex-column mb-5 fv-row col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟡 Srt.</label>

                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        placeholder="Enter card number" name="LuckCompose" value="0" />
                                </div>
                            </div>
                            <div class="d-flex flex-stack mb-5">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">💾 Log</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Exibe no chat quando o item for dropado.
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsTips"
                                        value="1" checked="checked" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-stack mb-5">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">❓ isTips</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        desconhecido.
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsLogs"
                                        value="1" checked="checked" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-stack mb-5">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🟢 Ilimitado</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se desmarcado o item <span class="text-success">poderá ser enviado</span>.
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind"
                                        value="1" checked="checked" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-stack">
                                <div class="me-5">
                                    <label class="fs-6 fw-bold form-label">🤚 Garantido</label>
                                    <div class="fs-7 fw-bold text-muted">
                                        Se marcado o item <span class="text-success">será garantido</span>
                                        ao abrir o pacote.
                                    </div>
                                </div>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsSelect"
                                        value="1" />
                                    <span class="form-check-label fw-bold text-muted"></span>
                                </label>
                            </div>
                            <div class="text-center pt-10">
                                <button type="button" onclick="itemBox.update()" id="btn-box-reward-update"
                                    class="btn btn-sm btn-light-primary w-100">
                                    <span class="indicator-label">Aplicar alterações</span>
                                    <span class="indicator-progress">
                                        aplicando...
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
    <script src="{{ url() }}/assets/js/admin/item/helper.js"></script>
    <script src="{{ url() }}/assets/js/admin/item/list.js"></script>
@endsection
