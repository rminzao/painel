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
                                <div id="gameContainer">
                                    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="7road-ddt-game"
                                        codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
                                        name="Main" width="1000" height="600" align="middle">
                                        <param name="allowScriptAccess" value="always">
                                        <param name="movie"
                                            value="{{ $server->flash }}/Loading.swf?user={{ $user->u_hash }}&amp;key={{ $hash }}&amp;config={{ url() }}/api/server/config/{{ $server->id }}">
                                        <param name="quality" value="high">
                                        <param name="menu" value="false">
                                        <param name="bgcolor" value="#000000">
                                        <param name="allowScriptAccess" value="always">
                                        <param name="wmode" value="direct">
                                        <param name="FlashVars"
                                            value="site=&amp;sitename=&amp;rid=&amp;enterCode=&amp;sex=">
                                        <embed flashvars="site=&amp;sitename=&amp;rid=&amp;enterCode=&amp;sex="
                                            src="{{ $server->flash }}/Loading.swf?user={{ $user->u_hash }}&amp;key={{ $hash }}&amp;config={{ url() }}/api/server/config/{{ $server->id }}"
                                            width="1000" height="600" align="middle" quality="high" name="Main"
                                            allowscriptaccess="always" type="application/x-shockwave-flash"
                                            pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="direct">
                                    </object>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
