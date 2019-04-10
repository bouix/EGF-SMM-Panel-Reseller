@extends('layouts.app')
@section('title', getOption('app_name') . ' - Stripe')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/payment/add-funds') }}"> @lang('menus.payment_methods')</a></li>
                <li class="active">@lang('menus.stripe')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <div style="text-align: right;">
                    <img src="/img/powered_by_stripe.png">
                </div>
                <form id="payment-form"
                      role="form"
                      method="POST"
                      action="{{ url('/payment/add-funds/stripe') }}">
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
                        <div class="form-group" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="card-holder-name">@lang('forms.name_on_card')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="required"
                                   id="card-holder-name"
                                   autocomplete="off">
                        </div>
                        <div class="form-group" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="card-number">@lang('forms.card_number')</label>
                            <input type="text"
                                   class="form-control"
                                   data-validation="required"
                                   id="card-number"
                                   autocomplete="off"
                                   size="20"
                                   maxlength="20">
                        </div>
                        <div class="form-group" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="expiry-month">@lang('forms.expiration')</label>
                            <div class="row">
                                <div class="col-xs-3">
                                    <input type="text"
                                           placeholder="MM"
                                           class="form-control"
                                           data-validation="required"
                                           autocomplete="off"
                                           size="2"
                                           maxlength="2"
                                           id="expiry-month">
                                </div>
                                <div class="col-xs-9" style="padding-left:0">
                                    <input type="text"
                                           placeholder="YYYY"
                                           autocomplete="off"
                                           data-validation="required"
                                           maxlength="4"
                                           class="form-control"
                                           id="expiry-year">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="padding-bottom: 0;margin-bottom:0">
                            <label class=" control-label" for="cvc">@lang('forms.cvs')</label>
                            <input type="text"
                                   class="form-control"
                                   autocomplete="off"
                                   size="3"
                                   data-validation="required"
                                   maxlength="3"
                                   id="cvc">
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <button type="submit" class="btn btn-primary btn-block submit-btn"><span>@lang('buttons.pay')</span></button>
                        </div>
                    </fieldset>
                </form>
                <p class="text-center"><small>@lang('forms.stripe_fee')</small></p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
{{--Stripe details--}}
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    Stripe.setPublishableKey("{{ $stripe_key }}");
</script>
<script type="text/javascript" src="/js/jquery.payment.js"></script>
<script type="text/javascript" src="/js/stripe-process-card.js"></script>
@endpush