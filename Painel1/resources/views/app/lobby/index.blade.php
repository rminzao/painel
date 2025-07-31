@extends('layouts.app')

@section('title', 'Lobby')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">Lobby</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url('app/lobby') }}" class="text-white text-hover-primary">
                            {{ $_ENV['APP_NAME'] }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Lobby</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="row g-5 g-xl-8">
                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column p-6">
                            <div class="menu-content d-flex align-items-center px-0 mb-5">
                                <a href="{{ url('app/me/account/overview') }}" class="symbol symbol-50px me-3">
                                    @if (isset($user->border) and $user->border != 'none')
                                        <div
                                            style=" background-image: url({{ url('assets/media/borders/' . $user->border) }}); background-size: cover; width: 120%; height: 120%; position: absolute; margin-top: -10%; margin-left: -10.2%; ">
                                        </div>
                                    @endif
                                    <img alt="Logo" src="{{ image_avatar($user->photo, 50, 50) }}">
                                </a>
                                <div class="d-flex flex-column">
                                    <a href="{{ url('app/me/account/overview') }}"
                                        class="fw-bolder text-dark d-flex align-items-center fs-7">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </a>
                                    <a href="{{ url('sair') }}" class="text-danger fs-6">sair</a>
                                </div>
                                <div class="user-select-none pe-none"
                                    style="
                                      position: absolute;
                                      max-width: 150px;
                                      right: 0;
                                      top: -11.8%;
                                  ">
                                    <img src="https://i.imgur.com/UhNvdFz.png" class="w-100 user-select-none pe-none">
                                </div>
                            </div>
                            <span class="text-dark fs-6 mb-3">❓ Jogado recentemente:</span>
                            <div class="bg-gray-100 rounded z-index-1 h-md-50px mb-6 px-4 overflow-auto mh-250px">
                                @if (!empty($last_server))
                                    <div class="px-0">
                                        <div class="d-flex justify-content-between pt-2">
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="{{ url('app/jogar/' . $last_server['id']) }}" target="_blank"
                                                    class="text-dark text-hover-primary fs-7">
                                                    {{ $last_server['name'] }}
                                                </a>
                                                <span class="text-muted fw-bold fs-7">
                                                    ult. acesso
                                                    {{ isset($last_server['last']) ? date_fmt_ago($last_server['last']) : 'desconhecido' }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                <a href="{{ url('app/jogar/' . $last_server['id']) }}" target="_blank"
                                                    class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16.9 10.7L7 5V19L16.9 13.3C17.9 12.7 17.9 11.3 16.9 10.7Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex">
                                <a href="{{ url('app/recarga') }}" class="btn btn-sm btn-light-primary w-100 me-2">
                                    Recarga
                                </a>
                                <a href="{{ url('app/me/account/overview') }}"
                                    class="btn btn-sm btn-light-primary w-100 me-2">
                                    Meu Perfil
                                </a>
                            </div>

                            <div class="mb-6"></div>

                            <span class="text-dark fs-6 mb-3">🌍 Lista de servidores:</span>
                            <div class="bg-gray-100 rounded p-4 overflow-auto mh-300px">
                                @if (!empty($servers))
                                    @foreach ($servers as $server)
                                        @if ($server->active == 0 && $user->role != 3 && $server->status == 'not_visible')
                                            @continue
                                        @endif
                                        <div class="px-0">
                                            <div class="d-flex justify-content-between pt-2 {{ !$loop->last ?: 'pb-2' }}">
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="{{ $server->active || $user->role != 1 ? url('app/jogar/' . $server->id) : 'javascript:;' }}"
                                                        {{ !$session->has('clientUser') && ($server->active || $user->role != 1) ? 'target=_blank' : '' }}
                                                        class="text-dark text-hover-primary fs-7">
                                                        {{ $server->name }}
                                                    </a>
                                                    <span class="text-muted fw-bold fs-7">{!! $server->active == 0
                                                        ? match ($server->status) {
                                                            'maintenance' => '🚧 Servidor <span class="text-warning">em manutenção</span>',
                                                            'comming_soon' => '🚀 Servidor será lançado <span class="text-primary">em breve</span>',
                                                            'not_visible' => '👁‍🗨 Servidor <span class="text-danger">não visível</span>',
                                                            'offline' => '🔴 Servidor <span class="text-danger">desligado</span>',
                                                        }
                                                        : '🟢 Servidor <span class="text-primary">disponível</span>' !!}</span>
                                                </div>
                                                <div class="d-flex justify-content-end flex-shrink-0">
                                                    <a href="{{ $server->active || $user->role != 1 ? url('app/jogar/' . $server->id) : 'javascript:;' }}"
                                                        {{ !$session->has('clientUser') && ($server->active || $user->role != 1) ? 'target=_blank' : '' }}
                                                        @class([
                                                            'align-self-center',
                                                            'text-primary' => $server->active == 1 || $user->role != 1,
                                                            'text-danger disabled' => $server->active == 0 && $user->role == 1,
                                                        ])>
                                                        <span class="indicator-label">
                                                            {{ $server->active == 0 && $user->role == 1 ? 'indisponível' : 'Jogar' }}
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!$loop->last)
                                            <div class="mt-3 separator"></div>
                                        @endif
                                    @endforeach
                                @else
                                    @include('components.default.notfound', [
                                        'title' => 'Opss',
                                        'message' =>
                                            'nenhum servidor encontrado, fique atento<br> as redes sociais para novos servidores',
                                    ])
                                @endif
                            </div>
							@if (!$session->has('clientUser'))
								{{-- 
								<div class="mt-5 cursor-pointer"
									onclick="window.location.href='https://redtankbr.com.br/app/me/account/referrals'"
									style="background-image: url(https://media.discordapp.net/attachments/942075882233790486/1014734702629896192/IndiqueR.png?width=813&height=480); background-size: cover; background-position: center; height: 212px; border-radius: 7px; flex: auto;">
								</div> --}}
							@endif
                        </div>
                    </div>
                </div>
				@if (!$session->has('clientUser'))
                <div class="d-flex flex-column col-xl-8">
                    <div class="row g-5 g-xl-8 mb-5">
                        <div class="col-xl-7 pe-0">
                            <div class="card h-100">
                                <div class="card-body p-6">
                                    <div class="pb-2 d-flex flex-stack">
                                        <span class="fs-6 fw-bolder">📢 Notícias</span>
                                        <a class="fs-7 text-muted text-hover-primary me-2" href="javascript:;">ver mais</a>
                                    </div>
                                    <div class="scroll-y h-lg-auto">
                                        <div class="d-flex flex-stack pt-2">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a
                                                        class="fs-8 fw-bolder text-gray-600 text-hover-primary cursor-pointer mb-2">
                                                        Lançamento dia "<span class="text-primary">em breve</span>" fiquem
                                                        atentos !
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end ms-2 text-muted">
                                                30/08 15:06
                                            </div>
                                        </div>
                                        <div class="d-flex flex-stack pt-2">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="{{ url('links') }}" target="_blank"
                                                        class="fs-8 fw-bolder text-gray-600 text-hover-primary cursor-pointer mb-2">
                                                        Visite nossas redes sociais "<span class="text-primary">clicando
                                                            aqui</span>"
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end ms-2 text-muted">
                                                29/08 11:22
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-5">
                            <div class="card card-xl-stretch">
                                <div class="card-body p-0" style="justify-content: center;">
                                    <div class="tns card-rounded">
                                        <div data-tns="true" data-tns-nav="false" data-tns-nav-position="bottom"
                                            data-tns-controls="false">											
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="card ranking-bg">
                        <div class="row w-100 h-100">

                            <div class="col-12 col-xl-5 ms-4 me-n4">
                                <div class="highlight p-1 my-5">
                                    <table class="table align-middle table-row-dashed fs-7 gy-0 mb-0">
                                        <tbody class="text-gray-600" id="ranking_list"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7">
                                <div class="d-flex justify-content-center align-items-center rounded m-4 w-100"
                                    style="padding-top: 9rem!important;">
                                    <div class="me-6 mt-n5 ranking-p2" id="podium_2">
                                        <div class="d-flex flex-column align-content-center align-items-center">
                                            <div class="d-flex highlight p-1 m-0 mb-3 text-center justify-content-center w-100"
                                                id="podium_2_name">❓ Desconhecido</div>
                                        </div>
                                        <div class="position-relative p_picture mb-n11">
                                            <div class="f_face">
                                                <img data-current="0" src="">
                                            </div>
                                            <div class="f_hair">
                                                <img src="">
                                            </div>
                                            <div class="f_head">
                                                <img src="">
                                            </div>
                                            <div class="f_cloth">
                                                <img src="">
                                            </div>
                                            <div class="f_arm">
                                                <img src="">
                                            </div>
                                            <div class="i_grade" style="width: 39px; top: 1px;">
                                                <div class="icon-ranking silver me-3 mb-3"></div>
                                            </div>
                                        </div>
                                        <div
                                            style="background-image: url(/assets/media/rank/b6G809p.png); width: 144%; height: 135px; background-size: cover; background-position: center; margin-left: -22px;">
                                        </div>
                                    </div>
                                    <div class="me-6 mt-n15 ranking-p1" id="podium_1">
                                        <div class="d-flex flex-column align-content-center align-items-center">
                                            <div class="d-flex highlight p-1 m-0 mb-3 text-center justify-content-center w-100"
                                                id="podium_1_name">❓ Desconhecido</div>
                                        </div>
                                        <div class="position-relative p_picture mb-n11">
                                            <div class="f_face">
                                                <img data-current="0" src="">
                                            </div>
                                            <div class="f_hair">
                                                <img src="">
                                            </div>
                                            <div class="f_head">
                                                <img src="">
                                            </div>
                                            <div class="f_cloth">
                                                <img src="">
                                            </div>
                                            <div class="f_arm">
                                                <img src="">
                                            </div>
                                            <div class="i_grade" style="width: 39px; top: 1px;">
                                                <div class="icon-ranking gold me-3 mb-3"></div>
                                            </div>
                                        </div>

                                        <div
                                            style="background-image: url(/assets/media/rank/zZ23Vlh.png); width: 144%; height: 135px; background-size: cover; background-position: center; margin-left: -22px;">
                                        </div>
                                    </div>
                                    <div class="me-6 ranking-p3" id="podium_3">
                                        <div class="d-flex flex-column align-content-center align-items-center">
                                            <div class="d-flex highlight p-1 m-0 mb-3 text-center justify-content-center w-100"
                                                id="podium_3_name">❓ Desconhecido</div>
                                        </div>
                                        <div class="position-relative p_picture mb-n9">
                                            <div class="f_face">
                                                <img data-current="0" src="">
                                            </div>
                                            <div class="f_hair">
                                                <img src="">
                                            </div>
                                            <div class="f_head">
                                                <img src="">
                                            </div>
                                            <div class="f_cloth">
                                                <img src="">
                                            </div>
                                            <div class="f_arm">
                                                <img src="">
                                            </div>
                                            <div class="i_grade" style="width: 39px; top: 1px;">
                                                <div class="icon-ranking bronze me-3 mb-3"></div>
                                            </div>
                                        </div>
                                        <div
                                            style="background-image: url(/assets/media/rank/tfow5a8.png); width: 100%; height: 102px; background-size: cover; background-position: center;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="position-absolute" style="bottom: 3%; right: 1.5%;">
                            <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                data-placeholder="Selecione o servidor" data-hide-search="true" id="ranking_server">
                                @foreach ($servers as $server)
                                    <option value="{{ $server->id }}">{{ $server->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            
				@endif
			</div>
        </div>
    </div>
@endsection

@section('modals')
    {{-- <div class="modal fade" id="md_discount" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div>
                    <div class="w-100 h-200px rounded-top"
                        style="background-image: url(https://i.imgur.com/A1KE123.png); background-size: cover;"></div>
                    <div class="btn btn-sm btn-icon btn-color-white btn-active-color-danger position-absolute top-0 end-0"
                        data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>


                <div class="modal-body">
                    <div class="fs-7 fw-bold text-gray-800">
                        <p>
                            Olá jogador, você tem um cupom de desconto para usar no site.
                        </p>
                        <p>
                            para usar o cupom, basta digitar o código abaixo no ato de compra e clicar em "Usar Cupom",
                            para realizar a compra acesse "<a class="text-primary"
                                href="{{ url('app/recarga') }}">recarga</a>".
                        </p>
                        <p>
                            Código: <span class="text-primary fs-4"><strong>NOVOSERVIDOR</strong></span>
                        </p>
                    </div>
                </div>
                <div class="pe-4 pb-2 d-flex flex-row-reverse">
                    <label class="form-check form-check-sm form-check-custom form-check-solid">
                        <span class="form-check-label text-muted fs-8 me-2">não mostrar este aviso novamente nas proximas
                            24h</span>
                        <input class="form-check-input w-13px h-13px" type="checkbox" name="hidden_md_discount"
                            value="1">
                    </label>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="modal fade" id="md_laboratory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div>
                    <div class="w-100 h-200px rounded-top"
                        style="background-image: url(https://i.imgur.com/Ipj9koh.jpg); background-size: cover;background-position: center;">
                    </div>
                    <div class="btn btn-sm btn-icon btn-color-white btn-active-color-danger position-absolute top-0 end-0"
                        data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>


                <div class="modal-body">
                    <div class="highlight p-3 fs-6 fw-bold text-gray-900 mb-5">
                        <p>
                            Olá <span class="text-primary">{{ $user->first_name }}</span>, está sem tempo ou não consegue
                            passar daquele nível chato do laboratório ?
                        </p>
                        <p class="mb-0">
                            Resolva este problema com apenas um clique, <br>comprando o "<span class="text-success"
                                style="text-transform: uppercase;">🏹 pacote de laboratório</span>" todo seu laboratório é
                            finalizado e os prêmios enviados
                            diretamente ao seu correio.
                        </p>
                    </div>
                    <div>
                        <a href="{{ url('/app/recarga') }}" class="btn btn-sm btn-light-success w-100">
                            COMPLETAR MEU LABORATÓRIO
                        </a>
                        <div
                            style="background-image: url(https://imgur.com/5qRov7Z.gif); width: 100px; height: 100px; position: absolute; right: -9%; bottom: 0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="md_social" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h3 class="modal-title fs-5">😆 Evento facebook</h3>
                    <div class="btn w-25px h-25px btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                    rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="currentColor">
                                </rect>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <iframe
                        src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FRM4ST3RTANK%2Fposts%2Fpfbid0K7541qYCptXevtmaPqcQhWYtPpZgUqL1AbvP8MuniXeoFDKjPAMHzmiQV91Tj3DRl&amp;show_text=true&amp;width=500"
                        width="500" height="718" style="background-color: white;border: none;border-radius: 8px;"
                        scrolling="no" frameborder="0" allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    <div>
                        // <a href="{{ $_ENV['FACEBOOK_URL'] }}" class="btn btn-sm btn-light-primary w-100">
                            // Acessar página
                        // </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        const getRaking = () => {
            const sid = $('#ranking_server').val();
            axios.get(`${baseUrl}/api/ranking/lobby`, {
                params: {
                    sid: sid
                }
            }).then(res => {
                $('#ranking_list').empty();

                //check if have results
                if (res?.data?.list.lenght == 0) return;

                //populate list
                $.each(res.data.list, (index, char) => {
                    index = index + 1;
                    const getIconByIndex = (index) => {
                        switch (index) {
                            case 1:
                                return `<div class="icon-ranking gold" style="width: 24px;height: 20px;"></div>`;
                            case 2:
                                return `<div class="icon-ranking silver" style="width: 24px;height: 20px;"></div>`;
                            case 3:
                                return `<div class="icon-ranking bronze" style="width: 24px;height: 20px;"></div>`;
                            default:
                                return `<span class="text-primary">${index}</span> - `;
                        }
                    };

                    $('#ranking_list').append(`<tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-30px ms-2 me-3">
                                    <img src="${char?.app?.photo}"
                                        onerror="this.src='${baseUrl}/assets/media/icons/original.png';"
                                        alt="">
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-n1 ${char?.State == '1' ? 'bg-success' : 'bg-danger'} rounded-circle border border-1 border-light h-10px w-10px"
                                        style="border-color: var(--bs-body-bg)!important;"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a class="fs-7 text-gray-800 text-hover-primary mb-1 d-flex">
                                        <div class="symbol symbol-20px overflow-hidden me-2">
                                            ${getIconByIndex(index)}
                                        </div>
                                        ${char?.NickName}
                                    </a>
                                    <span class="fs-7">nv. ${char?.Grade}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="fightpower-icon"></div>
                                ${char?.FightPower}
                            </div>
                        </td>
                    </tr>`);
                });

                //populate podium
                if (res.data?.podium[0]) {
                    const podium_1 = res.data?.podium[0];
                    if (!podium_1?.equipment?.hair.includes('default'))
                        podium_1.equipment.hair = podium_1.equipment.hair.replace('/b/', '/a/');

                    $('#podium_1_name').text(podium_1?.NickName ?? '❓ Desconhecido');
                    $('#podium_1').find('.f_face img').attr('src', podium_1?.equipment?.face);
                    $('#podium_1').find('.f_hair img').attr('src', podium_1?.equipment?.hair);
                    $('#podium_1').find('.f_head img').attr('src', podium_1?.equipment?.head);
                    $('#podium_1').find('.f_cloth img').attr('src', podium_1?.equipment?.cloth);
                    $('#podium_1').find('.f_arm img').attr('src', podium_1?.equipment?.arm);
                }

                if (res.data?.podium[1]) {
                    const podium_2 = res.data?.podium[1];
                    if (!podium_2?.equipment?.hair.includes('default'))
                        podium_2.equipment.hair = podium_2.equipment.hair.replace('/b/', '/a/');

                    $('#podium_2_name').text(podium_2?.NickName ?? '❓ Desconhecido');
                    $('#podium_2').find('.f_face img').attr('src', podium_2?.equipment?.face);
                    $('#podium_2').find('.f_hair img').attr('src', podium_2?.equipment?.hair);
                    $('#podium_2').find('.f_head img').attr('src', podium_2?.equipment?.head);
                    $('#podium_2').find('.f_cloth img').attr('src', podium_2?.equipment?.cloth);
                    $('#podium_2').find('.f_arm img').attr('src', podium_2?.equipment?.arm);
                }

                if (res.data?.podium[2]) {
                    const podium_3 = res.data?.podium[2];
                    if (!podium_3?.equipment?.hair.includes('default'))
                        podium_3.equipment.hair = podium_3.equipment.hair.replace('/b/', '/a/');

                    $('#podium_3_name').text(podium_3?.NickName ?? '❓ Desconhecido');
                    $('#podium_3').find('.f_face img').attr('src', podium_3?.equipment?.face);
                    $('#podium_3').find('.f_hair img').attr('src', podium_3?.equipment?.hair);
                    $('#podium_3').find('.f_head img').attr('src', podium_3?.equipment?.head);
                    $('#podium_3').find('.f_cloth img').attr('src', podium_3?.equipment?.cloth);
                    $('#podium_3').find('.f_arm img').attr('src', podium_3?.equipment?.arm);
                }
            }).catch(err => console.error(err))
        };

        function faceAnmite() {
            var faceObj = $('#podium_1, #podium_2, #podium_3').find('.f_face img');
            var current = faceObj.data('current');
            var faceTrans = [0, 397, 264.8, 397];
            current++;

            if (current == 4) {
                current = 0;
            }

            faceObj.data('current', current);
            faceObj.css('transform', 'translateX(-' + faceTrans[current] + 'px)');

            setTimeout(faceAnmite, current > 0 ? 100 : 2000);
        }

        $(document).ready(() => {
            $('#yt_frame iframe').attr('src', 'https://www.youtube.com/embed/xEWbjZ7gQA8');
            getRaking();
            setTimeout(faceAnmite, 400);

            if (KTCookie.get(`hidden_md_discount`)) return;

            //$('#md_discount').modal('show');
            //$('#md_laboratory').modal('show');
            //$('#md_social').modal('show');
            var date = new Date(Date.now() + (24 * 60 * 60 * 1000)); // +2 day from now
            var options = {
                expires: date
            }

            $('input[name="hidden_md_discount"]').change(function() {
                $(this).is(':checked') ?
                    KTCookie.set(`hidden_md_discount`, 1, options) :
                    KTCookie.remove(`hidden_md_discount`);
            });


        });

        $('#ranking_server').on('change', () => getRaking());
    </script>
@endsection

