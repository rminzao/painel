@extends('layouts.app')

@section('title', 'Criar fatura')

@section('custom-js')
    <script src="{{ url() }}/assets/js/admin/invoice/create.js "></script>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🧾 Criar fatura</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Criar fatura</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                    <!--begin::Card-->
                    <div class="card" id="card_create">
                        <!--begin::Card body-->
                        <div class="card-body p-12">
                            <!--begin::Form-->
                            <form id="invoice-create-form">
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-5">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bolder fs-6 text-gray-700">🌏 Servidor</label>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select name="sid" data-control="select2" data-placeholder="Select server"
                                            class="form-select form-select-solid">
                                            @foreach ($servers as $server)
                                                <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                                    {{ $server->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-5">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bolder fs-6 text-gray-700">💣 Jogador</label>
                                        <!--end::Label-->
                                        <!--begin::Select-->
                                        <select name="uid" id="user-list" data-placeholder="Selecione o usuario"
                                            class="form-select form-select-solid">
                                            <option value=""></option>
                                        </select>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Input group-->

                                </div>
                                <!--begin::Wrapper-->
                                <div class="mb-5">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Input group-->
                                        <div class="col-6 mb-5">
                                            <label class="form-label fs-6 fw-bolder text-gray-700">💳 Método de
                                                pagamento</label>
                                            <select class="form-select form-select-solid"
                                                data-placeholder="Método de pagamento" name="method" id="payment_list">
                                                <option></option>
                                                <option value="picpay"
                                                    data-kt-select2-user="{{ url() }}/assets/media/payments/picpay.png"
                                                    selected>PicPay</option>
                                                <option value="mercadopago"
                                                    data-kt-select2-user="{{ url() }}/assets/media/payments/mercadopago.png">
                                                    MercadoPago</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="col-6 mb-5">
                                            <label class="form-label fs-6 fw-bolder text-gray-700">📦 Produto</label>
                                            <select class="form-select form-select-solid" data-control="select2"
                                                data-placeholder="Selecione o produto" name="pid" id="product-list">
                                                <option></option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fs-6 fw-bolder text-gray-700">🏷️ Valor</label>
                                        <input type="text" class="form-control form-control-solid" name="price"
                                            placeholder="Ex: 100.98" />
                                    </div>
                                    <!--begin::Notes-->
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bolder text-gray-700">📝 Nota</label>
                                        <textarea name="note" class="form-control form-control-solid" rows="3"
                                            placeholder="Ex: Pagamento realizado fora do site via pix."></textarea>
                                    </div>
                                    <!--end::Notes-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Input group-->

                                <div class="d-flex flex-stack mb-5">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">Pagamento aprovado</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se desmarcado o status da fatura ficará como <span
                                                class="text-warning">pendente</span>.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="approved"
                                            value="1" checked="checked">
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>

                                <div class="d-flex flex-stack mb-5">
                                    <div class="me-5">
                                        <label class="fs-6 fw-bold form-label">Enviar cupons</label>
                                        <div class="fs-7 fw-bold text-muted">
                                            Se marcado o jogador selecionado <span class="text-primary">receberá a
                                                quantidade de cupons </span> definida no produto selecionado.
                                        </div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input h-20px w-30px" type="checkbox" name="send" value="1">
                                        <span class="form-check-label fw-bold text-muted"></span>
                                    </label>
                                </div>

                                <!--begin::Actions-->
                                <div class="mb-0">
                                    <button type="button" class="btn btn-primary w-100 mt-5" id="invoice-create-form-submit">
                                        <span class="indicator-label"> 
                                            Criar fatura
                                            {!! getSvgIcon('general/gen057.svg', 'svg-icon svg-icon-3') !!}
                                        </span>
                                        <span class="indicator-progress">
                                            gerando fatura...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Post-->
    </div>
@endsection
