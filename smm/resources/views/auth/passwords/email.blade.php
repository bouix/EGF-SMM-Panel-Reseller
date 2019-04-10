@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div class="container">
        <div class="row">
            @if (session('status'))
                <div class="alert alert-success no-auto-close">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-md-4 col-md-offset-4">
                <div class="clearfix" style="height: 70px;"></div>
                <div class="login-form">
                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <center><img style="height:75px" src="https://www.smmleader.com/king-logo.png"></center>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email"
                                   type="email"
                                   class="form-control"
                                   name="email"
                                   placeholder="@lang('forms.email') "
                                   value="{{ old('email') }}"
                                   data-validation="required">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                @lang('buttons.send_password_reset')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
