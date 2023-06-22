@extends('frontend.layouts.app')

@section('content')
    @php
        $status = $order->orderDetails->first()->delivery_status;
    @endphp

    <section class="mb-4">
        <div class="">
            <div class="aiz-carousel mobile-img-auto-height dot-small-white dots-inside-bottom " data-dots="true" data-autoplay="true">
                @foreach(explode(",",get_setting('product_banner')) as $value)
                <div class="carousel-box">
                    <img src="{{ uploaded_asset($value) }}" class="img-fluid w-100">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment')}}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center text-primary">
                                <i class="la-3x mb-2 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card shadow-none border-gray-200 rounded">
                        <div class="card-body">
                            <div class="text-center py-4 mb-4">
                                <i class="la la-check-circle la-3x text-success mb-3"></i>
                                <h1 class="h3 mb-3 fw-600">{{ translate('Thank You for Your Order!')}}</h1>
                                <h2 class="h5">{{ translate('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                @auth
                                <p class="opacity-70 font-italic">{{  translate('A copy or your order summary has been sent to') }} {{ json_decode($order->shipping_address)->email }}</p>
                                @endauth
                                <div>
                                    <a class="btn btn-soft-primary" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                        {{ translate('Download Invoice')}}
                                    </a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Summary')}}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Order Code')}}:</td>
                                                <td>{{ $order->code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Order date')}}:</td>
                                                <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                            </tr>
                                          @if($order->orderDetails[0]->shipping_type!='home_delivery')
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Expected PickUp')}}:</td>
                                                <td>{{ date('d-m-Y H:i A', $order->delivery_date) }}</td>
                                            </tr>
                                          @endif
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Total order amount')}}:</td>
                                                <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Order status')}}:</td>
                                                <td>{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Shipping')}}:</td>
                                                <td>{{ translate('Flat shipping rate')}}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">{{ translate('Payment method')}}:</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                            </tr>

                                            @if(get_setting('proxypay') == 1 && !$order->proxy_cart_reference_id->isEmpty())
                                                <tr>
                                                    <td class="w-50 fw-600">{{ translate('Proxypay Reference')}}:</td>
                                                    <td>{{ $order->proxy_cart_reference_id->first()->reference_id }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Shipping Address')}}</h5>
                                    <div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Name') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->name }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Phone') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->phone }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Address') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->address }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Postal Code') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->postal_code }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Area') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->area }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('City') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->city }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Country') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->shipping_address)->country }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Billing Address')}}</h5>
                                    <div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Name') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->name }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Phone') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->phone }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Address') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->address }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Postal Code') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->postal_code }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Area') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->area }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('City') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->city }}</span>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <span class="opacity-70 w-25">{{ translate('Country') }}:</span>
                                            <span class="fw-600 ml-2">{{ json_decode($order->billing_address)->country }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Details')}}</h5>
                                <div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th width="30%">{{ translate('Product')}}</th>
                                                <th>{{ translate('Variation')}}</th>
                                                <th>{{ translate('Quantity')}}</th>
                                                <th>{{ translate('Delivery Type')}}</th>
                                                <th class="text-right">{{ translate('Price')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderDetails as $key => $orderDetail)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        @if ($orderDetail->product != null)
                                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                {{ $orderDetail->product->getTranslation('name') }}
                                                            </a>
                                                        @else
                                                            <strong>{{  translate('Product Unavailable') }}</strong>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $orderDetail->variation }}
                                                    </td>
                                                    <td>
                                                        {{ $orderDetail->quantity }}
                                                    </td>
                                                    <td>
                                                        @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                                            {{  translate('Home Delivery') }}
                                                        @elseif ($orderDetail->shipping_type == 'pickup_point')
                                                            @if ($orderDetail->pickup_point != null)
                                                                {{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})

                                                            @endif
                                                            {{-- picup point days timeslot --}}
                                                            {{-- @if ($orderDetail->time_slot != null)
                                                                @php
                                                                    $time=\App\PickupTime::findOrFail($orderDetail->time_slot);

                                                                @endphp
                                                                <br> Days: {{ implode(",",json_decode($time->days)) }} <br> From : {{ $time->start_time }} to {{ $time->end_time }}

                                                            @endif --}}

                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">

                                    <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                        <table class="table ">
                                            <tbody>
                                                <tr>
                                                    <th>{{ translate('Subtotal')}}</th>
                                                    <td class="text-right">
                                                        <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Shipping')}}</th>
                                                    <td class="text-right">
                                                        <span class="font-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Tax')}}</th>
                                                    <td class="text-right">
                                                        <span class="font-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Coupon Discount')}}</th>
                                                    <td class="text-right">
                                                        <span class="font-italic">{{ single_price($order->coupon_discount) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-600">{{ translate('Total')}}</span></th>
                                                    <td class="text-right">
                                                        <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="row">
                                    <h6><b class="mx-3">Note ::</b>
                                    {{ $order->note }} </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
