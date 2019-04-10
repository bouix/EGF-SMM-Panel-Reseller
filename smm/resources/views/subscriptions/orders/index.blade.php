@extends('layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/subscriptions') }}"><i class="fa fa-dashboard"></i> @lang('menus.subscriptions')</a></li>
                <li class="active">@lang('menus.show')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="panel panel-default">
                <div class="panel-body" style="padding:0">
                    <div class="table-responsive">
                        <table class="table mydatatable table-condensed table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.package')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.amount')</th>
                                <th>@lang('general.quantity')</th>
                                <th>@lang('general.posts')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>{{ $subscription->package->service->name }}</td>
                                <td>{{ $subscription->package->name }}</td>
                                <td><a rel="noopener noreferrer" href="{{getOption('anonymizer') . $subscription->link }}" target="_blank">{{str_limit($subscription->link, 30)}}</a></td>
                                <td>{{ $subscription->price }}</td>
                                <td>{{ $subscription->quantity }}</td>
                                <td>{{ $orders->count() .'/'.$subscription->posts }}</td>
                                <td>{{ $subscription->created_at }}</td>
                                <td><span class="status-{{strtolower($subscription->status)}}">{{ $subscription->status }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>Orders</p>
                    <div class="table-responsive">
                        <table class="table mydatatable table-bordered table-hover " style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.order_id')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.start_counter')</th>
                                <th>@lang('general.remains')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$orders->isEmpty())
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->id}}</td>
                                        <td><a rel="noopener noreferrer" href="{{getOption('anonymizer') . $order->link }}" target="_blank">{{str_limit($order->link, 30)}}</a></td>
                                        <td>{{$order->start_counter}}</td>
                                        <td>{{$order->remains}}</td>
                                        <td>{{$order->created_at}}</td>
                                        <td><span class="status-completed">{{ $order->status }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
