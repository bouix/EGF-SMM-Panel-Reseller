@extends('layouts.app')
@section('title', getOption('app_name') . ' - New Order' )
@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-form">
                <form
                        role="form"
                        method="POST"
                        action="{{ url('/order') }}">
                    {{ csrf_field() }}
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">@lang('forms.new') @lang('forms.order')</legend>
                        <div class="form-group{{ $errors->has('service_id') ? ' has-error' : '' }}">
                            <label for="service_id" class="control-label">@lang('forms.service')</label>
                            <select name="service_id"
                                    id="service_id"
                                    data-validation="required"
                                    class="form-control">
                                <option value="">Select a service</option>
                                @if( ! $services->isEmpty() )
                                    @foreach( $services as $service)
                                        <option value="{{ $service->id }}"> {{ $service->name  }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('service_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('service_id') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('package_id') ? ' has-error' : '' }}">
                            <label for="package_id" class="control-label">@lang('forms.package')</label>
                            <select name="package_id"
                                    id="package_id"
                                    data-validation="required"
                                    class="form-control">
                                <option value="">Select service first</option>
                            </select>
                            @if ($errors->has('package_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('package_id') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label">@lang('forms.description')</label>
                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      style="height: 50px"
                                      class="form-control"></textarea>
                        </div>
                        <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                            <label for="link" class="control-label">@lang('forms.link')</label>
                            <input name="link"
                                   id="link"
                                   value="{{ old('link') }}"
                                   type="text"
                                   data-validation="required"
                                   class="form-control"
                                   placeholder="">
                            @if ($errors->has('link'))
                                <span class="help-block">
                                <strong>{{ $errors->first('link') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                            <label for="quantity" class="control-label">@lang('forms.quantity')</label>
                            <input name="quantity"
                                   id="quantity"
                                   type="text"
                                   value="{{ old('quantity') }}"
                                   class="form-control"
                                   data-validation="number"
                                   data-validation-allowing="range[1;100]"
                                   placeholder="">
                            <span class="help-block">
                            <span class="label label-default">@lang('forms.minimum_quantity') : <span id="min-q">0</span></span> <span class="label label-default">@lang('forms.maximum_quantity') : <span
                                            id="max-q">0</span></span>
                        </span>
                            @if ($errors->has('quantity'))
                                <span class="help-block">
                                <strong>{{ $errors->first('quantity') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <p>@lang('forms.price_total') {{ getOption('currency_symbol') }}<span id="order_total">0</span></p>
                            <p id="not-enough-funds" style="display:none;color:red">@lang('forms.order_amount_exceed')</p>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" id="custom-comments-div" style="display: none">
                            <label for="custom_comments" class="control-label">@lang('forms.custom_comments')</label>
                            <textarea
                                    class="form-control"
                                    id="custom_comments"
                                    style="height: 150px;"
                                    placeholder="1 on each line"
                                    name="custom_comments">{{old('custom_comments')}}</textarea>
                            @if ($errors->has('custom_comments'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('custom_comments') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" id="btn-proceed" class="btn btn-primary">@lang('buttons.place_order')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!!  getPageContent('new-order') !!}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        var userFunds = '{{ Auth::user()->funds }}';
        $(function () {

            $('#service_id').change(function () {
                var service_id = $(this).val();
                if (service_id !== '') {
                    resetValues();
                    $.ajax({
                        url: baseUrl + '/service/get-packages/' + service_id,
                        type: "GET",
                        success: function (packages) {
                            $('#package_id').html(packages);
                        }
                    });

                }
            });

            // On select display minimum quantity of package
            $('#package_id').change(function () {
                var sel = $('#package_id option:selected');
                if (sel.val() != '') {
                    $('#min-q').html(sel.data('min'));
                    $('#max-q').html(sel.data('max'));
                    $('#description').text(sel.data('description'));
                    $('#quantity').attr('data-validation-allowing', 'range[' + sel.data('min') + ';' + sel.data('max') + ']')
                    $('#link').focus();

                    if (sel.data('comments') == 1) {
                        $('#custom-comments-div').show();
                        $('#quantity')
                            .val(0)
                            .attr('readonly', true);
                    } else {
                        $('#custom-comments-div').hide();
                        $('#quantity').removeAttr('readonly');
                    }
                }
            });

            $('#custom_comments').on('keyup', function () {
                var text = $(this).val();
                var lines = text.split(/\r|\r\n|\n/);
                var q = lines.length;
                $('#quantity').val($.trim(q));

                var sel = $('#package_id option:selected');
                var orderTotal = 0;
                if (q > 0) {
                    var price_per_item = sel.data('peritem');
                    orderTotal = q * price_per_item;
                }
                $('#order_total').text(orderTotal.toFixed(2).replace(".", "{{ getOption('currency_separator') }}"));

                if (orderTotal > userFunds) {
                    $('#not-enough-funds').show();
                } else {
                    $('#not-enough-funds').hide();
                }
            });

            $('#quantity').on('keyup', function () {

                var sel = $('#package_id option:selected');
                var orderTotal = 0;
                var q = $(this).val();
                if (q > 0) {
                    var price_per_item = sel.data('peritem');
                    orderTotal = q * price_per_item;
                }
                $('#order_total').text(orderTotal.toFixed(2).replace(".", "{{ getOption('currency_separator') }}"));

                if (orderTotal > userFunds) {
                    $('#not-enough-funds').show();
                } else {
                    $('#not-enough-funds').hide();
                }

            });

        });

        function resetValues() {
            $('#order_total').text(0);
            $('#description').text('');
            $('#quantity').val(0);
            $('#min-q').html(0);
            $('#max-q').html(0);
        }
    </script>
@endpush