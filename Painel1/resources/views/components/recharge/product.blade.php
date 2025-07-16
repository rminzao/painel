<div class="col-sm-3">
    <div class="d-flex h-100 align-items-center bg-body rounded" style="position:relative;">
        <div class="w-100 d-flex flex-column flex-center rounded-2 py-10 px-5 pb-7">
            @if ($info['type'] == 3)
                <div
                    style="background-image: url(https://imgur.com/5qRov7Z.gif); width: 100px; height: 100px; z-index: 1; position: absolute; top: -9%; right: -9%; opacity: 0.9;">
                </div>
            @endif
            <div class="mb-7 text-center w-100">
                <h1 class="text-gray-900 mb-5 text-uppercase">
                    @if ($info['type'] != 1)
                        {{ $info['name'] }}
                    @else
                        <span class="text-primary">{{ $info['ammount'] }}</span> Cupons
                    @endif
                </h1>
                <div class="highlight text-center fs-2 d-flex flex-column">
                    <div>
                        <span class="mb-2 fw-bolder text-warning">$</span>
                        <span class="fw-bolder">{{ str_price($info['value']) }}</span>
                    </div>
                    <span class="fw-bolder fs-7 text-success">
                        reais
                    </span>
                </div>
            </div>
            <a onclick="recharge.detail({{ $info['id'] }})"
                class="btn btn-sm btn-light-{{ $info['type'] == 3 ? 'warning' : 'primary' }} w-100">
                Comprar
            </a>
        </div>
    </div>
</div>
