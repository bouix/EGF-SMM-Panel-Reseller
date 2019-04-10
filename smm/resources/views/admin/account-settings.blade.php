@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Account')
@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form role="form" method="POST" action="{{ url('/admin/account/password') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.account')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ Auth::user()->name }}"
                                   data-validation="required"
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">@lang('forms.email')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ Auth::user()->email }}"
                                   disabled
                                   id="email">
                        </div>
                        <div class="form-group{{ $errors->has('old') ? ' has-error' : '' }}">
                            <label class="control-label" for="old"> @lang('forms.old')</label>
                            <input type="password"
                                   name="old"
                                   placeholder="@lang('forms.password')"
                                   id="old"
                                   class="form-control"
                                   data-validation="required">
                            @if ($errors->has('old'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('old') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">@lang('forms.new')</label>
                            <input id="password"
                                   type="password"
                                   class="form-control"
                                   placeholder="@lang('forms.password')"
                                   name="password"
                                   data-validation="required">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">@lang('forms.confirm')</label>
                            <input id="password-confirm"
                                   type="password"
                                   class="form-control"
                                   name="password_confirmation"
                                   placeholder="@lang('forms.password')"
                                   data-validation="required">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection