@extends('layouts.app')

@section('title', 'Novo Ticket')

@section('custom-js')
    <script src="{{ url() }}/assets/js/app/ticket/ticket.js"></script>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📮 Novo ticket</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">Suporte</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">Novo</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-3 py-md-1">
                <!--begin::Buttons-->

                <a href="{{ url('app/me/ticket/list') }}"
                    class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-primary fw-bolder">Voltar</a>
                <!--end::Buttons-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Layout-->
                        <div class="d-flex flex-column flex-xl-row p-7">
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid mb-20 mb-xl-0">
                                <!--begin::Ticket view-->
                                <div class="mb-0">
                                    <!--begin::Details-->
                                    <div class="mb-0">
                                        <form action="{{ url('api/ticket/new') }}" enctype="multipart/form-data"
                                            method="post" id="new_ticket_form">
                                            <div class="row mb-5">
                                                <div class="col-lg-8">
                                                    <div class="me-n7 pe-7">
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-7 fv-plugins-icon-container">
                                                            <!--begin::Label-->
                                                            <label class="fs-6 fw-bold mb-2">
                                                                <span class="required">Assunto</span>
                                                                <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Informe um assunto"
                                                                    aria-label="Informe um assunto"></i>
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input type="text" class="form-control form-control-solid"
                                                                placeholder="" name="title" value="">
                                                            <!--end::Input-->
                                                            <div class="fv-plugins-message-container invalid-feedback">
                                                            </div>
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Input group-->
                                                        <div class="fv-row mb-7">
                                                            <label for="" class="form-label required">Description</label>
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Informe um assunto"
                                                                aria-label="Descreva o motivo do chamado"></i>
                                                            <textarea class="form-control form-control form-control-solid"
                                                                rows="4" name="content" data-kt-autosize="true"></textarea>
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <!--begin::Input group-->
                                                    <div class="form-group">
                                                        <!--begin::Label-->
                                                        <label for="" class="form-label">Anexos:</label>
                                                        <!--end::Label-->
                                                        <!--begin::Dropzone-->
                                                        <div class="dropzone dropzone-queue mb-2">
                                                            <input type="file" name="attachments[]" id="attachments"
                                                                multiple>
                                                        </div>
                                                        <!--end::Dropzone-->

                                                        <!--begin::Hint-->
                                                        <span class="form-text text-muted">Envie no maximo 4 arquivos de no
                                                            maximo 6mb cada.</span>
                                                        <!--end::Hint-->
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary" id="button_send_ticket"
                                                    style="width:100%;">
                                                    <span class="indicator-label">Enviar</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <!--end::Ticket view-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection
