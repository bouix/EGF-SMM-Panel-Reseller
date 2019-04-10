@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - New Package')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/packages') }}"><i class="fa fa-dashboard"></i> @lang('menus.packages')</a></li>
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
                        action="{{ url('/admin/packages') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.create_package')</legend>
                        <div class="form-group{{ $errors->has('service_id') ? ' has-error' : '' }}">
                            <label for="service_id" class="control-label">@lang('forms.service')</label>
                            <select name="service_id"
                                    id="service_id"
                                    class="form-control">
                                @if( ! $services->isEmpty() )
                                    @foreach( $services as $service)
                                        <option value="{{ $service->id }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}
                                        > {{ $service->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('service'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('service') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="required"
                                   id="name"
                                   value="{{ old('name') }}"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('price_per_item') ? ' has-error' : '' }}">
                            <label for="price_per_item" class="control-label">@lang('forms.price')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="number"
                                   placeholder="00.00000"
                                   data-validation-allowing="float"
                                   id="price_per_item"
                                   value="{{ old('price_per_item') }}"
                                   name="price_per_item">
                            @if ($errors->has('price_per_item'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('price_per_item') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('minimum_quantity') ? ' has-error' : '' }}">
                            <label for="minimum_quantity" class="control-label">@lang('forms.minimum_quantity')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="number"
                                   id="minimum_quantity"
                                   value="{{ old('minimum_quantity') }}"
                                   name="minimum_quantity">
                            @if ($errors->has('minimum_quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('minimum_quantity') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('maximum_quantity') ? ' has-error' : '' }}">
                            <label for="maximum_quantity" class="control-label">@lang('forms.maximum_quantity')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="number"
                                   id="maximum_quantity"
                                   value="{{ old('maximum_quantity') }}"
                                   name="maximum_quantity">
                            @if ($errors->has('maximum_quantity'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('maximum_quantity') }}</strong>
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
                                    name="description">{{old('description')}}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="custom_comments" class="control-label">@lang('forms.custom_comments')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="custom_comments"
                                    name="custom_comments">
                                <option
                                        value="0"
                                        {{ old('custom_comments') == '0' ? 'selected' : '' }}>No
                                </option>
                                <option
                                        value="1"
                                        {{ old('custom_comments') == '1' ? 'selected' : '' }}>Yes
                                </option>
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
                                        {{ old('status') == 'ACTIVE' ? 'selected' : '' }}>Active
                                </option>
                                <option
                                        value="INACTIVE"
                                        {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="preferred_api_id" class="control-label">@lang('forms.api')</label>
                            <select class="form-control" name="preferred_api_id" id="preferred_api_id">
                                <option value="">Select</option>
                                @if(! $apis->isEmpty()):
                                @foreach ($apis as  $api):
                                <option value="{{$api->id}}" {{ old('preferred_api_id') == $api->id ? 'selected' : '' }} >{{$api->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.create')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection