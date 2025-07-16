<div class="col-xl-4">
    <!--begin::Mixed Widget 5-->
    <div class="card card-xl-stretch mb-xl-3">
        <div style="
            {{-- /*background-image: url(https://c4.wallpaperflare.com/wallpaper/673/713/99/video-game-ddtank-wallpaper-preview.jpg);*/ --}}
            background-size: cover;
            width: 100%;
            height: 200px;
            position: absolute;
            background-position: center;
            border-radius: 4px;">
        </div>
        <!--begin::Beader-->
        <div class="card-header border-0 py-5" style="position: relative;">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{ $info['name'] }}</span>
                <span class="text-muted fw-bold fs-7">servidor disponivel</span>
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body d-flex flex-column" style="position: relative;">
            <!--begin::Items-->
            <a href="{{ url('app/ranking/' . $info['id']) }}" @class(['btn', 'btn-light-primary']) style="width:100%;">Acessar
                ranking</a>
            <!--end::Items-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Mixed Widget 5-->
</div>
