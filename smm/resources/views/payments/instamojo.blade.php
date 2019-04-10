@extends('layouts.app')
@section('title', getOption('app_name') . ' - Instamojo')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/payment/add-funds') }}"> @lang('menus.payment_methods')</a></li>
                <li class="active">Instamojo</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <div style="text-align: left;">
                    <img src="{{ url('/img/instamojo.png') }}">
                </div>
                <form id="payment-form"
                      role="form"
                      method="POST"
                      action="{{ url('/payment/add-funds/instamojo') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <div class="form-group">
                            <p id="card-errors" style="color:red"></p>
                        </div>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="amount">@lang('forms.amount') ({{ getOption('currency_code') }})</label>
                            <input class="form-control"
                                   name="amount"
                                   id="amount"
                                   data-validation="number"
                                   type="text">
                            @if ($errors->has('amount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="name">@lang('forms.name')</label>
                            <input class="form-control"
                                   name="name"
                                   id="name"
                                   data-validation="required"
                                   type="text">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="email">@lang('forms.email')</label>
                            <input class="form-control"
                                   name="email"
                                   id="email"
                                   data-validation="email"
                                   type="text">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="email">@lang('forms.mobile_number')</label>
                            <input class="form-control"
                                   name="phone"
                                   id="phone"
                                   data-validation="required"
                                   type="text">
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group" style="margin-top:15px;">
                            <button type="submit" class="btn btn-primary btn-block submit-btn">@lang('buttons.pay')</button>
                        </div>
                    </fieldset>
                </form>
                <p class="text-center"><small>@lang('forms.transaction_fee')</small></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!!  getPageContent('instamojo-page') !!}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function () {
        $.validate({
            form: '#payment-form',
            modules: 'date',
            validateOnBlur: false,
            onSuccess: function (e) {
                $('.submit-btn')
                    .attr('disabled',true)
                    .empty()
                    .html(spinner);
            }
        });
    });
</script>
@endpush