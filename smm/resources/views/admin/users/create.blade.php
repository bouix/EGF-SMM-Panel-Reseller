@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - New User')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/users') }}"> @lang('menus.users')</a></li>
                <li class="active">@lang('menus.new')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/users') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.create_user')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   data-validation="required"
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">@lang('forms.email')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   id="email"
                                   data-validation="email"
                                   name="email">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="skype_id" class="control-label">SkypeID</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ old('skype_id') }}"
                                   id="skype_id"
                                   name="skype_id">
                        </div>
                        <div class="form-group{{ $errors->has('funds') ? ' has-error' : '' }}">
                            <label for="funds" class="control-label">@lang('forms.funds')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ old('funds') }}"
                                   id="funds"
                                   data-validation="number"
                                   data-validation-allowing="float"
                                   name="funds">
                            @if ($errors->has('funds'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('funds') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="role" class="control-label">@lang('forms.role')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="role"
                                    name="role">
                                <option
                                        value="USER">USER
                                </option>
                                <option
                                        value="ADMIN">ADMIN
                                </option>
                            </select>
                            @if ($errors->has('role'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">@lang('forms.status')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="status"
                                    name="status">
                                <option
                                        value="ACTIVE">Active
                                </option>
                                <option
                                        value="DEACTIVATED">Deactivate
                                </option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">@lang('forms.password')</label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   data-validation="required"
                                   name="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="payment_methods" class="control-label">@lang('forms.payment_method')</label>
                            <br>
                            @if( ! $paymentMethods->isEmpty())
                                @foreach($paymentMethods as $method)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" style="margin: 0; margin-left: -20px" name="payment_methods[]" value="{{ $method->id }}">{{ $method->name }}
                                    </label>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.create')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
@endsection