@extends('layouts.app')
@section('title', getOption('app_name') . ' - Paytm')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/payment/add-funds') }}"> @lang('menus.payment_methods')</a></li>
                <li class="active">Paytm</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form id="payment-form"
                      role="form"
                      method="POST"
                      action="{{ url('/payment/add-funds/paytm') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <div class="form-group">
                            <p id="card-errors" style="color:red"></p>
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="transaction_id">TransactionID</label>
                            <input class="form-control"
                                   name="transaction_id"
                                   id="transaction_id"
                                   data-validation="required"
                                   type="text">
                            @if ($errors->has('transaction_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('transaction_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}" style="padding-bottom: 0;margin-bottom:0">
                            <label class="control-label" for="amount">@lang('forms.amount')</label>
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
                        <div class="form-group" style="margin-top:15px;">
                            <button type="submit" class="btn btn-primary btn-block submit-btn">@lang('buttons.pay')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!!  getPageContent('paytm-page') !!}
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