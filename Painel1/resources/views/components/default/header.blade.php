<div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header"
    data-kt-sticky-offset="{default: '200px', lg: '300px'}">
    <div class="container-xxl d-flex align-items-center">
        <div class="d-flex topbar align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary btn-custom w-30px h-30px w-md-40px h-md-40px"
                id="kt_header_menu_mobile_toggle">
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                            fill="currentColor" />
                        <path opacity="0.3"
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
            <a href="{{ url() }}">
                <img alt="Logo" src="{{ url() }}/assets/media/logos/logo.png" class="logo-default h-25px"
                    style="width: 90px; height: 75px !important;" />
                <img alt="Logo" src="{{ url() }}/assets/media/logos/logo.png" class="logo-sticky h-25px"
                    style="width: 90px; height: 75px !important;" />
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                    data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
                        id="#kt_header_menu" data-kt-menu="true">
                        <a class="menu-item me-lg-1" href="{{ url() }}">
                            <span class="menu-link py-3">
                                <span class="menu-title">Início</span>
                            </span>
                        </a>
                        @if (in_array($user->role, [2, 3]))
                            <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                                class="menu-item menu-lg-down-accordion me-lg-1">
                                <span class="menu-link py-3">
                                    <span class="menu-title">👑 Administração</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div
                                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M18.041 22.041C18.5932 22.041 19.041 21.5932 19.041 21.041C19.041 20.4887 18.5932 20.041 18.041 20.041C17.4887 20.041 17.041 20.4887 17.041 21.041C17.041 21.5932 17.4887 22.041 18.041 22.041Z"
                                                            fill="currentColor"></path>
                                                        <path opacity="0.3"
                                                            d="M6.04095 22.041C6.59324 22.041 7.04095 21.5932 7.04095 21.041C7.04095 20.4887 6.59324 20.041 6.04095 20.041C5.48867 20.041 5.04095 20.4887 5.04095 21.041C5.04095 21.5932 5.48867 22.041 6.04095 22.041Z"
                                                            fill="currentColor"></path>
                                                        <path opacity="0.3"
                                                            d="M7.04095 16.041L19.1409 15.1409C19.7409 15.1409 20.141 14.7409 20.341 14.1409L21.7409 8.34094C21.9409 7.64094 21.4409 7.04095 20.7409 7.04095H5.44095L7.04095 16.041Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M19.041 20.041H5.04096C4.74096 20.041 4.34095 19.841 4.14095 19.541C3.94095 19.241 3.94095 18.841 4.14095 18.541L6.04096 14.841L4.14095 4.64095L2.54096 3.84096C2.04096 3.64096 1.84095 3.04097 2.14095 2.54097C2.34095 2.04097 2.94096 1.84095 3.44096 2.14095L5.44096 3.14095C5.74096 3.24095 5.94096 3.54096 5.94096 3.84096L7.94096 14.841C7.94096 15.041 7.94095 15.241 7.84095 15.441L6.54096 18.041H19.041C19.641 18.041 20.041 18.441 20.041 19.041C20.041 19.641 19.641 20.041 19.041 20.041Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">eCommerce</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/ecommerce/dashboard') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Visão geral</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/ecommerce/product') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Produtos</span>
                                                </a>
                                            </div>
                                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                data-kt-menu-placement="right-start"
                                                class="menu-item menu-lg-down-accordion">
                                                <span class="menu-link py-3">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Faturas</span>
                                                    <span class="menu-arrow"></span>
                                                </span>
                                                <div
                                                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/ecommerce/invoice/list') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Lista</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/ecommerce/invoice/create') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Criar</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                data-kt-menu-placement="right-start"
                                                class="menu-item menu-lg-down-accordion">
                                                <div
                                                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                                </div>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3"
                                                    href="{{ url('admin/ecommerce/product/code') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Códigos promocionais</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M6 22H4V3C4 2.4 4.4 2 5 2C5.6 2 6 2.4 6 3V22Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M18 14H4V4H18C18.8 4 19.2 4.9 18.7 5.5L16 9L18.8 12.5C19.3 13.1 18.8 14 18 14Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Game Utils</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/drop') }}">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M5 8.04999L11.8 11.95V19.85L5 15.85V8.04999Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M20.1 6.65L12.3 2.15C12 1.95 11.6 1.95 11.3 2.15L3.5 6.65C3.2 6.85 3 7.15 3 7.45V16.45C3 16.75 3.2 17.15 3.5 17.25L11.3 21.75C11.5 21.85 11.6 21.85 11.8 21.85C12 21.85 12.1 21.85 12.3 21.75L20.1 17.25C20.4 17.05 20.6 16.75 20.6 16.45V7.45C20.6 7.15 20.4 6.75 20.1 6.65ZM5 15.85V7.95L11.8 4.05L18.6 7.95L11.8 11.95V19.85L5 15.85Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Drop</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/warpass') }}">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M2 8.4L12 4L22 8.4V15.6L12 20L2 15.6V8.4Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M11 2.7V1C11 0.4 11.4 0 12 0C12.6 0 13 0.4 13 1V2.7L22.7 7.1C23.1 7.3 23.3 7.7 23.3 8.1V15.9C23.3 16.3 23.1 16.7 22.7 16.9L13 21.3V23C13 23.6 12.6 24 12 24C11.4 24 11 23.6 11 23V21.3L1.3 16.9C0.9 16.7 0.7 16.3 0.7 15.9V8.1C0.7 7.7 0.9 7.3 1.3 7.1L11 2.7ZM10.5 8.8L5.7 11.2L12 14.7L18.3 11.2L13.5 8.8V4.3L10.5 2.8V8.8Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Warpass</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/quest') }}">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M6 21C6 21.6 6.4 22 7 22H17C17.6 22 18 21.6 18 21V20H6V21Z"
                                                                    fill="currentColor">
                                                                </path>
                                                                <path opacity="0.3"
                                                                    d="M17 2H7C6.4 2 6 2.4 6 3V20H18V3C18 2.4 17.6 2 17 2Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M12 4C11.4 4 11 3.6 11 3V2H13V3C13 3.6 12.6 4 12 4Z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Missões</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/item') }}">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M7 20.5L2 17.6V11.8L7 8.90002L12 11.8V17.6L7 20.5ZM21 20.8V18.5L19 17.3L17 18.5V20.8L19 22L21 20.8Z"
                                                                    fill="currentColor" />
                                                                <path d="M22 14.1V6L15 2L8 6V14.1L15 18.2L22 14.1Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Itens</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/shop') }}">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3"
                                                                    d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3"
                                                                    d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3"
                                                                    d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3" d="M14 4H10V10H14V4Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Shop</span>
                                                </a>
                                            </div>
                                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                data-kt-menu-placement="right-start"
                                                class="menu-item menu-lg-down-accordion">
                                                <span class="menu-link py-3">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z"
                                                                    fill="currentColor" />
                                                                <path opacity="0.3"
                                                                    d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Eventos</span>
                                                    <span class="menu-arrow"></span>
                                                </span>
                                                <div
                                                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/event/activities') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Atividades</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/event/activity-system') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Atividades do sistema</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/event/activity') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Evento belo</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/event/gm-activity') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Gm Activity</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/event/missions') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">
                                                                Atividades e Premiações
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                data-kt-menu-placement="right-start"
                                                class="menu-item menu-lg-down-accordion">
                                                <span class="menu-link py-3">
                                                    <span class="menu-icon">
                                                        <span class="svg-icon svg-icon-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M20.0381 4V10C20.0381 10.6 19.6381 11 19.0381 11H17.0381C16.4381 11 16.0381 10.6 16.0381 10V4C16.0381 2.9 16.9381 2 18.0381 2C19.1381 2 20.0381 2.9 20.0381 4ZM9.73808 18.9C10.7381 18.5 11.2381 17.3 10.8381 16.3L5.83808 3.29999C5.43808 2.29999 4.23808 1.80001 3.23808 2.20001C2.23808 2.60001 1.73809 3.79999 2.13809 4.79999L7.13809 17.8C7.43809 18.6 8.23808 19.1 9.03808 19.1C9.23808 19 9.53808 19 9.73808 18.9ZM19.0381 18H17.0381V20H19.0381V18Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M18.0381 6H4.03809C2.93809 6 2.03809 5.1 2.03809 4C2.03809 2.9 2.93809 2 4.03809 2H18.0381C19.1381 2 20.0381 2.9 20.0381 4C20.0381 5.1 19.1381 6 18.0381 6ZM4.03809 3C3.43809 3 3.03809 3.4 3.03809 4C3.03809 4.6 3.43809 5 4.03809 5C4.63809 5 5.03809 4.6 5.03809 4C5.03809 3.4 4.63809 3 4.03809 3ZM18.0381 3C17.4381 3 17.0381 3.4 17.0381 4C17.0381 4.6 17.4381 5 18.0381 5C18.6381 5 19.0381 4.6 19.0381 4C19.0381 3.4 18.6381 3 18.0381 3ZM12.0381 17V22H6.03809V17C6.03809 15.3 7.33809 14 9.03809 14C10.7381 14 12.0381 15.3 12.0381 17ZM9.03809 15.5C8.23809 15.5 7.53809 16.2 7.53809 17C7.53809 17.8 8.23809 18.5 9.03809 18.5C9.83809 18.5 10.5381 17.8 10.5381 17C10.5381 16.2 9.83809 15.5 9.03809 15.5ZM15.0381 15H17.0381V13H16.0381V8L14.0381 10V14C14.0381 14.6 14.4381 15 15.0381 15ZM19.0381 15H21.0381C21.6381 15 22.0381 14.6 22.0381 14V10L20.0381 8V13H19.0381V15ZM21.0381 20H15.0381V22H21.0381V20Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                    <span class="menu-title">Outros</span>
                                                    <span class="menu-arrow"></span>
                                                </span>
                                                <div
                                                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/announcements') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Anuncios</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/suit') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">NPC</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3" href="{{ url('admin/game/pve') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Pve</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3" href="{{ url('admin/game/map') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Mapa</span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link py-3"
                                                            href="{{ url('admin/game/config') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">Server config</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M14.1 15.013C14.6 16.313 14.5 17.813 13.7 19.113C12.3 21.513 9.29999 22.313 6.89999 20.913C5.29999 20.013 4.39999 18.313 4.39999 16.613C5.09999 17.013 5.99999 17.313 6.89999 17.313C8.39999 17.313 9.69998 16.613 10.7 15.613C11.1 15.713 11.5 15.813 11.9 15.813C12.7 15.813 13.5 15.513 14.1 15.013ZM8.5 12.913C8.5 12.713 8.39999 12.513 8.39999 12.313C8.39999 11.213 8.89998 10.213 9.69998 9.613C9.19998 8.313 9.30001 6.813 10.1 5.513C10.6 4.713 11.2 4.11299 11.9 3.71299C10.4 2.81299 8.49999 2.71299 6.89999 3.71299C4.49999 5.11299 3.70001 8.113 5.10001 10.513C5.80001 11.813 7.1 12.613 8.5 12.913ZM16.9 7.313C15.4 7.313 14.1 8.013 13.1 9.013C14.3 9.413 15.1 10.513 15.3 11.713C16.7 12.013 17.9 12.813 18.7 14.113C19.2 14.913 19.3 15.713 19.3 16.613C20.8 15.713 21.8 14.113 21.8 12.313C21.9 9.513 19.7 7.313 16.9 7.313Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M9.69998 9.61307C9.19998 8.31307 9.30001 6.81306 10.1 5.51306C11.5 3.11306 14.5 2.31306 16.9 3.71306C18.5 4.61306 19.4 6.31306 19.4 8.01306C18.7 7.61306 17.8 7.31306 16.9 7.31306C15.4 7.31306 14.1 8.01306 13.1 9.01306C12.7 8.91306 12.3 8.81306 11.9 8.81306C11.1 8.81306 10.3 9.11307 9.69998 9.61307ZM8.5 12.9131C7.1 12.6131 5.90001 11.8131 5.10001 10.5131C4.60001 9.71306 4.5 8.91306 4.5 8.01306C3 8.91306 2 10.5131 2 12.3131C2 15.1131 4.2 17.3131 7 17.3131C8.5 17.3131 9.79999 16.6131 10.8 15.6131C9.49999 15.1131 8.7 14.1131 8.5 12.9131ZM18.7 14.1131C17.9 12.8131 16.7 12.0131 15.3 11.7131C15.3 11.9131 15.4 12.1131 15.4 12.3131C15.4 13.4131 14.9 14.4131 14.1 15.0131C14.6 16.3131 14.5 17.8131 13.7 19.1131C13.2 19.9131 12.6 20.5131 11.9 20.9131C13.4 21.8131 15.3 21.9131 16.9 20.9131C19.3 19.6131 20.1 16.5131 18.7 14.1131Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Fugura</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('/admin/gameutils/fugura') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Editar</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M4.05424 15.1982C8.34524 7.76818 13.5782 3.26318 20.9282 2.01418C21.0729 1.98837 21.2216 1.99789 21.3618 2.04193C21.502 2.08597 21.6294 2.16323 21.7333 2.26712C21.8372 2.37101 21.9144 2.49846 21.9585 2.63863C22.0025 2.7788 22.012 2.92754 21.9862 3.07218C20.7372 10.4222 16.2322 15.6552 8.80224 19.9462L4.05424 15.1982ZM3.81924 17.3372L2.63324 20.4482C2.58427 20.5765 2.5735 20.7163 2.6022 20.8507C2.63091 20.9851 2.69788 21.1082 2.79503 21.2054C2.89218 21.3025 3.01536 21.3695 3.14972 21.3982C3.28408 21.4269 3.42387 21.4161 3.55224 21.3672L6.66524 20.1802L3.81924 17.3372ZM16.5002 5.99818C16.2036 5.99818 15.9136 6.08615 15.6669 6.25097C15.4202 6.41579 15.228 6.65006 15.1144 6.92415C15.0009 7.19824 14.9712 7.49984 15.0291 7.79081C15.0869 8.08178 15.2298 8.34906 15.4396 8.55884C15.6494 8.76862 15.9166 8.91148 16.2076 8.96935C16.4986 9.02723 16.8002 8.99753 17.0743 8.884C17.3484 8.77046 17.5826 8.5782 17.7474 8.33153C17.9123 8.08486 18.0002 7.79485 18.0002 7.49818C18.0002 7.10035 17.8422 6.71882 17.5609 6.43752C17.2796 6.15621 16.8981 5.99818 16.5002 5.99818Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M4.05423 15.1982L2.24723 13.3912C2.15505 13.299 2.08547 13.1867 2.04395 13.0632C2.00243 12.9396 1.9901 12.8081 2.00793 12.679C2.02575 12.5498 2.07325 12.4266 2.14669 12.3189C2.22013 12.2112 2.31752 12.1219 2.43123 12.0582L9.15323 8.28918C7.17353 10.3717 5.4607 12.6926 4.05423 15.1982ZM8.80023 19.9442L10.6072 21.7512C10.6994 21.8434 10.8117 21.9129 10.9352 21.9545C11.0588 21.996 11.1903 22.0083 11.3195 21.9905C11.4486 21.9727 11.5718 21.9252 11.6795 21.8517C11.7872 21.7783 11.8765 21.6809 11.9402 21.5672L15.7092 14.8442C13.6269 16.8245 11.3061 18.5377 8.80023 19.9442ZM7.04023 18.1832L12.5832 12.6402C12.7381 12.4759 12.8228 12.2577 12.8195 12.032C12.8161 11.8063 12.725 11.5907 12.5653 11.4311C12.4057 11.2714 12.1901 11.1803 11.9644 11.1769C11.7387 11.1736 11.5205 11.2583 11.3562 11.4132L5.81323 16.9562L7.04023 18.1832Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Servidores</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/server/list') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Lista</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                                            fill="currentColor"></path>
                                                        <rect opacity="0.3" x="8" y="3"
                                                            width="8" height="8" rx="4"
                                                            fill="currentColor"></rect>
                                                    </svg>
                                                </span>
                                            </span>
                                            <span class="menu-title">Usuários</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/users/equip') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Equipe</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/game/users') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Lista de jogadores</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3" href="{{ url('admin/users') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Lista de usuários</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                                        <span class="menu-link py-3">
                                            <span class="menu-icon">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
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
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">
                                            <div class="menu-item">
                                                <a class="menu-link py-3"
                                                    href="{{ url('admin/game/utils/message/send') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Enviar menssagem</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3"
                                                    href="{{ url('admin/game/utils/item/send') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Enviar itens</span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link py-3"
                                                    href="{{ url('admin/game/utils/recharge/send') }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">Enviar recarga</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <a class="menu-item me-lg-1 d-none" href="{{ url('app/me/ticket/list') }}">
                            <span class="menu-link py-3">
                                <span class="menu-title">Suporte</span>
                            </span>
                        </a>

                        {{-- BOTÕES DE SERVIDOR (ADMIN ONLY) --}}
                        @if (in_array($user->role, [2, 3]))
                            <div class="menu-item me-lg-1 align-self-center">
								<button onclick="reloadXml()" class="btn btn-success btn-sm me-2" id="btn-reload-xml">
									<i class="fas fa-file-code me-1"></i>Reload XML
								</button>
							</div>
							<div class="menu-item me-lg-1 align-self-center">
								<button onclick="getServerStatus()" class="btn btn-info btn-sm me-2" id="btn-status">
									<i class="fas fa-heartbeat me-1"></i>Status
								</button>
							</div>
                            <div class="menu-item me-lg-1 align-self-center">
                                <button onclick="stopAllServers()" class="btn btn-danger btn-sm me-2" id="btn-stop-all">
                                    <i class="fas fa-stop me-1"></i>Stop All
                                </button>
                            </div>
                            <div class="menu-item me-lg-1 align-self-center">
                                <button onclick="startAllServers()" class="btn btn-primary btn-sm" id="btn-start-all">
                                    <i class="fas fa-play me-1"></i>Start All
                                </button>
                            </div>
                        @else
                            {{-- BOTÕES PARA USUÁRIOS NORMAIS --}}
                            <a class="menu-item me-lg-1 align-self-center" href="{{ url('links') }}" target="_blank">
                                <button class="btn btn-light-success btn-sm">
                                    Redes sociais
                                </button>
                            </a>
                            <a class="menu-item me-lg-1 align-self-center" href="{{ env('APP_CLIENT') }}"
                                target="_blank">
                                <button class="btn btn-light-primary btn-sm">
                                    Baixar client
                                </button>
                            </a>
                            <a class="menu-item me-lg-1 align-self-center"
                                href="https://static.centbrowser.com/win_stable/4.3.9.248/centbrowser_4.3.9.248.exe"
                                target="_blank">
                                <button class="btn btn-light-primary btn-sm">
                                    Baixar centbrowser
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="topbar d-flex align-items-stretch flex-shrink-0">
                <div class="app-navbar-item ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <div class="d-flex cursor-pointer" data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <div class="d-flex flex-column justify-content-center align-items-end m-0 p-0 me-3">
                            <span class="badge badge-light-primary align-self-start fs-6 py-1 ps-2 h-100 text-white"
                                style="
                                line-height: 35px;
                                background-color: #00000054;
                            ">
                                <img class="w-30px me-1" src="{{ url('assets/media/others/coin.png') }}"
                                    alt="">
                                    {{ $user->money }} mE
                            </span>
                        </div>
                        <img class="h-40px w-40px rounded" src="{{ image_avatar($user->photo, 40, 40) }}"
                            id="header_profile_image1" alt="" />
                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="{{ image_avatar($user->photo, 50, 50) }}"
                                        id="header_profile_image2" />
                                </div>

                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">
                                        {{ $user->first_name }}
                                        <span
                                            class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">online</span>
                                    </div>
                                    <span
                                        class="fw-bold text-muted fs-7">{{ str_limit_chars(str_obfuscate_email($user->email), 23) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            <a href="{{ url('app/me/account/overview') }}" class="menu-link px-5">Meu perfil</a>
                        </div>

                        <div class="menu-item px-5">
                            <a href="{{ url('app/me/account/characters') }}" class="menu-link px-5">
                                <span class="menu-text">Minhas contas</span>
                            </a>
                        </div>

                        <div class="menu-item px-5">
                            <a href="{{ url('app/me/ticket/list') }}" class="menu-link px-5">
                                <span class="menu-text">Meus Chamados</span>
                            </a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ url('app/me/account/settings') }}" class="menu-link px-5">
                                Configurações
                            </a>
                        </div>
                        <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
                            <a href="#" class="menu-link px-5">
                                <span class="menu-title position-relative">
                                    Idioma
                                    <span
                                        class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                        Português <img class="w-15px h-15px rounded-1 ms-2"
                                            src="{{ url() }}/assets/media/flags/brazil.svg" alt="" />
                                    </span>
                                </span>
                            </a>
                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link d-flex px-5 active">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1"
                                                src="{{ url() }}/assets/media/flags/brazil.svg"
                                                alt="" />
                                        </span>
                                        Português
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="menu-item px-5">
                            <a href="{{ url('sair') }}" class="menu-link px-5">Sair</a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <div class="menu-content px-5">
                                <label
                                    class="form-check form-switch form-check-custom form-check-solid pulse pulse-success"
                                    for="kt_user_menu_dark_mode_toggle">
                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1"
                                        id="change-theme" checked />
                                    <span class="pulse-ring ms-n1"></span>
                                    <span class="form-check-label text-gray-600 fs-7">Dark Mode</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMAÇÃO PARA ADMINS --}}
@if (in_array($user->role, [2, 3]))
<div class="modal fade" id="serverActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="modal-title">Confirmar Ação</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i id="modal-icon" class="fas fa-server fa-3x text-warning mb-3"></i>
                    <h6 id="modal-action-title">Ação no Servidor</h6>
                    <p class="text-muted" id="modal-description">
                        Esta ação será executada no servidor.
                    </p>
                </div>
                <div class="alert alert-warning">
                    <strong>⚠️ Atenção:</strong> Todos os jogadores online podem ser afetados!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="btn-confirm-action">
                    <i class="fas fa-check me-2"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
const SERVER_CONFIG = {
    API_BASE: '/api/admin/emulators'
};

let currentAction = '';
let currentEndpoint = '';
let progressInterval;
let progressStartTime = Date.now();
let isStartingSequence = false;

function sanitizeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function processResponse(response, operation = 'operação') {
    const responseText = await response.text();
    
    if (!responseText.trim()) {
        return { success: false, message: `Resposta vazia do servidor para ${operation}` };
    }
    
    try {
        return JSON.parse(responseText);
    } catch (jsonError) {
        if (responseText.includes('<title>') && responseText.includes('</title>')) {
            const titleMatch = responseText.match(/<title>(.*?)<\/title>/);
            const errorTitle = titleMatch ? sanitizeHtml(titleMatch[1]) : 'Erro no servidor';
            return { success: false, message: errorTitle };
        }
        
        return { success: false, message: `Resposta inválida do servidor para ${operation}` };
    }
}

async function reloadXml() {
    const btn = document.getElementById('btn-reload-xml');
    const originalText = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Atualizando...';
        
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/reload-xml`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await processResponse(response, 'Reload XML');
        
        if (data.success) {
            showToast('success', 'XML Atualizado!', sanitizeHtml(data.message));
        } else {
            showToast('error', 'Erro!', sanitizeHtml(data.message || 'Erro ao atualizar XML'));
        }
        
    } catch (error) {
        showToast('error', 'Erro de Conexão', `Erro: ${sanitizeHtml(error.message)}`);
    } finally {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 2000);
    }
}

async function getServerStatus() {
    const btn = document.getElementById('btn-status');
    const originalText = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Verificando...';
        
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/status`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await processResponse(response, 'Status');
        
        if (data.success) {
            showStatusModal(data.emulators, data.server_info, false);
            updateButtonsStatus(data.server_info.all_online);
        } else {
            showToast('error', 'Erro!', sanitizeHtml(data.message));
            showStatusModal(data.emulators || getDefaultEmulators());
        }
        
    } catch (error) {
        showToast('error', 'Erro de Conexão', `Erro: ${sanitizeHtml(error.message)}`);
        updateButtonsStatus(false);
    } finally {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 2000);
    }
}

function stopAllServers() {
    showConfirmModal('stop-all', 'Parar Todos os Servidores', 'Isso vai desconectar todos os jogadores online!', 'fas fa-stop', 'stop');
}

function startAllServers() {
    showConfirmModal('start-all', 'Iniciar Todos os Servidores', 'Sequência: Center (3s) → Fighting (15s) → Road', 'fas fa-play', 'start');
}

function showConfirmModal(action, title, description, icon, endpoint) {
    currentAction = action;
    currentEndpoint = endpoint;
    
    document.getElementById('modal-title').textContent = 'Confirmar ' + title;
    document.getElementById('modal-action-title').textContent = title;
    document.getElementById('modal-description').textContent = description;
    document.getElementById('modal-icon').className = icon + ' fa-3x text-warning mb-3';
    
    const modal = new bootstrap.Modal(document.getElementById('serverActionModal'));
    modal.show();
}

async function testEndpoint() {
    const endpoints = [
        { name: 'Status', url: `${SERVER_CONFIG.API_BASE}/status`, method: 'GET' },
        { name: 'Start', url: `${SERVER_CONFIG.API_BASE}/start`, method: 'POST' },
        { name: 'Stop', url: `${SERVER_CONFIG.API_BASE}/stop`, method: 'POST' },
        { name: 'Ping', url: `${SERVER_CONFIG.API_BASE}/ping`, method: 'GET' },
        { name: 'Reload XML', url: `${SERVER_CONFIG.API_BASE}/reload-xml`, method: 'POST' }
    ];
    
    for (const endpoint of endpoints) {
        try {
            const response = await fetch(endpoint.url, {
                method: endpoint.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const responseText = await response.text();
            
            if (responseText.trim()) {
                try {
                    const jsonData = JSON.parse(responseText);
                } catch (e) {}
            }
            
        } catch (error) {}
    }
}

window.testEndpoints = testEndpoint;
window.testStartAll = function() {
    currentEndpoint = 'start';
    executeStartWithStatusModal();
};
window.testStatusModal = function() {
    openStatusModalWithProgress();
};
window.testLoadingModal = function() {
    openProgressModalLoading();
    setTimeout(() => {
        transitionToProgressModal();
    }, 3000);
};
window.testServerConfig = function() {};

document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('btn-confirm-action');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (currentEndpoint === 'start') {
                executeStartWithStatusModal();
            } else {
                executeServerAction();
            }
        });
    }
    
    startAutoStatus();
    setTimeout(updateServerStatusSilently, 2000);
});

async function executeStartWithStatusModal() {
    const btn = document.getElementById('btn-confirm-action');
    const originalText = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
        
        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('serverActionModal'));
        if (confirmModal) confirmModal.hide();
        
        openProgressModalLoading();
        
        setTimeout(async () => {
            try {
                const response = await fetch(`${SERVER_CONFIG.API_BASE}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const responseText = await response.text();
                
                let commandSent = false;
                if (response.status === 200) {
                    commandSent = true;
                } else if (responseText.trim()) {
                    try {
                        const data = JSON.parse(responseText);
                        commandSent = data.success === true || ['started', 'command_sent'].includes(data.status);
                    } catch (e) {
                        commandSent = true;
                    }
                } else {
                    commandSent = true;
                }
                
                if (commandSent) {
                    transitionToProgressModal();
                } else {
                    showModalError('Erro ao enviar comando para VPS');
                }
                
            } catch (error) {
                setTimeout(transitionToProgressModal, 1000);
            }
        }, 500);
        
    } finally {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 2000);
    }
}

function openProgressModalLoading() {
    const emulators = getDefaultEmulators();
    
    let statusHtml = '<div class="row">';
    
    emulators.forEach(emu => {
        statusHtml += `
            <div class="col-4">
                <div class="card border-info mb-3" id="status-card-${sanitizeHtml(emu.name)}">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-clock fa-2x text-info mb-2" id="status-icon-${sanitizeHtml(emu.name)}"></i>
                        <h6 class="card-title">${sanitizeHtml(emu.name)}</h6>
                        <span class="badge bg-info" id="status-badge-${sanitizeHtml(emu.name)}">Aguardando...</span>
                        <div class="mt-2 text-muted small" id="status-details-${sanitizeHtml(emu.name)}">
                            <div><strong>Porta:</strong> ${sanitizeHtml(getPortByName(emu.name))}</div>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" 
                                 id="progress-bar-${sanitizeHtml(emu.name)}" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block" id="progress-text-${sanitizeHtml(emu.name)}">Preparando...</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    statusHtml += '</div>';
    
    const modalHtml = `
        <div class="modal fade" id="statusModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-paper-plane me-2"></i>Enviando Comando de Inicialização
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning mb-3">
                            <div class="row">
                                <div class="col-8">
                                    <strong>Enviando comando para VPS...</strong><br>
                                    <small>Aguarde enquanto o comando é processado</small>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="spinner-border spinner-border-sm text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ${statusHtml}
                        <div class="progress mt-4" style="height: 30px;">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" 
                                 id="overall-progress" style="width: 10%">
                                <span class="fw-bold fs-6">Enviando comando...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" disabled>
                            Aguardando resposta...
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('statusModal');
    if (existingModal) existingModal.remove();
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function transitionToProgressModal() {
    const modalTitle = document.querySelector('#statusModal .modal-title');
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-rocket me-2"></i>Iniciando Emuladores';
    }
    
    const modalHeader = document.querySelector('#statusModal .modal-header');
    if (modalHeader) {
        modalHeader.className = 'modal-header bg-primary text-white';
    }
    
    const alert = document.querySelector('#statusModal .alert');
    if (alert) {
        alert.className = 'alert alert-info mb-3';
        alert.innerHTML = `
            <div class="row">
                <div class="col-8">
                    <strong>Comando enviado! Iniciando emuladores...</strong><br>
                    <small><strong>Sequência:</strong> Center (0-8s) → Fighting (8-20s) → Road (20-25s)</small>
                </div>
                <div class="col-4 text-end">
                    <div class="small">
                        <div><strong>Status:</strong> Monitorando</div>
                        <div id="progress-timer" class="text-info mt-1"><strong>Tempo:</strong> 0s</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    const overallProgress = document.getElementById('overall-progress');
    if (overallProgress) {
        overallProgress.style.width = '15%';
        overallProgress.className = 'progress-bar bg-info progress-bar-striped progress-bar-animated';
        overallProgress.innerHTML = '<span class="fw-bold fs-6">Monitorando progresso... (0/3)</span>';
    }
    
    const footerButton = document.querySelector('#statusModal .modal-footer button');
    if (footerButton) {
        footerButton.innerHTML = 'Aguarde...';
        footerButton.id = 'progress-close-btn';
    }
    
    const footer = document.querySelector('#statusModal .modal-footer');
    if (footer) {
        footer.innerHTML = `
            <button type="button" class="btn btn-secondary" id="progress-close-btn" disabled data-bs-dismiss="modal">
                Aguarde...
            </button>
            <button type="button" class="btn btn-info" onclick="refreshStatusModal()">
                <i class="fas fa-sync me-1"></i>Atualizar
            </button>
            <button type="button" class="btn btn-warning" onclick="forceCloseProgressModal()">
                <i class="fas fa-times me-1"></i>Forçar Fechar
            </button>
        `;
    }
    
    isStartingSequence = true;
    progressStartTime = Date.now();
    startProgressTimer();
    startProgressMonitoring();
}

function showModalError(errorMessage) {
    const alert = document.querySelector('#statusModal .alert');
    if (alert) {
        alert.className = 'alert alert-danger mb-3';
        alert.innerHTML = `
            <div class="row">
                <div class="col-12 text-center">
                    <strong>Erro ao enviar comando</strong><br>
                    <small>${sanitizeHtml(errorMessage)}</small>
                </div>
            </div>
        `;
    }
    
    const overallProgress = document.getElementById('overall-progress');
    if (overallProgress) {
        overallProgress.style.width = '100%';
        overallProgress.className = 'progress-bar bg-danger';
        overallProgress.innerHTML = '<span class="fw-bold fs-6">Erro na operação</span>';
    }
    
    const footerButton = document.querySelector('#statusModal .modal-footer button');
    if (footerButton) {
        footerButton.disabled = false;
        footerButton.className = 'btn btn-danger';
        footerButton.innerHTML = '<i class="fas fa-times me-1"></i>Fechar';
    }
}

async function openStatusModalWithProgress() {
    try {
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/status`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        let emulators;
        if (!response.ok) {
            emulators = getDefaultEmulators();
        } else {
            const data = await response.json();
            emulators = data.success ? data.emulators : getDefaultEmulators();
        }
        
        showStatusModal(emulators, null, true);
        
        isStartingSequence = true;
        progressStartTime = Date.now();
        startProgressMonitoring();
        
    } catch (error) {
        showStatusModal(getDefaultEmulators(), null, true);
        isStartingSequence = true;
        progressStartTime = Date.now();
        startProgressMonitoring();
    }
}

function getDefaultEmulators() {
    return [
        { name: 'Center', isRunning: false, pid: null, memoryMB: 0, startTime: null },
        { name: 'Fighting', isRunning: false, pid: null, memoryMB: 0, startTime: null },
        { name: 'Road', isRunning: false, pid: null, memoryMB: 0, startTime: null }
    ];
}

function showStatusModal(emulators, serverInfo = null, withProgress = false) {
    if (!emulators || emulators.length === 0) {
        emulators = getDefaultEmulators();
    }
    
    let statusHtml = '<div class="row">';
    let allOnline = true;
    
    emulators.forEach(emu => {
        const isRunning = emu.isRunning;
        if (!isRunning) allOnline = false;
        
        const statusClass = isRunning ? 'success' : (withProgress ? 'warning' : 'danger');
        const statusIcon = isRunning ? 'fas fa-check-circle' : (withProgress ? 'fas fa-clock' : 'fas fa-times-circle');
        const statusText = isRunning ? 'Online' : (withProgress ? 'Preparando...' : 'Offline');
        
        statusHtml += `
            <div class="col-4">
                <div class="card border-${statusClass} mb-3" id="status-card-${sanitizeHtml(emu.name)}">
                    <div class="card-body text-center p-3">
                        <i class="${statusIcon} fa-2x text-${statusClass} mb-2" id="status-icon-${sanitizeHtml(emu.name)}"></i>
                        <h6 class="card-title">${sanitizeHtml(emu.name)}</h6>
                        <span class="badge bg-${statusClass}" id="status-badge-${sanitizeHtml(emu.name)}">${statusText}</span>
                        ${isRunning ? `
                            <div class="mt-2 text-muted small" id="status-details-${sanitizeHtml(emu.name)}">
                                <div><strong>Status:</strong> Ativo</div>
                                <div><strong>Porta:</strong> ${sanitizeHtml(getPortByName(emu.name))}</div>
                            </div>
                        ` : `
                            <div class="mt-2 text-muted small" id="status-details-${sanitizeHtml(emu.name)}">
                                <div><strong>Porta:</strong> ${sanitizeHtml(getPortByName(emu.name))}</div>
                            </div>
                        `}
                        
                        ${withProgress ? `
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" 
                                     id="progress-bar-${sanitizeHtml(emu.name)}" style="width: 0%"></div>
                            </div>
                            <small class="text-muted mt-1 d-block" id="progress-text-${sanitizeHtml(emu.name)}">Aguardando...</small>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    statusHtml += '</div>';
    
    let summary = `
        <div class="alert alert-${allOnline ? 'success' : (withProgress ? 'info' : 'warning')} mb-3">
            <div class="row">
                <div class="col-8">
                    <strong>${withProgress ? 'Iniciando emuladores...' : (allOnline ? 'Todos online' : 'Alguns offline')}</strong><br>
                    <small>Última verificação: ${new Date().toLocaleString('pt-BR')}</small>
                    ${withProgress ? '<br><small><strong>Sequência:</strong> Center (0-8s) → Fighting (8-20s) → Road (20-25s)</small>' : ''}
                </div>
                <div class="col-4 text-end">
                    <div class="small">
                        ${serverInfo ? `
                            <div><strong>Online:</strong> ${serverInfo.online_count}/${serverInfo.total_emulators}</div>
                            <div><strong>Status:</strong> ${serverInfo.all_online ? 'OK' : 'Parcial'}</div>
                        ` : `
                            <div><strong>Status:</strong> ${allOnline ? 'OK' : 'Verificando'}</div>
                        `}
                        ${withProgress ? `<div id="progress-timer" class="text-info mt-1"><strong>Tempo:</strong> 0s</div>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const modalHtml = `
        <div class="modal fade" id="statusModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-${withProgress ? 'primary' : 'info'} text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-${withProgress ? 'rocket' : 'server'} me-2"></i>${withProgress ? 'Iniciando Emuladores' : 'Status dos Emuladores'}
                        </h5>
                        ${!withProgress ? '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>' : ''}
                    </div>
                    <div class="modal-body">
                        ${summary}
                        ${statusHtml}
                        ${withProgress ? `
                            <div class="progress mt-4" style="height: 30px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     id="overall-progress" style="width: 0%">
                                    <span class="fw-bold fs-6">0% Concluído (0/3)</span>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" ${withProgress ? 'id="progress-close-btn" disabled data-bs-dismiss="modal"' : 'data-bs-dismiss="modal"'}>
                            ${withProgress ? 'Aguarde...' : 'Fechar'}
                        </button>
                        <button type="button" class="btn btn-info" onclick="refreshStatusModal()">
                            <i class="fas fa-sync me-1"></i>Atualizar
                        </button>
                        ${withProgress ? `
                            <button type="button" class="btn btn-warning" onclick="forceCloseProgressModal()">
                                <i class="fas fa-times me-1"></i>Forçar Fechar
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('statusModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    const modalElement = document.getElementById('statusModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        if (withProgress) {
            startProgressTimer();
        }
    }
}

function forceCloseProgressModal() {
    clearInterval(progressInterval);
    isStartingSequence = false;
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
    if (modal) modal.hide();
    
    showToast('info', 'Modal Fechado', 'Progresso interrompido pelo usuário');
}

function getPortByName(name) {
    const ports = {
        'Center': '7000',
        'Fighting': '7001',
        'Road': '7002'
    };
    return ports[name] || 'N/A';
}

function refreshStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        bootstrap.Modal.getInstance(modal).hide();
        setTimeout(() => {
            getServerStatus();
        }, 300);
    }
}

function startProgressTimer() {
    const timerElement = document.getElementById('progress-timer');
    if (!timerElement) return;
    
    const startTime = Date.now();
    
    const timerInterval = setInterval(() => {
        if (!document.getElementById('progress-timer')) {
            clearInterval(timerInterval);
            return;
        }
        
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        timerElement.innerHTML = `<strong>Tempo:</strong> ${elapsed}s`;
    }, 1000);
}

function startProgressMonitoring() {
    let checkCount = 0;
    const maxChecks = 35;
    
    progressInterval = setInterval(async () => {
        checkCount++;
        
        try {
            const response = await fetch(`${SERVER_CONFIG.API_BASE}/status`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) return;
            
            const data = await response.json();
            
            if (data.success && data.emulators) {
                updateProgressInStatusModal(data.emulators);
                
                const allOnline = data.emulators.every(e => e.isRunning);
                if (allOnline) {
                    completeProgressInStatusModal();
                    return;
                }
            }
            
            if (checkCount >= maxChecks) {
                timeoutProgressInStatusModal();
                return;
            }
            
        } catch (error) {
            if (checkCount > 15) {
                timeoutProgressInStatusModal();
                return;
            }
        }
    }, 1500);
}

function updateProgressInStatusModal(emulators) {
    let onlineCount = 0;
    const elapsed = Math.floor((Date.now() - progressStartTime) / 1000);
    
    const simulatedStates = getSimulatedProgress(elapsed);
    
    emulators.forEach(emu => {
        const card = document.getElementById(`status-card-${sanitizeHtml(emu.name)}`);
        const icon = document.getElementById(`status-icon-${sanitizeHtml(emu.name)}`);
        const badge = document.getElementById(`status-badge-${sanitizeHtml(emu.name)}`);
        const details = document.getElementById(`status-details-${sanitizeHtml(emu.name)}`);
        const progressBar = document.getElementById(`progress-bar-${sanitizeHtml(emu.name)}`);
        const progressText = document.getElementById(`progress-text-${sanitizeHtml(emu.name)}`);
        
        if (!card) return;
        
        const simulated = simulatedStates[emu.name];
        const isActuallyRunning = emu.isRunning;
        
        if (isActuallyRunning && simulated.phase === 'completed') {
            onlineCount++;
            
            card.className = 'card border-success mb-3';
            icon.className = 'fas fa-check-circle fa-2x text-success mb-2';
            badge.textContent = 'Online';
            badge.className = 'badge bg-success';
            details.innerHTML = `
                <div><strong>Status:</strong> Ativo</div>
                <div><strong>Porta:</strong> ${sanitizeHtml(getPortByName(emu.name))}</div>
            `;
            
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.className = 'progress-bar bg-success';
            }
            if (progressText) {
                progressText.textContent = 'Online - Ativo';
                progressText.className = 'text-success mt-1 d-block small fw-bold';
            }
            
        } else {
            if (simulated.phase === 'waiting') {
                card.className = 'card border-info mb-3';
                icon.className = 'fas fa-clock fa-2x text-info mb-2';
                badge.textContent = 'Aguardando';
                badge.className = 'badge bg-info';
                
                if (progressBar) {
                    progressBar.style.width = `${simulated.progress}%`;
                    progressBar.className = 'progress-bar bg-info';
                }
                if (progressText) {
                    progressText.textContent = simulated.message;
                    progressText.className = 'text-info mt-1 d-block small';
                }
                
            } else if (simulated.phase === 'starting') {
                card.className = 'card border-warning mb-3';
                icon.className = 'fas fa-spinner fa-spin fa-2x text-warning mb-2';
                badge.textContent = 'Iniciando';
                badge.className = 'badge bg-warning';
                
                if (progressBar) {
                    progressBar.style.width = `${simulated.progress}%`;
                    progressBar.className = 'progress-bar bg-warning progress-bar-striped progress-bar-animated';
                }
                if (progressText) {
                    progressText.textContent = simulated.message;
                    progressText.className = 'text-warning mt-1 d-block small';
                }
                
            } else if (simulated.phase === 'completing') {
                card.className = 'card border-success mb-3';
                icon.className = 'fas fa-check-circle fa-2x text-success mb-2';
                badge.textContent = 'Finalizando';
                badge.className = 'badge bg-success';
                
                if (progressBar) {
                    progressBar.style.width = `${simulated.progress}%`;
                    progressBar.className = 'progress-bar bg-success progress-bar-striped progress-bar-animated';
                }
                if (progressText) {
                    progressText.textContent = simulated.message;
                    progressText.className = 'text-success mt-1 d-block small';
                }
                
                if (simulated.progress >= 98) {
                    onlineCount++;
                }
            }
        }
    });
    
    const overallProgress = document.getElementById('overall-progress');
    if (overallProgress) {
        const timeBasedProgress = getOverallTimeProgress(elapsed);
        const actualProgress = Math.round((onlineCount / emulators.length) * 100);
        
        const displayProgress = Math.max(timeBasedProgress, actualProgress);
        
        overallProgress.style.width = `${displayProgress}%`;
        
        if (displayProgress === 100) {
            overallProgress.className = 'progress-bar bg-success';
            overallProgress.innerHTML = `<span class="fw-bold fs-6">Concluído! (${onlineCount}/${emulators.length})</span>`;
        } else {
            overallProgress.className = 'progress-bar bg-info progress-bar-striped progress-bar-animated';
            overallProgress.innerHTML = `<span class="fw-bold fs-6">${displayProgress}% - ${getPhaseMessage(elapsed)} (${onlineCount}/${emulators.length})</span>`;
        }
    }
}

function getSimulatedProgress(elapsed) {
    const states = {
        'Center': { phase: 'waiting', progress: 0, message: 'Aguardando...' },
        'Fighting': { phase: 'waiting', progress: 0, message: 'Aguardando...' },
        'Road': { phase: 'waiting', progress: 0, message: 'Aguardando...' }
    };
    
    if (elapsed >= 2 && elapsed < 10) {
        const centerProgress = Math.min(((elapsed - 2) / 8) * 100, 95);
        states.Center = {
            phase: 'starting',
            progress: centerProgress,
            message: `Iniciando Center... ${Math.round(centerProgress)}%`
        };
    } else if (elapsed >= 10) {
        states.Center = {
            phase: 'completing',
            progress: 100,
            message: 'Center iniciado'
        };
    } else if (elapsed >= 0) {
        states.Center = {
            phase: 'waiting',
            progress: Math.min((elapsed / 2) * 10, 10),
            message: `Preparando Center... ${Math.max(3 - elapsed, 0)}s`
        };
    }
    
    if (elapsed >= 10 && elapsed < 22) {
        const fightingProgress = Math.min(((elapsed - 10) / 12) * 100, 95);
        states.Fighting = {
            phase: 'starting',
            progress: fightingProgress,
            message: `Iniciando Fighting... ${Math.round(fightingProgress)}%`
        };
    } else if (elapsed >= 22) {
        states.Fighting = {
            phase: 'completing',
            progress: 100,
            message: 'Fighting iniciado'
        };
    } else if (elapsed >= 8) {
        states.Fighting = {
            phase: 'waiting',
            progress: Math.min(((elapsed - 8) / 2) * 15, 15),
            message: `Aguardando Center... ${Math.max(10 - elapsed, 0)}s`
        };
    }
    
    if (elapsed >= 22 && elapsed < 28) {
        const roadProgress = Math.min(((elapsed - 22) / 6) * 100, 95);
        states.Road = {
            phase: 'starting',
            progress: roadProgress,
            message: `Iniciando Road... ${Math.round(roadProgress)}%`
        };
    } else if (elapsed >= 28) {
        states.Road = {
            phase: 'completing',
            progress: 100,
            message: 'Road iniciado'
        };
    } else if (elapsed >= 20) {
        states.Road = {
            phase: 'waiting',
            progress: Math.min(((elapsed - 20) / 2) * 10, 10),
            message: `Aguardando Fighting... ${Math.max(22 - elapsed, 0)}s`
        };
    }
    
    return states;
}

function getOverallTimeProgress(elapsed) {
    if (elapsed < 2) return Math.min((elapsed / 2) * 5, 5);
    if (elapsed < 10) return 5 + ((elapsed - 2) / 8) * 25;
    if (elapsed < 22) return 30 + ((elapsed - 10) / 12) * 50;
    if (elapsed < 28) return 80 + ((elapsed - 22) / 6) * 20;
    return 100;
}

function getPhaseMessage(elapsed) {
    if (elapsed < 2) return 'Preparando';
    if (elapsed < 10) return 'Iniciando Center';
    if (elapsed < 22) return 'Iniciando Fighting';
    if (elapsed < 28) return 'Iniciando Road';
    return 'Finalizando';
}

function completeProgressInStatusModal() {
    clearInterval(progressInterval);
    isStartingSequence = false;
    
    const modalTitle = document.querySelector('#statusModal .modal-title');
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i>Emuladores Online';
    }
    
    const modalHeader = document.querySelector('#statusModal .modal-header');
    if (modalHeader) {
        modalHeader.className = 'modal-header bg-success text-white';
    }
    
    const alert = document.querySelector('#statusModal .alert');
    if (alert) {
        alert.className = 'alert alert-success mb-3';
        alert.innerHTML = `
            <div class="row">
                <div class="col-8">
                    <strong>Todos os emuladores estão online!</strong><br>
                    <small>Inicialização concluída com sucesso</small>
                </div>
                <div class="col-4 text-end">
                    <div class="small text-success">
                        <div><strong>Sucesso!</strong></div>
                        <div id="progress-timer" class="mt-1"></div>
                    </div>
                </div>
            </div>
        `;
    }
    
    const overallProgress = document.getElementById('overall-progress');
    if (overallProgress) {
        overallProgress.style.width = '100%';
        overallProgress.className = 'progress-bar bg-success';
        overallProgress.innerHTML = '<span class="fw-bold fs-6">100% Concluído - Todos Online!</span>';
    }
    
    const closeBtn = document.getElementById('progress-close-btn');
    if (closeBtn) {
        closeBtn.disabled = false;
        closeBtn.className = 'btn btn-success';
        closeBtn.innerHTML = '<i class="fas fa-check me-1"></i>Concluído!';
    }
    
    updateButtonsStatus(true);
    
    setTimeout(() => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        if (modal) {
            modal.hide();
        }
    }, 5000);
}

function timeoutProgressInStatusModal() {
    clearInterval(progressInterval);
    isStartingSequence = false;
    
    const closeBtn = document.getElementById('progress-close-btn');
    if (closeBtn) {
        closeBtn.disabled = false;
        closeBtn.className = 'btn btn-warning';
        closeBtn.textContent = 'Fechar';
    }
    
    showToast('warning', 'Timeout', 'Tempo limite atingido. Verifique o status manualmente.');
}

async function executeServerAction() {
    const btn = document.getElementById('btn-confirm-action');
    const originalText = btn.innerHTML;
    
    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Executando...';
        bootstrap.Modal.getInstance(document.getElementById('serverActionModal')).hide();
        
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/${currentEndpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', 'Sucesso!', sanitizeHtml(data.message));
            
            if (currentEndpoint === 'stop' && data.results && data.results.length > 0) {
                setTimeout(() => {
                    let stoppedCount = data.results.filter(r => ['stopped', 'not_running'].includes(r.status)).length;
                    showToast('info', 'Emuladores Parados', `${stoppedCount}/${data.results.length} emuladores foram parados`);
                }, 1000);
            }
            
            setTimeout(updateServerStatusSilently, 2000);
            
        } else {
            showToast('error', 'Erro!', sanitizeHtml(data.message || 'Erro ao executar ação'));
        }
        
    } catch (error) {
        showToast('error', 'Erro de Conexão', `Erro: ${sanitizeHtml(error.message)}`);
    } finally {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 3000);
    }
}

async function testVpsConnection() {
    try {
        showToast('info', 'Testando', 'Verificando conexão com VPS...');
        
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/ping`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', 'VPS Online', `Conexão OK! Tempo: ${sanitizeHtml(data.response_time)}`);
        } else {
            showToast('error', 'VPS Offline', sanitizeHtml(data.message || 'VPS não está acessível'));
        }
        
    } catch (error) {
        showToast('error', 'Erro de Conexão', `Erro: ${sanitizeHtml(error.message)}`);
    }
}

async function updateServerStatusSilently() {
    try {
        const response = await fetch(`${SERVER_CONFIG.API_BASE}/status`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            updateButtonsStatus(false);
            return;
        }
        
        const data = await response.json();
        
        if (data.success && data.server_info) {
            updateButtonsStatus(data.server_info.all_online);
        } else {
            updateButtonsStatus(false);
        }
        
    } catch (error) {
        updateButtonsStatus(false);
    }
}

let autoStatusInterval;

function startAutoStatus() {
    autoStatusInterval = setInterval(() => {
        updateServerStatusSilently();
    }, 60000);
}

function updateButtonsStatus(allOnline) {
    const statusBtn = document.getElementById('btn-status');
    if (statusBtn) {
        statusBtn.className = statusBtn.className.replace(/btn-(success|danger|info)/, `btn-${allOnline ? 'success' : 'danger'}`);
        
        const icon = statusBtn.querySelector('i');
        if (icon) {
            icon.className = icon.className.replace(/fa-(heartbeat|times|check)/, allOnline ? 'fa-check' : 'fa-times');
        }
    }
}

function showToast(type, title, message) {
    const toast = document.createElement('div');
    const bgColor = {
        'success': 'bg-success',
        'error': 'bg-danger',
        'warning': 'bg-warning',
        'info': 'bg-info'
    }[type] || 'bg-info';
    
    toast.className = `toast align-items-center text-white ${bgColor} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <strong>${sanitizeHtml(title)}</strong><br>${sanitizeHtml(message)}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    new bootstrap.Toast(toast, { autohide: true, delay: 5000 }).show();
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

window.addEventListener('beforeunload', function() {
    if (autoStatusInterval) clearInterval(autoStatusInterval);
    if (progressInterval) clearInterval(progressInterval);
});
</script>
@endif