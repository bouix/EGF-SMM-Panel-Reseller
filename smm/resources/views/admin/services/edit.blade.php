@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Service ' . $service->name)
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/services') }}"><i class="fa fa-dashboard"></i> @lang('menus.services')</a></li>
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
                        action="{{ url('/admin/services/'.$service->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit_service')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $service->name }}"
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
                                    name="description">{{ $service->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="is_subscription_allowed" class="control-label">@lang('forms.is_subscription_allowed')</label>
                            <select name="is_subscription_allowed" id="is_subscription_allowed" class="form-control">
                                <option value="0" {{ $service->is_subscription_allowed == 0 ? 'selected' : '' }} >No</option>
                                <option value="1" {{ $service->is_subscription_allowed == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">@lang('forms.status')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="status"
                                    name="status">
                                <option
                                        value="ACTIVE"
                                        {{ $service->status === 'ACTIVE' ? 'selected' : '' }}
                                >ACTIVE
                                </option>
                                <option
                                        value="INACTIVE"
                                        {{ $service->status === 'INACTIVE' ? 'selected' : '' }}
                                >INACTIVE
                                </option>
                            </select>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
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