@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Orders')
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div id="api-status-msg" class="alert no-auto-close " style="position: relative;display: none">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  mydatatable table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('general.order_id')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.api')</th>
                                <th style="width: 50%">@lang('general.response')</th>
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
                ajax: '{!! url('/admin/automate/response-logs-index/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'api.name', name: 'api.name', orderable:false},
                    {data: 'response', name: 'response', orderable:false}
                ]
            });
        })
    </script>
@endpush