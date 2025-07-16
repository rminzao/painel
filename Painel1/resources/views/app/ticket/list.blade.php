@extends('layouts.app')

@section('title', 'Meus Tickets')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📮 Ticket</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Suporte</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Meus tickets</li>
                </ul>
            </div>
            {{--
            <div class="d-flex align-items-center py-3 py-md-1">
                <a href="{{ url('app/me/ticket/new') }}"
                    class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-primary fw-bolder">
                    Novo Ticket
                </a>
            </div>
            --}}
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center flex-column flex-xl-row p-7">
                            <div class="mb-0">
                                @include('components.default.notfound', [
                                    'title' => 'Desculpe, nada encontrado',
                                    'message' => '<span class="fs-7">O sistema de ticket está em manutenção, por favor, tente novamente mais tarde.</span>',
                                    'icon' => true
                                ])
                                {{-- 
                                @isset($tickets)
                                    <div class="mb-10">
                                        @each('components.ticket.item', $tickets, 'info', 'components.ticket.empty')
                                    </div>
                                    {!! $paginator !!}
                                @endisset
                                @empty($tickets)
                                    @include('components.default.notfound', [
                                        'title' => 'nada encontrado',
                                        'message' => 'Você ainda nao criou nenhum chamado',
                                    ])
                                @endempty  
                                --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
