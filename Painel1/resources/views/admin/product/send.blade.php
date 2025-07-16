@extends('layouts.app') 

@section('title', 'Enviar recarga') 

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">💸 Enviar recarga</h1>

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

                    <li class="breadcrumb-item text-white opacity-75">Enviar mensagem</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="container">
                <div class="card" id="kt_block_ui_4_target">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-xl-row p-2">
                            <div class="flex-lg-row-fluid mb-10 mb-xl-0">
                                <div class="mb-0">
                                    <form action="{{ url('api/admin/product/send') }}" method="post">
                                        <div class="mb-5">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                                        <label class="fs-6 fw-bold mb-2">
                                                            <span class="required">🌍 Servidor</span>
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Selecione um servidor"
                                                                aria-label="Selecione um servidor"></i>
                                                        </label>

                                                        <select class="form-select form-select-solid" data-control="select2"
                                                            data-placeholder="Selecione um servidor" id="sid" name="sid">
                                                            <option></option>
                                                            @foreach ($servers as $server)
                                                                <option value="{{ $server->id }}"
                                                                    {{ !$loop->first ?: 'selected' }}>
                                                                    {{ $server->name }}
                                                                    {{ $server->active ? '' : '(servidor desligado)' }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                                        <label class="fs-6 fw-bold mb-2">
                                                            <span class="required">🕹️ Usuário</span>
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Selecione um usuário"
                                                                aria-label="Selecione um usuário"></i>
                                                        </label>

                                                        <select class="form-select form-select-solid" data-control="select2"
                                                            data-placeholder="Selecione um usuário" id="uid"
                                                            multiple="multiple" name="uid[]">
                                                            <option></option>
                                                        </select>

                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="fs-6 fw-bold mb-2 d-flex">
                                                    <div class="w-20px h-20px me-2"
                                                        style="background-image: url('{{ url() }}/assets/media/icons/cupon.png') ; background-size: cover;">
                                                    </div>
                                                    <span>Cupons</span>
                                                </label>

                                                <div class="position-relative d-flex align-items-center">
                                                    <input type="number" class="form-control form-control-solid" value="1"
                                                        min="1" step="1" name="ammount" />
                                                </div>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label for="" class="form-label">🏷️ Descrição</label>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                                    title="" data-bs-original-title="Informe o motivo"
                                                    aria-label="Informe o motivo"></i>
                                                <textarea class="form-control form-control form-control-solid" rows="4" name="payway" data-kt-autosize="true"
                                                    placeholder="Ex: 🎯 REEMBOLSO 1/2" maxlength="100"></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100" id="button_send_recharge">
                                            <span class="indicator-label">Enviar recarga</span>
                                            <span class="indicator-progress">Enviando... <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/product/send.js"></script>
@endsection 
