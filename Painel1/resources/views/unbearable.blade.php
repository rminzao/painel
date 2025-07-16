<!DOCTYPE html>

<html lang="pt-br">

<head>
    <base href="" />
    <title>{{ $_ENV['APP_NAME'] }} :: Navegador não suportado</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ $_ENV['APP_NAME'] }} :: Navegador não suportado" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="{{ url() }}" />
    <link rel="shortcut icon" href="{{ url() }}/assets/media/icons/favicon.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ url() }}/assets/plugins/global/plugins.dark.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url() }}/assets/css/style.dark.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url() }}/assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body" style="background-image: url({{ url() }}/assets/media/patterns/header-bg.jpg)"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start mt-10">
                    <div class="content flex-row-fluid" id="kt_content">
                        <div class="container mb-4">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column p-5">
                                        <div class="d-flex flex-column">
                                            <span class="mb-2 text-danger fs-5">Whoops</span>
                                            <span class="fs-7 highlight p-3">Olá tanker, desculpe o incoveniente mas o
                                                navegador <b
                                                    class="text-danger">{{ match ($browser) {'MxNitro' => 'Maxthon Nitro','Maxthon' => 'Maxthon',default => $browser} }}</b>
                                                não tem suporte para as tecnologias utilizadas em nosso site. Para
                                                resolver este problema utilize uma das opções abaixo.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="content flex-row-fluid">
                                <div class="fv-row d-flex">
                                    <div class="card col-3 me-3">
                                        <div class="card-body p-0">
                                            <div class="p-2 mb-2">
                                                <img src="https://i.imgur.com/299ORUC.png"
                                                    class="rounded w-100 mx-100 h-150px" style="object-fit: cover;">
                                                <span style="z-index:2;"
                                                    class="position-absolute top-10 start-50 translate-middle badge badge-light-primary">recomendado</span>
                                            </div>
                                            <div class="d-flex flex-column px-3 pb-3 fs-8 ">
                                                <div class="mb-3">
                                                    <span class="mb-4">{{ $_ENV['APP_NAME'] }} Client</span>
                                                    <div class="highlight p-2 mt-2">
                                                        <ul class="mb-0">
                                                            <li>2gb Ram</li>
                                                            <li>Suporte 32/64 bits</li>
                                                            <li>Windows 10/11</li>
                                                            <li class="text-primary">Flash player incluso</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <a href="{{ env('APP_CLIENT') }}" class="btn btn-sm btn-primary w-100">
                                                    Baixar </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card col-3 me-3">
                                        <div class="card-body p-0">
                                            <div class="p-2 mb-2">
                                                <img src="https://gratuittelecharger.com/wp-content/uploads/2021/09/Cent-Browser-windows-pc-download-free.jpg"
                                                    class="rounded w-100 mx-100 h-150px" style="object-fit: cover;">
                                            </div>
                                            <div class="d-flex flex-column px-3 pb-3 fs-8 ">
                                                <div class="mb-3">
                                                    <span class="mb-4">Cent browser</span>
                                                    <div class="highlight p-2 mt-2">
                                                        <ul class="mb-0">
                                                            <li>Versão: 4.3.9.248</li>
                                                            <li>Chromium: 86.0.4240.198</li>
                                                            <li class="text-warning">Necessário flash player instalado
                                                                [<a
                                                                    href="https://www.mediafire.com/file/mz81m8xqbd5e1zg/32_0_r0_142.rar/file">baixar
                                                                    aqui</a>]</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <a href="https://static.centbrowser.com/win_stable/4.2.9.152/centbrowser_4.2.9.152_x64.exe" target="_blank"
                                                    class="btn btn-sm btn-primary w-100">Baixar</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card col-3">
                                        <div class="card-body p-0">
                                            <div class="p-2 mb-2">
                                                <img src="https://lcom23.files.wordpress.com/2018/11/basilisk_browser.jpg"
                                                    class="rounded w-100 mx-100 h-150px" style="object-fit: cover;">
                                                <span style="z-index:2;"
                                                    class="position-absolute top-10 start-50 translate-middle badge badge-light-primary">navegador recomendado</span>
                                            </div>
                                            <div class="d-flex flex-column px-3 pb-3 fs-8 ">
                                                <div class="mb-3">
                                                    <span class="mb-4">Basilisk browser</span>
                                                    <div class="highlight p-2 mt-2">
                                                        <ul class="mb-0">
                                                            <li>1gb Ram</li>
                                                            <li>Windows 7+</li>
                                                            <li class="text-warning">Necessário flash player instalado
                                                                [<a
                                                                    href="https://www.mediafire.com/file/mz81m8xqbd5e1zg/32_0_r0_142.rar/file">baixar
                                                                    aqui</a>]</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <a href="https://archive.basilisk-browser.org/2022.09.28/windows/x86_64/basilisk-20220928153025.win64.installer.exe"
                                                    target="_blank" class="btn btn-sm btn-primary w-100">Baixar</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <div
                        class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted me-1">{{ date('Y') }}©</span>
                            <a href="{{ url() }}" target="_blank"
                                class="text-gray-800 text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                    transform="rotate(90 13 6)" fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>
    </div>
    <script src="{{ url() }}/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ url() }}/assets/js/scripts.bundle.js"></script>
</body>

</html>
