@extends('layouts.app')

@section('title', 'Minhas referencias')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">⚙️ Minhas configurações</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Meu perfil</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Minhas configurações</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            @include('components.profile.navbar', ['page' => $page])
            <div class="card mb-5 mb-xl-10">
                <!--begin::Body-->
                <div class="card-body py-10">
                    <h4 class="mb-9">Programa de referência</h4>
                    <!--begin::Overview-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-xl-6 mb-15 mb-xl-0 pe-5">
                            <h5 class="mb-0">Oque ganho no programa de indicações ?</h5>
                            <p class="fs-6 fw-semibold text-gray-600 py-4 m-0">
                            <ul>
                                <li>Recebe <span class="text-primary">10</span> pontos, por indicação (Indicados ao alcançar
                                    lv.20 de personagem)</li>
                                <li>Recebe <span class="text-primary">10</span> pontos a cada 10 niveis do <span
                                        class="text-primary">personagem indicado</span>. (A
                                    partir do nv.20)</li>
                                <li>Recebe <span class="text-primary">10%</span> de toda recarga feita pelo <span
                                        class="text-primary">jogador indicado</span>
                                </li>
                                <li>Recarga realizada pelo indicado, é convertido <span class="text-primary">20%</span> do
                                    valor em <span class="text-primary">pontos de indicação</span></li>
                            </ul>
                            {{-- O convidado ao se registrar utilizando seu link/código, será necessário alcançar o level 20
                                de personagem,
                                assim você receberá grandes recompensas. --}}
                            </p>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xl-6">
                            <h4 class="text-gray-800 mb-0">😝 Meu link de referência</h4>
                            <p class="fs-6 fw-semibold text-gray-600 py-4 m-0">
                                Copie seu link de referência e convide novas pessoas para o servidor
                                <span class="text-warning">{{ $_ENV['APP_NAME'] }}</span>
                            </p>
                            <div class="d-flex">
                                <input id="kt_referral_link_input" type="text"
                                    class="form-control form-control-solid form-control-sm me-3 flex-grow-1"
                                    value="{{ url("auth/cadastro?refer=$user->reference") }}" readonly>
                                <button id="kt_referral_program_link_copy_btn"
                                    class="btn btn-sm btn-light btn-active-light-primary flex-shrink-0"
                                    data-clipboard-target="#kt_referral_link_input">Copiar link</button>
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Overview-->
                </div>
                <!--end::Body-->
            </div>
            <div class="card">
                {{-- @include('components.default.notfound', [
                    'title' => 'Whoops',
                    'message' => '<span class="fs-4">Função em desenvolvimento.</span>',
                ]) --}}
                <!--begin::Header-->
                <div class="card-header card-header-stretch">
                    <!--begin::Title-->
                    <div class="card-title">
                        <h3>Usuários indicados</h3>
                    </div>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <div class="table-responsive" id="referred_body">
                    <div id="no_results">
                        @include('components.default.notfound', [
                            'title' => 'Que pena !!',
                            'message' => 'Você ainda não indicou nenhum jogador.',
                        ])
                    </div>
                    <!--begin::Table-->
                    <table class="table table-row-bordered table-flush align-middle gy-6" id="table_referred_list"
                        style="display:none;">
                        <!--begin::Thead-->
                        <thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                            <tr>
                                <th class="min-w-125px px-9">Usuário</th>
                                <th class="min-w-125px">Data</th>
                                <th class="min-w-125px">Pontos</th>
                                <th class="min-w-125px ps-0">Cupons</th>
                            </tr>
                        </thead>
                        <!--end::Thead-->
                        <!--begin::Tbody-->
                        <tbody class="fs-6 fw-semibold text-gray-600"></tbody>
                        <!--end::Tbody-->
                    </table>
                    <!--end::Table-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ url('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ url('assets/js/app/account.js') }}"></script>
@endsection
