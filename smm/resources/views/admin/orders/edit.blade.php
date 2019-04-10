@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Edit Orders')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/orders') }}"><i class="fa fa-dashboard"></i> @lang('menus.orders')</a></li>
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
                        action="{{ url('/admin/orders/'.$order->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.order_detail')</legend>
                        <div class="form-group">
                            <label class="control-label">@lang('general.order_id')</label>
                            <input type="text" name="name" class="form-control" value="{{ $order->id }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.user')</label>
                            <input type="text" name="name" class="form-control" value="{{ $order->user->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.service')</label>
                            <input type="text" name="service" class="form-control" value="{{ $order->package->service->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.package')</label>
                            <input type="text" name="package_id" class="form-control" value="{{ $order->package->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.quantity')</label>
                            <input type="text" name="quantity" class="form-control" value="{{ $order->quantity }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.order_source')</label>
                            <input type="text" name="order_source" class="form-control" value="{{ $order->source }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.total')</label>
                            <input type="text" name="price" class="form-control" value="{{ getOption('currency_symbol') . $order->price }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.date')</label>
                            <input type="text" name="date" class="form-control" value="{{ $order->created_at }}" readonly>
                        </div>
                        @if((bool)$order->package->custom_comments)
                            <div class="form-group">
                                <label class="control-label">@lang('forms.custom_comments')</label>
                                <textarea class="form-control"
                                          name="custom_comments"
                                          id="custom_comments"
                                          style="height: 150px;">{{ $order->custom_comments }}</textarea>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">@lang('forms.link')</label>
                            <input type="text" name="link" class="form-control" value="{{ $order->link }}" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('forms.start_counter')</label>
                            <input type="text" name="start_counter" class="form-control" value="{{ $order->start_counter }}" data-validation="number" data-validation-optional="true">
                        </div>
                        <div class="form-group">
                            <label class="control-label">@lang('general.remains')</label>
                            <input type="text" name="remains" class="form-control" value="{{ $order->remains }}" data-validation="number" data-validation-optional="true">
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">@lang('forms.status')</label>
                            @php
                                $disabled = '';
                                if(in_array(strtoupper($order->status),['COMPLETED','PARTIAL','REFUNDED','CANCELLED'])){
                                    $disabled = 'disabled';
                                }
                            @endphp
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="status"
                                    {{ $disabled }}
                                    name="status">
                                <option
                                        value="PENDING"
                                        {{ $order->status === title_case('PENDING') ? 'selected' : '' }}
                                >PENDING
                                </option>
                                <option
                                        value="INPROGRESS"
                                        {{ $order->status === title_case('INPROGRESS') ? 'selected' : '' }}
                                >INPROGRESS
                                </option>
                                <option
                                        value="PROCESSING"
                                        {{ $order->status === title_case('PROCESSING') ? 'selected' : '' }}
                                >PROCESSING
                                </option>
                                <option
                                        value="PARTIAL"
                                        {{ $order->status === title_case('PARTIAL') ? 'selected' : '' }}
                                >PARTIAL
                                </option>
                                <option
                                        value="COMPLETED"
                                        {{ $order->status === title_case('COMPLETED') ? 'selected' : '' }}
                                >COMPLETED
                                </option>
                                <option
                                        value="REFUNDED"
                                        {{ $order->status === title_case('REFUNDED') ? 'selected' : '' }}
                                >REFUNDED
                                </option>
                                <option
                                        value="CANCELLED"
                                        {{ $order->status === title_case('CANCELLED') ? 'selected' : '' }}
                                >CANCELLED
                                </option>

                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="preferred_api_id" class="control-label">@lang('forms.api')</label>
                            <select class="form-control" name="api_id" id="api_id">
                                <option value="">Select</option>
                                @if(! $apis->isEmpty()):
                                @foreach ($apis as  $api):
                                <option value="{{$api->id}}" {{ ($api->id == $order->api_id) ? 'selected' : '' }}>{{$api->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @if (!in_array(strtoupper($order->status), ['COMPLETED', 'PARTIAL', 'REFUNDED', 'CANCELLED']))
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                            </div>
                        @endif
                    </fieldset>
                </form>
            </div>
        </div>
@endsection