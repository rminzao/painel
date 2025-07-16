@extends('layouts.app')

@section('title', 'Shop')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🏬 Shop</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Shop</li>
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

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="shop.updateOnGame()">
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
                                        data-control="select2" data-placeholder="Categoria da loja">
                                        <option value="0" selected>Todos</option>
                                        @foreach ($shopTypes as $key => $type)
                                            <option value="{{ $key }}">
                                                {{ $key }} -
                                                {{ $type['name'] == '' ? $type['prefix'] : $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#md_new_shop">
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
                            <div class="scroll-y me-n5 h-lg-auto" id="shop_list" style="display: none;"></div>
                            <div class="mt-8" id="item_paginator"></div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="shop_data_body">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um item para continuar',
                            ])
                        </div>
                        <div id="shop_data" style="display: none;">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <!--begin::User-->
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#shop_detail">📃
                                                    Detalhes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#shop_show_detail">📃
                                                    Show List</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <div id="detail_buttons">
                                        <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="shop.update(this)">
                                            <span class="indicator-label">Aplicar alterações</span>
                                            <span class="indicator-progress">
                                                aplicando...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                    <div id="parts_buttons" style="display:none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_shop_show"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20"
                                                        height="20" rx="5" fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2"
                                                        rx="1" transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2"
                                                        rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar para loja
                                        </button>
                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="shop_detail" role="tabpanel">
                                    <div class="card-body">
                                        <form>
                                            <input type="hidden" name="ID" />
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">💾 Tipo de loja</label>
                                                        <select name="ShopID" class="form-select form-select-solid"
                                                            data-control="select2" data-tags="true"
                                                            data-placeholder="Tipo de moeda">
                                                            @foreach ($moneyTypes as $key => $value)
                                                                <option value="{{ $key }}">{{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Tipo de moeda ultilizada na compra do item
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">📆 Data inicial</label>
                                                        <input class="form-control form-control-solid" name="StartDate" />
                                                        <div class="fs-7 fw-bold text-muted">
                                                            data em que o item irá ficar disponpivel na loja
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">📆 Data final</label>
                                                        <input class="form-control form-control-solid" name="EndDate" />
                                                        <div class="fs-7 fw-bold text-muted">
                                                            data <span class="text-danger">limite</span> em que o item
                                                            irá ficar disponpivel na loja
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🏷️ TemplateID</label>
                                                        <input type="number" class="form-control form-control-solid"
                                                            name="TemplateID" value="" />
                                                        <div class="fs-7 fw-bold text-muted">
                                                            ID do item (referência: <span
                                                                class="text-primary">Shop_Goods</span>)
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎲 Sort</label>
                                                        <input type="number" class="form-control form-control-solid"
                                                            name="Sort" value="" />
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Ordem de exibição (padrão: <span class="text-primary">0</span>)
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">📈 LimitCount</label>
                                                        <input type="number" class="form-control form-control-solid"
                                                            name="LimitCount" value="" />
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Vezes que o item pode ser vendido (padrão: <span
                                                                class="text-primary">-1</span>)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">👛 BuyType</label>
                                                        <select name="BuyType" class="form-select form-select-solid"
                                                            data-control="select2" data-tags="true"
                                                            data-hide-search="true" data-placeholder="BuyType">
                                                            <option value="0">📆 Dias</option>
                                                            <option value="1">🚃 Quantidade</option>
                                                        </select>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎟 Label</label>
                                                        <select name="Label" class="form-select form-select-solid"
                                                            data-control="select2" data-placeholder="Tipo de moeda">
                                                            @foreach ($labels as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ !$loop->first ?: 'selected' }}>
                                                                    {{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="fs-7 fw-bold text-muted">
                                                            Card exibido encima do item na loja
                                                        </div>
                                                    </div>
                                                    <div class="fv-row mb-7 col-4">
                                                        <label class="fs-6 fw-bold form-label mb-2">❓ Beat</label>
                                                        <input type="number" class="form-control form-control-solid"
                                                            name="Beat" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4 d-flex flex-column">
                                                        <div class="fv-row mb-7">
                                                            <label class="fs-6 fw-bold form-label mb-2"
                                                                id="unit_label">AUnit (<span
                                                                    class="text-primary">Dias</span>)</label>
                                                            <input type="number" class="form-control form-control-solid"
                                                                name="AUnit" value="" />
                                                        </div>

                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">APrice1</label>
                                                                <select name="APrice1"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">AValue1</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="AValue1"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">APrice2</label>
                                                                <select name="APrice2"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">AValue2</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="AValue2"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">APrice3</label>
                                                                <select name="APrice3"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">AValue3</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="AValue3"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 d-flex flex-column">
                                                        <div class="fv-row mb-7">
                                                            <label class="fs-6 fw-bold form-label mb-2"
                                                                id="unit_label">BUnit (<span
                                                                    class="text-primary">Dias</span>)</label>
                                                            <input type="number" class="form-control form-control-solid"
                                                                name="BUnit" value="" />
                                                        </div>

                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BPrice1</label>
                                                                <select name="BPrice1"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BValue1</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="BValue1"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BPrice2</label>
                                                                <select name="BPrice2"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BValue2</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="BValue2"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BPrice3</label>
                                                                <select name="BPrice3"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">BValue3</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="BValue3"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 d-flex flex-column">
                                                        <div class="fv-row mb-7">
                                                            <label class="fs-6 fw-bold form-label mb-2"
                                                                id="unit_label">CUnit (<span
                                                                    class="text-primary">Dias</span>)</label>
                                                            <input type="number" class="form-control form-control-solid"
                                                                name="CUnit" value="" />
                                                        </div>

                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CPrice1</label>
                                                                <select name="CPrice1"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CValue1</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="CValue1"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CPrice2</label>
                                                                <select name="CPrice2"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CValue2</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="CValue2"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CPrice3</label>
                                                                <select name="CPrice3"
                                                                    class="form-select form-select-solid"
                                                                    data-control="select2"
                                                                    data-placeholder="Tipo de moeda">
                                                                    @foreach ($moneyBuyTypes as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="fv-row mb-7 col-6">
                                                                <label class="fs-6 fw-bold form-label mb-2">CValue3</label>
                                                                <input type="number"
                                                                    class="form-control form-control-solid" name="CValue3"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="d-flex flex-stack mb-7">
                                                        <div class="me-5">
                                                            <label class="fs-6 fw-bold form-label">IsCheap</label>
                                                            <div class="fs-7 fw-bold text-muted"> Se desmarcado o item
                                                                <span class="text-primary">será exibido</span> na tabela
                                                                de preços baixos na loja do jogo
                                                            </div>
                                                        </div>
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input h-20px w-30px" type="checkbox"
                                                                name="IsCheap" value="1" checked />
                                                            <span class="form-check-label fw-bold text-muted"></span>
                                                        </label>
                                                    </div>
                                                    <div class="d-flex flex-stack mb-7">
                                                        <div class="me-5">
                                                            <label class="fs-6 fw-bold form-label">IsContinue</label>
                                                            <div class="fs-7 fw-bold text-muted">
                                                                desconhecido (padrão: <span
                                                                    class="text-primary">desconhecido</span>)
                                                            </div>
                                                        </div>
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input h-20px w-30px" type="checkbox"
                                                                name="IsContinue" value="1" checked />
                                                            <span class="form-check-label fw-bold text-muted"></span>
                                                        </label>
                                                    </div>
                                                    <div class="d-flex flex-stack mb-7">
                                                        <div class="me-5">
                                                            <label class="fs-6 fw-bold form-label">IsBind</label>
                                                            <div class="fs-7 fw-bold text-muted"> Se desmarcado o item irá
                                                                ficar <span class="text-success">ilimitado</span> (<span
                                                                    class="text-primary">pode ser enviado</span>) </div>
                                                        </div>
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input h-20px w-30px" type="checkbox"
                                                                name="IsBind" value="1" checked />
                                                            <span class="form-check-label fw-bold text-muted"></span>
                                                        </label>
                                                    </div>
                                                    <div class="d-flex flex-stack mb-7">
                                                        <div class="me-5">
                                                            <label class="fs-6 fw-bold form-label">IsVouch</label>
                                                            <div class="fs-7 fw-bold text-muted">
                                                                desconhecido (padrão: <span
                                                                    class="text-primary">desconhecido</span>)
                                                            </div>
                                                        </div>
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input h-20px w-30px" type="checkbox"
                                                                name="IsVouch" value="1" checked />
                                                            <span class="form-check-label fw-bold text-muted"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="shop_show_detail" role="tabpanel">
                                    <div class="card-body p-4">
                                        <div id="show_body">
                                            <div id="no_shows">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem exibição',
                                                    'message' => 'esse item não possui nenhuma exibição',
                                                ])
                                            </div>
                                            <div class="highlight w-100 p-2 pt-0 overflow-auto mh-400px"
                                                id="shop_show_list" style="display:none;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_new_shop" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-850px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-6">🛒 Novo item (shop)</h3>
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
                                <div class="fv-row mb-7">
                                    <label for="itemID" class="form-label required">📦 Item</label>
                                    <select class="form-select form-select-solid" data-dropdown-parent="#md_new_shop"
                                        data-placeholder="Selecione um item" data-allow-clear="true" id="itemID"
                                        name="itemID">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📆 Data inicial</label>
                                    <input class="form-control form-control-solid" name="StartDate"
                                        value="2000-01-01 23:59:59.000" />
                                    <div class="fs-7 fw-bold text-muted">
                                        data em que o item irá ficar disponpivel na loja
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">📆 Data final</label>
                                    <input class="form-control form-control-solid" name="EndDate"
                                        value="2050-01-01 23:59:59.000" />
                                    <div class="fs-7 fw-bold text-muted">
                                        data <span class="text-danger">limite</span> em que o item irá ficar disponpivel
                                        na loja
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">💾 Tipo de loja</label>
                                    <select name="ShopID" class="form-select form-select-solid" data-hide-search="true"
                                        data-control="select2" data-tags="true" data-placeholder="Tipo de moeda">
                                        @foreach ($moneyTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }} </option>
                                        @endforeach
                                    </select>
                                    <div class="fs-7 fw-bold text-muted">
                                        Tipo de moeda ultilizada na compra do item
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">🎲 Sort</label>
                                    <input type="number" class="form-control form-control-solid" name="Sort"
                                        value="0" />
                                    <div class="fs-7 fw-bold text-muted">
                                        Ordem de exibição (padrão: <span class="text-primary">0</span>)
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">📈 LimitCount</label>
                                    <input type="number" class="form-control form-control-solid" name="LimitCount"
                                        value="-1" />
                                    <div class="fs-7 fw-bold text-muted">
                                        Vezes que o item pode ser vendido (padrão: <span class="text-primary">-1</span>)
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">👛 BuyType</label>
                                    <select name="BuyType" class="form-select form-select-solid" data-control="select2"
                                        data-tags="true" data-hide-search="true" data-placeholder="BuyType">
                                        <option value="0">📆 Dias</option>
                                        <option value="1">🚃 Quantidade</option>
                                    </select>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">🎟 Label</label>
                                    <select name="Label" class="form-select form-select-solid" data-control="select2"
                                        data-placeholder="Tipo de moeda">
                                        @foreach ($labels as $key => $label)
                                            <option value="{{ $key }}" {{ !$loop->first ?: 'selected' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="fs-7 fw-bold text-muted">
                                        Card exibido encima do item na loja
                                    </div>
                                </div>
                                <div class="fv-row mb-7 col-4">
                                    <label class="fs-6 fw-bold form-label mb-2">❓ Beat</label>
                                    <input type="number" class="form-control form-control-solid" name="Beat"
                                        value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 d-flex flex-column">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold form-label mb-2" id="unit_label">AUnit (<span
                                                class="text-primary">Dias</span>)</label>
                                        <input type="number" class="form-control form-control-solid" name="AUnit"
                                            value="1" />
                                    </div>

                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">APrice1</label>
                                            <select name="APrice1" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">AValue1</label>
                                            <input type="number" class="form-control form-control-solid" name="AValue1"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">APrice2</label>
                                            <select name="APrice2" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">AValue2</label>
                                            <input type="number" class="form-control form-control-solid" name="AValue2"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">APrice3</label>
                                            <select name="APrice3" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">AValue3</label>
                                            <input type="number" class="form-control form-control-solid" name="AValue3"
                                                value="0" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 d-flex flex-column">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold form-label mb-2" id="unit_label">BUnit (<span
                                                class="text-primary">Dias</span>)</label>
                                        <input type="number" class="form-control form-control-solid" name="BUnit"
                                            value="-1" />
                                    </div>

                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BPrice1</label>
                                            <select name="BPrice1" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BValue1</label>
                                            <input type="number" class="form-control form-control-solid" name="BValue1"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BPrice2</label>
                                            <select name="BPrice2" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BValue2</label>
                                            <input type="number" class="form-control form-control-solid" name="BValue2"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BPrice3</label>
                                            <select name="BPrice3" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">BValue3</label>
                                            <input type="number" class="form-control form-control-solid" name="BValue3"
                                                value="0" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 d-flex flex-column">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold form-label mb-2" id="unit_label">CUnit (<span
                                                class="text-primary">Dias</span>)</label>
                                        <input type="number" class="form-control form-control-solid" name="CUnit"
                                            value="-1" />
                                    </div>

                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CPrice1</label>
                                            <select name="CPrice1" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CValue1</label>
                                            <input type="number" class="form-control form-control-solid" name="CValue1"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CPrice2</label>
                                            <select name="CPrice2" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CValue2</label>
                                            <input type="number" class="form-control form-control-solid" name="CValue2"
                                                value="0" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CPrice3</label>
                                            <select name="CPrice3" class="form-select form-select-solid"
                                                data-dropdown-parent="#md_new_shop" data-control="select2"
                                                data-placeholder="Tipo de moeda">
                                                @foreach ($moneyBuyTypes as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row mb-7 col-6">
                                            <label class="fs-6 fw-bold form-label mb-2">CValue3</label>
                                            <input type="number" class="form-control form-control-solid" name="CValue3"
                                                value="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsCheap</label>
                                        <div class="fs-7 fw-bold text-muted"> Se desmarcado o item
                                            <span class="text-primary">será exibido</span> na tabela
                                            de preços baixos na loja do jogo
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsCheap"
                                            value="1" checked />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsContinue</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsContinue"
                                            value="1" checked />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsBind</label>
                                        <div class="fs-7 fw-bold text-muted"> Se desmarcado o item irá
                                            ficar <span class="text-success">ilimitado</span> (<span
                                                class="text-primary">pode ser enviado</span>) </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind"
                                            value="1" checked />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                                <div class="d-flex flex-stack mb-7">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">IsVouch</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            desconhecido (padrão: <span class="text-primary">desconhecido</span>)
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="IsVouch"
                                            value="1" checked />
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary w-100" onclick="shop.create()">
                                    <span class="indicator-label">Criar item</span>
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
    <div class="modal fade" id="md_new_shop_show" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Adicionar a loja</h3>
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
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="form-label fs-6 fw-bolder text-gray-700">🎲 ShopID</label>
                                <input name="shopID" class="form-control form-control-solid" readonly type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label for="ContainEquip" class="form-label required">📦 Tipo de loja</label>
                                <select name="type" class="form-select form-select-solid" data-control="select2"
                                    data-dropdown-parent="#md_new_shop_show" data-placeholder="Selecione a loja">
                                    <option value=""></option>
                                    @foreach ($shopTypes as $key => $type)
                                        <option value="{{ $key }}">
                                            {{ $type['name'] == '' ? $type['prefix'] : $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary w-100" onclick="shopShow.create()">
                                <span class="indicator-label">Adicionar a loja</span>
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
    <div class="modal fade" id="md_edit_shop_show" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Editando showList</h3>
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
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="form-label fs-6 fw-bolder text-gray-700">🎲 ShopID</label>
                                <input name="shopID" class="form-control form-control-sm form-control-solid" readonly
                                    type="text">
                                <input name="originalType" class="form-control form-control-solid" readonly
                                    type="hidden">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label for="ContainEquip" class="form-label required">📦 Tipo de loja</label>
                                <select name="type" class="form-select form-select-sm form-select-solid"
                                    data-control="select2" data-dropdown-parent="#md_edit_shop_show"
                                    data-placeholder="Tipo de moeda">
                                    @foreach ($shopTypes as $key => $type)
                                        <option value="{{ $key }}">
                                            {{ $type['name'] == '' ? $type['prefix'] : $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-sm btn-light-primary w-100"
                                onclick="shopShow.update()">
                                <span class="indicator-label">Aplicar alterações</span>
                                <span class="indicator-progress">
                                    aplicando..
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
    <script>
        const shopTypeList = @json($shopTypes),
            moneyTypeList = @json($moneyTypes);
    </script>
    <script src="{{ url() }}/assets/js/admin/shop/list.js"></script>
@endsection
