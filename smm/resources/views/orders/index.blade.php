@extends('layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    @php
        $status = $status ?? false;
        $dataURL = $status ? "/orders-filter-ajax/$status/data" : "/orders-index/data";
    @endphp
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 5px">
            <a href="{{ url('/order/new') }}" class="btn btn-primary btn-sm">@lang('buttons.new_order')</a>
            <div class="btn-group pull-right">
                <a href="{{ url('/orders/') }}" class="btn btn-default btn-sm btn btn-default btn-sm {{ $status == false ? 'active' : '' }}">ALL</a>
                <a href="{{ url('/orders-filter/pending') }}" class="btn btn-default btn-sm {{ $status == 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ url('/orders-filter/inprogress') }}" class="btn btn-default btn-sm {{ $status == 'inprogress' ? 'active' : '' }}">In Progress</a>
                <a href="{{ url('/orders-filter/completed') }}" class="btn btn-default btn-sm {{ $status == 'completed' ? 'active' : '' }}">Completed</a>
                <a href="{{ url('/orders-filter/partial') }}" class="btn btn-default btn-sm {{ $status == 'partial' ? 'active' : '' }}">Partial</a>
                <a href="{{ url('/orders-filter/refunded') }}" class="btn btn-default btn-sm {{ $status == 'refunded' ? 'active' : '' }}">Refunded</a>
                <a href="{{ url('/orders-filter/cancelled') }}" class="btn btn-default btn-sm {{ $status == 'cancelled' ? 'active' : '' }} ">Cancelled</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table mydatatable table-bordered table-hover "style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.order_id')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.package')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.amount')</th>
                                <th>@lang('general.quantity')</th>
                                <th>@lang('general.start_counter')</th>
                                <th>@lang('general.remains')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.status')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('.mydatatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [ [0, 'desc'] ],
                ajax: '{!! url($dataURL) !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'package.service.name', name: 'package.service.name'},
                    {data: 'package.name', name: 'package.name'},
                    {data: 'link', name: 'link', sortable:false},
                    {data: 'price', name: 'amount', sortable:false, searchable:false},
                    {data: 'quantity', name: 'quantity', sortable:false, searchable:false},
                    {data: 'start_counter', name: 'start_counter', sortable:false, searchable:false},
                    {data: 'remains', name: 'remains', sortable:false, searchable:false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'}
                ]
            });
        })
    </script>
@endpush