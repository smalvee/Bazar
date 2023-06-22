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
    <div class="text-white bg-cover bg-no-repeat bg-center py-9" style="background-image: url({{uploaded_asset(get_page_setting('banner',$page->id)) }});">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    {{-- <div class="text-uppercase mb-1">{{ $page->getTranslation('subtitle')  }}</div> --}}
                    {{-- <h1 class="h2 mb-3">{{ $page->getTranslation('title') }}</h1> --}}
                    {{-- <a href="{{ get_page_setting('shop_link',$page->id) }}" class="btn btn-primary text-uppercase btn-circle px-4 fs-12">{{ translate('Visit Shop') }}</a> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<section class="brder-bottom pb-5">
	{{-- FAQ section  --}}
<div class="container my-5" id="faq">
    <div class="row" >
        <div class="col d-flex justify-content-center align-items-center">
            <h5 class="text text-center text-alter-6 text-uppercase my-3">{{ get_page_setting('stage_text',$page->id,null,App::getLocale()) }}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 d-none d-lg-block"></div>
        <div class="col-md-8">
            @if (get_setting('faq_questions') != null)
            @foreach (json_decode(get_setting('faq_questions'), true) as $key => $value)
                <div class="row">
                   <div class="col-12  text-white accordion" style="background-color: #143697;" data-toggle="collapse"  role="button" aria-expanded="false" aria-controls="faq-{{ $key }}" onclick="collapse({{ $key }})" id="q-{{$key}}">
                    <a class="btn text-white fs-14 fw-600 ">{{ json_decode(get_setting('faq_questions',null,App::getLocale()),true)[$key] }}</a>
                   </div>
                </div>

               <div class="row">
                    <div class="col-12 p-1">
                        <div class="collapse multi-collapse border "  id="faq-{{ $key }}">
                            <div class="card-body">
                                {{ json_decode(get_setting('faq_answers',null,App::getLocale()),true)[$key]  }}
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


@endsection
