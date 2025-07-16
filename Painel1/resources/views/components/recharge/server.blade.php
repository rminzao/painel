<div class="col-xl-4">
    <div class="card card-xl-stretch mb-xl-3">
        <div class="card-header border-0 px-7 py-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{ $info['name'] }}</span>
                <span class="text-muted fw-bold fs-7">servidor <span class="text-success">disponivel</span></span>
            </h3>
        </div>
        <div class="card-body d-flex flex-column p-1">
            @isset($info['user'])
                <div class="mt-5 p-5 pt-0">
                    <div class="d-flex flex-stack mb-5">
                        <div class="d-flex align-items-center me-2">
                            <div class="symbol symbol-50px me-3">
                                <div class="game-avatar bg-lighten">
                                    <div class="equip face"
                                        style="background-image: url({{ $info['user']['equipment']['face'] }});"></div>
                                    <div class="equip hair"
                                        style="background-image: url({{ $info['user']['equipment']['hair'] }});"></div>
                                </div>
                                <div @class([
                                    'bg-success' => $info['user']['State'] != 0,
                                    'bg-danger' => $info['user']['State'] == 0,
                                    'position-absolute translate-middle bottom-0 start-100 mb-0 rounded-circle border border-1 border-light h-10px w-10px',
                                ]) style="border-color: var(--bs-body-bg)!important;">
                                </div>
                            </div>
                            <div>
                                <a
                                    class="fs-6 text-gray-800 text-hover-primary fw-bolder">{{ $info['user']['NickName'] }}</a>
                                <div class="fs-7 text-muted fw-bold mt-1 d-flex">
                                    <div class="icon-power me-2" style=" width: 11px; height: 14px; margin-top: 0; "></div>
                                    {{ $info['user']['FightPower'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ isset($info['user']) ? url('app/recarga/' . $info['id']) : url('app/jogar/' . $info['id']) }}"
                        @class(['btn btn-sm', 'btn-light-primary']) style="width:100%;">
                        {{ isset($info['user']) ? 'Acessar recarga' : 'Criar conta' }}
                    </a>
                </div>
            @endisset
            @empty($info['user'])
                <div class="mt-6">
                    @include('components.default.notfound', [
                        'title' => 'Conta não encontrada',
                        'message' => '<span class="text-warning">é necessário ter uma conta para acessar a recarga</span>',
                        'icon' => false,
                    ])
                </div>
            @endempty
        </div>
    </div>
</div>
