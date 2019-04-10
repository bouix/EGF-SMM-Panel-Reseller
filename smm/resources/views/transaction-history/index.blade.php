@extends('layouts.app')
@section('title', getOption('app_name') . ' - My Orders')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table mydatatable table-bordered table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.transaction_id')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.payment_method')</th>
                                <th>@lang('general.amount')</th>
                                <th>@lang('general.details')</th>
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
                ajax: '{!! url('account/funds-load-history-index/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'payment_method.name', name: 'payment_method.name',sortable:false,searchable:false},
                    {data: 'amount', name: 'amount', sortable:false},
                    {data: 'details', name: 'details', sortable:false}
                ]
            });
        })
    </script>
@endpush