<!--begin::Ticket-->
<div class="d-flex mb-10">
    <!--begin::Symbol-->
    <!--begin::Svg Icon | path: icons/duotune/files/fil008.svg-->

    <!--end::Svg Icon-->
    <!--end::Symbol-->
    <!--begin::Section-->
    <div class="d-flex flex-column">
        <!--begin::Content-->
        <div class="d-flex align-items-center mb-2">
            <!--begin::Title-->
            <a href="{{ url('app/me/ticket/detail/' . $info['id']) }}"
                class="text-dark text-hover-primary fs-4 me-3 fw-bold">{{ $info['title'] }}</a>
            <!--end::Title-->
            <!--begin::Label-->
            <span
                @class([
                    'badge',
                    'badge-success' => $info['status'] == 'open',
                    'badge-danger' => $info['status'] == 'closed',
                    'badge-primary' => $info['status'] == 'resolved',
                    'my-1',
                ])>{{ match ($info['status']) { 'open' => 'aberto',  'closed' => 'fechado',  'resolved' => 'resolvido' } }}</span>
            <span class="fw-bold text-muted ms-3">{{ date_fmt_ago($info['created_at']) }}</span>
            <!--end::Label-->
        </div>
        <!--end::Content-->
        <!--begin::Text-->
        <span class="text-muted fw-bold fs-6">
            {{ str_limit_chars($info['content'], 109) }}
        </span>
        <!--end::Text-->
    </div>
    <!--end::Section-->
</div>
<!--end::Ticket-->
