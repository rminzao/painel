@extends('layouts.app')

@section('title', 'Meus personagens')

@section('custom-js')
    <script src="{{ url() }}/assets/js/widgets.bundle.js"></script>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        {{-- begin::Container --}}
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            {{-- begin::Page title --}}
            <div class="page-title d-flex flex-column me-3">
                {{-- begin::Title --}}
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🚏 Meus personagens</h1>
                {{-- end::Title --}}
                {{-- begin::Breadcrumb --}}
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
                    <li class="breadcrumb-item text-white opacity-75">Meus personagens</li>
                    {{-- end::Item --}}
                </ul>
                {{-- end::Breadcrumb --}}
            </div>
            {{-- end::Page title --}}
        </div>
        {{-- end::Container --}}
    </div>
@endsection

@section('content')
    {{-- begin::Container --}}
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        {{-- begin::Post --}}
        <div class="content flex-row-fluid" id="kt_content">
            {{-- begin::Navbar --}}
            @include('components.profile.navbar', ['page' => $page])
            {{-- end::Navbar --}}
            {{-- begin::Main --}}
            @isset($charsList)
                {{-- begin::Row --}}
                <div class="row g-6 mb-6 g-xl-9 mb-xl-9">
                    {{-- begin::Characters --}}
                    @each('components.profile.characters.item', $charsList, 'info', 'components.profile.characters.empty')
                    {{-- begin::Characters --}}
                </div>
                {{-- end::Row --}}
            @endisset
            {{-- end::Main --}}
            @empty($charsList)
                <div class="card">
                    @include('components.default.notfound', [
                        'title' => 'Ops',
                        'message' => 'Você ainda nao possui nenhum personagem',
                    ])
                </div>
            @endempty
        </div>
        {{-- end::Post --}}
    </div>
    {{-- end::Container --}}
@endsection
