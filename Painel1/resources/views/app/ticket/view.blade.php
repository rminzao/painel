@extends('layouts.app')

@section('title', 'Ticket')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column me-3">
                <!--begin::Title-->
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📮 Ticket</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">Suporte</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75">Detalhes do Ticket</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-3 py-md-1">
                @if ($user->role >= 2)
                    <select class="form-select form-select-solid" data-control="select2" data-placeholder="Selecione o Tipo"
                        data-hide-search="true" name="state" id="ticket-state">
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>🔴 fechado
                        </option>
                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>🔵 resolvido
                        </option>
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>🟢
                            aberto</option>
                    </select>
                @endif
                <!--begin::Button-->
                <a href="{{ url('app/me/ticket/list') }}"
                    class="btn btn-custom btn-active-white btn-flex btn-color-white btn-active-color-primary fw-bolder"
                    style="margin-left: 13px;">Voltar</a>
                <!--end::Button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Layout-->
                        <div class="d-flex flex-column flex-xl-row p-7">
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid me-xl-15 mb-20 mb-xl-0">
                                <!--begin::Ticket view-->
                                <div class="mb-0">
                                    <!--begin::Heading-->
                                    <div class="d-flex align-items-center mb-12">
                                        <!--begin::Icon-->
                                        <!--begin::Svg Icon | path: icons/duotune/files/fil008.svg-->
                                        @if ($ticket->status == 'open')
                                            <span class="svg-icon svg-icon-4qx svg-icon-success ms-n2 me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z"
                                                        fill="currentColor" />
                                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="svg-icon svg-icon-4qx svg-icon-danger ms-n2 me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z"
                                                        fill="currentColor" />
                                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        @endif

                                        <!--end::Svg Icon-->
                                        <!--end::Icon-->
                                        <!--begin::Content-->
                                        <div class="d-flex flex-column">
                                            <!--begin::Title-->
                                            <h1 class="text-gray-800 fw-bold">{{ $ticket->title }}</h1>
                                            <!--end::Title-->
                                            <!--begin::Info-->
                                            <div class="">
                                                <!--begin::Label-->
                                                <span class="fw-bold text-muted me-3">Por:
                                                    <a href="#"
                                                        class="text-muted text-hover-primary">{{ $owner->first_name }}</a></span>
                                                <!--end::Label-->
                                                <!--begin::Label-->
                                                <span class="fw-bold text-muted">Criado:
                                                    <span class="fw-bolder text-gray-600 me-1">
                                                        {{ date_fmt_ago($ticket->created_at) }}
                                                    </span>
                                                    ({{ date('d.m.Y h:i A', strtotime($ticket->created_at)) }})
                                                </span>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Info-->

                                        </div>

                                        <!--end::Content-->
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Details-->
                                    <div class="mb-15">
                                        <!--begin::Description-->
                                        <div class="mb-5 fs-5 fw-normal text-gray-800">
                                            {!! $ticket->content !!}
                                        </div>
                                        <!--end::Description-->
                                        <!--begin::Images-->
                                        @if (isset($attachments) and sizeof($attachments) > 0)
                                            <div class="mb-15">
                                                <span class="fw-bold text-gray-600">Anexos:</span>
                                                <div class="row">
                                                    @foreach ($attachments as $url)
                                                        <!--begin::Col-->
                                                        <div class="col-lg-3">
                                                            <!--begin::Overlay-->
                                                            <a class="d-block overlay" data-fslightbox="lightbox-basic"
                                                                href="{{ $url }}">
                                                                <!--begin::Image-->
                                                                <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                                                    style="background-image:url('{{ $url }}')">
                                                                </div>
                                                                <!--end::Image-->
                                                                <!--begin::Action-->
                                                                <div
                                                                    class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                                                    <i class="bi bi-eye-fill text-white fs-3x"></i>
                                                                </div>
                                                                <!--end::Action-->
                                                            </a>
                                                            <!--end::Overlay-->
                                                        </div>
                                                        <!--end::Col-->
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <!--end::Images-->

                                        @if ($ticket->status == 'open')
                                            <form action="{{ url("api/ticket/response/{$ticket->id}") }}" method="POST">
                                                <!--begin::Input group-->
                                                <div class="mb-3">
                                                    <textarea class="form-control form-control-solid placeholder-gray-600 fw-bolder fs-4 ps-9 pt-7" rows="6" name="content"
                                                        placeholder="Resposta"></textarea>
                                                    <!--begin::Submit-->
                                                    <button type="submit" id="button_send_response"
                                                        class="btn btn-primary mt-n20 mb-20 position-relative float-end me-7">Enviar</button>
                                                    <!--end::Submit-->
                                                </div>
                                                <!--end::Input group-->
                                            </form>
                                        @endif
                                        @if (sizeof($comments) > 0)
                                            <div class="border-gray-300 border-bottom"></div>
                                        @endif

                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Comments-->
                                    @if (sizeof($comments) > 0)
                                        <div class="mb-15">
                                            @each('components.ticket.comment',$comments, 'info', 'components.ticket.empty')
                                        </div>
                                    @endif
                                    <!--end::Comments-->
                                </div>
                                <!--end::Ticket view-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@section('custom-js')
    <script>
        var ticket_id = '{{ $ticket->id }}'
    </script>
    <script src="{{ url() }}/assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
    <script src="{{ url() }}/assets/js/app/ticket/sendmessage.js"></script>
@endsection
