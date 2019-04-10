@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    @php
        $status = $status ?? false;
        $dataURL = $status ? "/admin/subscriptions-filter-ajax/$status/data" : "/admin/subscriptions-index/data";
    @endphp
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 15px;">
            <div class="btn-group">
                <a href="{{ url('/admin/subscriptions/') }}" class="btn btn-default btn-sm btn btn-default {{ $status == false ? 'active' : '' }}">ALL</a>
                <a href="{{ url('/admin/subscriptions-filter/pending') }}" class="btn btn-default btn-sm {{ $status == 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ url('/admin/subscriptions-filter/active') }}" class="btn btn-default btn-sm {{ $status == 'active' ? 'active' : '' }}">Active</a>
                <a href="{{ url('/admin/subscriptions-filter/completed') }}" class="btn btn-default btn-sm {{ $status == 'completed' ? 'active' : '' }}">Completed</a>
                <a href="{{ url('/admin/subscriptions-filter/stopped') }}" class="btn btn-default btn-sm {{ $status == 'stopped' ? 'active' : '' }} ">Stopped</a>
                <a href="{{ url('/admin/subscriptions-filter/cancelled') }}" class="btn btn-default btn-sm {{ $status == 'cancelled' ? 'active' : '' }} ">Cancelled</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table mydatatable table-bordered table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.order_id')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.package')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.amount')</th>
                                <th>@lang('general.quantity')</th>
                                <th>@lang('general.posts')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.status')</th>
                                <th>@lang('general.action')</th>
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
                    {data: 'posts', name: 'posts', sortable:false, searchable:false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', sortable:false, searchable:false}
                ]
            });

            $(document).on("click", ".btn-cancel-record", function (e) {
                var $el = $(this);
                bootbox.confirm({
                    message: "Are you sure to cancel subscription?",
                    buttons: {
                        cancel: {
                            label: 'No',
                            className: 'btn-default'
                        },
                        confirm: {
                            label: 'Yes',
                            className: 'btn-danger'
                        },
                    },
                    callback: function (result) {
                        if (result) {
                            $el.parents('form').submit();
                        }
                    }
                });
            });

            $(document).on("click", ".btn-stop-subscription", function (e) {
                console.log('e');
                var $el = $(this);
                bootbox.confirm({
                    message: "Are you sure to stop subscription?",
                    buttons: {
                        cancel: {
                            label: 'No',
                            className: 'btn-default'
                        },
                        confirm: {
                            label: 'Yes',
                            className: 'btn-danger'
                        },
                    },
                    callback: function (result) {
                        if (result) {
                            $el.parents('form').submit();
                        }
                    }
                });
            })
        })
    </script>
@endpush