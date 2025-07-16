@extends('layouts.app')

@section('title', 'Servidores')

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
                    <div class="card card-xl-stretch mb-xl-3">
                        <div class="card-body d-flex flex-column">
                            <div class="menu-content d-flex align-items-center px-0 mb-5">
                                <div class="symbol symbol-50px me-3">
                                    @if (isset($user->border) and $user->border != 'none')
                                        <div
                                            style=" background-image: url({{ url('assets/media/borders/' . $user->border) }}); background-size: cover; width: 120%; height: 120%; position: absolute; margin-top: -10%; margin-left: -10.2%; ">
                                        </div>
                                    @endif
                                    <img alt="Logo" src="{{ image_avatar($user->photo, 50, 50) }}">
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-7">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </div>
                                    <span class="fw-bold text-muted fs-6">{{ str_obfuscate_email($user->email) }}</span>
                                </div>
                            </div>
                            @if (!empty($characters))
                                <select class="form-select form-select-sm form-select-solid px-6" name="person_id"
                                    data-hide-search="true" data-control="select2" data-placeholder="Personagem">
                                    @foreach ($servers as $server)
                                        @if (isset($characters[$server->id]))
                                            <option value="{{ $server->id }}" {{ !$loop->first ?: 'selected' }}>
                                                {{ $characters[$server->id]->NickName }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                            <div class="bg-gray-100 rounded mt-3" id="character-preview" style="">
                                @if (!empty($characters))
                                    <div class="position-relative p_picture">
                                        <div class="d-none p_circlelightc"
                                            style="background-image: url(http://localhost:8000/assets/media/circlelight/6.gif);">
                                        </div>
                                        <div class="f_face">
                                            <img src="">
                                        </div>
                                        <div class="f_effect">
                                            <img src="">
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
                                        <div class="i_grade">
                                            <img src="">
                                        </div>
                                    </div>
                                @else
                                    @include('components.default.notfound', [
                                        'title' => 'Opss',
                                        'message' => 'nenhum personagem encontrado',
                                        'icon' => true,
                                    ])
                                @endif
                            </div>
                            <div class="mt-5" id="person-info"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder  fs-6 mb-1">🌍 Lista de servidores</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            @if (!empty($servers))
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bolder text-muted">
                                                <th class="w-250px">Servidor</th>
                                                <th class="min-w-150px">Personagem</th>
                                                <th class="min-w-100px text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($servers as $server)
                                                @if ($server->active == 0 && $user->role != 3 && $server->status == 'not_visible')
                                                    @continue
                                                @endif
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="{{ $server->active || $user->role != 1 ? url('app/jogar/' . $server->id) : 'javascript:;' }}"
                                                                    {{ $server->active || $user->role != 1 ? 'target=_blank' : '' }}
                                                                    class="text-dark text-hover-primary fs-6">
                                                                    {{ $server->name }}
                                                                </a>
                                                                <span
                                                                    class="text-muted fw-bold fs-7">{!! $server->active == 0 ? match ($server->status) { 'maintenance' => '🚧 Servidor <span class="text-warning">em manutenção</span>',  'comming_soon' => '🚀 Servidor será lançado <span class="text-primary">em breve</span>',  'not_visible' => '👁‍🗨 Servidor <span class="text-danger">não visível</span>',  'offline' => '🔴 Servidor <span class="text-danger">desligado</span>' } : '🟢 Servidor <span class="text-primary">disponível</span>' !!}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center me-2">
                                                            <div class="symbol symbol-40px me-3">
                                                                @if (isset($characters[$server->id]))
                                                                    <div class="game-avatar bg-lighten w-40px h-40px">
                                                                        <div class="equip face"
                                                                            style="background-image: url({{ $characters[$server->id]->equipments['face'] }});">
                                                                        </div>
                                                                        <div class="equip hair"
                                                                            style="background-image: url({{ $characters[$server->id]->equipments['hair'] }});">
                                                                        </div>
                                                                    </div>
                                                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-0 bg-{{ $characters[$server->id]->State == '1' ? 'success' : 'danger' }} rounded-circle border border-1 border-light h-10px w-10px"
                                                                        style="border-color: var(--bs-body-bg)!important;">
                                                                    </div>
                                                                @else
                                                                    <div class="symbol-label bg-light-danger">
                                                                        <span class="text-danger">?</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                @if (isset($characters[$server->id]))
                                                                    <a
                                                                        class="fs-6 text-gray-800 text-hover-primary fw-bolder">
                                                                        {{ $characters[$server->id]->NickName }}
                                                                    </a>
                                                                    <div class="fs-7 text-muted fw-bold mt-1 d-flex">
                                                                        <div class="icon-power me-2"
                                                                            style="width: 11px; height: 14px; margin-top: 0; ">
                                                                        </div>
                                                                        {{ $characters[$server->id]->FightPower }}
                                                                    </div>
                                                                @else
                                                                    <span class="fs-6 text-muted">
                                                                        Nenhum personagem
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end flex-shrink-0">
                                                            <a href="{{ $server->active || $user->role != 1 ? url('app/jogar/' . $server->id) : 'javascript:;' }}"
                                                                {{ $server->active || $user->role != 1 ? 'target=_blank' : '' }}
                                                                @class([
                                                                    'btn btn-sm',
                                                                    'btn-light-primary' => $server->active == 1 || $user->role != 1,
                                                                    'btn-danger disabled' => $server->active == 0 && $user->role == 1,
                                                                    'w-100',
                                                                ])>
                                                                <span class="indicator-label">
                                                                    {{ $server->active == 0 && $user->role == 1 ? 'indisponível' : 'Jogar' }}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include('components.default.notfound', [
                                    'title' => 'Opss',
                                    'message' => 'nenhum servidor encontrado, fique atento<br> as redes sociais para novos servidores',
                                ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        const characters = @json($characters);

        $('select[name="person_id"]').on('change', () => {
            const person = characters[$('select[name="person_id"]').val()];

            if (!person)
                return;

            if (person.equipments.head)
                person.equipments.hair = person.equipments.hair.replace('/b/', '/a/');

            $('#character-preview .f_head img').attr('src', person.equipments.head);
            $('#character-preview .f_hair img').attr('src', person.equipments.hair);
            $('#character-preview .f_effect img').attr('src', person.equipments.eff);
            $('#character-preview .f_face img').attr('src', person.equipments.face);
            $('#character-preview .f_cloth img').attr('src', person.equipments.cloth);
            $('#character-preview .f_arm img').attr('src', person.equipments.arm);
            $('#character-preview .i_grade img').attr('src', `${baseUrl}/assets/media/levels/${person.Grade}.png`);

            $('#person-info').empty();

            const getRanking = (position) => {
                switch (position) {
                    case 1:
                        return '<div class="symbol-label bg-transparent icon-ranking gold w-40px h-30px"></div>';
                    case 2:
                        return '<div class="symbol-label bg-transparent icon-ranking silver w-40px h-30px"></div>';
                    case 3:
                        return '<div class="symbol-label bg-transparent icon-ranking bronze w-40px h-30px"></div>';
                    default:
                        return `<div class="symbol-label bg-light-${position <= 100 ? 'primary' : 'danger'}">
                            <span class="text-${position <= 100 ? 'primary' : 'danger'}">${position}</span>
                        </div>`;
                }
            }

            $('#person-info').html(`<div class="d-flex flex-stack mb-5">
                <div class="d-flex align-items-center me-2">
                    <div class="symbol symbol-40px me-3">
                        ${getRanking(person.position)}
                    </div>
                    <div>
                        <a class="fs-6 text-gray-800 text-hover-primary fw-bolder">
                            ${person.position}°
                        </a>
                        <div class="fs-7 text-muted fw-bold mt-1">ranking de força</div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-stack mb-5">
                <div class="d-flex align-items-center w-100 me-2">
                    <div class="d-flex flex-column w-100 me-2">
                        <div class="d-flex flex-stack mb-2">
                            <span class="text-muted me-2 fs-7 fw-bold">Taxa de vitória</span>
                        </div>
                        <div class="progress h-6px w-100">
                            <div class="progress-bar bg-${person.WinRate > 70 ? 'primary' : 'danger'}" role="progressbar" style="width:${person.WinRate}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="badge text-${person.WinRate > 70 ? 'primary' : 'danger'} fw-bold py-4 px-3">${person.WinRate}%</div>
            </div>`);
        })

        $('select[name="person_id"]').trigger('change');
    </script>
@endsection
