@extends('layouts.app')
@section('title', getOption('app_name') . ' - Settings')
@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                @if(getOption('module_api_enabled') == 1)
                    <form role="form" method="POST" action="{{ url('/account/api') }}">
                        {{ csrf_field() }}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">@lang('forms.api')</legend>
                            <div class="form-group">
                                <label for="api_key" class="control-label">@lang('forms.token')</label>
                                @if( ! is_null( Auth::user()->api_token ))
                                    <input type="text"
                                           class="form-control"
                                           value="{{ Auth::user()->api_token }}"
                                           id="api_key"
                                           onClick="this.setSelectionRange(0, this.value.length)"
                                           readonly
                                           name="api_key"><br>
                                    <button type="submit" class="btn btn-primary btn-sm">@lang('buttons.regenerate')</button>
                                @else
                                    <button type="submit" class="btn btn-primary">@lang('buttons.generate')</button>
                                @endif
                            </div>
                        </fieldset>
                    </form>
                @endif
                <form role="form" method="POST" action="{{ url('/account/password') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.account')</legend>
                        <div class="form-group">
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
                        <div class="form-group">
                            <label for="skype_id" class="control-label">SkypeID</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ Auth::user()->skype_id }}"
                                   disabled
                                   id="skype_id">
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
                                   placeholder="@lang('forms.confirm_password')"
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
    <div class="row">
        <div class="col-md-4 col-md-offset-4 mt10">
            <div class="login-form mt10">
            <form role="form" method="POST" action="{{ url('/account/config') }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <fieldset class="scheduler-border">
                    <div class="form-group">
                        <label for="timezone" class="control-label">Timezone</label>
                        <select name="timezone" id="timezone" class="form-control">
                            @foreach($tzlist as $item)
                                <option value="{{$item}}" {{ ( Auth::user()->timezone == $item) ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
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