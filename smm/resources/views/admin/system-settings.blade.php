@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - System Settings')
@section('content')
    <link rel="stylesheet" href="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.css">
    <link rel="stylesheet" href="/vendor/jquery-palette-color-picker/palette-color-picker.css">
    <style>
        .btn-default {
            color: #444;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: #fff;
        }
    </style>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-form">
                <form role="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ url('/admin/system/settings') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.system_settings')</legend>
                        <div class="form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                            <label for="app_name" class="control-label">@lang('forms.application_name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['app_name'] }}"
                                   data-validation="required"
                                   id="app_name"
                                   name="app_name">
                            @if ($errors->has('app_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('app_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                            <label for="logo" class="control-label">@lang('forms.logo')</label>
                            @if( ! is_null($options['logo']) )
                                <img src="{{ asset($options['logo']) }}" width="50" height="50">
                                <div style="height: 15px;"></div>
                            @endif
                            <input type="file"
                                   class="form-control"
                                   value=""
                                   id="logo"
                                   name="logo">
                            @if ($errors->has('logo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('logo') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('banner') ? ' has-error' : '' }}">
                            <label for="logo" class="control-label">@lang('forms.banner')</label>
                            @if( ! is_null($options['banner']) )
                                <img src="{{ asset($options['banner']) }}" width="50" height="50">
                                <div style="height: 15px;"></div>
                            @endif
                            <input type="file"
                                   class="form-control"
                                   value=""
                                   id="banner"
                                   name="banner">
                            @if ($errors->has('banner'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('banner') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('home_page_meta') ? ' has-error' : '' }}">
                            <label for="home_page_meta" class="control-label">@lang('forms.home_page_meta_tags')</label>
                            <textarea
                                    style="height: 150px;"
                                    class="form-control"
                                    data-validation="required"
                                    rows="5"
                                    id="home_page_meta"
                                    name="home_page_meta">{{ $options['home_page_meta'] }}</textarea>
                            @if ($errors->has('home_page_meta'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('home_page_meta') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('home_page_description') ? ' has-error' : '' }}">
                            <label for="home_page_description" class="control-label">@lang('forms.home_page_description')</label>
                            <textarea
                                    style="height: 150px;"
                                    class="form-control"
                                    data-validation="required"
                                    rows="5"
                                    id="home_page_description"
                                    name="home_page_description">{{ $options['home_page_description'] }}</textarea>
                            @if ($errors->has('home_page_description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('home_page_description') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="notify_email" class="control-label">@lang('forms.notify_email')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['notify_email'] }}"
                                   data-validation="email"
                                   id="notify_email"
                                   name="notify_email">
                        </div>
                        <div class="form-group">
                            <label for="front_page" class="control-label">@lang('forms.front_page')</label>
                            <select name="front_page" id="front_page" class="form-control">
                                <option value="home" {{ ($options['front_page'] == 'home') ? 'selected' : '' }}>Home</option>
                                <option value="login" {{ ($options['front_page'] == 'login') ? 'selected' : '' }}>Login</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="show_service_list_without_login" class="control-label">@lang('forms.show_service_list_without_login')</label>
                            <select name="show_service_list_without_login" id="front_page" class="form-control">
                                <option value="YES" {{ ($options['show_service_list_without_login'] == 'YES') ? 'selected' : '' }}>Yes</option>
                                <option value="NO" {{ ($options['show_service_list_without_login'] == 'NO') ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timezone" class="control-label">Timezone</label>
                            <select name="timezone" id="timezone" class="form-control">
                                @foreach($tzlist as $item)
                                    <option value="{{$item}}" {{ ($options['timezone'] == $item) ? 'selected' : '' }}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group{{ $errors->has('currency_code') ? ' has-error' : '' }}">
                            <label for="currency_code" class="control-label">@lang('forms.currency_code')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['currency_code'] }}"
                                   data-validation="required"
                                   id="currency_code"
                                   maxlength="3"
                                   name="currency_code">
                            @if ($errors->has('currency_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('currency_code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('currency_symbol') ? ' has-error' : '' }}">
                            <label for="currency_symbol" class="control-label">@lang('forms.currency_symbol')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['currency_symbol'] }}"
                                   data-validation="required"
                                   id="currency_symbol"
                                   maxlength="2"
                                   name="currency_symbol">
                            @if ($errors->has('currency_symbol'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('currency_symbol') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('currency_separator') ? ' has-error' : '' }}">
                            <label for="currency_separator" class="control-label">@lang('forms.currency_separator')</label>
                            <select class="form-control" name="currency_separator" id="currency_separator">
                                <option value="." {{ ($options['currency_separator'] == '.') ? 'selected' : ''  }}>Point</option>
                                <option value="," {{ ($options['currency_separator'] == ',') ? 'selected' : ''  }}>Comma</option>
                            </select>
                            @if ($errors->has('currency_separator'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('currency_separator') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('date_format') ? ' has-error' : '' }}">
                            <label for="date_format" class="control-label">@lang('forms.date_format')</label>
                            <select name="date_format"
                                    class="form-control"
                                    id="date_format">
                                <option value="d-m-Y" {{ ($options['date_format'] === 'd-m-Y') ? 'selected' : '' }}>{{ date('d-m-Y') }}</option>
                                <option value="d-M-Y" {{ ($options['date_format'] === 'd-M-Y') ? 'selected' : '' }}>{{ date('d-M-Y') }}</option>
                                <option value="m-d-Y" {{ ($options['date_format'] === 'm-d-Y') ? 'selected' : '' }}>{{ date('m-d-Y') }}</option>
                                <option value="M-d-Y" {{ ($options['date_format'] === 'M-d-Y') ? 'selected' : '' }}>{{ date('M-d-Y') }}</option>
                            </select>
                            @if ($errors->has('date_format'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date_format') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('recaptcha_public_key') ? ' has-error' : '' }}">
                            <label for="recaptcha_public_key" class="control-label">@lang('forms.recaptcha_public_key')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['recaptcha_public_key'] }}"
                                   data-validation="required"
                                   id="recaptcha_public_key"
                                   name="recaptcha_public_key">
                            @if ($errors->has('recaptcha_public_key'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('recaptcha_public_key') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('recaptcha_private_key') ? ' has-error' : '' }}">
                            <label for="recaptcha_private_key" class="control-label">@lang('forms.recaptcha_private_key')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['recaptcha_private_key'] }}"
                                   data-validation="required"
                                   id="recaptcha_private_key"
                                   name="recaptcha_private_key">
                            @if ($errors->has('recaptcha_private_key'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('recaptcha_private_key') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="anonymizer" class="control-label">Anonymizer</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['anonymizer'] }}"
                                   data-validation="required"
                                   id="anonymizer"
                                   name="anonymizer">
                        </div>
                        <div class="form-group{{ $errors->has('minimum_deposit_amount') ? ' has-error' : '' }}">
                            <label for="minimum_deposit_amount" class="control-label">@lang('forms.mnimum_deposit')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $options['minimum_deposit_amount'] }}"
                                   data-validation="required"
                                   id="minimum_deposit_amount"
                                   name="minimum_deposit_amount">
                            @if ($errors->has('minimum_deposit_amount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('minimum_deposit_amount') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="display_price_per" class="control-label">@lang('forms.display_price_per')</label>
                            <select name="display_price_per" id="display_price_per" class="form-control">
                                <option value="1" {{ ($options['display_price_per'] ==1) ? 'selected' : '' }}>1</option>
                                <option value="100" {{ ($options['display_price_per'] == 100) ? 'selected' : '' }}>100</option>
                                <option value="1000" {{ ($options['display_price_per'] == 1000) ? 'selected' : '' }}>1000</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="module_api_enabled" class="control-label">@lang('forms.api_functionality_enabled')</label>
                            <select name="module_api_enabled" id="module_api_enabled" class="form-control">
                                <option value="1" {{ ($options['module_api_enabled'] == 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($options['module_api_enabled'] == 0) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="module_support_enabled" class="control-label">@lang('forms.support_functionality_enabled')</label>
                            <select name="module_support_enabled" id="module_support_enabled" class="form-control">
                                <option value="1" {{ ($options['module_support_enabled'] == 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($options['module_support_enabled'] == 0) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="module_subscription_enabled" class="control-label">@lang('forms.subscription_functionality_enabled')</label>
                            <select name="module_subscription_enabled" id="module_subscription_enabled" class="form-control">
                                <option value="1" {{ ($options['module_subscription_enabled'] == 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($options['module_subscription_enabled'] == 0) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="language" class="control-label">@lang('forms.system_language')</label>
                            <select name="language" id="language" class="form-control">
                                <option value="en" {{ ($options['language'] == 'en') ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($options['language'] == 'es') ? 'selected' : '' }}>Spanish</option>
                                <option value="ru" {{ ($options['language'] == 'ru') ? 'selected' : '' }}>Russian</option>
                                <option value="de" {{ ($options['language'] == 'de') ? 'selected' : '' }}>German</option>
                                <option value="fr" {{ ($options['language'] == 'fr') ? 'selected' : '' }}>French</option>
                                <option value="pt" {{ ($options['language'] == 'pt') ? 'selected' : '' }}>Portuguese</option>
                                <option value="zh" {{ ($options['language'] == 'zh') ? 'selected' : '' }}>Chinese</option>
                                <option value="it" {{ ($options['language'] == 'it') ? 'selected' : '' }}>Italian</option>
                                <option value="tr" {{ ($options['language'] == 'tr') ? 'selected' : '' }}>Turkish</option>
                                <option value="ar" {{ ($options['language'] == 'ar') ? 'selected' : '' }}>Arabic</option>
                                <option value="th" {{ ($options['language'] == 'th') ? 'selected' : '' }}>Thai</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="admin_layout" class="control-label">@lang('forms.admin_layout')</label>
                            <select name="admin_layout" id="admin_layout" class="form-control">
                                <option value="container" {{ ($options['admin_layout'] == 'container') ? 'selected' : '' }}>Fixed Width</option>
                                <option value="container-fluid" {{ ($options['admin_layout'] == 'container-fluid') ? 'selected' : '' }}>Full Width</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_layout" class="control-label">@lang('forms.user_layout')</label>
                            <select name="user_layout" id="user_layout" class="form-control">
                                <option value="container" {{ ($options['user_layout'] == 'container') ? 'selected' : '' }}>Fixed Width</option>
                                <option value="container-fluid" {{ ($options['user_layout'] == 'container-fluid') ? 'selected' : '' }}>Full Width</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_layout" class="control-label">Theme</label>
                            <select name="panel_theme" id="panel_theme" class="form-control">
                                <option value="material" {{ ($options['panel_theme'] == 'material') ? 'selected' : '' }}>Default</option>
                                <option value="simple" {{ ($options['panel_theme'] == 'simple') ? 'selected' : '' }}>Simple</option>
                            </select>
                            <div class="hlep-block">Theme colors will only work with default theme</div>
                        </div>
                        <div class="form-group">
                            <label for="theme_color" class="control-label">@lang('forms.theme_color')</label>
                            <div>
                                <input type="text" name="theme_color" id="theme_color" value="{{ $options['theme_color'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="background_color" class="control-label">@lang('forms.theme_background_color')</label>
                            <div>
                                <input type="text" name="background_color" id="background_color" value="{{ $options['background_color'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" disabled>@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="/js/vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="/vendor/jquery-palette-color-picker/palette-color-picker.js"></script>
<script>
    $(function () {
        //bootstrap WYSIHTML5 - text editor
        $("#home_page_description").wysihtml5({
            toolbar: {
                "font-styles": true, // Font styling, e.g. h1, h2, etc.
                "emphasis": true, // Italics, bold, etc.
                "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
                "html": true, // Button which allows you to edit the generated HTML.
                "link": false, // Button to insert a link.
                "image": false, // Button to insert an image.
                "color": true, // Button to change color of font
                "blockquote": true, // Blockquote,
                "size": 'sm'
            }
        });

        $('#theme_color').paletteColorPicker({
            colors: [
                "#0073b7",
                "#E53935",
                "#D32F2F",
                "#C62828",
                "#B71C1C",
                "#EC407A",
                "#E91E63",
                "#D81B60",
                "#C2185B",
                "#AD1457",
                "#880E4F",
                "#AB47BC",
                "#9C27B0",
                "#8E24AA",
                "#7B1FA2",
                "#6A1B9A",
                "#4A148C",
                "#7E57C2",
                "#673AB7",
                "#5E35B1",
                "#512DA8",
                "#4527A0",
                "#311B92",
                "#5C6BC0",
                "#3F51B5",
                "#3949AB",
                "#303F9F",
                "#283593",
                "#1A237E",
                "#2196F3",
                "#1976D2",
                "#1565C0",
                "#0D47A1",
                "#03A9F4",
                "#039BE5",
                "#0288D1",
                "#0277BD",
                "#01579B",
                "#26C6DA",
                "#00BCD4",
                "#00ACC1",
                "#0097A7",
                "#00838F",
                "#006064",
                "#26A69A",
                "#00897B",
                "#004D40",
                "#66BB6A",
                "#43A047",
                "#388E3C",
                "#2E7D32",
                "#1B5E20",
                "#9CCC65",
                "#8BC34A",
                "#8BC34A",
                "#689F38",
                "#558B2F",
                "#33691E",
                "#FFEE58",
                "#FDD835",
                "#FBC02D",
                "#F9A825",
                "#F57F17",
                "#A1887F",
                "#8D6E63",
                "#795548",
                "#6D4C41",
                "#5D4037",
                "#4E342E",
                "#3E2723",
                "#BDBDBD",
                "#9E9E9E",
                "#757575",
                "#616161",
                "#424242",
                "#212121",
                "#78909C",
                "#607D8B",
                "#546E7A",
                "#455A64",
                "#263238",
                "#4285f4",
            ]
        });

        $('#background_color').paletteColorPicker({
            colors: [
                "#fff",
                "#e9ebee",
                "#fafafa"
            ]
        });
    });
</script>

@endpush