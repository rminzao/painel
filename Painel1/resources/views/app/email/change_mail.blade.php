<div style="margin:0;padding:0" bgcolor="#FFFFFF">
    <table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="pt-br">
        <tbody>
            <tr height="32" style="height:32px">
                <td></td>
            </tr>
            <tr align="center">
                <td>
                    <div>
                        <div></div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0"
                        style="padding-bottom:20px;max-width:516px;min-width:220px">
                        <tbody>
                            <tr>
                                <td width="8" style="width:8px"></td>
                                <td>
                                    <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px"
                                        align="center" class="m_-4738374353796801555mdv2rw"><img
                                            src="{{ url() }}/assets/media/logos/logo.webp"
                                            width="74" height="24" aria-hidden="true" style="margin-bottom:16px"
                                            alt="Google" class="CToWUd">
                                        <div
                                            style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word">
                                            <div style="font-size:24px">Alteração de conta </div>
                                            <table align="center" style="margin-top:8px">
                                                <tbody>
                                                    <tr style="line-height:normal">
                                                        <td align="right" style="padding-right:8px"><img width="20"
                                                                height="20"
                                                                style="width:20px;height:20px;vertical-align:sub;border-radius:50%"
                                                                src="{{ image_avatar($user->photo, 28, 28) }}" alt=""
                                                                class="CToWUd"></td>
                                                        <td><a
                                                                style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.87);font-size:14px;line-height:20px">{{ $user->email }}</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div
                                            style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:center">
                                            Detectamos uma solicitação de alteração na sua Conta do
                                            <b>{{ $_ENV['APP_NAME'] }}</b>. Se foi você
                                            que fez isso clique no botão abaixo. Se não foi, contate o suporte e
                                            ajudaremos a proteger
                                            sua conta.
                                            <div style="padding-top:32px;text-align:center">
                                                <a href="{{ $confirm_link }}"
                                                    style="font-family:'Google Sans',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px"
                                                    target="_blank">Confirmar alteração
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align:left">
                                        <div
                                            style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                            <div>Este e-mail foi enviado a você para informar sobre alterações
                                                importantes na sua Conta do {{ $_ENV['APP_NAME'] }} e serviços
                                                relacionados.</div>
                                            <div style="direction:ltr">© {{ date('Y') }} {{ $_ENV['APP_NAME'] }}
                                                LLC, <a class="m_-4738374353796801555afal"
                                                    style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">1600
                                                    Amphitheatre Parkway, Mountain View, CA 94043, USA</a></div>
                                        </div>
                                    </div>
                                </td>
                                <td width="8" style="width:8px"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr height="32" style="height:32px">
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
