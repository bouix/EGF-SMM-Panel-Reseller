@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Users')
@section('content')
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="mb10">
                <a href="{{ url('/admin/users/create') }}" class="btn btn-primary btn-sm">@lang('buttons.create_new')</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mydatatable table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.user_id')</th>
                                <th>@lang('general.name')</th>
                                <th>@lang('general.email')</th>
                                <th>SkypeID</th>
                                <th>@lang('general.funds')</th>
                                <th>@lang('general.role')</th>
                                <th>@lang('general.status')</th>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.last_login')</th>
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
                ajax: '{!! url('admin/users-ajax/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'skype_id', name: 'skype_id'},
                    {data: 'funds', name: 'funds'},
                    {data: 'role', name: 'role'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'last_login', name: 'last_login'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })
    </script>
@endpush