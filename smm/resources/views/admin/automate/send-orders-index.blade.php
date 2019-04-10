@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Orders')
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div id="api-status-msg" class="alert no-auto-close " style="position: relative;display: none;z-index:5">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mydatatable table-hover" style="width: 99.9%">
                            <thead>
                            <tr>
                                <th>@lang('general.order_id')</th>
                                <th>@lang('general.user')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.package')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.quantity')</th>
                                <th>@lang('general.start_counter')</th>
                                <th>@lang('general.remains')</th>
                                <th>@lang('general.api')</th>
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
                order: [[0, 'desc']],
                ajax: '{!! url('/admin/automate/send-orders-index/data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'package.service.name', name: 'package.service.name'},
                    {data: 'package.name', name: 'package.name'},
                    {data: 'link', name: 'link'},
                    {data: 'quantity', name: 'quantity', orderable:false},
                    {data: 'start_counter', name: 'start_counter', orderable:false},
                    {data: 'remains', name: 'remains', orderable:false},
                    {data: 'api', name: 'api', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $(document).on('click', '.send-api', function () {

                var id = $(this).data('id');
                var package_id = $(this).data('package_id');
                var api_id = $('#api_' + id).val();
                if (api_id === '') {
                    $('#api_' + id).focus();
                    return false;
                }

                var button = $(this);
                var tr = $(this).parents('tr');
                button
                    .find('span')
                    .text('')
                    .addClass('glyphicon glyphicon-refresh spinning');
                $('.send-api').attr('disabled', 'disabled');

                $.ajax({
                    url: baseUrl + '/admin/automate/send-order-to-api',
                    type: 'POST',
                    data: {'id': id, 'api_id': api_id, 'package_id': package_id},
                    dataType: 'JSON',
                    success: function (resp) {
                        $('#api-status-msg')
                            .removeClass('alert-info alert-warning alert-danger')
                            .addClass(resp.css_class)
                            .html(resp.message).show();
                        if (resp.success) {
                            tr.remove();
                        } else {
                            button
                                .find('span')
                                .text('@lang("buttons.send")')
                                .removeClass('glyphicon glyphicon-refresh spinning');
                        }
                    },
                    complete: function () {
                        $('.send-api').removeAttr('disabled');
                    }
                });

            });
        });
    </script>
@endpush