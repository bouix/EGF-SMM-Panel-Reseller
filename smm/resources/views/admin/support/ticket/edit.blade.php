@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Edit Ticket')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/support/tickets') }}"><i class="fa fa-dashboard"></i> @lang('menus.ticket')</a></li>
                <li class="active">@lang('menus.edit')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/support/tickets/'.$ticket->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.ticket_details')</legend>
                        <div class="form-group">
                            <label for="user" class="control-label">@lang('forms.user')</label>
                            <input type="text"
                                   class="form-control"
                                   id="user"
                                   readonly
                                   value="{{ $ticket->user->name }}"
                                   name="user"
                                   data-validation="required">
                        </div>
                        <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="subject" class="control-label">@lang('forms.subject')</label>
                            <input type="text"
                                   class="form-control"
                                   id="subject"
                                   value="{{ $ticket->subject }}"
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
                                    data-validation="required">{{ $ticket->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
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
                                        value="OPEN"
                                        {{ $ticket->status === title_case('OPEN') ? 'selected' : '' }}
                                >Open
                                </option>
                                <option
                                        value="CLOSED"
                                        {{ $ticket->status === title_case('CLOSED') ? 'selected' : '' }}
                                >Close
                                </option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection