@extends('layouts.app')

@section('title', 'Meus dados')

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
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">🖌️ Meus dados</h1>
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
                    <li class="breadcrumb-item text-white opacity-75">Meus dados</li>
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
            <!--begin::details View-->
            <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

                <!--begin::Card body-->
                <div class="card-body p-9">
                    @if ($user->status != 'confirmed')
                        <!--begin::Information-->
                        <div class="d-flex align-items-center rounded mb-5 py-5 px-5 bg-light-warning">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                            <span class="svg-icon svg-icon-3x svg-icon-warning me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)"
                                        fill="currentColor">
                                    </rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)"
                                        fill="currentColor">
                                    </rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Description-->
                            <div class="text-gray-700 fw-bold fs-6">Você ainda não confirmou seu e-mail, é importante
                                confirmar para se previnir contra fraudes e caso precise recuperar sua conta.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Information-->
                    @endif
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">Nome</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span
                                class="fw-bolder fs-6 text-gray-800">{{ $user->first_name . ' ' . $user->last_name }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">Email
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="{{ $user->status == 'confirmed' ? 'Email confirmado' : 'Confirme seu e-mail' }}"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bolder fs-6 text-gray-800 me-2">{{ str_obfuscate_email($user->email) }}</span>
                            <span
                                @class([
                                    'badge',
                                    'badge-danger' => $user->status != 'confirmed',
                                    'badge-success' => $user->status == 'confirmed',
                                ])>{{ $user->status == 'confirmed' ? 'Verificado' : 'Não verificado' }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">Código de referencia</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <a href="#" class="fw-bold fs-6 text-gray-800 text-hover-primary">{{ $user->reference }}</a>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">Conta criada</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <span class="fw-bold text-gray-800 fs-6">{{ date_fmt_ago($user->created_at) }}
                                ({{ $user->created_at }})</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">última alteração</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ date_fmt_ago($user->updated_at) }}
                                ({{ $user->updated_at }})</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::details View-->
        </div>
        {{-- end::Post --}}
    </div>
    {{-- end::Container --}}
@endsection
