@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Payment Method '.$paymentMethod->name)
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/payment-methods') }}"> @lang('menus.payment_methods')</a></li>
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
                        action="{{ url('/admin/payment-methods/'.$paymentMethod->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit_payment_method')</legend>
                        <div class="form-group">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $paymentMethod->name }}"
                                   readonly
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
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
                                        value="ACTIVE"
                                        {{ strtoupper($paymentMethod->status) == 'ACTIVE' ? 'selected' : '' }}
                                >ACTIVE
                                </option>
                                <option
                                        value="INACTIVE"
                                        {{ strtoupper($paymentMethod->status) == 'INACTIVE' ? 'selected' : '' }}
                                >INACTIVE
                                </option>
                            </select>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        @if(! $configOptions->isEmpty())
                            @foreach($configOptions as $row)
                                <div class="form-group">
                                    <label  class="control-label">{{ $row->config_key }}</label>
                                    <input type="hidden" name="config_key[]" value="{{ $row->config_key }}">
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $row->config_value }}"
                                           data-validation="required"
                                           name="config_value[]">
                                </div>
                            @endforeach
                        @endif
                        <div class="form-group">
                            <input type="checkbox"
                                   name="is_disabled_default"
                                   id="is_disabled_default"
                                   {{ ($paymentMethod->is_disabled_default == 1) ? 'checked' : '' }}
                                   value="1">
                            <label class="control-label" for="is_disabled_default">@lang('forms.disable_for_new_users')</label>
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