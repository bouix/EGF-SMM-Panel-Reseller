@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - ' .  title_case(str_replace('-', ' ', $page->slug)))
@section('content')
    <div class="row">
        <div class="col-md-12">

            {!! $page->content !!}

        </div>
    </div>
@endsection