@extends('layouts.app')

@section('title', 'Servidores')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">Selecione um servidor para continuar</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">Lista de servidores</li>
                </ul>
            </div>
        </div>
    </div>
@endsection 

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="row g-5 g-xl-8">
                @each('components.server.detail', $servers, 'info', 'components.server.empty')
            </div>
        </div>
    </div>
@endsection
