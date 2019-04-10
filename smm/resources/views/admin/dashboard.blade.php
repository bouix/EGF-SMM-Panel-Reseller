@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Dashboard')
@section('content')
    <link rel="stylesheet" href="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <style>
        .btn-default {
            color: #444;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #fff;
        }
    </style>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.total_earning')</h3>
                        <h3>{{ getOption('currency_symbol') . number_format($totalSell,2, getOption('currency_separator'), '') }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.open_support_tickets')</h3>
                        <h3>{{ $supportTicketOpen }}</h3>
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
                        <h3 class="tile-title">@lang('general.pending_orders')</h3>
                        <h3>{{ $totalOrdersPending }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.orders_inprogress')</h3>
                        <h3>{{ $totalOrdersInProgress }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.total_orders_completed')</h3>
                        <h3>{{ $totalOrdersCompleted }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.cancelled_orders')</h3>
                        <h3>{{ $totalOrdersCancelled }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.total_orders')</h3>
                        <h3>{{ $totalOrders }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tile">
                        <h3 class="tile-title">@lang('general.total_users')</h3>
                        <h3>{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default panel-custom-bordered">
                <div class="panel-heading">
                    @lang('general.note_to_users')
                </div>
                <div class="panel-body note-from-admin-body" style="min-height: 260px;">
                    <form method="post" action="{{ url('/admin/note') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="admin_note" class="form-control" rows="6" id="admin_note">{{ getOption('admin_note',true) }}</textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v={{ config('constants.VERSION') }}"></script>
    <script>
        $(function () {
            //bootstrap WYSIHTML5 - text editor
            $("#admin_note").wysihtml5({
                toolbar: {
                    "font-styles": false, // Font styling, e.g. h1, h2, etc.
                    "emphasis": true, // Italics, bold, etc.
                    "lists": false, // (Un)ordered lists, e.g. Bullets, Numbers.
                    "html": false, // Button which allows you to edit the generated HTML.
                    "link": false, // Button to insert a link.
                    "image": false, // Button to insert an image.
                    "color": true, // Button to change color of font
                    "blockquote": false, // Blockquote
                    "size": 'sm'
                }
            });
        })
    </script>
@endpush