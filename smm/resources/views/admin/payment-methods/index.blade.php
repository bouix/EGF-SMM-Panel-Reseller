@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Payment Methods')
@section('content')
    <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table mydatatable table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.slug')</th>
                                    <th>@lang('general.status')</th>
                                    <th class="text-center">@lang('general.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( ! $paymentMethods->isEmpty() )
                                    @foreach($paymentMethods as $paymentMethod)
                                        <tr>
                                            <td>{{ $paymentMethod->name }}</td>
                                            <td>{{ $paymentMethod->slug }}</td>
                                            <td>{{ $paymentMethod->status }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/admin/payment-methods/'.$paymentMethod->id.'/edit') }}"
                                                   class="btn btn-xs btn-primary"
                                                   title="Edit"><span class="fui-new"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">@lang('general.no_record')</td>
                                    </tr>
                                @endif
                                </tbody>
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
                "bPaginate": false,
            });
        })
    </script>
@endpush