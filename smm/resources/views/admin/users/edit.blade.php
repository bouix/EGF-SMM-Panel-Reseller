@extends('admin.layouts.app')
@section('title', getOption('app_name') . ' - Edit User')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> @lang('menus.dashboard')</a></li>
                <li><a href="{{ url('/admin/users') }}"> @lang('menus.users')</a></li>
                <li class="active">@lang('menus.edit')</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/users/'.$user->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.edit_user')</legend>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">@lang('forms.name')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $user->name }}"
                                   data-validation="required"
                                   id="name"
                                   name="name">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">@lang('forms.email')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $user->email }}"
                                   id="email"
                                   data-validation="email"
                                   readonly
                                   name="email">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="skype_id" class="control-label">SkypeID</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $user->skype_id }}"
                                   id="skype_id"
                                   name="skype_id">
                        </div>
                        <div class="form-group{{ $errors->has('funds') ? ' has-error' : '' }}">
                            <label for="funds" class="control-label">@lang('forms.funds')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $user->funds }}"
                                   id="funds"
                                   data-validation="number"
                                   data-validation-allowing="float"
                                   name="funds">
                            @if ($errors->has('funds'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('funds') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="role" class="control-label">@lang('forms.role')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="role"
                                    name="role">
                                <option
                                        value="USER"
                                        {{ $user->role === 'USER' ? 'selected' : '' }}
                                >USER
                                </option>
                                <option
                                        value="ADMIN"
                                        {{ $user->role === 'ADMIN' ? 'selected' : '' }}
                                >ADMIN
                                </option>
                            </select>
                            @if ($errors->has('role'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">@lang('forms.status')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="status"
                                    name="status">
                                <option
                                        value="ACTIVE"
                                        {{ $user->status === 'Active' ? 'selected' : '' }}
                                >Active
                                </option>
                                <option
                                        value="DEACTIVATED"
                                        {{ $user->status === 'Deactivated' ? 'selected' : '' }}
                                >Deactivate
                                </option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="api_key" class="control-label">@lang('forms.api_token')</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $user->api_token }}"
                                   id="api_key"
                                   onClick="this.setSelectionRange(0, this.value.length)"
                                   readonly
                                   name="api_key">
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">@lang('forms.password')</label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="payment_methods" class="control-label">@lang('forms.payment_method')</label>
                            <br>
                            @if( ! $paymentMethods->isEmpty())
                                @foreach($paymentMethods as $method)
                                    <label class="checkbox-inline">
                                        <input type="checkbox"
                                               style="margin: 0; margin-left: -20px"
                                               name="payment_methods[]"
                                               @if(in_array($method->id,$enabled_payment_methods))
                                               {{ 'checked' }}
                                               @endif
                                               value="{{ $method->id }}">{{ $method->name }}
                                    </label>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/users/add-funds/'.$user->id) }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border" style="margin-bottom: 0">@lang('menus.add_funds')</legend>
                        <div class="form-group">
                            <label for="payment_method_id" class="control-label">@lang('general.payment_method')</label>
                            <select
                                    class="form-control"
                                    data-validation="required"
                                    id="payment_method_id"
                                    name="payment_method_id">
                                <option value="">Select</option>
                                @foreach($paymentMethods as $payment)
                                    <option value="{{ $payment->id }}">{{$payment->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group{{ $errors->has('fund') ? ' has-error' : '' }}">
                            <label for="fund" class="control-label">@lang('forms.funds')</label>
                            <input type="text"
                                   class="form-control"
                                   value=""
                                   id="fund"
                                   data-validation="number"
                                   data-validation-allowing="float"
                                   name="fund">
                            @if ($errors->has('fund'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fund') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                            <label for="details" class="control-label">@lang('general.details')</label>
                            <textarea name="details"
                                      class="form-control"
                                      data-validation="required"
                                      rows="4"
                                      style="height: 80px;"
                                      id="details"></textarea>
                            @if ($errors->has('details'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('details') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.add')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="clearfix">&nbsp;</div>
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/admin/users/package-special-prices/'.$user->id) }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border" style="margin-bottom: 0">@lang('forms.package_special_prices')</legend>
                        <small>
                            <input type="file" id="csv" class="btn btn-sm btn-inverse">
                            .csv or .txt file, with format <code>ID,@lang('forms.price')</code>eg: <code>6,0.001</code> and then click update
                        </small>
                        <div class="table-responsive">
                            <table class="table table-bordered services-table">
                                <thead>
                                <tr>
                                    <th>@lang('general.package_id')</th>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('forms.price')</th>
                                    <th>@lang('general.minimum_quantity')</th>
                                    <th>@lang('general.maximum_quantity')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( ! empty($packages) )
                                    @foreach($packages as $package)
                                        <tr>
                                            <td>{{ $package->id }}</td>
                                            <td>{{ $package->name }}</td>
                                            <td>
                                                <input type="hidden" name="package_id[]" value="{{ $package->id }}">
                                                <input type="hidden" name="minimum_quantity[{{$package->id}}]" value="{{ $package->minimum_quantity }}">
                                                <input type="text"
                                                       class="form-control"
                                                       name="price_per_item[{{$package->id}}]"
                                                       data-validation="number"
                                                       data-validation-allowing="float"
                                                       value="{{ isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] :  $package->price_per_item }}"
                                                >
                                            </td>
                                            <td>{{ $package->minimum_quantity }}</td>
                                            <td>{{ $package->maximum_quantity }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">No Record Found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang('buttons.update')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div id="out"></div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('#csv').change(function(){
                var file = this.files[0];
                var reader = new FileReader();
                // start reading the file. When it is done, calls the onload event defined above.
                reader.readAsText(file);
                reader.onload = function () {
                    var arr = csvToArray(reader.result);
                    $.each(arr,function(i,v){
                        $('input[name="price_per_item['+v[0]+']"]').val(v[1]);
                    });
                };
            });
        });

        function csvToArray(text) {
            var p = '', row = [''], ret = [row], i = 0, r = 0, s = !0, l;
            for (l in text) {
                l = text[l];
                if ('"' === l) {
                    if (s && l === p) row[i] += l;
                    s = !s;
                } else if (',' === l && s) l = row[++i] = '';
                else if ('\n' === l && s) {
                    if ('\r' === p) row[i] = row[i].slice(0, -1);
                    row = ret[++r] = [l = '']; i = 0;
                } else row[i] += l;
                p = l;
            }
            return ret;
        };

    </script>
@endpush