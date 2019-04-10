@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Tickets')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mydatatable table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.ticket_id')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.user')</th>
                                <th>@lang('general.subject')</th>
                                <th>@lang('general.description')</th>
                                <th>@lang('general.new_messages')</th>
                                <th>@lang('general.status')</th>
                                <th class="text-center">@lang('general.action')</th>
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
                ajax: '{!! url('admin/orders-index/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'subject', name: 'subject',sortable:false},
                    {data: 'description', name: 'description', sortable:false},
                    {data: 'unread_message_count', name: 'unread_message_count', sortable:false, searchable:false},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', sortable:false, searchable:false}
                ]
            });
        })
    </script>
@endpush