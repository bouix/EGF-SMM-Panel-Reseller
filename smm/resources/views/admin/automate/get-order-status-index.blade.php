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
                                <th>Api @lang('general.order_id')</th>
                                <th>@lang('general.user')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.package')</th>
                                <th>@lang('general.link')</th>
                                <th>@lang('general.quantity')</th>
                                <th>@lang('general.start_counter')</th>
                                <th>@lang('general.remains')</th>
                                <th>@lang('general.api')</th>
                                <th width="17%">@lang('general.action')</th>
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
            ajax: '{!! url('/admin/automate/get-status-index/data') !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'api_order_id', name: 'api_order_id'},
                {data: 'user.name', name: 'user.name'},
                {data: 'package.service.name', name: 'package.service.name'},
                {data: 'package.name', name: 'package.name'},
                {data: 'link', name: 'link'},
                {data: 'quantity', name: 'quantity', sortable:false},
                {data: 'start_counter', name: 'start_counter', sortable:false},
                {data: 'remains', name: 'remains', sortable:false},
                {data: 'api.name', name: 'api.name', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });


        $(document).on('click', '.get-status', function () {

            var id = $(this).data('id');
            var button = $(this);
            var tr = $(this).parents('tr');
            button
                .find('span')
                .text('')
                .addClass('glyphicon glyphicon-refresh spinning');
            $('.get-status, .change-reseller').attr('disabled', 'disabled');

            $.ajax({
                url: baseUrl + '/admin/automate/get-status-from-api',
                type: 'POST',
                data: {'id': id},
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
                            .text('@lang("buttons.get_status")')
                            .removeClass('glyphicon glyphicon-refresh spinning');
                    }
                },
                complete: function () {
                    $('.get-status, .change-reseller').removeAttr('disabled');
                }
            });

        });


        $(document).on('click','.change-reseller',function(){
            var id = $(this).data('id');
            var button = $(this);
            var tr = $(this).parents('tr');
            button
                .find('span')
                .text('')
                .addClass('glyphicon glyphicon-refresh spinning');
            $('.get-status, .change-reseller').attr('disabled', 'disabled');

            $.ajax({
                url: baseUrl + '/admin/automate/change-reseller',
                type: 'POST',
                data: {'id': id},
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
                            .text('@lang("buttons.change_reseller")')
                            .removeClass('glyphicon glyphicon-refresh spinning');
                    }
                },
                complete: function () {
                    $('.get-status, .change-reseller').removeAttr('disabled');
                }
            });
        });

    });
</script>
@endpush