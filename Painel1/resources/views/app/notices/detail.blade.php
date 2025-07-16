@extends('layouts.app')

@section('title', $post->title)

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📢 {!! $post->title !!}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">App</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Notícias</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white">{!! $post->title !!}</li>
                </ul>
            </div>

            <div class="d-flex align-items-center py-3 py-md-1">
                <button type="button" class="btn btn-sm btn-light-primary me-2">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path opacity="0.3"
                                d="M12.3408 20.7578C12.3408 21.7578 13.6408 22.0578 14.1408 21.2578L19.5408 11.8578C19.9408 11.1578 19.4408 10.3578 18.6408 10.3578H12.3408V20.7578Z"
                                fill="currentColor" />
                            <path
                                d="M12.3408 3.9578C12.3408 2.9578 11.0408 2.6578 10.5408 3.4578L5.14078 12.8578C4.74078 13.5578 5.24078 14.3578 6.04078 14.3578H12.3408V3.9578Z"
                                fill="currentColor" />
                        </svg>
                        Voltar a lista de notícias
                    </span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="container">
                <div class="card">
                    <div class="card-body p-lg-20 pb-lg-0">
                        <div class="d-flex flex-column flex-xl-row">
                            <div class="flex-lg-row-fluid me-xl-15">
                                <div class="mb-17">
                                    <div class="mb-8">
                                        <div class="d-flex flex-wrap mb-6">
                                            <div class="me-9 my-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2 me-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="2" y="2" width="9" height="9"
                                                            rx="2" fill="currentColor" />
                                                        <rect opacity="0.3" x="13" y="2" width="9"
                                                            height="9" rx="2" fill="currentColor" />
                                                        <rect opacity="0.3" x="13" y="13" width="9"
                                                            height="9" rx="2" fill="currentColor" />
                                                        <rect opacity="0.3" x="2" y="13" width="9"
                                                            height="9" rx="2" fill="currentColor" />
                                                    </svg>
                                                </span>

                                                <span class="fw-bold text-gray-400">06 April 2021</span>
                                            </div>
                                            {{-- <div class="me-9 my-1">
                                                <span class="fw-bold text-gray-400">Announcements</span>
                                            </div>

                                            <div class="my-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2 me-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.3"
                                                            d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>

                                                <span class="fw-bold text-gray-400">24 Comments</span>
                                            </div> --}}
                                        </div>

                                        <a href="#" class="text-dark text-hover-primary fs-2 fw-bold">
                                            {!! $post->subtitle !!} <span class="fw-bold text-muted fs-5 ps-1">2 minutos de leitura</span>
                                        </a>
                                    </div>
                                    <style>
                                        #content img {
                                            width: 100%;
                                            height: 100%;
                                            border-radius: 6px;
                                        }
                                    </style>
                                    <div class="fs-5 fw-semibold text-gray-600" id="content">
                                        {!! htmlspecialchars_decode($post->content) !!}
                                    </div>

                                    <div
                                        class="d-flex align-items-center border-1 border-dashed card-rounded p-5 p-lg-10 mb-14">
                                        <div class="text-center flex-shrink-0 me-7 me-lg-13">
                                            <div class="symbol symbol-70px symbol-circle mb-2">
                                                <img src="{{url()}}/assets/media/avatars/default-new.png" class=""
                                                    alt="" />
                                            </div>

                                            <div class="mb-0">
                                                <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                    class="text-gray-700 fw-bold text-hover-primary">Administrador</a>
                                                <span class="text-gray-400 fs-7 fw-semibold d-block mt-1">{{$_ENV['APP_NAME']}}</span>
                                            </div>
                                        </div>

                                        <div class="mb-0 fs-6">
                                            <div class="text-muted fw-semibold lh-lg mb-2">
                                                Administrador do servidor {{$_ENV['APP_NAME']}}.
                                            </div>
                                            {{-- <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                class="fw-semibold link-primary">Author’s Profile</a> --}}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-center">
                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/facebook-4.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/instagram-2-1.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/github.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/behance.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/pinterest-p.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/twitter.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>

                                        <a href="#" class="mx-4">
                                            <img src="/metronic8/demo2/assets/media/svg/brand-logos/dribbble-icon-1.svg"
                                                class="h-20px my-2" alt="" />
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                                <div class="mb-16">
                                    <h4 class="text-dark mb-7">Search Blog</h4>

                                    <div class="position-relative">
                                        <span
                                            class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                    height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                    fill="currentColor" />
                                                <path
                                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>

                                        <input type="text" class="form-control form-control-solid ps-10"
                                            name="search" value="" placeholder="Search" />
                                    </div>
                                </div>

                                <div class="mb-16">
                                    <h4 class="text-dark mb-7">Categories</h4>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                                        <a href="#" class="text-muted text-hover-primary pe-2">SaaS Solutions</a>

                                        <div class="m-0">24</div>
                                    </div>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                                        <a href="#" class="text-muted text-hover-primary pe-2">Company News</a>

                                        <div class="m-0">152</div>
                                    </div>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                                        <a href="#" class="text-muted text-hover-primary pe-2">Events &amp;
                                            Activities</a>

                                        <div class="m-0">52</div>
                                    </div>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                                        <a href="#" class="text-muted text-hover-primary pe-2">Support Related</a>

                                        <div class="m-0">305</div>
                                    </div>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                                        <a href="#" class="text-muted text-hover-primary pe-2">Innovations</a>

                                        <div class="m-0">70</div>
                                    </div>

                                    <div class="d-flex flex-stack fw-semibold fs-5 text-muted">
                                        <a href="#" class="text-muted text-hover-primary pe-2">Product Updates</a>

                                        <div class="m-0">585</div>
                                    </div>
                                </div>

                                <div class="m-0">
                                    <h4 class="text-dark mb-7">Recent Posts</h4>

                                    <div class="d-flex flex-stack mb-7">
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-1.jpg');">
                                            </div>
                                        </div>

                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">About
                                                Bootstrap Admin</a>
                                            <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused
                                                on making a the sky</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack mb-7">
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-2.jpg');">
                                            </div>
                                        </div>

                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">A yellow
                                                sofa</a>
                                            <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused
                                                on making a the sky</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack mb-7">
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-3.jpg');">
                                            </div>
                                        </div>

                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Our Camra
                                                Mega Set</a>
                                            <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused
                                                on making a the sky</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack">
                                        <div class="symbol symbol-60px symbol-2by3 me-4">
                                            <div class="symbol-label"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-4.jpg');">
                                            </div>
                                        </div>

                                        <div class="m-0">
                                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Time to
                                                cook and eat?</a>
                                            <span class="text-gray-600 fw-semibold d-block pt-1 fs-7">We’ve been a focused
                                                on making a the sky</span>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        {{-- <div class="mb-17">
                            <div class="d-flex flex-stack mb-5">
                                <h3 class="text-dark">Video Tutorials</h3>

                                <a href="#" class="fs-6 fw-semibold link-primary">View All Videos</a>
                            </div>

                            <div class="separator separator-dashed mb-9"></div>

                            <div class="row g-10">
                                <div class="col-md-4">
                                    <div class="card-xl-stretch me-md-6">
                                        <a class="d-block bgi-no-repeat bgi-size-cover bgi-position-center card-rounded position-relative min-h-175px mb-5"
                                            style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-73.jpg');"
                                            data-fslightbox="lightbox-video-tutorials"
                                            href="https://www.youtube.com/embed/btornGtLwIo">
                                            <img src="/metronic8/demo2/assets/media/svg/misc/video-play.svg"
                                                class="position-absolute top-50 start-50 translate-middle"
                                                alt="" />
                                        </a>

                                        <div class="m-0">
                                            <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">Admin
                                                Panel - How To Started the Dashboard Tutorial</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark my-4">We’ve been focused
                                                on making a the from also not been afraid to and step away been focused
                                                create eye</div>

                                            <div class="fs-6 fw-bold">
                                                <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                    class="text-gray-700 text-hover-primary">Jane Miller</a>

                                                <span class="text-muted">on Mar 21 2021</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-xl-stretch mx-md-3">
                                        <a class="d-block bgi-no-repeat bgi-size-cover bgi-position-center card-rounded position-relative min-h-175px mb-5"
                                            style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-74.jpg');"
                                            data-fslightbox="lightbox-video-tutorials"
                                            href="https://www.youtube.com/embed/btornGtLwIo">
                                            <img src="/metronic8/demo2/assets/media/svg/misc/video-play.svg"
                                                class="position-absolute top-50 start-50 translate-middle"
                                                alt="" />
                                        </a>

                                        <div class="m-0">
                                            <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">Admin
                                                Panel - How To Started the Dashboard Tutorial</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark my-4">We’ve been focused
                                                on making the from v4 to v5 but we have also not been afraid to step away
                                                been focused</div>

                                            <div class="fs-6 fw-bold">
                                                <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                    class="text-gray-700 text-hover-primary">Cris Morgan</a>

                                                <span class="text-muted">on Apr 14 2021</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-xl-stretch ms-md-6">
                                        <a class="d-block bgi-no-repeat bgi-size-cover bgi-position-center card-rounded position-relative min-h-175px mb-5"
                                            style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-47.jpg');"
                                            data-fslightbox="lightbox-video-tutorials"
                                            href="https://www.youtube.com/embed/TWdDZYNqlg4">
                                            <img src="/metronic8/demo2/assets/media/svg/misc/video-play.svg"
                                                class="position-absolute top-50 start-50 translate-middle"
                                                alt="" />
                                        </a>

                                        <div class="m-0">
                                            <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">Admin
                                                Panel - How To Started the Dashboard Tutorial</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark my-4">We’ve been focused
                                                on making the from v4 to v5 but we’ve also not been afraid to step away been
                                                focused</div>

                                            <div class="fs-6 fw-bold">
                                                <a href="/metronic8/demo2/../demo2/pages/user-profile/overview.html"
                                                    class="text-gray-700 text-hover-primary">Carles Nilson</a>

                                                <span class="text-muted">on May 14 2021</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="mb-17">
                            <div class="d-flex flex-stack mb-5">
                                <h3 class="text-dark">Hottest Bundles</h3>

                                <a href="#" class="fs-6 fw-semibold link-primary">View All Offers</a>
                            </div>

                            <div class="separator separator-dashed mb-9"></div>

                            <div class="row g-10">
                                <div class="col-md-4">
                                    <div class="card-xl-stretch me-md-6">
                                        <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                            href="/metronic8/demo2/assets/media/stock/600x400/img-23.jpg">
                                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-23.jpg');">
                                            </div>

                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                                            </div>
                                        </a>

                                        <div class="mt-5">
                                            <a href="#"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">25
                                                Products Mega Bundle with 50% off discount amazing</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark mt-3">We’ve been focused
                                                on making a the from also not been eye</div>

                                            <div class="fs-6 fw-bold mt-5 d-flex flex-stack">
                                                <span class="badge border border-dashed fs-2 fw-bold text-dark p-2"> <span
                                                        class="fs-6 fw-semibold text-gray-400">$</span>28</span>

                                                <a href="#" class="btn btn-sm btn-primary">Purchase</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-xl-stretch mx-md-3">
                                        <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                            href="/metronic8/demo2/assets/media/stock/600x600/img-14.jpg">
                                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x600/img-14.jpg');">
                                            </div>

                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                                            </div>
                                        </a>

                                        <div class="mt-5">
                                            <a href="#"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">25
                                                Products Mega Bundle with 50% off discount amazing</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark mt-3">We’ve been focused
                                                on making a the from also not been eye</div>

                                            <div class="fs-6 fw-bold mt-5 d-flex flex-stack">
                                                <span class="badge border border-dashed fs-2 fw-bold text-dark p-2"> <span
                                                        class="fs-6 fw-semibold text-gray-400">$</span>27</span>

                                                <a href="#" class="btn btn-sm btn-primary">Purchase</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-xl-stretch ms-md-6">
                                        <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                            href="/metronic8/demo2/assets/media/stock/600x400/img-71.jpg">
                                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                                style="background-image: url('/metronic8/demo2/assets/media/stock/600x400/img-71.jpg');">
                                            </div>

                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                                            </div>
                                        </a>

                                        <div class="mt-5">
                                            <a href="#"
                                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base">25
                                                Products Mega Bundle with 50% off discount amazing</a>

                                            <div class="fw-semibold fs-5 text-gray-600 text-dark mt-3">We’ve been focused
                                                on making a the from also not been eye</div>

                                            <div class="fs-6 fw-bold mt-5 d-flex flex-stack">
                                                <span class="badge border border-dashed fs-2 fw-bold text-dark p-2"> <span
                                                        class="fs-6 fw-semibold text-gray-400">$</span>25</span>

                                                <a href="#" class="btn btn-sm btn-primary">Purchase</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
@endsection
