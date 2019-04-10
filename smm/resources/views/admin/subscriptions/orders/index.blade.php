@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/subscriptions') }}"><i class="fa fa-dashboard"></i> @lang('menus.subscriptions')</a></li>
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
    @if(!in_array(strtoupper($subscription->status),['CANCELLED','COMPLETED','STOPPED']))
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-form">
                    <form
                            role="form"
                            method="POST"
                            action="{{ url('/admin/subscriptions/'.$subscription->id.'/order') }}">
                        {{ csrf_field() }}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">@lang('general.add') @lang('forms.order')</legend>
                            <span class="help-block" style="margin-top: 0; padding-top:0">@lang('general.create_when_completed')</span>
                            <div class="form-group">
                                <label for="link" class="control-label">@lang('forms.link')</label>
                                <input type="text" for="link" name="link" class="form-control" value="{{ old('link') }}" data-validation="required">
                            </div>
                            <div class="form-group">
                                <label for="start_counter" class="control-label">@lang('forms.start_counter')</label>
                                <input type="text" id="start_counter" name="start_counter" class="form-control" value="{{ old('start_counter') }}" data-validation="number">
                            </div>
                            <div class="form-group">
                                <label for="remains" class="control-label">@lang('general.remains')</label>
                                <input type="text" id="remains" name="remains" class="form-control" value="{{ old('remains') }}" data-validation="number">
                            </div>
                            <div class="form-group">
                                <button type="submit" id="btn-proceed" class="btn btn-primary">@lang('buttons.create')</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection