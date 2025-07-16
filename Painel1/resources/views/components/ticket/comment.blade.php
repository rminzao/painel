<div @class([
    'ms-9' => $info['admin'],
    'mb-9',
])" id="response">
    <div class="overflow-hidden position-relative card-rounded">

        @if ($info['admin'])
            <!--begin::Ribbon-->
            <div class="ribbon ribbon-triangle ribbon-top-start border-primary">
                <!--begin::Ribbon icon-->
                <div class="ribbon-icon mt-n5 ms-n6">
                    <i class="bi bi-check2 fs-2 text-white"></i>
                </div>
                <!--end::Ribbon icon-->
            </div>
            <!--end::Ribbon-->
        @endif
        <!--begin::Card-->
        <div class="card card-bordered w-100">
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Wrapper-->
                <div class="w-100 d-flex flex-stack mb-8">
                    <!--begin::Container-->
                    <div class="d-flex align-items-center f">
                        <!--begin::Author-->
                        <div class="symbol symbol-50px me-5">
                            <img src="{{ $info['avatar'] }}" alt="">
                        </div>
                        <!--end::Author-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column fw-bold fs-5 text-gray-600 text-dark">
                            <!--begin::Text-->
                            <div class="d-flex align-items-center">
                                <!--begin::Username-->
                                <a href="javascript:;"
                                    class="text-gray-800 fw-bolder text-hover-primary fs-5 me-3">{{ $info['receive_id'] == $info['uid'] ? 'Eu' : $info['name'] }}</a>
                                <!--end::Username-->
                                <span class="m-0"></span>
                            </div>
                            <!--end::Text-->
                            <!--begin::Date-->
                            <span class="text-muted fw-bold fs-6">{{ date_fmt_ago($info['created_at']) }}</span>
                            <!--end::Date-->
                        </div>
                        <!--end::Info-->

                    </div>
                    <!--end::Container-->
                    @if ($info['admin'])
                        <button type="button" id="delete" url="{{ url("api/ticket/response/{$info['id']}") }}"
                            class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-danger"
                            onclick="quests.confirmDelete(8)">
                            <span class="svg-icon svg-icon-3" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.5"
                                        d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.5"
                                        d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                    @endif
                </div>

                <!--end::Wrapper-->
                <!--begin::Desc-->
                <p class="fw-normal fs-5 text-gray-700 m-0">{!! $info['content'] !!}</p>
                <!--end::Desc-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card-->
    </div>
</div>
