@extends('layouts.app')

@section('title', 'Conjuntos')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🎩 Conjuntos</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Conjuntos</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <div class="me-2">
                    <select name="sid" class="form-select" data-control="select2" data-hide-search="true"
                        data-placeholder="Selecione um Servidor">
                        @foreach ($servers as $server)
                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>{{ $server->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn btn-light-primary" id="update_on_game" onclick="suit.updateOnGame()">
                    <span class="indicator-label">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
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
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#suit_body">🎩 Conjuntos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#skill_body">🔥 Atributos</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="suit_body" role="tabpanel">
                                <div class="p-5 pt-7 pb-0">
                                    <div class="w-100 position-relative d-flex" autocomplete="off">
                                        <div class="input-group me-4">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="Nome/id do conjunto" name="search" />
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#md_new_suit">
                                            novo
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-4 ps-7">
                                    <div id="not_results">
                                        @include('components.default.notfound', [
                                            'title' => 'Opss',
                                            'message' => 'nenhum conjunto encontrado',
                                        ])
                                    </div>
                                    <div class="scroll-y me-n5 h-lg-auto" id="suit_list" style="display: none;"></div>

                                    <div class="" id="item_paginator"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="skill_body" role="tabpanel">
                                <div class="p-5 pt-7 pb-0">
                                    <div class="w-100 position-relative d-flex" autocomplete="off">
                                        <div class="input-group me-4">
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                placeholder="id do atributo" name="search" />
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#md_new_skill">
                                            novo
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pt-4 ps-7">
                                    <div id="not_skill_results">
                                        @include('components.default.notfound', [
                                            'title' => 'Opss',
                                            'message' => 'nenhuma skill encontrada',
                                        ])
                                    </div>
                                    <div class="scroll-y me-n5 h-lg-auto" id="skill_list" style="display: none;"></div>

                                    <div class="" id="item_skill_paginator"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card" id="suit_data_body">
                        <div id="not_selected">
                            @include('components.default.notfound', [
                                'title' => 'Sem dados',
                                'message' => 'clique em um conjunto para continuar',
                            ])
                        </div>
                        <div id="suit_data" style="display: none;">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <div class="card-title">
                                    <!--begin::User-->
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#suit_detail">📃
                                                    Detalhes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#suit_parts">🚅
                                                    Partes</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <div id="parts_buttons" style="display:none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_part"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                        fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar parte
                                        </button>
                                    </div>
                                    <div id="attributes_buttons" style="display:none;">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#md_new_attribute"
                                            class="btn btn-light-primary btn-sm">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                                        fill="currentColor">
                                                    </rect>
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)" fill="currentColor"></rect>
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor">
                                                    </rect>
                                                </svg>
                                            </span>
                                            Adicionar atributo
                                        </button>
                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body" id="kt_chat_messenger_body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="suit_detail" role="tabpanel">
                                        <form>
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 ID</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="SuitId" readonly value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🎯 Nome</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="SuitName" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount1</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="EqipCount1" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧵 Skill1</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="Skill1" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🧻 SkillDescribe1</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe1"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount2</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="EqipCount2" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧵 Skill2</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="Skill2" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🧻 SkillDescribe2</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe2"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount3</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="EqipCount3" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧵 Skill3</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="Skill3" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🧻 SkillDescribe3</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe3"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount4</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="EqipCount4" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧵 Skill4</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="Skill4" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🧻 SkillDescribe4</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe4"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount5</label>
                                                        <input type="number" class="form-control form-control-sm form-control-solid"
                                                            name="EqipCount5" value="" />
                                                    </div>
                                                    <div class="fv-row mb-7 col-6">
                                                        <label class="fs-6 fw-bold form-label mb-2">🧵 Skill5</label>
                                                        <input type="text" class="form-control form-control-sm form-control-solid"
                                                            name="Skill5" value="" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label">🧻 SkillDescribe5</label>
                                                        <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe5"
                                                            data-kt-autosize="true"></textarea>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <button type="button" class="btn btn-sm btn-light-primary w-100"
                                                        onclick="suit.update()">
                                                        <span class="indicator-label">Aplicar alterações</span>
                                                        <span class="indicator-progress">
                                                            aplicando...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="suit_parts" role="tabpanel">
                                        <div id="parts_body">
                                            <div id="no_parts">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem partes',
                                                    'message' => 'esse conjuto não possui nenhuma parte',
                                                ])
                                            </div>
                                            <div class="highlight w-100 pt-5 pb-2 overflow-auto mh-400px" id="part_list"
                                                style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="suit_attributes" role="tabpanel">
                                        <div id="attributes_body">
                                            <div id="no_attributes">
                                                @include('components.default.notfound', [
                                                    'title' => 'Sem atributos',
                                                    'message' => 'esse conjunto não possui nenhum atributo',
                                                ])
                                            </div>
                                            <div class="highlight w-100 pt-5 pb-2 overflow-auto mh-400px" id="rewards_list"
                                                style="display:none;">
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
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_new_suit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Novo conjunto</h3>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-12">
                                    <label class="fs-6 fw-bold form-label mb-2">🎯 Nome</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="SuitName" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount1</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="EqipCount1"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧵 Skill1</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Skill1" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🧻 SkillDescribe1</label>
                                    <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe1"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount2</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="EqipCount2"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧵 Skill2</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Skill2" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🧻 SkillDescribe2</label>
                                    <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe2"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount3</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="EqipCount3"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧵 Skill3</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Skill3" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🧻 SkillDescribe3</label>
                                    <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe3"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount4</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="EqipCount4"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧵 Skill4</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Skill4" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🧻 SkillDescribe4</label>
                                    <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe4"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧾 EqipCount5</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="EqipCount5"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🧵 Skill5</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Skill5" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7">
                                    <label for="" class="form-label">🧻 SkillDescribe5</label>
                                    <textarea class="form-control form-control-sm form-control-solid" rows="1" name="SkillDescribe5"
                                        data-kt-autosize="true"></textarea>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="suit.create()">
                                    <span class="indicator-label">Criar conjunto</span>
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
    <div class="modal fade" id="md_new_skill" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Nova skill</h3>
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
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-12">
                                    <label class="fs-6 fw-bold form-label mb-2">💖 Vida</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Blood" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🤺 Dano</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Damage" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🎒 Armadura</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Armor" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🟣 Ataque Mag.</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="MagickAttack"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6 ">
                                    <label class="fs-6 fw-bold form-label mb-2">🔷 Defesa Mag.</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="MagickDefence"
                                        value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔺 Ataque</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Attack" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔹 Defesa</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Defence" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟢 Agilidade</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Agility" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟨 Sorte</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Luck" value="" />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="suitSkill.create()">
                                    <span class="indicator-label">Criar skill</span>
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
    <div class="modal fade" id="md_edit_skill" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5">Editando skill</h4>
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
                        <input type="hidden" class="form-control form-control-sm form-control-solid" name="SkilID" value="" />
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="fv-row mb-7 col-12">
                                    <label class="fs-6 fw-bold form-label mb-2">💖 Vida</label>
                                    <input type="text" class="form-control form-control-sm form-control-solid" name="Blood" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🤺 Dano</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Damage" value="" />
                                </div>
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🎒 Armadura</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Armor" value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-6">
                                    <label class="fs-6 fw-bold form-label mb-2">🟣 Ataque Mag.</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="MagickAttack"
                                        value="" />
                                </div>
                                <div class="fv-row mb-7 col-6 ">
                                    <label class="fs-6 fw-bold form-label mb-2">🔷 Defesa Mag.</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="MagickDefence"
                                        value="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔺 Ataque</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Attack" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🔹 Defesa</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Defence" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟢 Agilidade</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Agility" value="" />
                                </div>
                                <div class="fv-row mb-7 col-3">
                                    <label class="fs-6 fw-bold form-label mb-2">🟨 Sorte</label>
                                    <input type="number" class="form-control form-control-sm form-control-solid" name="Luck" value="" />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="suitSkill.update()">
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
    <div class="modal fade" id="md_new_part" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Nova parte</h3>
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
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="form-label fs-6 fw-bolder text-gray-700">🎲 Nome</label>
                                <input name="PartName" class="form-control form-control-sm form-control-solid" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label for="ContainEquip" class="form-label required">📦 Item's</label>
                                <select class="form-select form-select-solid" data-dropdown-parent="#md_new_part"
                                    data-placeholder="Selecione um item" data-allow-clear="true" id="ContainEquip"
                                    name="ContainEquip">
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="suitPart.create()">
                                <span class="indicator-label">Criar parte</span>
                                <span class="indicator-progress">
                                    criando...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_edit_part" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-400px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Editar parte</h3>
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
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="form-label fs-6 fw-bolder text-gray-700">🎲 Nome</label>
                                <input name="PartName" class="form-control form-control-sm form-control-solid" type="text">
                                <input name="PartNameOriginal" class="form-control form-control-sm form-control-solid" type="hidden">
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label for="ContainEquip" class="form-label required">📦 Item's</label>
                                <select class="form-select form-select-solid" multiple="multiple"
                                    data-dropdown-parent="#md_edit_part" data-placeholder="Selecione um item"
                                    data-allow-clear="true" id="ContainEquipEdit" name="ContainEquip">
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-sm btn-light-primary w-100" onclick="suitPart.update()">
                                <span class="indicator-label">Aplicar alterações</span>
                                <span class="indicator-progress">
                                    aplicando...
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
    <script src="{{ url() }}/assets/js/admin/suit/list.js"></script>
@endsection
