@extends('layouts.app')

@section('title', 'Enviar mensagem')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📢 Enviar mensagem</h1>
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
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-xl-row p-0">
                            <div class="flex-lg-row-fluid mb-20 mb-xl-0">
                                <div class="mb-0">
                                    <div class="mb-0">
                                        <form action="{{ url('api/admin/server/message/send') }}" method="post">
                                            <div class="row">
                                                <div class="me-n7 pe-7">
                                                    <div class="fv-row mb-7 fv-plugins-icon-container">
                                                        <label class="fs-6 fw-bold mb-2">
                                                            🌍 Servidor
                                                        </label>
                                                        <select class="form-select form-select-solid"
                                                            data-hide-search="true" data-control="select2"
                                                            data-placeholder="Selecione o servidor" id="sid"
                                                            name="sid">
                                                            @foreach ($servers as $server)
                                                                <option value="{{ $server->id }}"
                                                                    {{ !$loop->first ?: 'selected' }}>
                                                                    {{ $server->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    </div>
                                                    <div class="fv-row mb-7">
                                                        <label for="" class="form-label required">
                                                            📢 Mensagem
                                                        </label>
                                                        <textarea class="form-control form-control form-control-solid" rows="4" name="content" data-kt-autosize="true"
                                                            placeholder="Ex: Nova recarga disponível 💸"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-sm btn-primary w-100"
                                                    id="button_send_ticket">
                                                    <span class="indicator-label">Enviar mensagem</span>
                                                    <span class="indicator-progress">Enviando...
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/server/message.js"></script>
@endsection
