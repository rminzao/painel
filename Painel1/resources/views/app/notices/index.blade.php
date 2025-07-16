@extends('layouts.app')

@section('title', 'ATUALIZAÇÃO 11.0 - Espírito Santo')

@section('toolbar')
    <!--begin::Toolbar-->
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📢 Notícias</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">App</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">Notícias</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white">ATUALIZAÇÃO 11.0 - Espírito Santo</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <div class="d-flex align-items-center py-3 py-md-1">
                <button type="button" class="btn btn-sm btn-light-primary me-2">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z"
                                fill="currentColor" />
                            <path
                                d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z"
                                fill="currentColor" />
                        </svg>
                        Ver todos
                    </span>
                </button>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Post card-->
                <div class="card">
                    <!--begin::Body-->
                    <div class="card-body p-lg-15 pb-lg-0">
                        <!--begin::Layout-->
                        <div class="d-flex flex-column flex-xl-row">
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid me-xl-15">
                                <!--begin::Post content-->
                                <div class="mb-17">
                                    <!--begin::Wrapper-->
                                    <div class="mb-8">
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap mb-6">
                                            <!--begin::Item-->
                                            <div class="me-9 my-1">
                                                <!--begin::Icon-->
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                                <span class="svg-icon svg-icon-primary svg-icon-2 me-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                                        <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                                            fill="currentColor" />
                                                        <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                            fill="currentColor" />
                                                        <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--end::Icon-->
                                                <!--begin::Label-->
                                                <span class="fw-bolder text-gray-400">09 May 2022</span>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="me-9 my-1">
                                                <!--begin::Icon-->
                                                <!--SVG file not found: icons/duotune/finance/fin006.svgFolder.svg-->
                                                <!--end::Icon-->
                                                <!--begin::Label-->
                                                <span class="fw-bolder text-gray-400">Announcements</span>
                                                <!--begin::Label-->
                                            </div>
                                            <!--end::Item-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Title-->
                                        <a href="#" class="text-dark text-hover-primary fs-2 fw-bolder">ATUALIZAÇÃO 11.0 -
                                            Espírito Santo
                                            <span class="fw-bolder text-muted fs-5 ps-1">1 min read</span></a>
                                        <!--end::Title-->
                                        <!--begin::Container-->
                                        <div class="mt-8">
                                            <!--begin::Image-->
                                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-350px"
                                                style="background-size:contain; background-image:url('https://pbs.twimg.com/media/E9A3bbCWUAQUJUA?format=png&name=900x900')">
                                            </div>
                                            <!--end::Image-->
                                        </div>
                                        <!--end::Container-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Description-->
                                    <div class="fs-5 fw-bold text-gray-600">
                                        <!--begin::Text-->
                                        <p class="mb-8">Com a chegada da versão 11.0, os grandiosos pets
                                            elementares chegaram com tudo.
                                            Preparem-se pois a nova atualização veio recheada de novidades. 💦</p>
                                        <!--end::Text-->
                                        <p class="fs-4 text-dark">
                                            💣🔥 ATENÇÃO 🔥💣
                                        </p>
                                        <!--begin::Text-->
                                        <p class="mb-8">
                                            A versão 11.0 chega em TODOS os servidores no dia 23 desse mês.</p>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Description-->
                                    <!--begin::Block-->
                                    <div
                                        class="d-flex align-items-center border-1 border-dashed card-rounded p-5 p-lg-10 mb-14">
                                        <!--begin::Section-->
                                        <div class="text-center flex-shrink-0 me-7 me-lg-13">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-70px symbol-circle mb-2">
                                                <img src="http://localhost:8000/storage?path=images/cache/user-84-1649282899-50x50-4cdab0df.webp"
                                                    class="" alt="" />
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Info-->
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Section-->
                                        <!--begin::Text-->
                                        <div class="mb-0 fs-6">
                                            <div class="text-muted fw-bold lh-lg mb-2">First, a disclaimer – the entire
                                                process of writing a blog post often takes more than a couple of hours, even
                                                if you can type eighty words per minute and your writing skills are sharp
                                                writing a blog post often takes more than a couple.</div>
                                            <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                class="fw-bold link-primary">Author’s Profile</a>
                                        </div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Block-->
                                    <!--begin::Icons-->
                                    <div class="d-flex flex-center">
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/facebook-4.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/instagram-2-1.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/github.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/behance.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/pinterest-p.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/twitter.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                        <!--begin::Icon-->
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/dribbble-icon-1.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                        <!--end::Icon-->
                                    </div>
                                    <!--end::Icons-->
                                </div>
                                <!--end::Post content-->
                            </div>
                            <!--end::Content-->
                            <!--begin::Sidebar-->
                            <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                                <!--begin::Recent posts-->
                                <div class="m-0">
                                    <h4 class="mb-7">Recent Posts</h4>
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-7">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('https://pbs.twimg.com/media/E6E1CPwXMAIHXum?format=png&name=900x900')">
                                            </div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bolder text-hover-primary fs-6">Atualização 10.9</a>
                                            <span class="text-gray-600 fw-bold d-block pt-1 fs-7">Otimização na força dos robôs...</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Item-->
                                     <!--begin::Item-->
                                     <div class="d-flex flex-stack mb-7">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('https://pbs.twimg.com/media/E56gm_XXoAgTTTV?format=png&name=900x900')">
                                            </div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bolder text-hover-primary fs-6">Novo pet</a>
                                            <span class="text-gray-600 fw-bold d-block pt-1 fs-7">O novo pet chega no inicio dessa semana...</span>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Recent posts-->
                            </div>
                            <!--end::Sidebar-->
                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Post card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@section('custom-js')
@endsection
