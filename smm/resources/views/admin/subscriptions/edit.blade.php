@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - New Order' )
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/subscriptions') }}"><i class="fa fa-dashboard"></i> @lang('menus.subscriptions')</a></li>
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
                        action="{{ url('/admin/subscriptions/'.$subscription->id) }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit') @lang('general.subscription')</legend>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.service')</label>
                            <input type="text" name="service" class="form-control" value="{{ $subscription->package->service->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.package')</label>
                            <input type="text" name="package_id" class="form-control" value="{{ $subscription->package->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.quantity')</label>
                            <input type="text" name="quantity" class="form-control" value="{{ $subscription->quantity }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('general.posts')</label>
                            <input type="text" name="posts" class="form-control" value="{{ $subscription->posts }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.total')</label>
                            <input type="text" name="price" class="form-control" value="{{ getOption('currency_symbol') . $subscription->price }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.date')</label>
                            <input type="text" name="date" class="form-control" value="{{ $subscription->created_at }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.status')</label>
                            <input type="text" name="date" class="form-control" value="{{ $subscription->status }}" readonly>
                        </div>
                        <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                            <label for="link" class="control-label">@lang('forms.link')</label>
                            <input name="link"
                                   id="link"
                                   value="{{ $subscription->link }}"
                                   type="text"
                                   data-validation="required"
                                   class="form-control"
                                   placeholder="">
                            @if ($errors->has('link'))
                                <span class="help-block">
                                <strong>{{ $errors->first('link') }}</strong>
                            </span>
                            @endif
                        </div>
                        @if (in_array(strtoupper($subscription->status), ['ACTIVE', 'PENDING']))
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                            </div>
                        @endif
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection

