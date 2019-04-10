@extends('layouts.app')
@section('title', getOption('app_name'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="heroImage">
                <img src="{{ asset(getOption('banner')) }}">
            </div>
            <div style="height: 25px;">&nbsp;</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <h6 style="margin: 0; text-align: center;">{{ getOption('app_name') }}</h6>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div style="height: 10px;">&nbsp;</div>
            <div class="text-center">{!! getOption('home_page_description')  !!} </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div style="height: 10px;">&nbsp;</div>
            <div class="panel panel-default">
                <div class="panel-body p0">
                    <div class="table-responsive">
                        <table class="table table-bordered services-table">
                            <thead>
                            <tr>
                                <th>@lang('general.package_id')</th>
                                <th>@lang('general.service')</th>
                                <th>@lang('general.name')</th>
                                <th>@lang('general.description')</th>
                                <th>@lang('general.price_per_item') {{ getOption('display_price_per') }}</th>
                                <th>@lang('general.minimum_quantity')</th>
                                <th>@lang('general.maximum_quantity')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( ! empty($packages) )
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->id }}</td>
                                        <td>{{ $package->service->name }}</td>
                                        <td>{{ $package->name }}</td>
                                        <td>{{ $package->description }}</td>
                                        <td>
                                            {{ getOption('currency_symbol') . number_format(($package->price_per_item * getOption('display_price_per')),2, getOption('currency_separator'), '') }}
                                        </td>
                                        <td>{{ $package->minimum_quantity }}</td>
                                        <td>{{ $package->maximum_quantity }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">No Record Found</td>
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