@extends('frontend.layouts.app')

@section('content')

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
                            <i class="la-3x mb-2 las la-map"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. Shipping info')}}</h3>
                        </div>
                    </div>
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-truck"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Delivery info')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Payment')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4 gry-bg">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-xxl-10 mx-auto text-left">
                @php
                    $admin_products = array();
                    $seller_products = array();

                    foreach ($carts as $key => $cartItem){
                        if(\App\Product::find($cartItem['product_id'])->added_by == 'admin'){
                            array_push($admin_products, $cartItem['product_id']);
                        }
                        else{
                            $product_ids = array();
                            if(array_key_exists(\App\Product::find($cartItem['product_id'])->user_id, $seller_products)){
                                $product_ids = $seller_products[\App\Product::find($cartItem['product_id'])->user_id];
                            }
                            array_push($product_ids, $cartItem['product_id']);
                            $seller_products[\App\Product::find($cartItem['product_id'])->user_id] = $product_ids;
                        }
                    }
                    // dd($admin_products,$seller_products);
                @endphp

                @if (!empty($admin_products))
                <form class="form-default" action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                    @csrf
                    <div class="card mb-3 shadow-none border-gray-200 rounded">
                        <div class="card-header p-3">
                            <h5 class="fs-16 fw-600 mb-0">{{ get_setting('site_name') }} {{ translate('Products') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($admin_products as $key => $cartItem)
                                @php
                                    $product = \App\Product::find($cartItem);
                                @endphp
                                <li class="list-group-item">
                                    <div class="d-flex">
                                        <span class="mr-2">
                                            <img
                                                src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                class="img-fit size-60px rounded"
                                                alt="{{  $product->getTranslation('name')  }}"
                                            >
                                        </span>
                                        <span class="fs-14 opacity-60">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>

                            <div class="row border-top pt-3">
                                <div class="col-md-6">
                                    <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="row gutters-5">
                                        <div class="col-6">
                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                <input
                                                    type="radio"
                                                    name="shipping_type_{{ \App\User::where('user_type', 'admin')->first()->id }}"
                                                    value="home_delivery"
                                                    onchange="show_pickup_point(this)"
                                                    data-target=".pickup_point_id_admin"
                                                    checked
                                                >
                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                    <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                        @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                        <div class="col-6">
                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                <input
                                                    type="radio"
                                                    name="shipping_type_{{ \App\User::where('user_type', 'admin')->first()->id }}"
                                                    value="pickup_point"
                                                    onchange="show_pickup_point(this)"
                                                    data-target=".pickup_point_id_admin"
                                                >
                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                    <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 pickup_point_id_admin d-none">
                                        <select
                                            class="form-control aiz-selectpicker"
                                            name="pickup_point_id_{{ \App\User::where('user_type', 'admin')->first()->id }}"
                                            id="pickup_point_id_{{ \App\User::where('user_type', 'admin')->first()->id }}"
                                            onchange="gettimes({{ \App\User::where('user_type', 'admin')->first()->id }})"
                                            data-live-search="true"
                                        >
                                                <option>{{ translate('Select your nearest pickup point')}}</option>
                                            @foreach (\App\PickupPoint::where('pick_up_status',1)->get() as $key => $pick_up_point)
                                                <option
                                                    value="{{ $pick_up_point->id }}"
                                                    data-content="<span class='d-block'>
                                                                    <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                </span>"
                                                >
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- picup point time slots --}}
                                    {{-- <div class="mt-4 d-none " id="time-slot-{{ \App\User::where('user_type', 'admin')->first()->id }}">
                                        <select
                                            class="form-control "
                                            name="pickup_point_time_id_{{ \App\User::where('user_type', 'admin')->first()->id }}"
                                            id="pickup_point_time_id_{{ \App\User::where('user_type', 'admin')->first()->id }}"

                                        >
                                                <option>{{ translate('Select a Time Slot')}}</option>

                                        </select>
                                    </div> --}}


                                </div>

                            </div>
                            <div class="form-group row my-2 pickup_point_id_admin d-none">
                                <label class="col-sm-6 control-label fs-15 fw-600" for="delivery_date">{{translate('Expected PickUp Date & Time')}}</label>
                                <div class="col-sm-6">
                                  <input type="text" class="form-control aiz-date-range" name="delivery_date" data-past-disable="true" placeholder="Select Date" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-single="true" autocomplete="off" >
                                </div>


                            </div>

                            <div class="form-group row my-2">
                                <label for="exampleFormControlTextarea1" class="col-4"> <h6 class="fs-15 fw-600">{{ translate('Note') }}</h6></label>
                                <textarea class="col-8 px-2  form-control" name="note" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>


                        <div class="card-footer justify-content-end">
                            <button type="submit" name="owner_id" value="{{ App\User::where('user_type', 'admin')->first()->id }}" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</a>
                        </div>
                    </div>
                </form>
                @endif

                {{-- seller products  --}}
                <form class="form-default"  action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                    @csrf
                    @if (!empty($seller_products))
                        @foreach ($seller_products as $key => $seller_product)
                            <div class="card mb-3 shadow-sm border-0 rounded">
                                <div class="card-header p-3">
                                    <h5 class="fs-16 fw-600 mb-0">{{ \App\Shop::where('user_id', $key)->first()->name }} {{ translate('Products') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($seller_product as $cartItem)
                                        @php
                                            $product = \App\Product::find($cartItem);
                                        @endphp
                                        <li class="list-group-item">
                                            <div class="d-flex">
                                                <span class="mr-2">
                                                    <img
                                                        src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        class="img-fit size-60px rounded"
                                                        alt="{{  $product->getTranslation('name')  }}"
                                                    >
                                                </span>
                                                <span class="fs-14 opacity-60">{{ $product->getTranslation('name') }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>

                                    <div class="row border-top pt-3">
                                        <div class="col-md-6">
                                            <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row gutters-5">
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input
                                                            type="radio"
                                                            name="shipping_type_{{ $key }}"
                                                            value="home_delivery"
                                                            onchange="show_pickup_point(this)"
                                                            data-target=".pickup_point_id_{{ $key }}"
                                                            checked
                                                        >
                                                        <span class="d-flex p-3 aiz-megabox-elem">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                    @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                                    <div class="col-6">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input
                                                                type="radio"
                                                                name="shipping_type_{{ $key }}"
                                                                value="pickup_point"
                                                                onchange="show_pickup_point(this)"
                                                                data-target=".pickup_point_id_{{ $key }}"
                                                            >
                                                            <span class="d-flex p-3 aiz-megabox-elem">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    @endif
                                                @endif
                                            </div>

                                            @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                                <div class="mt-4 pickup_point_id_{{ $key }} d-none">
                                                    <select
                                                        class="form-control aiz-selectpicker"
                                                        name="pickup_point_id_{{ $key }}"
                                                        data-live-search="true"
                                                    >
                                                            <option>{{ translate('Select your nearest pickup point')}}</option>
                                                        @foreach (json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id) as $pick_up_point)
                                                            @if (\App\PickupPoint::find($pick_up_point) != null)
                                                            <option
                                                                value="{{ \App\PickupPoint::find($pick_up_point)->id }}"
                                                                data-content="<span class='d-block'>
                                                                                <span class='d-block fs-16 fw-600 mb-2'>{{ \App\PickupPoint::find($pick_up_point)->getTranslation('name') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ \App\PickupPoint::find($pick_up_point)->getTranslation('address') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i> {{ \App\PickupPoint::find($pick_up_point)->phone }}</span>
                                                                            </span>"
                                                            >
                                                            </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                            @endif





                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer justify-content-end">
                                    <button type="submit" name="owner_id" value="{{ $key }}" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </form>
                <div class="pt-4">
                    <a href="{{ route('home') }}" >
                        <i class="la la-angle-left"></i>
                        {{ translate('Return to shop')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script type="text/javascript">
        function display_option(key){

        }
        function gettimes(id){
            var ids='#pickup_point_id_'+id;
            var item=$(ids).val();
            if(item){
                $.post('{{ route('times.get') }}', {_token:'{{ @csrf_token() }}',id:item}, function(data){
                    if(data.slots.length>0){
                        let tmp='#pickup_point_time_id_'+id;
                        let tmp2='#time-slot-'+id;
                       data.slots.forEach(elem => {

                             // $(tmp).append(new Option(`${elem.str}`,`${elem.id}`))
                        $(tmp).append(`
                                <option value="${elem.id}" class="fw-500 fs-14">${elem.str}</option>
                            `);
                       });
                       $(tmp2).removeClass("d-none");

                    }
                });
            }
        }
        function show_pickup_point(el) {
        	var value = $(el).val();
        	var target = $(el).data('target');

            // console.log(value);

        	if(value == 'home_delivery'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
        	}else{
        		$(target).removeClass('d-none');
        	}
        }

    </script>
@endsection
