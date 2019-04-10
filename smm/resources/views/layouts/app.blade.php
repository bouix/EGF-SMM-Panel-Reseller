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
    {!! getOption('home_page_meta') !!}
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="http://child.easygrowfast.com/images/MxycgeaF5HkHa6GjICf0VUTzyCpihUEaxODNMPwE.png">
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
                background-color: {{ getOption('background_color') }} !important;
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

            .dropdown-lang li a{
                padding-top: 3px;
                padding-bottom: 3px;
            }

            .theme-bg{
                background-color: {{ getOption('theme_color') }} !important;
            }
        </style>
    @elseif(getOption('panel_theme') == 'simple')
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic" rel="stylesheet">
        <link href="/css/page-theme-simple.css?v={{ config('constants.VERSION') }}" rel="stylesheet">
        <style>
            .theme-bg{
                background-color: #b9b6b6 !important;
            }
        </style>
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

            $(document).on('click','.dropdown-lang a',function (e) {
                e.preventDefault();
                var locale = $(this).data('locale');
                $('#locale').val(locale);
                document.getElementById('lang-form').submit();
            });
        });
    </script>
    <script src="/js/my-script.js?v={{ config('constants.VERSION') }}"></script>
</head>
<body dir="{{ $dir }}">
<div id="app">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
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
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::check())
                        <li>
                            <a href="{{ url('/dashboard') }}">@lang('menus.dashboard')</a>
                        </li>
                        <li>
                            <a href="{{ url('/order/new') }}">@lang('menus.new_order')</a>
                        </li>
                        <li>
                            <a href="{{ url('/order/mass-order') }}">@lang('menus.mass_order')</a>
                        </li>
                        <li>
                            <a href="{{ url('/orders') }}">@lang('menus.order_history')</a>
                        </li>
                        @if(getOption('module_subscription_enabled') == 1)
                            <li><a href="{{ url('/subscriptions') }}">@lang('menus.subscriptions')</a></li>
                        @endif
                        <li>
                            <a href="{{ url('/payment/add-funds') }}">@lang('menus.add_funds')</a>
                        </li>
                        @if(getOption('module_support_enabled') == 1)
                            <li><a href="{{ url('/support') }}">@lang('menus.support')</a></li>
                        @endif
                        @if(getOption('module_api_enabled') == 1)
                            <li><a href="{{ url('/api') }}">@lang('menus.api')</a></li>
                        @endif
                        <li><a href="{{ url('/services') }}">@lang('menus.service_list')</a></li>
                    @elseif(getOption('show_service_list_without_login') == 'YES')
                    <li class="dropdown">
                            <a href="#"
                               class="dropdown-toggle"
                               data-toggle="dropdown"
                               role="button"
                               aria-expanded="false">
                                Language <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-lang" role="menu">
                                <li>
                                    <form id="lang-form" action="{{ url('/change-lang') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="en" name="locale" id="locale">
                                    </form>
                                    <a href="#" data-locale="en">English</a>
                                </li>
                                <li><a href="#" data-locale="es">Spanish</a></li>
                                <li><a href="#" data-locale="ru">Russian</a></li>
                                <li><a href="#" data-locale="de">German</a></li>
                                <li><a href="#" data-locale="fr">French</a></li>
                                <li><a href="#" data-locale="pt">Portuguese</a></li>
                                <li><a href="#" data-locale="zh">Chinese</a></li>
                                <li><a href="#" data-locale="it">Italian</a></li>
                                <li><a href="#" data-locale="tr">Turkish</a></li>
                                <li><a href="#" data-locale="ar">Arabic</a></li>
                                <li><a href="#" data-locale="th">Thai</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="https://easygrowfast.com/">EGF Panel</a></li>
                        <li><a href="{{ url('/login') }}">@lang('menus.login')</a></li>
                        <li><a href="{{ url('/register') }}">@lang('menus.register')</a></li>
                        <li><a href="{{ url('/services') }}">@lang('menus.service_list')</a></li>
                    @else
                        <li>
                            <a href="{{ url('/payment/add-funds') }}" style="font-size: 18px;"><span class="label label-success user-fund-top">@lang('menus.available')
                                    : {{ getOption('currency_symbol') . number_format(Auth::user()->funds,2, getOption('currency_separator'), '') }}</span></a>
                        </li>
                        <li class="dropdown">
                            <a href="#"
                               class="dropdown-toggle"
                               data-toggle="dropdown"
                               role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/account/funds-load-history') }}">@lang('menus.funds_load_history')</a>
                                </li>
                                <li>
                                    <a href="{{ url('/account/settings') }}">@lang('menus.settings')</a>
                                </li>
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
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="clearfix" style="height: 70px;"></div>
    <div class="{{ getOption('user_layout') }}">
        @if(Session::has('alert'))
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
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
<!-- Scripts -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/vendor/datatable/datatables.min.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/flat-ui.min.js?v={{ config('constants.VERSION') }}"></script>
<script src="/js/application.js?v={{ config('constants.VERSION') }}"></script>
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
