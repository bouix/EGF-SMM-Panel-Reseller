@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - New Category')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/services') }}"><i class="fa fa-dashboard"></i> @lang('menus.services')</a></li>
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
                        action="{{ url('/admin/services/') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.create_service')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="required"
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">@lang('forms.description')</label>
                            <textarea
                                    class="form-control"
                                    data-validation="required"
                                    id="description"
                                    style="height: 150px;"
                                    name="description"></textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="is_subscription_allowed" class="control-label">@lang('forms.is_subscription_allowed')</label>
                            <select name="is_subscription_allowed" id="is_subscription_allowed" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status" class="ontrol-label">@lang('forms.status')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="status"
                                    name="status">
                                <option
                                        value="ACTIVE">Active
                                </option>
                                <option
                                        value="INACTIVE">Inactive
                                </option>
                            </select>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
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