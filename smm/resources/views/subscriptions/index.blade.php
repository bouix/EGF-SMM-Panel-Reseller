@extends('layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="mb10">
                <a href="{{ url('/subscription/new') }}" class="btn btn-primary btn-sm">@lang('buttons.new_order')</a>
            </div>
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
                ajax: '{!! url('/subscriptions-index/data') !!}',
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
        })
    </script>
@endpush