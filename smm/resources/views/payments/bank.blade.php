@extends('layouts.app')
@section('title', getOption('app_name') . ' - Bank/Manual/Other')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/payment/add-funds') }}"> @lang('menus.payment_methods')</a></li>
                <li class="active">Bank//Other</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                {{ $details }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!!  getPageContent('bank-account-page') !!}
            </div>
        </div>
    </div>
@endsection