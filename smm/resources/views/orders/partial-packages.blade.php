@if( ! $packages->isEmpty() )
    <option value="">Select a package</option>
    @foreach( $packages as $package)
        @php
            $price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;
        @endphp
        <option value="{{ $package->id }}"
                data-min="{{$package->minimum_quantity}}"
                data-max="{{$package->maximum_quantity}}"
                data-comments="{{$package->custom_comments}}"
                data-description="{{$package->description}}"
                data-peritem="{{$price}}">
            {{ $package->name . ' --' }} {{ getOption('currency_symbol') . number_format(($price * getOption('display_price_per')),2, getOption('currency_separator'), '') }}
        </option>
    @endforeach
@endif