@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Ticket - '.$ticket->subject)
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/support/tickets') }}"><i class="fa fa-dashboard"></i> @lang('menus.ticket')</a></li>
                <li class="active">@lang('menus.show')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @lang('general.status'):
            <span class="label label-{{ ($ticket->status === title_case('OPEN') ) ? 'success' : 'danger'  }}">
                {{ $ticket->status }}
            </span>
            <h4>{{ $ticket->subject }}</h4>
            <p>
                {{ $ticket->description }}
            </p>
            Ticket By: <span class="label label-default">{{ $ticket->user->name }}</span>
            <div id="messages-container">
                @if( ! $ticketMessages->isEmpty() )
                    @foreach($ticketMessages as $ticketMessage)
                        <div class="panel panel-default panel-custom-bordered">
                            <div class="panel-heading">
                                <strong>{{ $ticketMessage->user->name }}</strong>
                                &nbsp;<span class="text-muted">{{ $ticketMessage->created_at }}</span>
                            </div>
                            <div class="panel-body">
                                {!! nl2br(e($ticketMessage->content)) !!}
                            </div><!-- /panel-body -->
                        </div><!-- /panel panel-default -->
                    @endforeach
                @else
                    <p>
                        @lang('general.no_messages')
                    </p>
                @endif
            </div>
            @if($ticket->status === 'Open')
                <div class="panel panel-default panel-custom-bordered">
                    <div class="panel-heading">
                        @lang('general.new_message')
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal"
                              role="form"
                              method="POST"
                              action="{{ url('/admin/support/'.$ticket->id.'/message') }}">

                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <textarea
                                            class="form-control"
                                            id="content"
                                            name="content"
                                            rows="4"
                                            data-validation="required"></textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <button class="btn btn-block btn-primary" type="submit">@lang('buttons.send')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection