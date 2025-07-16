@extends('layouts.app')

@section('title', 'Recarga ' . $server->name)

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">💰 Recarga</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url('app/recarga') }}" class="text-white text-hover-primary">Recarga</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">{{ $server->name }}</li>
                </ul>
            </div>
            <div class="d-flex align-items-center py-3 py-md-1">
                <a href="{{ url('app/recarga') }}" class="btn btn-sm btn-light-primary">
                    Selecionar outro servidor</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="row g-10">
                @if (empty($products))
                    <div class="card">
                        @include('components.default.notfound', [
                            'title' => 'Opss',
                            'message' => "O servidor <span class=\"text-primary\">$server->name</span> não há produtos disponíveis para comprar.",
                        ])
                    </div>
                @else
                    @each('components.recharge.product', $products, 'info', 'components.recharge.empty')
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="kt_modal_product_detail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content" id="md_product_detail">
                <div class="modal-header">
                    <h4 class="modal-title" id="product_name"></h4>
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
                <div class="modal-body scroll-y pt-0 pb-3">
                    <div class="mw-lg-600px mx-auto" id="content_md">
                        <div class="row align-items-center text-center mt-10">
                            <div class="col-3 flex-grow-1">
                                <span class="d-flex align-items-center fs-6 fw-bold text-gray-800 w-100 ms-3">
                                    <span class="me-2">R$ </span>
                                    <div class="d-flex flex-column">
                                        <span class="text-danger opacity-50" id="product_old_value"
                                            style="display:none;"><del></del></span>
                                        <span class="text-primary" id="product_value"></span>
                                    </div>
                                </span>
                                <span class="fs-7 fw-bold text-muted">preço</span>
                            </div>
                            <div class="col-3 flex-grow-1" id="cupon_info">
                                <span class="fs-6 fw-bold text-gray-800 d-block" id="product_amount"></span>
                                <span class="fs-7 fw-bold text-muted">cupons</span>
                            </div>
                            <div class="col-3 flex-grow-1">
                                <span class="fs-6 fw-bold text-gray-800 d-block">
                                    {{ $server->name }}
                                </span>
                                <span class="fs-7 fw-bold text-muted">servidor</span>
                            </div>
                            <div class="col-3 flex-grow-1">
                                <span class="fs-6 fw-bold text-gray-800 d-block">
                                    {{ $character->NickName }}
                                </span>
                                <span class="fs-7 fw-bold text-muted">personagem</span>
                            </div>
                        </div>
                        <div class="mt-5">
                            <span class="fs-7 fw-bold text-gray-800 d-block mb-3">
                                🏷️ Possui um código promocional ?
                            </span>
                            <div class="d-flex">
                                <input type="text" class="form-control form-control-solid w-80 me-3"
                                    name="promotion-code" placeholder="Opicional...">
                                <button class="btn btn-light-primary fw-bold w-20" id="btn_check_code"
                                    onclick="recharge.checkCode()">
                                    <span class="indicator-label">
                                        Utilizar
                                    </span>
                                    <span class="indicator-progress">
                                        Aguarde <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="text-muted fs-8" id="product_discount" style="display:none;">
                                O código foi aplicado com sucesso, você obteve R$<span class="link-primary"></span> de
                                desconto.
                            </div>
                        </div>
                        <div class="mt-5 p-3 highlight" id="laboratory_info">
                            <span class="">
                                O pacote de laboratório <span class="text-warning">só pode ser comprado 1 vez</span>, e
                                <span class="text-warning">não contabiliza como recarga</span>, logo
                                eventos de recarga não serão completos.
                            </span>
                        </div>
                        <div id="rewards" class="mt-5">
                            <span class="fs-7 fw-bold text-gray-800 d-block mb-3">
                                📦 Você irá receber os seguintes itens:
                            </span>
                            <div id="rewards-list"
                                class="d-flex flex-wrap justify-content-center rounded highlight p-5 pb-0"></div>
                        </div>
                        <div class="mt-5 p-3 highlight" id="pix_alert">
                            <span class="">
                                Pagamentos realizados com método <span class="fs-5 fw-bolder"
                                    style="color: #4AB7A8!important;">pix</span>, terão um bônus de <span
                                    class="fs-5 fw-bolder" style="color: #4AB7A8!important;">10%</span> de cupons na
                                quantidade
                                total da recarga.
                            </span>
                        </div>
                        <div class="mt-5">
                            <span class="fs-7 fw-bold text-gray-800 d-block mb-3">
                                💳 Selecione o método de pagamento:
                            </span>
                            <div class="d-flex flex-column">
                                <div class="btn btn-sm text-white w-100 fs-4 mb-4" id="btn_pix"
                                    style="background-color: #4AB7A8!important;">
                                    <span class="me-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            viewBox="0 0 36 36" style="width: 23px;">
                                            <g fill="#fff">
                                                <path
                                                    d="M9.125 26.761a4.717 4.717 0 0 0 3.362-1.392l4.853-4.853a.926.926 0 0 1 1.278 0l4.872 4.873a4.726 4.726 0 0 0 3.364 1.39h.957l-6.15 6.147a4.911 4.911 0 0 1-6.953 0l-6.175-6.165h.592zM26.854 9.588a4.724 4.724 0 0 0-3.362 1.391l-4.865 4.875a.904.904 0 0 1-1.277 0L12.497 11a4.715 4.715 0 0 0-3.362-1.392h-.602l6.175-6.169a4.917 4.917 0 0 1 6.953 0l6.15 6.148h-.957z">
                                                </path>
                                                <path
                                                    d="M3.442 14.707l3.672-3.673h2.011c.882.001 1.727.35 2.353.97l4.853 4.853a2.327 2.327 0 0 0 3.3 0l4.872-4.872a3.346 3.346 0 0 1 2.353-.97h2.384l3.688 3.688a4.917 4.917 0 0 1 0 6.953l-3.688 3.688h-2.386c-.881 0-1.727-.35-2.353-.97L19.63 19.5a2.389 2.389 0 0 0-3.3 0l-4.853 4.853c-.626.62-1.471.97-2.353.97h-2.01L3.442 21.66a4.915 4.915 0 0 1 0-6.953z">
                                                </path>
                                            </g>
                                        </svg>
                                    </span>
                                    Pix
                                </div>
                                <div class="d-flex">
                                    <div class="btn btn-light-primary w-50 me-4" id="btn_mp">
                                        <img alt="Logo" src="{{ url('assets/media/payments/mercadopago.png') }}"
                                            class="h-30px me-3" />
                                        M.Pago
                                    </div>
                                    <div class="btn btn-light-success w-50" id="btn_picpay" style="line-height: 30px;">
                                        <span class="svg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 984.47 317.57"
                                                style="width: 5rem;height: 2rem;">
                                                <path
                                                    d="M208.51,595.65h46V461.48h-46Zm61.92-225.47h-31v31h31ZM96.56,385.26H52.1v38.91H93.39c26.2,0,41.28,12.7,41.28,36.52S119.59,498,93.39,498H52.1V425h-46V595.65h46V536.9H95.77c53.19,0,84.16-28.58,84.16-77.8C179.93,413.05,149.76,385.26,96.56,385.26Zm204.84-46H208.51V432.1H301.4Zm-15.09,77H224.39V354.3h61.92Zm269.94-31H514.17v38.91h39.69c26.2,0,41.29,12.7,41.29,36.52S580.06,498,553.86,498H514.17V425h-46V595.65h46V536.9h42.08c53.19,0,84.15-28.58,84.15-77.8C640.4,413.05,609.44,385.26,556.25,385.26Zm386.64,49.23-39.7,100-39.7-100H815.86l63.51,161.16-24.61,61.13h48.43l87.33-222.29Zm-206.42-.8c-27.79,0-49.23,6.35-73.05,18.26l14.3,31.76c16.67-9.53,33.34-14.29,48.42-14.29,22.23,0,33.35,9.53,33.35,27v3.18H715c-39.7,0-61.13,18.26-61.13,48.43,0,29.37,20.64,50,55.57,50,22.23,0,38.11-7.93,50.81-21.43v17.46h45.26V489.27C804,455.13,779.34,433.69,736.47,433.69Zm27,108c-4.76,13.49-18.26,24.61-37.32,24.61-15.87,0-25.4-7.94-25.4-20.64s8.73-18.26,26.2-18.26h36.52ZM372.05,560.72c-22.23,0-38.1-17.47-38.1-43.67,0-25.4,15.87-42.87,38.1-42.87,15.88,0,27.79,6.35,36.53,17.47l31-22.23c-14.29-21.44-38.9-34.14-69.87-34.14-48.43-.79-81.77,32.55-81.77,81.77s33.34,81.78,81.77,81.78c33.35,0,58-13.5,71.46-35.73l-31.76-21.43C401.43,554.37,388.73,560.72,372.05,560.72Z"
                                                    transform="translate(-6.06 -339.22)" fill="#21c25e"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted fs-8 mt-5">
                            Ao comprar este pacote, você esta de acordo com os <a href="#"
                                class="link-primary">Termos &
                                Condições</a>.
                        </div>
                    </div>
                    <div class="mw-lg-600px mx-auto" id="picpay_content" style="display:none;">
                        <div class="d-flex justify-content-center mt-5">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 984.47 317.57"
                                style="width: 7rem;height: 3rem;">
                                <path
                                    d="M208.51,595.65h46V461.48h-46Zm61.92-225.47h-31v31h31ZM96.56,385.26H52.1v38.91H93.39c26.2,0,41.28,12.7,41.28,36.52S119.59,498,93.39,498H52.1V425h-46V595.65h46V536.9H95.77c53.19,0,84.16-28.58,84.16-77.8C179.93,413.05,149.76,385.26,96.56,385.26Zm204.84-46H208.51V432.1H301.4Zm-15.09,77H224.39V354.3h61.92Zm269.94-31H514.17v38.91h39.69c26.2,0,41.29,12.7,41.29,36.52S580.06,498,553.86,498H514.17V425h-46V595.65h46V536.9h42.08c53.19,0,84.15-28.58,84.15-77.8C640.4,413.05,609.44,385.26,556.25,385.26Zm386.64,49.23-39.7,100-39.7-100H815.86l63.51,161.16-24.61,61.13h48.43l87.33-222.29Zm-206.42-.8c-27.79,0-49.23,6.35-73.05,18.26l14.3,31.76c16.67-9.53,33.34-14.29,48.42-14.29,22.23,0,33.35,9.53,33.35,27v3.18H715c-39.7,0-61.13,18.26-61.13,48.43,0,29.37,20.64,50,55.57,50,22.23,0,38.11-7.93,50.81-21.43v17.46h45.26V489.27C804,455.13,779.34,433.69,736.47,433.69Zm27,108c-4.76,13.49-18.26,24.61-37.32,24.61-15.87,0-25.4-7.94-25.4-20.64s8.73-18.26,26.2-18.26h36.52ZM372.05,560.72c-22.23,0-38.1-17.47-38.1-43.67,0-25.4,15.87-42.87,38.1-42.87,15.88,0,27.79,6.35,36.53,17.47l31-22.23c-14.29-21.44-38.9-34.14-69.87-34.14-48.43-.79-81.77,32.55-81.77,81.77s33.34,81.78,81.77,81.78c33.35,0,58-13.5,71.46-35.73l-31.76-21.43C401.43,554.37,388.73,560.72,372.05,560.72Z"
                                    transform="translate(-6.06 -339.22)" fill="#21c25e" style="fill: #21c25e;">
                                </path>
                            </svg>
                        </div>
                        <div id="loading">
                            <div class="d-flex justify-content-center mb-5">
                                <img class="w-50 rounded" src="" alt="">
                            </div>
                            <div class="d-flex align-items-center mb-5">
                                <div class="text-center">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <span class="w-100 ms-3">Aguardando pagamento. Está tendo dificuldades ao realizar o
                                    pagamento?
                                    <a href="" class="text-success" id="payment_url">Pague pelo site oficial do
                                        picpay</a></span>
                            </div>
                        </div>
                        <div id="finish" style="display:none;">
                            <div class="mb-7 rounded p-4 d-flex flex-column align-items-center">
                                <div class="highlight p-5 rounded w-100 text-center"><i
                                        class="bi bi-check-circle text-success fs-5tx"
                                        style="font-size: 8rem!important;"></i></div>
                                <div class="fs-6 mt-5">😝 Pagamento <span class="text-success">efetuado com
                                        sucesso</span>, verifique seu correio dentro do
                                    jogo.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(() => {
            $('.modal').modal({
                backdrop: "static"
            });
        });
    </script>
    <script src="{{ url() }}/assets/js/app/recharge.js"></script>
@endsection
