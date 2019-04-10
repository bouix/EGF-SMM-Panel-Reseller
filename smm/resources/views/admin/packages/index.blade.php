@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Packages')
@section('content')
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="mb10">
                <a href="{{ url('/admin/packages/create') }}" class="btn btn-primary btn-sm">@lang('buttons.create_new')</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table mydatatable table-bordered table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.package_id')</th>
                                <th>@lang('general.name')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.description')</th>
                                <th>@lang('general.price_per_item') {{ getOption('display_price_per') }}</th>
                                <th>@lang('general.minimum_quantity')</th>
                                <th>@lang('general.maximum_quantity')</th>
                                <th>@lang('general.status')</th>
                                <th class="text-center" width="5%">@lang('general.action')</th>
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
                ajax: '{!! url('admin/packages-index/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name', sortable:false},
                    {data: 'service.name', name: 'service.name'},
                    {data: 'description', name: 'description', sortable:false},
                    {data: 'price_per_item', name: 'price_per_item', sortable:false, searchable:false},
                    {data: 'minimum_quantity', name: 'minimum_quantity', sortable:false, searchable:false},
                    {data: 'maximum_quantity', name: 'maximum_quantity', sortable:false, searchable:false},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })
    </script>
@endpush