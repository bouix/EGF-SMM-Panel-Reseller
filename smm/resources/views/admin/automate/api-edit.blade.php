@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Edit API')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/automate/api-list') }}"> @lang('menus.api_list')</a></li>
                <li class="active">@lang('menus.edit')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/automate/api/'.$api->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit_api')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $api->name }}"
                                   data-validation="required"
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="fieldset-outline">
                            <h6>@lang('forms.order_place_api')</h6>
                            <div class="form-group{{ $errors->has('order_end_point') ? ' has-error' : '' }}">
                                <label for="order_end_point" class="control-label">@lang('forms.api_url')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->order_end_point }}"
                                       data-validation="url"
                                       id="order_end_point"
                                       name="order_end_point">
                                @if ($errors->has('order_end_point'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('order_end_point') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('order_method') ? ' has-error' : '' }}">
                                <label for="order_method" class="control-label">HTTP Method</label>
                                <select class="form-control" style="width:auto" name="order_method" id="order_method">
                                    <option value="POST" {{ ($api->order_method === "POST") ? 'selected' : '' }}>POST</option>
                                    <option value="GET" {{ ($api->order_method === "GET") ? 'selected' : '' }}>GET</option>
                                </select>
                                @if ($errors->has('order_method'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('order_method') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('order_request_body') ? ' has-error' : '' }}">
                                <label for="order_request_body" class="control-label">@lang('forms.request_parameters')</label>
                                <table class="table table-bordered" id="tbl-order-request">
                                    <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Parameter Type</th>
                                        <th>Value</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( ! $apiRequestParams->isEmpty() )
                                        @foreach($apiRequestParams as $row)

                                            @if($row->api_type === "status")
                                                @continue
                                            @endif
                                            <tr>
                                                <td>
                                                    <input type="text" name="order_key[]" value="{{ $row->param_key }}" class="form-control order-key" data-validation="required">
                                                </td>
                                                <td>
                                                    <select name="order_key_type[]" class="form-control order-key-type" data-validation="required">
                                                        <option value="table_column" {{ ($row->param_type === "table_column") ? 'selected' : '' }}>Order Column</option>
                                                        <option value="custom" {{ ($row->param_type === "custom") ? 'selected' : '' }}>Custom Value</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($row->param_type === "table_column")
                                                        <select name="order_key_value[]" class="form-control order-key-value" data-validation="required">
                                                            <option value="id" {{ ($row->param_value ==='id') ? 'selected' : '' }} >id</option>
                                                            <option value="link" {{ ($row->param_value ==='link') ? 'selected' : '' }} >link</option>
                                                            <option value="price" {{ ($row->param_value ==='price') ? 'selected' : '' }}>price</option>
                                                            <option value="package_id" {{ ($row->param_value ==='package_id') ? 'selected' : '' }}>package_id</option>
                                                            <option value="start_counter" {{ ($row->param_value ==='start_counter') ? 'selected' : '' }}>start_counter</option>
                                                            <option value="quantity" {{ ($row->param_value ==='quantity') ? 'selected' : '' }} >quantity</option>
                                                            <option value="custom_comments" {{ ($row->param_value ==='custom_comments') ? 'selected' : '' }} >custom_data</option>
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control order-key-value" value="{{ $row->param_value }}" name="order_key_value[]" data-validation="required">
                                                    @endif
                                                </td>
                                                <td>
                                                    <button
                                                            type="button"
                                                            class="btn btn-danger btn-sm order-key-remove">
                                                        <span class="fui-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <button
                                                    type="button"
                                                    class="btn btn-primary btn-sm order-key-add">
                                                <span class="fui-plus"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                                @if ($errors->has('order_key_value'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('order_key_value') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('order_success_response') ? ' has-error' : '' }}">
                                <label for="order_success_response" class="control-label">@lang('forms.success_response')</label>
                                <br>
                                <small>Validate Json here: <a rel="noopener noreferrer" href="https://jsonlint.com/" target="_new">https://jsonlint.com/</a></small>
                                <textarea
                                        class="form-control"
                                        data-validation="required"
                                        id="order_success_response"
                                        style="height: 150px;"
                                        name="order_success_response">{{ $api->order_success_response }}</textarea>
                                @if ($errors->has('order_success_response'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('order_success_response') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('order_id_key') ? ' has-error' : '' }}">
                                <label for="order_id_key" class="control-label">@lang('forms.order_id_key')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->order_id_key }}"
                                       data-validation="required"
                                       style="width:auto"
                                       id="order_id_key"
                                       name="order_id_key">
                                @if ($errors->has('order_id_key'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('order_id_key') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="fieldset-outline">
                            <h6>@lang('forms.order_status_api')</h6>
                            <div class="form-group{{ $errors->has('end_point') ? ' has-error' : '' }}">
                                <label for="status_end_point" class="control-label">@lang('forms.api_url')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->status_end_point }}"
                                       data-validation="url"
                                       id="status_end_point"
                                       name="status_end_point">
                                @if ($errors->has('status_end_point'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('status_end_point') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('status_method') ? ' has-error' : '' }}">
                                <label for="status_method" class="control-label">HTTP Method</label>
                                <select class="form-control" style="width:auto" name="status_method" id="status_method">
                                    <option value="POST" {{ ($api->status_method === "POST") ? 'selected' : '' }}>POST</option>
                                    <option value="GET" {{ ($api->status_method === "GET") ? 'selected' : '' }}>GET</option>
                                </select>
                                @if ($errors->has('status_method'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('status_method') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('status_request_body') ? ' has-error' : '' }}">
                                <label for="status_request_body" class="control-label">@lang('forms.request_parameters')</label>
                                <table class="table table-bordered" id="tbl-status-request">
                                    <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Parameter Type</th>
                                        <th>Value</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( ! $apiRequestParams->isEmpty() )
                                        @foreach($apiRequestParams as $row)

                                            @if($row->api_type === "order")
                                                @continue
                                            @endif
                                            <tr>
                                                <td>
                                                    <input type="text" name="status_key[]" value="{{ $row->param_key }}" class="form-control status-key" data-validation="required">
                                                </td>
                                                <td>
                                                    <select name="status_key_type[]" class="form-control status-key-type" data-validation="required">
                                                        <option value="table_column" {{ ($row->param_type === "table_column") ? 'selected' : '' }}>Order Column</option>
                                                        <option value="custom" {{ ($row->param_type === "custom") ? 'selected' : '' }}>Custom Value</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($row->param_type === "table_column")
                                                        <select name="status_key_value[]" class="form-control status-key-value" data-validation="required">
                                                            <option value="id" {{ ($row->param_value ==='id') ? 'selected' : '' }} >id</option>
                                                            <option value="link" {{ ($row->param_value ==='link') ? 'selected' : '' }} >link</option>
                                                            <option value="price" {{ ($row->param_value ==='price') ? 'selected' : '' }}>price</option>
                                                            <option value="package_id" {{ ($row->param_value ==='package_id') ? 'selected' : '' }}>package_id</option>
                                                            <option value="start_counter" {{ ($row->param_value ==='start_counter') ? 'selected' : '' }}>start_counter</option>
                                                            <option value="quantity" {{ ($row->param_value ==='quantity') ? 'selected' : '' }} >quantity</option>
                                                            <option value="custom_comments" {{ ($row->param_value ==='quantity') ? 'selected' : '' }} >custom_data</option>
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control status-key-value" value="{{ $row->param_value }}" name="status_key_value[]" data-validation="required">
                                                    @endif
                                                </td>
                                                <td>
                                                    <button
                                                            type="button"
                                                            class="btn btn-danger btn-sm status-key-remove">
                                                        <span class="fui-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <button
                                                    type="button"
                                                    class="btn btn-primary btn-sm status-key-add">
                                                <span class="fui-plus"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                                @if ($errors->has('status_key_value'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('status_key_value') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('status_success_response') ? ' has-error' : '' }}">
                                <label for="status_success_response" class="control-label">@lang('forms.success_response')</label>
                                <br>
                                <small>Validate Json here: <a rel="noopener noreferrer" href="https://jsonlint.com/" target="_new">https://jsonlint.com/</a></small>
                                <textarea
                                        class="form-control"
                                        data-validation="required"
                                        id="status_success_response"
                                        style="height: 150px;"
                                        name="status_success_response">{{ $api->status_success_response }}</textarea>
                                @if ($errors->has('status_success_response'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('status_success_response') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('start_counter_key') ? ' has-error' : '' }}">
                                <label for="start_counter_key" class="control-label">@lang('forms.start_counter_key')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->start_counter_key }}"
                                       data-validation="required"
                                       style="width:auto"
                                       id="start_counter_key"
                                       name="start_counter_key">
                                @if ($errors->has('start_counter_key'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('start_counter_key') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('status_key_equal') ? ' has-error' : '' }}">
                                <label for="status_key_equal" class="control-label">@lang('forms.status_key')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->status_key }}"
                                       data-validation="required"
                                       style="width:auto"
                                       id="status_key_equal"
                                       name="status_key_equal">
                                @if ($errors->has('status_key_equal'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('status_key_equal') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('remains_key') ? ' has-error' : '' }}">
                                <label for="remains_key" class="control-label">@lang('forms.remains_key')</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ $api->remains_key }}"
                                       data-validation="required"
                                       style="width:auto"
                                       id="remains_key"
                                       name="remains_key">
                                @if ($errors->has('remains_key'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('remains_key') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="process_all_order" class="control-label">@lang('forms.process_all_order')</label>
                                <span class="help-block" style="margin-top: -5px">
                                    @lang('forms.process_all_order_help_block')
                                </span>
                                <select
                                        class="form-control"
                                        data-validation="required"
                                        style="width:auto"
                                        id="process_all_order"
                                        name="process_all_order">
                                    <option
                                            value="1"
                                            {{ $api->process_all_order == '1' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option
                                            value="0"
                                            {{ $api->process_all_order  == '0' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <form
                                role="form"
                                method="POST"
                                action="{{ url('/admin/automate/api/mapping/'.$api->id) }}">
                            {{ csrf_field() }}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">@lang('forms.api_package_id_mapping')</legend>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>@lang('general.package_id')</th>
                                        <th>@lang('general.service')</th>
                                        <th>@lang('general.name')</th>
                                        <th>Reseller Package ID</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( ! empty($packages) )
                                        @foreach($packages as $package)
                                            <tr>
                                                <td>{{ $package->id }}</td>
                                                <td>{{ $package->service->name }}</td>
                                                <td>{{ $package->name }}</td>
                                                <td>
                                                    <input type="hidden" name="package_id[]" value="{{ $package->id }}">
                                                    <input name="api_package_id[]"
                                                           value="{{ $apiMapping[$package->id] ?? ''}}"
                                                           class="input-sm form-control"
                                                           type="text">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">No Record Found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <br/>
                                    <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var tableColumns = '<select name="order_key_value[]" class="form-control order-key-value" data-validation="required">' +
            '<option value="link">link</option>' +
            '<option value="price">price</option>' +
            '<option value="package_id">package_id</option>' +
            '<option value="start_counter">start_counter</option>' +
            '<option value="quantity">quantity</option>' +
            '<option value="custom_comments">custom_data</option>' +
            '</select>';
        var custom = '<input type="text" class="form-control order-key-value" name="order_key_value[]" data-validation="required">';

        $(function () {
            $('.order-key-remove').on('click', function () {
                if ($('#tbl-order-request > tbody tr').length > 1) {
                    $(this).parents('tr').remove();
                }
            });

            $('.order-key-add').on('click', function () {
                var tr = $('#tbl-order-request tbody tr:last').clone(true, true);
                tr.find('input').val('');

                // Making select box selected
                $(tr).find(".order-key-type").val($('#tbl-order-request tbody tr:last').find(".order-key-type").val());


                $(tr).appendTo('#tbl-order-request > tbody');
            });

            $('.order-key-type').on('change', function () {
                var v = $(this).val();
                td = $(this).parents('td').siblings().eq(1);

                if (v === "table_column") {
                    td.html(tableColumns);
                } else {
                    td.html(custom);
                }
            });


            var tableColumnsStatus = '<select name="status_key_value[]" class="form-control status-key-value" data-validation="required">' +
                '<option value="id">id</option>' +
                '<option value="link">link</option>' +
                '<option value="price">price</option>' +
                '<option value="package_id">package_id</option>' +
                '<option value="start_counter">start_counter</option>' +
                '<option value="quantity">quantity</option>' +
                '<option value="custom_comments">custom_data</option>' +
                '</select>';
            var customStatus = '<input type="text" class="form-control status-key-value" name="status_key_value[]" data-validation="required">';

            $('.status-key-remove').on('click', function () {
                if ($('#tbl-status-request > tbody tr').length > 1) {
                    $(this).parents('tr').remove();
                }
            });

            $('.status-key-add').on('click', function () {
                var tr = $('#tbl-status-request tbody tr:last').clone(true, true);
                tr.find('input').val('');

                // Making select box selected
                $(tr).find(".status-key-type").val($('#tbl-status-request tbody tr:last').find(".status-key-type").val());


                $(tr).appendTo('#tbl-status-request > tbody');
            });

            $('.status-key-type').on('change', function () {
                var v = $(this).val();
                td = $(this).parents('td').siblings().eq(1);

                if (v === "table_column") {
                    td.html(tableColumnsStatus);
                } else {
                    td.html(customStatus);
                }
            });

        });
    </script>
@endpush