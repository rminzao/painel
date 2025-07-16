@extends('layouts.app')

@section('title', 'Minhas compras')

@section('custom-js')
    <script src="{{ url() }}/assets/js/app/invoice/list.js"></script>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🪙 Minhas compras</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    {{-- begin::Item --}}
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    {{-- end::Item --}}
                    {{-- begin::Item --}}
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    {{-- end::Item --}}
                    {{-- begin::Item --}}
                    <li class="breadcrumb-item text-white opacity-75">Meu perfil</li>
                    {{-- end::Item --}}
                    {{-- begin::Item --}}
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    {{-- end::Item --}}
                    {{-- begin::Item --}}
                    <li class="breadcrumb-item text-white opacity-75">Minhas compras</li>
                    {{-- end::Item --}}
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            @include('components.profile.navbar', ['page' => $page])
            <div class="card" id="invoice_data" style="display:none;">
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Método</th>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Status</th>
                                    <th>Cupons</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold" id="invoice_list"></tbody>
                        </table>
                    </div>
                    <div class="row mb-5">
                        <div
                            class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                        </div>
                        <div
                            class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                            <div class="paging_simple_numbers" id="invoice_paginator"></div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card" id="no_results">
                @include('components.default.notfound', [
                    'title' => 'Ops',
                    'message' =>
                        'Você ainda nao comprou nada <br>acesse a <a href="' .
                        url('app/recarga') .
                        '">página de recarga</a>',
                ])
            </div>
        </div>
    </div>
@endsection
