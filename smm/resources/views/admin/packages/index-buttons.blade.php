<a href="{{ url('/admin/packages/'.$id.'/edit') }}"
   class="btn btn-xs btn-primary"
   title="Edit"><span class="fui-new"></span></a>
<form method="POST"
      action="{{url('/admin/packages/'.$id)}}"
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