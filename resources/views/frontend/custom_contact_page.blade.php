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
                    <div class="text-uppercase mb-1">{{ get_page_setting('subtitle',$page->id,null,App::getLocale()) }}</div>
                    <h1 class="h2 mb-3">{{ $page->getTranslation('title') }}</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class=" pb-3">
	<div class="container">
        <div class="row">
            @if(get_page_setting('hotline',$page->id,null,App::getLocale()))
            <div class="col-12 d-flex align-items-center justify-content-center my-3"> <span class="fs-18 fw-600">Hot Line: {{ get_page_setting('hotline',$page->id,null,App::getLocale()) }}</span></div>
            @endif

            <div class="col-xl-10 mx-auto">
                <div class="card mb-5">
                    <div class="card-body">
                        <form id="contact-form" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>{{ translate('Name') }}</label>
                                        <input type="text" class="form-control" name="name" placeholder="{{ translate('Your name') }}" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Email') }}</label>
                                        <input type="email" class="form-control" name="email" placeholder="{{ translate('Your email') }}" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Contact Number') }}</label>
                                        <input type="number" class="form-control" name="phone" placeholder="{{ translate('Your mobile number') }}" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Company Name') }}</label>
                                        <input type="text" class="form-control" name="company" placeholder="{{ translate('Your company name') }}" required="">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>{{ translate('Message') }}</label>
                                        <textarea class="form-control" name="message" rows="13" placeholder="{{ translate('Your message') }}" required=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary" id="send-email">{{ translate('Send Inquiries') }}</button>
                            </div>
                            <div class="alert contact-alert d-none mt-2">

                            </div>
                        </form>
                    </div>
                </div>
                <!--<div class="embed-responsive embed-responsive-21by9">-->
                <!--    {!! get_page_setting('g_map_iframe_code',$page->id) !!}-->
                <!--</div>-->
            </div>
        </div>
	</div>
</section>
<section>
    @if(get_setting('branch_name', null,  App::getLocale())!=null)
        <div class="py-4 bg-primary mb-4">
            <div class="container">
                 <h3 class="text-light text-center fs-20 fw-700  text-uppercase">Company Addresses</h3>
                <div class="row gutters-5 d-flex align-items-center">
                    @foreach (json_decode(get_setting('branch_name', null,  App::getLocale()), true) as $key => $value)
                    @if (count(json_decode(get_setting('branch_name', null,  App::getLocale()), true))>2)
                        <div class="col-md-4 d-flex align-items-center px-3 hov-shadow-lg">
                            <div class="py-5 px-1 text-left text-light   ">
                                <h4 class="text-light text-left fs-16 fw-700  text-uppercase">{{json_decode(get_setting('branch_name', null, App::getLocale()), true)[$key] }}</h4>
                                <div class="lh-1-8 opacity-70  text-left" style="min-height:3rem;">{{ json_decode(get_setting('branch_address', null, App::getLocale()), true)[$key]  }}</div>
                                <div class="lh-1-8 opacity-70  text-left" style="min-height:2rem;">{{ json_decode(get_setting('branch_contact', null, App::getLocale()), true)[$key]  }}</div>
                            </div>
                        </div>
                        @elseif (count(json_decode(get_setting('branch_name', null,  App::getLocale()), true))==1)
                        <div class="col-md-10 offset-md-2 d-flex align-items-center justify-content-center px-3  hov-shadow-lg">
                            <div class="py-5 px-1 text-left text-light ">
                                <h4 class="text-light text-left fs-16 fw-700  text-uppercase">{{json_decode(get_setting('branch_name', null, App::getLocale()), true)[$key] }}</h4>
                                <div class="lh-1-8 opacity-70  text-left">{{ json_decode(get_setting('branch_address', null, App::getLocale()), true)[$key]  }}</div>
                                <div class="lh-1-8 opacity-70  text-left" style="min-height:5rem;">{{ json_decode(get_setting('branch_contact', null, App::getLocale()), true)[$key]  }}</div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-6 d-flex align-items-center px-3 hov-shadow-lg">
                            <div class="py-5 px-1 text-left text-light  ">
                                <h4 class="text-light text-left fs-16 fw-700  text-uppercase">{{json_decode(get_setting('branch_name', null, App::getLocale()), true)[$key] }}</h4>
                                <div class="lh-1-8 opacity-70  text-left">{{ json_decode(get_setting('branch_address', null, App::getLocale()), true)[$key]  }}</div>
                                <div class="lh-1-8 opacity-70  text-left">{{ json_decode(get_setting('branch_contact', null, App::getLocale()), true)[$key]  }}</div>
                            </div>
                        </div>
                    @endif
                    @endforeach

                </div>
            </div>
        </div>
    @endif
</section>
<section>
    <div class="container mt-4">
        <div class="col-xl-10 mx-auto">
            <div class="embed-responsive embed-responsive-21by9">
                {!! get_page_setting('g_map_iframe_code',$page->id) !!}
            </div>
        </div>
    </div>
</section>
{{-- sales center section of footer  --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="mb-3 d-flex justify-content-center">
            <div class="af-sec-title text-center">
                <h3 class="h4 fw-700 text-uppercase text-primary fs-20 ">{{ translate('Our Sales Center') }}</h3>
                <div class="opacity-70">{{ translate('Visit our sales center to get a more involved experience') }}</div>
            </div>
        </div>
        <div class="row gutters-5 justify-content-center">
            @if(get_setting('home_branch_name',null,App::getLocale()) != null)
                @php $home_branches = json_decode(get_setting('home_branch_name', null, App::getLocale())); @endphp
                @foreach ($home_branches as $key => $value)
                    <div class="col-lg-5 col-md-6">
                        <div class="position-relative mb-3">
                            <img src="{{ static_asset('assets/img/map_bg.png') }}" class="img-fluid w-100">
                            <div class="row align-items-center z-1 absolute-full">
                                <div class="col">

                                </div>
                                <div class="col">
                                    <div class="fw-700 mb-0 fs-16 mb-2">{{ $value }}</div>
                                    <p class="mb-3">{{ json_decode(get_setting('home_branch_subtitle',null,App::getLocale()))[$key] }}</p>
                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#gmapmocal-{{ $key }}" class="fw-700 text-alter">{{ translate('VIEW ON MAP') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade map-modal" id="gmapmocal-{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ $value }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                            <div class="embed-responsive embed-responsive-21by9 " style="min-height: 42vh;">

                                {!! json_decode(get_setting('home_branch_link'))[$key] !!}
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
{{-- end sales section of footer  --}}
@endsection


@section('script')
<script>
    $(document).ready(function() {
        $("#send-email").click(function(e){
            e.preventDefault();
            $(this).html('<span>Sending</span><span class="spinner-grow spinner-grow-sm ml-2"></span>');
            $.ajax({
                type: "POST",
                url: '{{ route('contact-send-email') }}',
                data: $('#contact-form').serialize(),
                success: function(data){


                    $('.contact-alert').removeClass('d-none');
                    if(data.error == 'success'){
                        $('#send-email').html('{{ translate('Send Inquiries') }}');
                        $('.contact-alert').removeClass('alert-danger').addClass('alert-success').html('Your email has been sent successfully!');
                    }else if(data.error == 'missing'){
                        setTimeout(function(){
                            $('#send-email').html('{{ translate('Send Inquiries') }}');
                            $('.contact-alert').removeClass('alert-success').addClass('alert-danger').html('Please fill all information!');
                        }, 2000);
                    }else if(data.error == 'email'){
                        setTimeout(function(){
                            $('#send-email').html('{{ translate('Send Inquiries') }}');
                            $('.contact-alert').removeClass('alert-success').addClass('alert-danger').html('Please provide a valid email!');
                        }, 2000);
                    }
                },
                error: function (data) {
                    console.log(data);
                    $('#send-email').html('Send Email');
                },
            });

        });
    });
</script>
@endsection
