<div class="btn-group">
    <a type="button"
       href="{{ url('/admin/support/tickets/'. $id) }}"
       class="btn btn-xs btn-default">
        <span class="fui-eye"></span>
    </a>
    <a type="button"
       href="{{ url('/admin/support/tickets/'. $id . '/edit') }}"
       class="btn btn-xs btn-primary">
        <span class="fui-new"></span>
    </a>
    <form style="display: inline" method="POST"
          action="{{url('/admin/support/tickets/'.$id)}}"
          accept-charset="UTF-8" class="form-inline">
        <input name="_method" type="hidden" value="DELETE">
        {{csrf_field()}}
        <button class="btn btn-xs btn-danger btn-delete-record"
                type="button">
            <span class="fui-trash"></span>
        </button>
    </form>
</div>