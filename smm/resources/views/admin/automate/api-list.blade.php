@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - API List')
@section('content')
    <div class="row">
        <div class="col-md-12 mtn10">
            <div class="mb10">
                <a href="{{ url('/admin/automate/api/add') }}" class="btn btn-primary btn-sm">@lang('buttons.add_new')</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mydatatable table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Order End Point</th>
                                <th>Status End Point</th>
                                <th class="text-right">@lang('general.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( ! $apis->isEmpty() )
                                @foreach($apis as $api)
                                    <tr>
                                        <td>{{ $api->name }}</td>
                                        <td>{{ $api->order_end_point }}</td>
                                        <td>{{ $api->status_end_point }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/automate/api/'.$api->id.'/edit') }}"
                                               class="btn btn-xs btn-primary"
                                               title="Edit"><span class="fui-new"></span></a>
                                            <form method="POST"
                                                  action="{{url('/admin/automate/api/'.$api->id)}}"
                                                  accept-charset="UTF-8"
                                                  class="form-inline"
                                                  style="display: inline-block">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{csrf_field()}}
                                                <button class="btn btn-danger btn-xs btn-delete-record"
                                                        type="button">
                                                    <span class="fui-trash"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
