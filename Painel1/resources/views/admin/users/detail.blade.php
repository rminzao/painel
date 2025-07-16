@extends('layouts.app')

@section('title', 'Detalhes do jogador')

@section('custom-js')
    <script>
        const state = {
            sid: null,
            user: {
                id: {{ $userSelected->id }},
                characters: []
            },
            character: {
                id: null,
            }
        }

        $('.menu-sub .menu-link').on('click', function() {
            if ($(this).hasClass('disabled'))
                return;

            $('.menu-sub .menu-link').removeClass('active');
            $(this).addClass('active').siblings();
        });
    </script>
    <script src="{{ url() }}/assets/js/admin/users/edit/web.js"></script>
    <script src="{{ url() }}/assets/js/admin/users/edit/game.js"></script>
@endsection

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">
                    🎮 {{ $userSelected->first_name . ' ' . $userSelected->last_name }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Administração</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Usuários</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Detalhes</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10 mb-lg-0">
                    <div class="card card-flush">
                        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 nav table-responsive "
                            role="tablist" id="#menu_utils" data-kt-menu="true">
                            <div class="menu-item p-6 border-bottom mb-5">
                                <div class="d-flex justify-content-between">
                                    <div class="menu-content d-flex align-items-center px-0 mb-n1">
                                        <div class="symbol symbol-50px me-3">
                                            <img alt="Logo" src="{{ image_avatar($userSelected->photo, 50, 50) }}">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="text-dark fs-8">
                                                {{ $userSelected->first_name . ' ' . $userSelected->last_name }}
                                            </div>
                                            <span class="text-muted fs-8 d-inline-block text-truncate mw-150px">
                                                {{ $userSelected->email }}
                                            </span>
                                            <span class="text-muted fs-8">
                                                {{ match ((int) $userSelected->role) {
                                                    1 => '💣 Jogador',
                                                    0 => '🚧 Tester',
                                                    2 => '👑 Administrador',
                                                    3 => '👨‍💻 Desenvolvedor',
                                                    default => '💣 Jogador'
                                                } }}
                                                | 🌍 <span id="s_name"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                @if (!empty($characters))
                                    <div class="mt-1 mb-2">
                                        <select class="form-select form-select-sm form-select-solid px-6" id="person_id"
                                            data-hide-search="true" data-control="select2" data-placeholder="Personagem">
                                        </select>
                                    </div>
                                    <div class="bg-gray-100 rounded" id="character-preview" style="display:none;">
                                        <div class="position-relative p_picture">
                                            <div class="p_circlelightc"></div>
                                            <div class="f_wing"></div>
                                            <div class="f_face"><img src=""></div>
                                            <div class="f_effect"><img src=""></div>
                                            <div class="f_hair"><img src=""></div>
                                            <div class="f_head"><img src=""></div>
                                            <div class="f_cloth"><img src=""></div>
                                            <div class="p_sinplelight"></div>
                                            <div class="f_arm">
                                                <img src="">
                                            </div>
                                            <div class="i_grade"
                                                style="background-image: url(https://redezaptank.com.br/images/grade/{{ $characters[0]['Grade'] }}.png);">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted fs-8 mt-3">
                                        🔐 Senha mochila: <span id="character-passwordTwo" class="text-danger"></span>
                                    </div>
                                    {{-- <div class="d-flex flex-stack mt-4 mb-n2">
                                        <div class="me-5">
                                            <label class="fs-7 fw-bold form-label">⭐ Animação</label>
                                            <div class="fs-7 fw-bold text-muted">
                                                Desabilita os efeitos.
                                            </div>
                                        </div>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input h-20px w-30px" type="checkbox" id="person_effect"
                                                value="1" checked="checked">
                                            <span class="form-check-label fw-bold text-muted"></span>
                                        </label>
                                    </div> --}}
                                @endif
                            </div>

                            <div class="px-2 pb-4">
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                    <span class="menu-link py-3">
                                        <span class="menu-icon">
                                            <span class="svg-icon svg-icon-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25"
                                                    viewBox="0 0 24 25" fill="none">
                                                    <path opacity="0.3"
                                                        d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                        </span>
                                        <span class="menu-title">Site</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion">
                                        <div class="menu-item">
                                            <a class="menu-link py-3" data-bs-toggle="tab" href="#tab_user_info"
                                                role="tab">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Dados da conta</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-3" data-bs-toggle="tab" href="#tab_user_invoice"
                                                role="tab">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Faturas</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-3" data-bs-toggle="tab" href="#tab_user_email"
                                                role="tab">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Trocar email</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-3" data-bs-toggle="tab" href="#tab_user_password"
                                                role="tab">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Trocar senha</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($characters))
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M10.613 16.163L7.837 13.387C7.65313 13.203 7.54983 12.9536 7.54983 12.6935C7.54983 12.4334 7.65313 12.184 7.837 12L14.775 5.06201C14.9589 4.87814 15.2084 4.7749 15.4685 4.7749C15.7286 4.7749 15.978 4.87814 16.162 5.06201L18.938 7.83801C19.1219 8.02197 19.2252 8.2714 19.2252 8.53149C19.2252 8.79159 19.1219 9.04102 18.938 9.22498L12 16.163C11.816 16.3468 11.5666 16.4502 11.3065 16.4502C11.0464 16.4502 10.797 16.3468 10.613 16.163ZM4.71301 17.203L2.48498 20.322C2.3675 20.4863 2.31218 20.687 2.32879 20.8883C2.3454 21.0896 2.43288 21.2784 2.57571 21.4213C2.71853 21.5641 2.90743 21.6516 3.10873 21.6682C3.31003 21.6848 3.51067 21.6294 3.67498 21.512L6.794 19.2841L4.71301 17.203Z"
                                                            fill="currentColor" />
                                                        <path opacity="0.3"
                                                            d="M7.83701 12L2.28699 6.44995C2.10306 6.26582 1.99976 6.01624 1.99976 5.75598C1.99976 5.49572 2.10306 5.24614 2.28699 5.06201L5.06201 2.28699C5.24614 2.10306 5.49575 1.99976 5.75601 1.99976C6.01627 1.99976 6.26588 2.10306 6.45001 2.28699L12 7.83704L7.83701 12ZM18.937 21.713L21.712 18.938C21.8959 18.7539 21.9992 18.5043 21.9992 18.244C21.9992 17.9838 21.8959 17.7342 21.712 17.55L16.162 12L12 16.163L17.55 21.713C17.7341 21.8969 17.9837 22.0002 18.244 22.0002C18.5042 22.0002 18.7539 21.8969 18.938 21.713H18.937ZM9.146 21.634C9.25064 21.7386 9.37833 21.8172 9.51883 21.8636C9.65933 21.9101 9.80874 21.923 9.95511 21.9014C10.1015 21.8797 10.2407 21.824 10.3618 21.7389C10.4828 21.6537 10.5822 21.5415 10.652 21.411C11.0778 20.2848 11.1695 19.0596 10.9161 17.8826C10.6628 16.7055 10.0752 15.6265 9.22385 14.7751C8.37248 13.9238 7.29352 13.3362 6.11646 13.0829C4.93939 12.8296 3.71424 12.9213 2.58801 13.347C2.45756 13.4169 2.34528 13.5162 2.26013 13.6372C2.17499 13.7582 2.11933 13.8976 2.09766 14.0439C2.07598 14.1903 2.08889 14.3397 2.13531 14.4802C2.18174 14.6207 2.26038 14.7484 2.36499 14.853L9.146 21.634ZM19.181 6.83398C19.3013 6.79376 19.4094 6.72347 19.495 6.62976C19.5806 6.53605 19.6408 6.42209 19.6699 6.29858C19.6991 6.17508 19.6962 6.04615 19.6615 5.92407C19.6269 5.802 19.5616 5.69074 19.472 5.60095L18.401 4.53003C18.3112 4.44036 18.2 4.37509 18.0779 4.34045C17.9559 4.30582 17.827 4.30288 17.7035 4.33203C17.58 4.36118 17.4659 4.42139 17.3722 4.50696C17.2785 4.59252 17.2082 4.7007 17.168 4.82104L16.855 5.75903L18.243 7.14697L19.181 6.83398Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Personagem</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" data-bs-toggle="tab"
                                                    href="#tab_user_person_messages" role="tab">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Correio</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" data-bs-toggle="tab"
                                                    href="#tab_user_person_bag" role="tab">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Mochila</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M17.9061 13H11.2061C11.2061 12.4 10.8061 12 10.2061 12C9.60605 12 9.20605 12.4 9.20605 13H6.50606L9.20605 8.40002V4C8.60605 4 8.20605 3.6 8.20605 3C8.20605 2.4 8.60605 2 9.20605 2H15.2061C15.8061 2 16.2061 2.4 16.2061 3C16.2061 3.6 15.8061 4 15.2061 4V8.40002L17.9061 13ZM13.2061 9C12.6061 9 12.2061 9.4 12.2061 10C12.2061 10.6 12.6061 11 13.2061 11C13.8061 11 14.2061 10.6 14.2061 10C14.2061 9.4 13.8061 9 13.2061 9Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M18.9061 22H5.40605C3.60605 22 2.40606 20 3.30606 18.4L6.40605 13H9.10605C9.10605 13.6 9.50605 14 10.106 14C10.706 14 11.106 13.6 11.106 13H17.8061L20.9061 18.4C21.9061 20 20.8061 22 18.9061 22ZM14.2061 15C13.1061 15 12.2061 15.9 12.2061 17C12.2061 18.1 13.1061 19 14.2061 19C15.3061 19 16.2061 18.1 16.2061 17C16.2061 15.9 15.3061 15 14.2061 15Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Utilitários</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#md_person_ban">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Banimento</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" data-bs-toggle="tab"
                                                    href="#tab_user_person_change_nick" role="tab">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Trocar nick</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#md_enable_mission">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Completar missões</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#md_complete_laboratpry">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Completar laboratório</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if (!empty($characters))
                                <div class="px-6 pb-4">
                                    <button type="button" id="play_in_account"
                                        class="btn btn-light-primary btn-sm w-100 mb-3">
                                        <span class="indicator-label">🚀 Acessar conta</span>
                                    </button>
                                    <button type="button" id="disconnect_account"
                                        class="btn btn-light-danger btn-sm w-100">
                                        <span class="indicator-label">Desconectar jogador</span>
                                        <span class="indicator-progress">desconectando aguarde...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="tab-content">
                        <div class="tab-pane active" id="no_results">
                            <div class="card">
                                <div class="card-body">
                                    @include('components.default.notfound', [
                                        'title' => 'Sem informações',
                                        'message' => 'não tem nada por aqui',
                                    ])
                                </div>
                            </div>
                        </div>
                        {{-- game components --}}
                        @include('components.user.tabs.invoices')

                        @include('components.user.tabs.profile', [
                            'userSelected' => $userSelected,
                        ])

                        @include('components.user.tabs.password', [
                            'userSelected' => $userSelected,
                        ])

                        @include('components.user.tabs.email', [
                            'userSelected' => $userSelected,
                        ])

                        @include('components.user.tabs.change-nick', [
                            'userSelected' => $userSelected,
                        ])

                        {{-- game components --}}
                        @include('components.user.tabs.game-messages', [
                            'userSelected' => $userSelected,
                        ])
                        @include('components.user.tabs.game-bag', [
                            'userSelected' => $userSelected,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="md_person_ban" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">⛔ Banir jogador</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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

                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="alert d-flex align-items-center p-0 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="text-warning fs-6">Atenção</span>
                                    <span>
                                        O jogador será desconctado do jogo caso esteja online,
                                        e o ban será válido até a data selecionada.
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="fv-row mb-7 ">
                                    <label class="fs-6 fw-bold form-label mb-2">🍃 Motivo</label>
                                    <textarea class="form-control form-control form-control-solid" name="reason" rows="3"></textarea>
                                </div>
                                <div class="row">
                                    <div class="fv-row mb-7">
                                        <label for="" class="form-label">📅 Data de término</label>
                                        <input class="form-control form-control-solid" name="forbid"
                                            value="{{ date('d/m/Y 00:00:00') }}" />
                                        <span class="text-warning">Para remover o banimento, selecione a data atual ou
                                            anterior.</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" onclick="others.banPerson()" id="btn_submit_person_ban"
                                        class="btn btn-sm btn-light-primary w-100">
                                        <span class="indicator-label">Banir jogador</span>
                                        <span class="indicator-progress">
                                            banindo...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_enable_mission" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Concluir missões</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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

                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row mb-5">
                                <div class="fv-row">
                                    <label class="fs-6 fw-bold form-label mb-2">📙 Tipo de missão</label>
                                    <select class="form-select form-select-sm form-select-solid px-6" name="type"
                                        data-hide-search="true" data-control="select2" data-placeholder="Tipo de missão">
                                        <option value=""></option>
                                        <option value="all">Todos</option>
                                        @foreach ($questTypes as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="others.completeQuests()" id="btn_submit_quest_complete"
                                    class="btn btn-sm btn-light-primary w-100">
                                    <span class="indicator-label">Concluir missões</span>
                                    <span class="indicator-progress">
                                        concluindo...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_complete_laboratpry" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Concluir laboratório</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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

                <div class="modal-body scroll-y">
                    <form>
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="alert d-flex align-items-center p-0 mb-5">
                                <div class="d-flex flex-column">
                                    <span class="text-warning fs-6">Atenção</span>
                                    <span>
                                        O jogador será desconctado do jogo caso esteja online, e apenas fases incompletas
                                        do laboratório terão suas respectivas recompensas enviadas. Essa ação não pode ser
                                        desfeita.
                                    </span>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" onclick="others.completeLaboratory()"
                                    id="btn_submit_laboratory_complete" class="btn btn-sm btn-light-primary w-100">
                                    <span class="indicator-label">Concluir laboratório</span>
                                    <span class="indicator-progress">
                                        concluindo...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="md_game_mail_edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detalhes do correio</h4>
                    <div class="btn btn-sm btn-icon btn-active-color-danger" data-bs-dismiss="modal">
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

                <div class="modal-body scroll-y">
                    <div class="d-flex flex-wrap gap-2 justify-content-between mb-8">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <h2 class="fw-bold me-3 my-1" id="mail_title"></h2>

                            <div id="mail_states"></div>
                        </div>
                    </div>

                    <div data-kt-inbox-message="message_wrapper">
                        <div class="d-flex flex-wrap gap-2 flex-stack" data-kt-inbox-message="header">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-danger">
                                        <span class="text-danger">A</span>
                                    </div>
                                </div>

                                <div class="pe-5">
                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                        <a href="#" class="fw-bolder text-dark text-hover-primary"
                                            id="mail_sender"></a>
                                        <span class="svg-icon svg-icon-7 svg-icon-success mx-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <circle fill="currentColor" cx="12" cy="12"
                                                    r="8"></circle>
                                            </svg>
                                        </span>
                                        <span class="text-muted fw-bolder" id="mail_time_ago"></span>
                                    </div>
                                    <div data-kt-inbox-message="details">
                                        <span class="text-muted fw-bold">para mim</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mt-2">
                            <span class="fw-bold text-muted text-end me-3" id="mail_time_send"></span>
                        </div>
                        <div class="collapse fade show" data-kt-inbox-message="message">
                            <div class="py-5 pb-0" id="mail_content"></div>
                        </div>
                    </div>
                    <div id="mail_annex">
                        <div class="separator my-6"></div>
                        <span class="text-gray-800 fs-6">📦 Anexos</span>
                        <div class="mt-2">
                            <div class="row highlight m-1 p-1 pb-5 px-4 overflow-auto mh-200px" id="mail_annex_list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
