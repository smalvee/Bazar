@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Category::find($category_id)->meta_title;
        $meta_description = \App\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title         = get_setting('meta_title');
        $meta_description   = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')
    <section class="">
        <div class="container-fluid" style="padding-left:0px;padding-right:0px;">
            <div class="aiz-carousel dot-small-white dots-inside-bottom "  data-items="1" data-dots="true" data-arrows="true" data-autoplay="true">
                @foreach(explode(",",get_setting('shop_banners')) as $value)
                <div class="carousel-box">
                    <img src="{{ uploaded_asset($value) }}" class="img-fluid  w-100">
                </div>
                @endforeach
            </div>
        </div>
        @if(get_setting('shop_slider_images') != null)
          @if(Route::currentRouteName() == 'home.shop')
            <div class="container pt-3">
                <div class="aiz-carousel dot-small-black gutters-5 outside-arrow af-arrow" data-items="3" data-md-items="2" data-xs-items="2" data-dots="false" data-arrows="true" data-autoplay="true">
                    @php $slider_images = json_decode(get_setting('shop_slider_images'), true);  @endphp
                    @foreach ($slider_images as $key => $value)
                        <div class="carousel-box">
                            <a href="{{ json_decode(get_setting('shop_slider_links'), true)[$key] }}" class="text-reset d-block">
                                <img src="{{ uploaded_asset($value) }}" class="img-fluid w-100">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </section>
    <section class="mb-4 pt-5">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left sticky">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" >
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>
                                <div class="bg-light rounded mb-3">
                                    <div class="pb-1">
                                        <h4 class="py-3 fs-16 fw-600 text-uppercase text-primary px-4 ">{{ translate('Categories')}}</h4>
                                        <ul class="list-unstyled">
                                            <li class="py-2 px-4 border-top border-white">
                                                <a class="text-reset fs-14" href="{{ route('flash-deals') }}">
                                                    <img
                                                        class="cat-image lazyload mr-2 size-25px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ static_asset('assets/img/offer_icon.png') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                    <span>{{ translate('Offers') }}</span>
                                                </a>
                                            </li>
                                            @php
                                                $parent_category = null;
                                                $current_category = \App\Category::find($category_id);
                                                if($current_category != null){
                                                    $parent_category = \App\Category::find($category_id)->parentCategory;
                                                }
                                            @endphp
                                            @foreach (\App\Category::where('level', 0)->get() as $category)

                                                @if ($category_id == $category->id)
                                                    <li class="py-2 px-4 border-top border-white">
                                                        <a class="text-reset fs-14 fw-600" href="{{ route('products.category', $category->slug) }}">
                                                            <img
                                                                class="cat-image lazyload mr-2 size-25px"
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($category->icon) }}"
                                                                alt="{{ $category->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                            <span>{{ $category->getTranslation('name') }}</span>
                                                        </a>
                                                    </li>
                                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $id)
                                                        <li class="ml-4 py-2 px-4">
                                                            <a class="text-reset fs-14 pl-2" href="{{ route('products.category', \App\Category::find($id)->slug) }}">{{ \App\Category::find($id)->getTranslation('name') }}</a>
                                                        </li>
                                                    @endforeach
                                                @elseif(optional($current_category)->level == 1 && $current_category->parent_id == $category->id)
                                                    <li class="py-2 px-4 border-top border-white">
                                                        <a class="text-reset fs-14" href="{{ route('products.category', $category->slug) }}">
                                                            <img
                                                                class="cat-image lazyload mr-2 size-25px"
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($category->icon) }}"
                                                                alt="{{ $category->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                            <span>{{ $category->getTranslation('name') }}</span>
                                                        </a>
                                                    </li>
                                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $sub_id)
                                                        @php
                                                            $sub_category = \App\Category::find($sub_id);
                                                        @endphp
                                                        @if($category_id == $sub_id)
                                                            <li class="ml-4 py-2 px-4">
                                                                <a class="text-reset fs-14 pl-2 fw-600" href="{{ route('products.category', $sub_category->slug) }}">{{ $sub_category->getTranslation('name') }}</a>
                                                            </li>
                                                            @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($sub_category->id) as $key => $sub_sub_id)
                                                                @php
                                                                    $sub_sub_category = \App\Category::find($sub_sub_id);
                                                                @endphp
                                                                <li class="ml-5 mb-2">
                                                                    <a class="text-reset fs-14" href="{{ route('products.category', $sub_sub_category->slug) }}">{{ $sub_sub_category->getTranslation('name') }}</a>
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li class="ml-4 py-2 px-4">
                                                                <a class="text-reset fs-14 pl-2" href="{{ route('products.category', $sub_category->slug) }}">{{ $sub_category->getTranslation('name') }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @elseif(optional($current_category)->level == 2 && $parent_category->parent_id == $category->id)
                                                    <li class="py-2 px-4 border-top border-white">
                                                        <a class="text-reset fs-14" href="{{ route('products.category', $category->slug) }}">
                                                            <img
                                                                class="cat-image lazyload mr-2 size-25px"
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($category->icon) }}"
                                                                alt="{{ $category->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                            <span>{{ $category->getTranslation('name') }}</span>
                                                        </a>
                                                    </li>
                                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $sub_id)
                                                        @php
                                                            $sub_category = \App\Category::find($sub_id);
                                                        @endphp
                                                        @if($current_category->parent_id == $sub_id)
                                                            <li class="ml-4 py-2 px-4">
                                                                <a class="text-reset fs-14 pl-2 fw-600" href="{{ route('products.category', $sub_category->slug) }}">{{ $sub_category->getTranslation('name') }}</a>
                                                            </li>
                                                            @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($sub_category->id) as $key => $sub_sub_id)
                                                                @php
                                                                    $sub_sub_category = \App\Category::find($sub_sub_id);
                                                                @endphp
                                                                @if($category_id == $sub_sub_id)
                                                                    <li class="ml-5 mb-2">
                                                                        <a class="text-reset fs-14 fw-600" href="{{ route('products.category', $sub_sub_category->slug) }}">{{ $sub_sub_category->getTranslation('name') }}</a>
                                                                    </li>
                                                                @else
                                                                    <li class="ml-5 mb-2">
                                                                        <a class="text-reset fs-14" href="{{ route('products.category', $sub_sub_category->slug) }}">{{ $sub_sub_category->getTranslation('name') }}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <li class="ml-4 py-2 px-4">
                                                                <a class="text-reset fs-14 pl-2" href="{{ route('products.category', $sub_category->slug) }}">{{ $sub_category->getTranslation('name') }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <li class="py-2 px-4 border-top border-white">
                                                        <a class="text-reset fs-14" href="{{ route('products.category', $category->slug) }}">
                                                            <img
                                                                class="cat-image lazyload mr-2 size-25px"
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($category->icon) }}"
                                                                alt="{{ $category->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                            <span>{{ $category->getTranslation('name') }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="bg-light rounded mb-3 p-4">
                                    <h4 class="mb-4 mt-3 fs-16 fw-600 text-uppercase text-primary">{{ translate('Price range')}}</h4>
                                    <div class="aiz-range-slider">
                                        <div
                                            id="input-slider-range"
                                            data-range-value-min="@if(count(\App\Product::query()->get()) < 1) 0 @else {{ filter_products(\App\Product::query())->get()->min('unit_price') }} @endif"
                                            data-range-value-max="@if(count(\App\Product::query()->get()) < 1) 0 @else {{ filter_products(\App\Product::query())->get()->max('unit_price') }} @endif"
                                        ></div>

                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                    @if (isset($min_price))
                                                        data-range-value-low="{{ $min_price }}"
                                                    @elseif($products->min('unit_price') > 0)
                                                        data-range-value-low="{{ $products->min('unit_price') }}"
                                                    @else
                                                        data-range-value-low="0"
                                                    @endif
                                                    id="input-slider-range-value-low"
                                                ></span>
                                            </div>
                                            <div class="col-6 text-right">
                                                <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                    @if (isset($max_price))
                                                        data-range-value-high="{{ $max_price }}"
                                                    @elseif($products->max('unit_price') > 0)
                                                        data-range-value-high="{{ $products->max('unit_price') }}"
                                                    @else
                                                        data-range-value-high="0"
                                                    @endif
                                                    id="input-slider-range-value-high"
                                                ></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-light rounded mb-3 p-4">
                                    <h4 class="mb-4 mt-3 fs-16 fw-600 text-uppercase text-primary">{{ translate('Filter by weight')}}</h4>
                                    <div class="aiz-checkbox-list">
                                        @foreach ($all_weights as $key => $weight)
                                        <label class="aiz-checkbox">
                                            <input
                                                type="checkbox"
                                                name="selected_weight_values[]"
                                                value="{{ $weight }}"
                                                @if (in_array($weight, $selected_weight_values)) checked @endif
                                                onchange="filter()"
                                            >
                                            <span class="aiz-square-check"></span>
                                            <span>{{ $weight }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="text-left">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h1 class="h6 fw-600 text-body">
                                        @if(isset($category_id))
                                            {{ \App\Category::find($category_id)->getTranslation('name') }}
                                        @elseif(isset($query))
                                            {{ translate('Search result for ') }}"{{ $query }}"
                                        @else
                                            {{ translate('All Products') }}
                                        @endif
                                    </h1>
                                    <ul class="breadcrumb bg-transparent p-0">
                                        <li class="breadcrumb-item opacity-50">
                                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                                        </li>
                                        @if(!isset($category_id))
                                            <li class="breadcrumb-item fw-600  text-dark">
                                                <a class="text-reset" href="{{ route('search') }}">"{{ translate('All Categories')}}"</a>
                                            </li>
                                        @else
                                            <li class="breadcrumb-item opacity-50">
                                                <a class="text-reset" href="{{ route('search') }}">{{ translate('All Categories')}}</a>
                                            </li>
                                        @endif
                                        @if(isset($category_id))
                                            <li class="text-dark fw-600 breadcrumb-item">
                                                <a class="text-reset" href="{{ route('products.category', \App\Category::find($category_id)->slug) }}">"{{ \App\Category::find($category_id)->getTranslation('name') }}"</a>
                                            </li>
                                        @endif
                                    </ul>
                                    <input type="hidden" name="q" value="{{ $query }}">
                                </div>
                                <div class="form-group ml-auto mr-0 w-200px">
                                    <label class="mb-0 opacity-50">{{ translate('Sort by')}}</label>
                                    <select class="form-control form-control-sm aiz-selectpicker rounded-pill" name="sort_by" onchange="filter()">
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                    </select>
                                </div>
                                <div class="d-xl-none ml-auto ml-xl-3 mr-0 form-group align-self-end">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="min_price" value="">
                        <input type="hidden" name="max_price" value="">
                        <div class="row gutters-5 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 exmp" >
                            @foreach ($products as $key => $product)
                                <div class="col">
                                    @include('frontend.partials.product_box_1',['product' => $product])
                                </div>
                            @endforeach
                        </div>
                        <div class="aiz-pagination aiz-pagination-center mt-4">
                            {{ $products->appends(request()->input())->links() }}
                        </div> 
                    </div>
                </div>
            </form>
        </div>
    </section>
     <style>
        .exmp::-webkit-scrollbar {
            display: none;
            }
            .exmp {
              -ms-overflow-style: none;
              scrollbar-width: none;
            }

    </style>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
