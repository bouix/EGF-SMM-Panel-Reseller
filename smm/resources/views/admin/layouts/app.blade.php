@php
    // alignment direction according to language
    $dir = "ltr";
    $rtlLang = ['ar'];
    if(in_array(getOption('language'),$rtlLang)):
        $dir="rtl";
    endif;
@endphp
        <!DOCTYPE html>
<html lang="{{ getOption('language') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    {{--<link rel="shortcut icon" href="/img/favicon.ico">--}}
    <link rel="shortcut icon" href="{{ asset(getOption('logo')) }}">

    <link href="/css/vendor/bootstrap/css/bootstrap.min.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
    <link href="/css/vendor/datatable/datatables.min.css?v={{ config('constants.VERSION') }}" rel="stylesheet">


    @if(in_array(getOption('language'),$rtlLang))
        <style>
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
                text-align: right;
            }
        </style>
    @endif


    @if(getOption('panel_theme') == 'material')
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="/css/flat-ui.min.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
        <link href="/css/page.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
        <style>
            body {
                background-color: {{ getOption('background_color') }}   !important;
            }

            .navbar-default {
                background-color: {{ getOption('theme_color') }};
            }

            .btn-primary,
            .btn-primary:hover,
            .btn-primary:active,
            .btn-primary:focus {
                background-color: {{ getOption('theme_color') }};
                border-color: #000;
            }

            .login-form .login-field:focus {
                border-color: {{ getOption('theme_color') }};
            }

            a,
            a:active,
            a:focus,
            a:hover {
                color: {{ getOption('theme_color') }};
            }

            .login-link:hover {
                color: {{ getOption('theme_color') }};
            }

            input[type=text]:focus,
            .form-control:focus {
                border-color: {{ getOption('theme_color') }};
            }

            .pagination li.active > a, .pagination li.active > span, .pagination li.active > a:hover, .pagination li.active > span:hover, .pagination li.active > a:focus, .pagination li.active > span:focus {
                background-color: {{ getOption('theme_color') }};
            }

            #footer-menu li a {
                color: {{ getOption('theme_color') }};
                font-size: 14px;
            }

            .pagination li > a:hover, .pagination li > span:hover {
                background-color: {{ getOption('theme_color') }};
            }

            g
        </style>
    @elseif(getOption('panel_theme') == 'simple')
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic" rel="stylesheet">
        <link href="/css/page-theme-simple.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
    @elseif(getOption('panel_theme') == 'fancy')
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic" rel="stylesheet">
        <link href="/css/page-theme-fancy.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
     @endif

    <link href="/css/my-style.css?v={{ config('constants.VERSION') }}" rel="stylesheet">

<!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
        window.baseUrl = "<?php echo url('/') ?>";
        var spinner = "<span class='loader'></span>";
    </script>
    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="/js/vendor/jquery.min.js?v={{ config('constants.VERSION') }}"></script>
    <script src="/js/vendor/form-validator/jquery.form-validator.min.js?v={{ config('constants.VERSION') }}"></script>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.validate({
                modules: 'date',
                validateOnBlur: false,
                lang: '{{ getOption('language') }}'
            });
        })
    </script>
    <script src="/js/my-script.js?v={{ config('constants.VERSION') }}"></script>
</head>

<body dir="{{ $dir }}">
<div id="app">
    <nav class="navbar navbar-default navbar-fixed-top">

        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset(getOption('logo')) }}" alt="Retina" width="50" height="50">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Right Side Of Navbar -->
            @if (Auth::user())
                <ul class="nav navbar-nav">
                    <!-- Authentication Links -->
                    <li>
                        <a href="{{ url('/admin') }}">@lang('menus.dashboard')</a>
                    </li>
                    <li class="dropdown">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-expanded="false">
                            @lang('menus.settings') <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/admin/system/settings') }}">@lang('menus.system')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/payment-methods') }}">@lang('menus.payment_methods')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/services') }}">@lang('menus.services')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/packages') }}">@lang('menus.packages')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/pages') }}">@lang('menus.pages')</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ url('/admin/funds-load-history') }}">@lang('menus.funds_load_history')</a></li>
                    <li><a href="{{ url('/admin/users') }}">@lang('menus.users')</a></li>
                    <li><a href="{{ url('/admin/orders') }}">@lang('menus.orders')</a></li>
                    @if(getOption('module_subscription_enabled') == 1)
                        <li><a href="{{ url('/admin/subscriptions') }}">@lang('menus.subscriptions')</a></li>
                    @endif
                    @if(getOption('module_support_enabled') == 1)
                        <li><a href="{{ url('/admin/support/tickets') }}">@lang('menus.support')</a></li>
                    @endif
                    <li class="dropdown">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-expanded="false">
                            @lang('menus.automate') <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/admin/automate/api-list') }}">@lang('menus.api_list')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/automate/send-orders') }}">@lang('menus.send_orders')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/automate/get-status') }}">@lang('menus.get_order_status')</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/automate/response-logs') }}">@lang('menus.response_logs')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right" style="padding-right:30px;">
                    <li class="dropdown">
                        <a href="#"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           role="button"
                           aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/admin/account/settings') }}">@lang('menus.account')</a></li>
                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    @lang('menus.logout')
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </nav>
    <div class="clearfix" style="height: 70px;"></div>
    <div class="{{ getOption('admin_layout') }}">
        @if(Session::has('alert'))
            <div class="row">
                <div class="col-md-5 col-md-offset-7">
                    <div style="font-size: 15px; margin-top: -15px;" class="alert alert-{{ Session::get('alertClass') }}">
                        <button type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-hidden="true">×
                        </button>
                        {{ Session::get('alert') }}
                    </div>
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div id="footer">
    <ul id="footer-menu">
        <li><a href="{{ url('/page/faqs') }}">@lang('menus.faqs')</a></li>
        <li><a href="{{ url('/page/terms-and-conditions') }}">@lang('menus.terms_and_conditions')</a></li>
        <li><a href="{{ url('/page/privacy-policy') }}">@lang('menus.privacy_policy')</a></li>
        <li><a href="{{ url('/page/about-us') }}">@lang('menus.about_us')</a></li>
        <li><a href="{{ url('/page/contact-us') }}">@lang('menus.contact_us')</a></li>
        <li><span style="color: gray; font-size: 12px;">Version: {{ config('constants.VERSION') }}</span></li>
    </ul>
</div>

<!-- Scripts -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/vendor/datatable/datatables.min.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/vendor/bootbox/bootbox.min.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/flat-ui.min.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/application.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/custom.js?v={{ config('constants.VERSION') }}"></script>
<script>
    $(function () {

        if (!$(".alert").hasClass('no-auto-close')) {
            $(".alert").delay(3000).slideUp(300);
        }

    });
</script>
@stack('scripts')
</body>
</html>
