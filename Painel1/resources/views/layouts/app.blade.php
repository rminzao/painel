@php
$theme = $_COOKIE['user_theme'] ?? '';
$asset = ($theme != '' and $theme == 'dark') || $session->has('clientUser') ? 'dark.' : '';
@endphp
<!DOCTYPE html>

<html lang="pt-br">
<!--begin::Head-->

<head>
    <base href="">
    <title>@yield('title') - {{ $_ENV['APP_NAME'] }}</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="ddtank é um novo MMORPG de tanque baseado em navegador gratuito com foco em trabalho em equipe e estratégia. Construa uma equipe, reivindique um território e domine o mundo!" />
    <meta name="keywords"
        content="ddtank 337, ddtank orange, orangetank, orange tank, ddtank pirata orange, ddtank pirata, ddtank brasil, ddtank bombom, ddtank mobile, ddtank 321, ddtank walker, ddtank nexus, ddtank antigo, ddtank aimbot, ddtank apk, ddtank angulo 20, ddtank armas, ddtank age, ddtank antigo pirata, ddtank ainda existe, ddtank a nova era, contas ddtank a venda, jogos semelhantes a ddtank, jogo similar a ddtank, juegos parecidos a ddtank, juegos similares a ddtank, ddtank net, corujaa ddtank, fullon ddtank, ddtank oficial, ddtank betel, ddtank brasil mobile, ddtank brasil download, ddtank brasil facebook, br ddtank antigo, ddtank 337 br, ddtank com painel, ddtank com painel 2022, ddtank chines, ddtank com painel 2021, ddtank cupons por batalha, ddtank cupons infinitos, ddtank celular, ddtank chines 7k7k, codigo ddtank, contas ddtank 337, ddtank dragon, ddtank download, ddtank download para pc, ddtank drigueticos, ddtank dinheiro infinito, ddtank dicas, ddtank discord, ddtank dinheiro infinito 2021, ddtank 337 faliu, ddtank empire, ddtank eros, ddtank espanhol, ddtank estilo de jogo, ddtank epic back, ddtank espirito santo, ddtank expansion, ddtank english, ddtank e grand chase, ddtank e gunbound, ddtank e habbo, ddtank é pago, ddtank é jogo, p que é ddtank, ddtank pirata é aqui, ddtank o que é, ddtank letra, ddtank acabou, ddtank fenix, ddtank facebook, ddtank faliu, ddtank full cupons, ddtank fenix 3, ddtank files, ddtank full cupons 2022, ddtank full cupons 2021, ddtank gunny, ddtank gollum, ddtank game, ddtank gunny mobile, ddtank gun24h, ddtank gunny site oficial, ddtank game nexus, ddtank gameplay, ddtank hack, ddtank hack apk, ddtank hack 2022, ddtank historia, ddtank hack 2021, ddtank hack 2020, ddtank html5, ddtank hard, www ddtank 337 com br, ddtank 337 site, ddtank instagram, ddtank inglês, ddtank imagens, ddtank imperium, ddtank ingles apk, ddtank impacto, ddtank ii, ddtank iba, ddtank forum, ddtank iii, ddtank break ii sürüm r12 pvp, ddtank infinito, drigueticos ddtank, ddtank jogar, ddtank jogos parecidos, ddtank japones, ddtank jogar online gratis, ddtank japones original, ddtank juegos, ddtank jewel build, ddtank javascript, ddtank 337 jogar, ddtank jogo, ddtank kabum, kabam ddtank, ddtank aimbot key, sự kiện ddtank, ddtank kurulum, ddtank kinh, ddtank kuponlu pvp serverler, ddtank bedava kupon, ddtank 7k7, ddtank login, ddtank logger, ddtank linux, ddtank logger 2020, ddtank lançamento, ddtank lord, ddtank like games, ddtank 321.com, ddtank mobile recarga, ddtank mobile mod, ddtank mobile codigos, ddtank mobile pirata, ddtank mobile facebook, ddtank mobile servidor brasileiro, ddtank mobile hack, ddtank nft, ddtank nova era, ddtank nostalgic, ddtank novo, ddtank nexus logger, ddtank new era, ddtank wan, ddtank online, ddtank original, ddtank orange, ddtank origens, ddtank oficial 337, ddtank omega, ddtank olimpo, ddtank o grande retorno, ddtank o que significa, ddtank o que fazer, qual o ddtank oficial, instalar o ddtank, quem criou o ddtank, como instalar o ddtank no celular, ddtank 2.0, ddtank pirata com painel, ddtank pirata 2022, ddtank pirata 2021, ddtank painel, ddtank pirata mobile, ddtank pallas, ddtank pirata para celular, pt.ddtank 4, ddtank p, web.337/pt/ddtank ddtank pt, ddtank qual melhor servidor, ddtank quantas super pedras pra upar, ddtank que ganha cupons por batalha, ddtank que da cupons por batalha, ddtank quantas pedras para avanço 1, ddtank qual o melhor, quanto custa para casar ddtank, ddtank quiz, ddtank recarga, ddtank region selection, ddtank requisitos, ddtank regua, ddtank resgate 4, ddtank recuperar conta, ddtank resgate 1 3 estrelas, ddtank rocket, r/ddtank ddtank system, ddtank splush, ddtank sword, ddtank surf, ddtank site oficial, ddtank site oficial 337, ddtank system login, ddtank steam, ddtank s, ddtank tabela, ddtank thor curar habib, ddtank titan, ddtank tabela angulo 65, ddtank the best, ddtank tool, ddtank tool aimbot download, ddtank turco, ddtank us, ddtank uol, ddtank universe, ddtank us private server, ddtank ultima atualização, ddtank update, ddtank.us login, ddtank unlimited coins, ddtank americano, ddtank online gratis, ddtank privado, como usar ddtank tool, ddtank versao antiga, ddtank vietnamita, ddtank vibe, ddtank versão antiga 2021, ddtank voltou, ddtank vai acabar, ddtank versao antiga com cupons, ddtank virus, ddtank wikipedia, ddtank walker logger, ddtank w7, ddtank wiki, ddtank wallpaper, ddtank walker login, www ddtank game321 com, www ddtankpirata.net, ddtank w, ddtank xml tools, ddtank xml, xp ddtank, ddtank lậu full xu, ddtank 337 launcher, ddtank youtube, ddtank yoogames, ddtank yeni çağ, yoo games ddtank, youtube ddtank 337, ddtank 337 youtube, ddtank pirata youtube, ddtank yeni, ddtank zaptank, ddtank zion, ddtank zata, ddtank zero, ddtank zobi, z ddtank, ddtank trackid=sp-006, ddtank 1337, ddtank 123, ddtank 1.0, resgate ddtank 1 3 estrelas, resgate ddtank 1 2 3, ddtank fenix 1, ddtank servidor 1, ddtank versao 1, ddtank 1, top 1 ddtank, ddtank 2022, ddtank 2009, ddtank 2022 pirata, ddtank 2.3, ddtank 2021 pirata, ddtank 2020 pirata, ddtank 2012, ddtank 227, ddtank 2, ddtank 2 online, fenix 2 ddtank, gollum 2 ddtank, fenix 2 ddtank pirata win, resgate ddtank 2 3 estrelas, ddtank 2 fight, ddtank 337 oficial, ddtank 337 bombom, ddtank 337 download, ddtank 337 login, ddtank 337 pirata, ddtank 3, ddtank 3 download, ddtank 3 logger, ddtank 3 baixar, ddtank 3 para android, resgate ddtank 3 estrelas, resgate ddtank 3, ddtank 3 official website, ddtank 4.1, ddtank 4399, ddtank 4.1 pirata, ddtank 4.5, ddtank 4.1 com painel, ddtank 4.2, ddtank splush 4.1, ddtank 4, resgate 4 ddtank, ddtank 5.9, ddtank 5.5 com painel, ddtank 5.5, ddtank 5.9 pirata, ddtank 5.3, ddtank 5.5 files, ddtank 5.0, ddtank 5.9 files, resgate 5 ddtank, ddtank 6.5, ddtank 6.1, ddtank 6.6, ddtank 6.4, ddtank 65 angle, ddtank 6.5 files, ddtank angulo 65, ddtank ang 65, ddtank 7road, ddtank 7k7k como se registrar, ddtank 7 games, ddtank 777, ddtank 7.5, ddtank 7t games, ddtank 7teen, ddtank 7 road, 7 road ddtank mobile, servidor 7 ddtank, ddtank 7 games español, code ddtank 7 2021, ddtank 8.5 source, ddtank 8.5, ddtank 99999 cupons gratis, 92 ddtank, ddtank pirata 99999 cupons, angulo 90 ddtank, ddtank pirata com 99999 cupons gratis, ddtank world 9.0, ddtank.91, ddtank 99999 coupons, ddtank 337 antigo" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="{{ url() }}" />

    <link rel="shortcut icon" href="{{ url() }}/assets/media/icons/original.png" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="{{ url() }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url() }}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ url() }}/assets/plugins/global/plugins.{{ $asset }}bundle.css" rel="stylesheet"
        type="text/css" id="plugins_css" />
    <link href="{{ url() }}/assets/css/style.{{ $asset }}bundle.css" rel="stylesheet" type="text/css"
        id="style_css" />
    <!--end::Global Stylesheets Bundle-->
    <link href="{{ url() }}/assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
    style="background-image: url({{ url('assets/media/patterns/header-bg.jpg') }}); background-size: 100%!important;"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
				@if (!$session->has('clientUser'))
					@include('components.default.header')
				@endif
                <!--end::Header-->
                <!--begin::Toolbar-->
                @section('toolbar')
                @show
                <!--end::Toolbar-->
                <!--begin::Container-->
                @yield('content')
                <!--end::Container-->
                <!--begin::Footer-->
                @include('components.default.footer')
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--begin::Drawers-->
    @section('drawers')
    @show
    <!--end::Drawers-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                    transform="rotate(90 13 6)" fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--begin::Modals-->
    @section('modals')
    @show
    <!--end::Modals-->
    <!--begin::Toast-->
    <div id="kt_docs_toast_stack_container" class="toast-container position-fixed top-0 end-0 p-3"
        style="z-index: 100!important;">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-kt-docs-toast="stack">
            <div class="toast-header">
                <span class="svg-icon svg-icon-2 svg-icon-primary me-3">...</span>
                <strong class="me-auto">Keenthemes</strong>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
    <!--end::Toast-->

    <!--begin::Javascript-->
    <script>
        const baseUrl = "{{ url() }}",
            uid = "{{ $user->id }}",
            role = "{{ $user->role }}",
            AUTH_TOKEN = "{{ $_SESSION['token'] ?? '' }}";

        const user_app = {
            id: {{ $user->id }},
            role: {{ $user->role }},
            token: "{{ $_SESSION['token'] ?? '' }}",
        }
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ url() }}/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{ url() }}/assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ url() }}/assets/js/widgets.bundle.js"></script>
    <script src="{{ url() }}/assets/js/custom/widgets.js"></script>
    <script src="{{ url() }}/assets/js/custom/axios.min.js"></script>
    <script src="{{ url() }}/assets/js/custom/main.js"></script>
    <script>
        // $(document).ready(() => {
        //     $('.modal').modal({
        //         backdrop: "static"
        //     });
        // });
    </script>
    @section('custom-js')
    @show
    <!--end::Page Custom Javascript-->
    <!--end::Javascript-->
    @stack('scripts')
</body>
<!--end::Body-->

</html>
