<div class="col-xl-4">
    <div class="card card-xl-stretch mb-xl-3">
        <div class="card-header border-0 py-5" style="position: relative;">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{ $info['name'] }}</span>
                <span class="text-muted fw-bold fs-7">{!! 
                    $info['active'] == 0 ? match($info['status']) {
                        'maintenance' => '🚧 Servidor <span class="text-warning">em manutenção</span>',
                        'comming_soon' => '🚀 Servidor será lançado <span class="text-primary">em breve</span>',
                        'not_visible' => '👁‍🗨 Servidor <span class="text-danger">não visível</span>',
                        'offline' => '🔴 Servidor <span class="text-danger">desligado</span>',
                    } : '🟢 Servidor <span class="text-primary">disponível</span>'
                !!}</span>
            </h3>
        </div>
        <div class="card-body d-flex flex-column" style="position: relative;">
            <a href="{{ ($info['active'] || $info['role'] != 1) ? url('app/jogar/' . $info['id']) : 'javascript:;' }}" @class([
                'btn', 
                'btn-light-primary' => $info['active'] == 1 || $info['role'] != 1,
                'btn-light-danger' => $info['active'] == 0 && $info['role'] == 1,
                'w-100'
                ])>
                {{ $info['active'] == 0 && $info['role'] == 1 ? 'indisponível' : 'Jogar' }}
            </a>
        </div>
    </div>
</div>