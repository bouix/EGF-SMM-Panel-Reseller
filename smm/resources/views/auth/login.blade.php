@extends('layouts.app')
@section('title', getOption('app_name') . ' - Login')
@section('content')
    <style>
        .login-form {
            padding: 20px;
        }

        /* already defined in bootstrap4 */
        .text-xs-center {
            text-align: center;
        }

        .g-recaptcha {
            display: inline-block;
        }
    </style>
    <div class="clearfix" style="height: 25px;"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-form" id="main-login">
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <center><img style="height:50px" src="http://child.easygrowfast.com/images/MxycgeaF5HkHa6GjICf0VUTzyCpihUEaxODNMPwE.png"></center>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email"
                                   type="email"
                                   class="form-control login-field"
                                   placeholder="@lang('forms.email')"
                                   name="email"
                                   value="{{ old('email') }}"
                                   data-validation="email"
                                   autofocus>
                            <label class="login-field-icon fui-user" for="email"></label>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password"
                                   type="password"
                                   class="form-control login-field"
                                   placeholder="@lang('forms.password')"
                                   name="password"
                                   data-validation="required">
                            <label class="login-field-icon fui-lock" for="password"></label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group" style="display: none">
                            <label class="checkbox" for="remember">
                                <input type="checkbox" id="remember" name="remember" data-toggle="checkbox"> @lang('forms.remember_me')
                            </label>
                        </div>
                        @if ($errors->has('email') || $errors->has('g-recaptcha-response'))
                            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                @php
                                    config(["no-captcha.sitekey" => getOption('recaptcha_public_key')]);
                                    config(["no-captcha.secret" => getOption('recaptcha_private_key')]);
                                @endphp
                                <div class="text-xs-center">
                                    {!! Captcha::display() !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                                @endif
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                        <a class="login-link" href="{{ url('/password/reset') }}">@lang('forms.lost_your_password')</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="text-center">
                    {!!  getPageContent('login-page') !!}
                </div>
            </div>
        </div>
    </div><center>
 <a href="https://easygrowfast.com">Powered by EasyGrowFast.Com | Designed by EGF © Copyright 2019, </a><br/></center>
@endsection
@push('scripts')
    {!! Captcha::script() !!}
@endpush