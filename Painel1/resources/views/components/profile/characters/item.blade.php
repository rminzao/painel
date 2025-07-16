
<div class="col-md-6 col-xxl-4">
    <div class="card">
        <div class="card-body d-flex flex-column p-9">
            <div class="d-flex align-items-center mb-5">
                <div class="symbol symbol-65px symbol-circle me-3">
                    <div class="game-avatar bg-lighten">
                        <div class="equip face" style="background-image: url({{ $info['equipment']['face'] }});"></div>
                        <div class="equip hair" style="background-image: url({{ $info['equipment']['hair'] }});"></div>
                    </div>
                    <div @class([
                        'bg-success' => $info['State'] != 0,
                        'bg-danger' => $info['State'] == 0,
                        'position-absolute translate-middle bottom-0 start-100 mb-0 rounded-circle border border-1 border-light h-10px w-10px',
                    ]) style="border-color: var(--bs-body-bg)!important;">
                    </div>
                </div>
                <div>
                    <span class="fs-6 text-gray-800 fw-bolder">
                        {{ $info['NickName'] }}
                    </span>
                    <div class="fs-7 text-muted fw-bold mt-1">
                        {{ $info['server']['name'] }}
                    </div>
                </div>
            </div>
            <div class="d-flex mb-5">
                <div class="highlight rounded w-50 d-flex py-3 px-4 me-2">
                    <div class="icon-power me-3"></div>
                    <div>
                        <div class="fs-6 fw-bolder text-warning">{{ $info['FightPower'] }}</div>
                        <div class="fs-7">Força</div>
                    </div>
                </div>
                <div class="highlight rounded w-50 d-flex py-3 px-4 ms-2">
                    <div class="icon-pvp me-3"></div>
                    <div>
                        <div class="fs-6 fw-bolder text-primary">{{ $info['Total'] - $info['Win'] }}</div>
                        <div class="fs-7">Vitórias</div>
                    </div>
                </div>
            </div>
            <div onclick="window.location.href='{{ $info['State'] != 0 ? 'javascript:;' : url('app/jogar/' . $info['server']['id']) }}'"
                @class([
                    'btn btn-sm w-100',
                    'btn-light-warning' => $info['State'] != 0,
                    'btn-light-primary' => $info['State'] == 0,
                ])>
                {{ $info['State'] != 0 ? 'Em jogo' : 'Entrar no jogo' }}
            </div>
        </div>
    </div>
</div>

