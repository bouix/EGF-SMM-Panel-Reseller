@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - View Order')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/orders') }}"><i class="fa fa-dashboard"></i> @lang('menus.orders')</a></li>
                <li class="active">@lang('menus.show')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-form">
                <form class="form-horizontal">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.order_detail')</legend>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('general.order_id')</label>
                            <div class="col-md-9">
                                {{ $order->id }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.user')</label>
                            <div class="col-md-9">
                                {{ $order->user->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.service')</label>
                            <div class="col-md-9">
                                {{ $order->package->service->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.package')</label>
                            <div class="col-md-9">
                                {{ $order->package->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.link')</label>
                            <div class="col-md-9">
                                <a rel="noopener noreferrer" href="{{ getOption('anonymizer').$order->link }}" target="_blank">{{ str_limit($order->link,50) }}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.quantity')</label>
                            <div class="col-md-9">
                                {{ $order->quantity }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.start_counter')</label>
                            <div class="col-md-9">
                                {{ $order->start_counter }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('general.remains')</label>
                            <div class="col-md-9">
                                {{ $order->remains }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.order_source')</label>
                            <div class="col-md-9">
                                {{ $order->source }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.total')</label>
                            <div class="col-md-9">
                                {{ getOption('currency_symbol') . $order->price }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.date')</label>
                            <div class="col-md-9">
                                {{ $order->created_at }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.status')</label>
                            <div class="col-md-9">
                                {{ $order->status }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.api')</label>
                            <div class="col-md-9">
                                @if(! $apis->isEmpty())
                                    @foreach ($apis as  $api)
                                        @if($api->id == $order->api_id)
                                            {{ $api->name }}
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">@lang('forms.custom_comments')</label>
                            <div class="col-md-9">
                                {!! nl2br($order->custom_comments) !!}
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection