$(function () {
    // window.addEventListener('beforeunload', (event) => {
    //     // Cancel the event as stated by the standard.
    //     event.preventDefault();
    //     // Chrome requires returnValue to be set.
    //     event.returnValue = '';
    // });

    function is_flashplayer() {
        var hasFlash = false;
        try {
            var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
            if (fo)
                hasFlash = true;
        } catch (e) {
            if (navigator.mimeTypes &&
                navigator.mimeTypes['application/x-shockwave-flash'] != undefined &&
                navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin)
                hasFlash = true;
        }
        return hasFlash;
    }

    if (!is_flashplayer()) {
        var element = $("#gameContainerArea");
        element.addClass("gameOuterContainerNoFlash");
        element.removeClass("gameOuterContainer");
        element.removeClass("gameOuterContainerMaintenance");
        element.on('click', () => {
            window.location.href = `https://static.centbrowser.com/win_stable/4.3.9.248/centbrowser_4.3.9.248.exe`;
        })
        return;
    }

    var loadGameScreen = (user, hash) => {
        $("#gameContainer").html(`
          <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="7road-ddt-game" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" name="Main" width="1000" height="600" align="middle" id="Main">
              <param name="allowScriptAccess" value="always" />
              <param name="movie" value="${flash}/Loading.swf?user=${user}&key=${hash}&config=${baseUrl}/api/server/config/${sid}" />
              <param name="quality" value="${flash_quality}" />
              <param name="menu" value="false">
              <param name="bgcolor" value="#000000" />
              <param name="allowScriptAccess" value="always" />
              <param name="wmode" value="direct" />
              <param name="FlashVars" value="site=&sitename=&rid=&enterCode=&sex=" />
              <embed flashVars="site=&sitename=&rid=&enterCode=&sex=" src="${flash}/Loading.swf?user=${user}&key=${hash}&config=${baseUrl}/api/server/config/${sid}" width="1000" height="600" align="middle" quality="${flash_quality}" name="Main" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="direct" />
          </object>
      `);
    }

    var urlApi = `${baseUrl}/api/play/server/${sid}`
    if (type == 'admin') {
        urlApi = `${baseUrl}/api/admin/play/server/${sid}/${uid}`;
    }

    $.post(urlApi, (res) => {
        if (!res.state) {
            alert(res.message);
            return;
        }

        loadGameScreen(res.data.user, res.data.hash)
    }, 'json');
});

function game_pay(param) {
    window.open(`${baseUrl}/app/recarga/${sid}`);
}
