<div class="btn-group">
    @if(in_array(strtoupper($status),['PENDING']))
        <a href="{{ url('/admin/subscriptions/'. $id . '/edit') }}"
           title="Edit Subscription"
           class="btn btn-xs btn-primary">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
        <form style="display: inline" method="POST"
              action="{{url('/admin/subscriptions/'.$id.'/cancel')}}"
              accept-charset="UTF-8" class="form-inline">
            <input name="_method" type="hidden" value="PUT">
            {{csrf_field()}}
            <button class="btn btn-xs btn-danger btn-cancel-record"
                    title="Cancel Subscription"
                    type="button">
                <span class="glyphicon glyphicon-off"></span>
            </button>
        </form>
    @endif
    @if(!in_array(strtoupper($status),['CANCELLED']))
        <a href="{{ url('/admin/subscriptions/'. $id . '/orders') }}"
           title="View orders"
           class="btn btn-xs btn-inverse">
            <span class="glyphicon glyphicon-list-alt"></span>
        </a>
    @endif
    @if(in_array(strtoupper($status),['ACTIVE']))
        <form style="display: inline" method="POST"
              action="{{url('/admin/subscriptions/'.$id.'/stop')}}"
              accept-charset="UTF-8" class="form-inline">
            <input name="_method" type="hidden" value="PUT">
            {{csrf_field()}}
            <button class="btn btn-xs btn-danger btn-stop-subscription"
                    title="Stop Subscription"
                    type="button">
                <span class="glyphicon glyphicon-stop"></span>
            </button>
        </form>
    @endif
</div>
