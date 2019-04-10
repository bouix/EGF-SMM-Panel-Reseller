@extends('layouts.app')
@section('title', getOption('app_name') . ' - Dashboard')
@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.funds_available')</h3>
                        <h3>{{ getOption('currency_symbol') . number_format(Auth::user()->funds,2, getOption('currency_separator'), '') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.total_spent')</h3>
                        <h3>{{ getOption('currency_symbol') . number_format($spentAmount,2, getOption('currency_separator'), '') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.new_messages')</h3>
                        <h3>{{ $unreadMessages }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.order_pending')</h3>
                        <h3>{{ $ordersPending }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.orders_inprogress')</h3>
                        <h3>{{ $ordersInProgress }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.order_completed')</h3>
                        <h3>{{ $ordersCompleted }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.order_cancelled')</h3>
                        <h3>{{ $ordersCancelled }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.open_support_tickets')</h3>
                        <h3>{{ $supportTicketOpen }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-heading">
                    @lang('general.note_from_admin')
                </div>
                <div class="panel-body note-from-admin-body" style="max-height: 250px; height: 250px; overflow:auto;">
                    {!! getOption('admin_note',true) !!}
                </div>
            </div>
        </div>
    </div>
@endsection