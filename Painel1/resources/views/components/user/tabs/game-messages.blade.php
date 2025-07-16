<div class="tab-pane" id="tab_user_person_messages">
    <div class="card">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Title-->
            <div class="card-title">
                <span style="color: #a1a5b7;font-size:13.975px;">Correio do jogador</span>
            </div>
            <!--end::Title-->
            <!--begin::Pagination-->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!--begin::Reload-->
                <a href="#" onclick="message.list(0, true)" class="btn btn-icon btn-color-gray-400 btn-sm btn-active-color-primary" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="" data-bs-original-title="Atualizar">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr029.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </a>
                <!--end::Reload-->
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-2 position-absolute ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" name="search" data-kt-inbox-listing-filter="search"
                        class="form-control form-control-sm form-control-solid mw-100 min-w-150px min-w-md-200px ps-12"
                        placeholder="Título, mens. ou nick">
                </div>
                <!--end::Search-->
                <!--begin::Filter-->
                <div>
                    <!--begin::Menu-->
                    <select class="form-select form-select-sm form-select-solid w-125px" name="email_filter"
                            data-hide-search="true" data-control="select2">
                        <option value="show_all">Todos</option>
                        <option value="show_read">Lidos</option>
                        <option value="show_unread">Não lidos</option>
                        <option value="show_deleted">Deletados</option>
                        <option value="show_with_attachment">Com anexos</option>
                        <option value="show_no_attachment">Sem anexos</option>
                        <option value="show_system_sent">Enviados pelo sistema</option>
                    </select>
                    <!--end::Menu-->
                </div>
                <!--end::Filter-->
            </div>
            <!--end::Pagination-->
        </div>
        <div class="card-body p-0">
            <div id="no_results">
                @include('components.default.notfound', [
                    'title' => 'Sem menssagems',
                    'message' => 'não tem nada por aqui',
                ])
            </div>
            <div class="table-responsive">
                <table class="table fs-6 gy-3 table-row-dashed my-0 no-footer">
                    <tbody id="messages-list"></tbody>
                </table>
            </div>
            <div class="row px-9 pt-3 pb-5">
                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="dataTables_length" id="email_limit_area" style="display:none;">
                        <select class="form-select form-select-sm form-select-solid" name="email_limit"
                            data-hide-search="true" data-control="select2">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dataTables_paginate paging_simple_numbers" id="email_paginator"></div>
                </div>
            </div>
        </div>
    </div>
</div>
