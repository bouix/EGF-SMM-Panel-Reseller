@extends('layouts.app')
@section('title', getOption('app_name') . '- Select Payment method')
@section('content')
    @if( ! $paymentMethods->isEmpty() )
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="list-group">
                    <span href="#" class="list-group-item theme-bg" style="color: white">
                        @lang('general.select_payment_method')
                    </span>
                @foreach($paymentMethods as $paymentMethod)
                        <a href="{{ url('/payment/add-funds/'.$paymentMethod->slug) }}" class="list-group-item">{{ $paymentMethod->name }}</a>
                @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!!  getPageContent('add-funds') !!}
            </div>
        </div>
    </div>
@endsection