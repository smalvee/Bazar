@extends('frontend.layouts.app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($page->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_img) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($page->unit_price) }}" />
@endsection

@section('content')
<section class="mb-4 ">
    <div class="text-white bg-cover bg-no-repeat bg-center position-relative">
        <div class="aiz-carousel dot-small-white dots-inside-bottom" data-dots="true" data-autoplay="false" data-arrows="true">
            @foreach(explode(',',get_page_setting('banner',$page->id)) as $value)
            <div class="carousel-box">
                <img src="{{ uploaded_asset($value) }}" class="mw-100 w-100">
            </div>
            @endforeach
        </div>
        <div class="absolute-full overflow-hidden d-flex align-items-center">
            <div class="container text-center">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-left">
                        <div class="text-uppercase mb-1">{{ get_page_setting('subtitle',$page->id,null,App::getLocale()) }}</div>
                        <h1 class="h2 mb-3">{{ $page->getTranslation('title') }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class=" border-bottom pb-2 pt-5">
	<div class="container mb-3 pb-1>

        <div class="container">
            <div class="mb-3"  style="background-image: url({{ static_asset('assets/img/logo-watermark.png') }});background-position: left center; background-repeat: no-repeat;background-size:contain;">
                <div class="row py-lg-7">
                    <div class="col-xxl-6 offset-xxl-4 col-xl-9 offset-xl-2">
                        <!--<h3 class="text-uppercase text-primary fs-24 fw-900">{{ translate(get_setting('who_we_are_title')) }}</h3>-->
                        <h3 class="text-uppercase text-primary fs-24 fw-900">{{ get_page_setting('who_we_are_title',$page->id,null,App::getLocale()) }}</h3>
                        <div class="lh-1-9 mb-3 fs-12 opacity-100 text-justify">{!! get_page_setting('who_we_are',$page->id,null, App::getLocale()) !!}</div>
                    </div>
                </div>
            </div>
        </div>


    </div>

   
</section>

<section >
     <div class="container">
        <div class="mb-3 mt-3">
            <div class="af-sec-title  text-center my-4">
                <h3 class="text-uppercase text-primary fw-700 fs-18">{{ get_page_setting('management_up_title',$page->id,null,App::getLocale()) }}</h3>
            </div>
            <div class="row text-center justify-content-center">
                @if (get_page_setting('management_team_images',$page->id) != null)
                    @foreach (json_decode(get_page_setting('management_team_images',$page->id), true) as $key => $value)
                        <div class="col-xl-10 col-lg-10 ">
                            <div class="manage-card">
                                <div class="mb-5 d-md-flex align-items-center p-2" >
                                    <img src="{{ uploaded_asset($value) }}" class="rounded-circle mb-4 mb-md-0 size-140px flex-shrik-0">
                                    <div class="flex-grow-1 lh-1-8 text-left ml-4 mr-4 pr-2">
                                        <div class="d-flex mb-2">
                                            <span class="fw-700 georgia  op text-uppercase">{{ json_decode(get_page_setting('management_team_names',$page->id,null,App::getLocale()),true)[$key] }}</span>
                                            <span class="border-left pl-3 ml-3 fw-600 opacity-70" style="border-color:#adafb4!important;">{{ json_decode(get_page_setting('management_team_designations',$page->id,null,App::getLocale()),true)[$key] }}</span>
                                        </div>
                                        <div class="lh-1-7 fs-12 mb-2 text-justify show-read-more"  id="text-{{ $key }}" >{!! json_decode(get_page_setting('management_team_details',$page->id,null,App::getLocale()),true)[$key] !!}</div>
                                       {{-- <a href="javascript:void(0);"  class="text text-primary read-more" > <strong>Read More</strong></a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .management{
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    }
</style>

    <style>
        .show-read-more .more-text{
            display: none;
        }
    </style>
@endsection
