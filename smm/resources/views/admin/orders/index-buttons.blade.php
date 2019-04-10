<div class="btn-group">
    <a href="{{ url('/admin/orders/'. $id . '/edit') }}"
       class="btn btn-xs btn-primary">
        <span class="glyphicon glyphicon-pencil"></span>
    </a>
    <form style="display: inline" method="POST"
          action="{{url('/admin/orders/'.$id)}}"
          accept-charset="UTF-8" class="form-inline">
        <input name="_method" type="hidden" value="DELETE">
        {{csrf_field()}}
        <button class="btn btn-xs btn-danger btn-delete-record"
                type="button">
            <span class="glyphicon glyphicon-trash"></span>
        </button>
    </form>
</div>
