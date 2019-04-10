@php
    $apis = \App\API::all();
@endphp
<select class="form-control" name="api_{{ $id }}" id="api_{{ $id }}">
    <option value="">Select API</option>
    @if (!$apis->isEmpty()):
    @foreach ($apis as $api):
        <option value='{{$api->id}}'>{{$api->name}}</option>
    @endforeach
    @endif
</select>
