<html>

<head>
    <meta HTTP-EQUIV="Pragma" content="no-cache" />
    <meta HTTP-EQUIV="Cache-Control" content="no-cache" />
    <meta HTTP-EQUIV="Expires" content="0" />
    <title> {{ $server->name }} </title>
    <link rel="shortcut icon" href="{{ url() }}/assets/media/icons/original.png" />
    <link rel="stylesheet" href="{{ url() }}/assets/css/play.bundle.css">
</head>

<body scroll="no">
    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="middle">
                <table border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <div @class([
                                'gameOuterContainer' => $server->active,
                                'gameOuterContainerMaintenance' => !$server->active && $user->role == 1,
                            ]) id="gameContainerArea">
                                <div id="gameContainer"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if ($server->settings->navbar == 1 && !is_mobile())
        <div class="header">
            <div class="logo"></div>
            <div class="menu">
                <a class="menu-item" href="{{ url() }}" target="_blank">Home</a>
                <a class="menu-item" href="{{ url('app/recarga') }}" target="_blank">Recarga</a>
                <a class="menu-item" href="#">Blog</a>
                <a class="menu-item" href="{{ url('app/me/ticket/list') }}" target="_blank">Ajuda</a>
            </div>
            <div class="aside-right">
                <div class="server-list">
                    <span class="server-name">{{ $server->name }}</span>
                </div>
                <a href="{{ url('app/me/account/overview') }}" target="_blank" class="user-profile">
                    <div class="avatar">
                        <img src="{{ image_avatar($user->photo, 28, 28) }}">
                    </div>
                    <span class="name">{{ $user->first_name . ' ' . $user->last_name }}</span>
                </a>
                <a class="logout-button" href="{{ url('sair') }}">
                    <img src="{{ url('assets/media/others/logout.svg') }}"></span>
                </a>
            </div>
        </div>
    @endif

    @if ($server->active || $user->role != 1 || $type == 'admin')
        <script src="{{ url() }}/assets/plugins/global/plugins.bundle.js"></script>
        <script src="{{ url() }}/assets/js/scripts.bundle.js"></script>
        <script>
            var baseUrl = "{{ url() }}",
                uid = "{{ $user->id }}",
                sid = "{{ $server->id }}",
                type = "{{ $type }}",
                flash = "{{ $server->flash }}",
                flash_quality = "{{ $server->settings->flash_quality }}";

            @if (!empty($cookie))
                KTCookie.set(`last_server`, '{{ $cookie }}', {
                    expires: new Date(Date.now() + 60 * 60 * 24 * 30)
                });
            @endif
        </script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.26.0/babel.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/smooth-scroll/16.1.3/smooth-scroll.polyfills.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ url() }}/assets/js/app/play.bundle.js"></script>
    @endif
</body>

</html>
