<!DOCTYPE html>

<html lang="pt-br">
<!--begin::Head-->

<head>
    <base href="{{ url() }}">
    <title>{{ $_ENV['APP_NAME'] ?? 'DDTank' }} | Links</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ url() }}/assets/media/icons/original.png" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ url() }}/assets/plugins/global/plugins.dark.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url() }}/assets/css/style.dark.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body data-kt-name="metronic" id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled"
    style="background-image: url({{ url('assets/media/patterns/header-bg.jpg') }}); background-size: 100% !important;">
    <!--begin::Theme mode setup on page load-->
    <script>
        if (document.documentElement) {
            const defaultThemeMode = "system";
            const name = document.body.getAttribute("data-kt-name");
            let themeMode = localStorage.getItem("kt_" + (name !== null ? name + "_" : "") + "theme_mode_value");
            if (themeMode === null) {
                if (defaultThemeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <!--end::Page bg image-->
        <!--begin::Authentication - Signup Welcome Message -->
        <div class="d-flex flex-column flex-center flex-column-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center text-center p-10">
                <!--begin::Wrapper-->
                <div class="card card-flush w-500 w-lg-500px py-5">
                    <div class="card-body py-5">
                        <div class="highlight mb-3">
                            <a href="https://ddtankiv.com/" class="btn btn-sm w-100 btn-light-primary mb-3 me-2">
                                Retornar ao site
                            </a>
                            <img src="{{ url('assets/media/logos/logo.png') }}" class="mw-100 mh-150px" alt="">
                        </div>
                        <span class="text-muted float-start">Downloads</span>
						<!--
							<a href="/Aplicativos/Instalador-DDTank.exe" class="btn btn-sm w-100 btn-primary mb-3 me-2">
                                Launcher Oficial
							</a>
						-->
							<a href="/Aplicativos/DDTankIV.apk" class="btn btn-sm w-100 btn-primary mb-3">
								Mobile (RECOMENDADO)
							</a>
							<a href="/Aplicativos/DDTankIV-14.apk" class="btn btn-sm w-100 btn-primary mb-3">
								Mobile (ANDROID 14)
							</a>
							<a href="/Aplicativos/Instalador-DDTank.exe" class="btn btn-sm w-100 btn-primary mb-3">
								Launcher (RECOMENDADO)
							</a>
							<a href="/Aplicativos/UCBrowser.exe" class="btn btn-sm w-100 btn-primary mb-3">
								UCBrowser (RECOMENDADO)
                            </a>
						    <a href="/Aplicativos/FlashBrowser.zip" class="btn btn-sm w-100 btn-primary mb-3">
								Flash Browser x32/x64
                            </a>
                            <a href="/Aplicativos/CentBrowser.zip" class="btn btn-sm w-100 btn-primary mb-3">
                                Cent Browser x64/Flash
                            </a>
                        <span class="text-muted float-start mt-3">Redes sociais</span>
                        <a href="{{ $_ENV['FACEBOOK_URL'] }}" target="_blank"
                            class="btn btn-sm w-100 btn-light-primary mb-3">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="{{ $_ENV['DISCORD_URL'] }}" target="_blank"
                            class="btn btn-sm w-100 btn-light-info mb-3">
                            <i class="fab fa-discord"></i> Discord
                        </a>
                        <a href="{{ $_ENV['INSTAGRAM_URL'] }}" target="_blank"
                            class="btn btn-sm w-100 btn-light-warning mb-3">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        @if ($_ENV['WHATSAPP1_URL'] != '')
                            <a href="{{ $_ENV['WHATSAPP1_URL'] }}" target="_blank"
                                class="btn btn-sm w-100 btn-light-success mb-3">
                                <i class="fab fa-whatsapp"></i> Whatsapp 
                            </a>
                        @endif

						<!--
                        <span class="text-muted float-start mt-3">Grupos WhatsApp</span>
						-->
                        @if ($_ENV['WHATSAPP2_URL'] != '')
                            <a href="{{ $_ENV['WHATSAPP2_URL'] }}" target="_blank"
                                class="btn btn-sm w-100 btn-light-success mb-3">
                                <i class="fab fa-whatsapp"></i> Grupo 2 (comércio)
                            </a>
                        @endif
                        @if ($_ENV['WHATSAPP3_URL'] != '')
                            <a href="{{ $_ENV['WHATSAPP3_URL'] }}" target="_blank"
                                class="btn btn-sm w-100 btn-light-success mb-3">
                                <i class="fab fa-whatsapp"></i> Grupo 3
                            </a>
                        @endif

                        <!--end::Link-->
                    </div>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Authentication - Signup Welcome Message-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ url() }}/assets/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ url() }}/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ url() }}/assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
