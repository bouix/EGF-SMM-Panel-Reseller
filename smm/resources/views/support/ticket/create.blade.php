@extends('layouts.app')
@section('title', getOption('app_name') . ' - New Ticket')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/support') }}">@lang('menus.ticket')</a></li>
                <li class="active">@lang('menus.new')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form role="form" method="POST" action="{{ url('/support/ticket/store') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.new_ticket')</legend>
                        <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="subject" class="control-label">@lang('forms.subject')</label>
                            <input type="text"
                                   class="form-control"
                                   id="subject"
                                   value="{{ old('subject') }}"
                                   name="subject"
                                   data-validation="required">
                            @if ($errors->has('subject'))
                                <span class="help-block">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">@lang('forms.description')</label>
                            <textarea
                                    class="form-control"
                                    id="description"
                                    name="description"
                                    style="height: 150px;"
                                    rows="10"
                                    data-validation="required">{{ old('subject') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">@lang('buttons.create')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection