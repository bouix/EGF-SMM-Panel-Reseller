@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Page Edit')
@section('content')
    <link rel="stylesheet" href="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?v={{ config('constants.VERSION') }}">
    <style>
        .btn-default {
            color: #444;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #fff;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/pages') }}"><i class="fa fa-dashboard"></i>@lang('menus.pages')</a></li>
                <li class="active">@lang('menus.edit')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/page-edit/'.$page->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit_page')</legend>
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <input type="text"
                                   class="form-control"
                                   value="{{ $page->slug }}"
                                   readonly
                                   id="slug"
                                   name="slug">
                        </div>
                        <div class="form-group{{ $errors->has('meta_tags') ? ' has-error' : '' }}">
                            <textarea
                                    style="height: 150px;"
                                    class="form-control"
                                    data-validation="required"
                                    rows="5"
                                    id="meta_tags"
                                    placeholder="Meta Tags"
                                    name="meta_tags">{{ $page->meta_tags }}</textarea>
                            @if ($errors->has('meta_tags'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('meta_tags') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <textarea
                                    style="height: 350px"
                                    name="content"
                                    id="content"
                                    data-validation="required"
                                    class="form-control">{{ $page->content }}</textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js?v={{ config('constants.VERSION') }}"></script>
<script>
    $(function () {
        //bootstrap WYSIHTML5 - text editor
        $("#content").wysihtml5({
            toolbar: {
                "font-styles": true, // Font styling, e.g. h1, h2, etc.
                "emphasis": true, // Italics, bold, etc.
                "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
                "html": true, // Button which allows you to edit the generated HTML.
                "link": false, // Button to insert a link.
                "image": true, // Button to insert an image.
                "color": true, // Button to change color of font
                "blockquote": false, // Blockquote,
                "size": 'sm'
            }
        });
    });
</script>
@endpush