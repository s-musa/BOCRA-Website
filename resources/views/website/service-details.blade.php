@php
   $serviceGallery = $service->media->where('collection_name', 'service-gallery')->shuffle();
@endphp
@extends('website.index')

@push('meta_tags')
   {!! SEOTools::generate() !!}
@endpush

@push('styles')
   <link href="{{ asset('/website/css/tobii.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
   @include('website.shared.service-banner', ['title' => $service->title, 'service' => $service])

   <section class="section">
      <div @class([
             $customisation->section_width_style ?? 'container',
             'w__' . ($customisation->section_width ?? '100')
          ])
      >
         <div class="row">
            <div class="col-md-7 col-12">
               <div class="mb-3">
                  <div class="section-title">
                     <h1 class="text-primary mt-4">{{ $service->title }}</h1>
                     <div class="text-muted details mt-4">{!! $service->details !!}</div>
                  </div>
               </div>
            </div>
            <div class="col-md-5 col-12">
               <div class="mb-3 sidebar sticky-bar">
                  <img src="{{ $service->media[0]->original_url ?? asset('website/dummy_800x600.jpg') }}" alt="{{$service->slug}}"
                       class="img-fluid rounded" style="object-fit:cover;">
               </div>
            </div>
{{--            <div class="col-lg-4 col-md-6">--}}
{{--               <div class="sidebar sticky-bar">--}}
{{--                  <div class="widget">--}}
{{--                     <div class="rounded p-4 shadow bg-white">--}}
{{--                        <h6 class="widget-title font-weight-bold pt-2 pb-2 shadow bg-light rounded text-center">--}}
{{--                           Other Services--}}
{{--                        </h6>--}}
{{--                        <div class="mt-4">--}}
{{--                           @foreach($otherServices as $key => $item)--}}
{{--                           <div class="d-flex align-items-center mb-3">--}}
{{--                              <img src="{{ $item->media[0]->original_url ?? asset('website/dummy_800x600.jpg') }}"--}}
{{--                                   class="avatar avatar-small rounded" style="width: auto;" alt="">--}}
{{--                              <div class="flex-1 ms-3">--}}
{{--                                 <a href="{{ route('services.show', $item->slug) }}" class="d-block title text-dark">--}}
{{--                                    {{ $item->title }}--}}
{{--                                 </a>--}}
{{--                              </div>--}}
{{--                           </div>--}}
{{--                           @endforeach--}}
{{--                        </div>--}}
{{--                     </div>--}}
{{--                  </div>--}}
{{--               </div>--}}
{{--            </div>--}}
            @if($service->features && count($service->features) > 0)
            <div class="col-12 mt-5">
               <div class="row">
                  @foreach($service->features as $feature)
                  <div class="col-lg-4 col-md-6">
                     <div class="features feature-primary border-0 d-flex">
                        <div class="fea-icon rounded-circle text-white title-dark">
                           <i class="mdi {{ $feature->icon ?? '' }}"></i>
                        </div>

                        <div class="content flex-1 ms-3">
                           <h5 class="title text-dark">{{ $feature->title }}</h5>
                           <div class="text-muted details mt-2">{!! $feature->details !!}</div>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
            @endif
            @if($service->sub_services)
            <div class="col-12 mt-5">
               <div class="row mb-3">
                  @foreach($service->sub_services as $key => $subService)
                  <div class="col-lg-6 col-md-6 col-12">
                     <div class="sidebar sticky-bar mb-3">
                        <div class="section-title">
                           <h3 class="text-secondary">{{ $subService->title }}</h3>
                           <div class="text-muted details">{!! $subService->details !!}</div>
                        </div>
                     </div>
                     @if(!empty($subService->features) && count($subService->features))
                     <div class="mb-3">
                        @foreach($subService->features as $feature)
                           <div class="col-md-6 col-12 mb-3">
                              <div class="features feature-primary border-0 d-flex">
                                 <div class="fea-icon rounded-circle text-white title-dark">
                                    <i class="mdi {{ $feature->icon ?? '' }}"></i>
                                 </div>

                                 <div class="content flex-1 ms-3">
                                    <h5 class="title text-dark">{{ $feature->title }}</h5>
                                    <div class="text-muted details mt-2">{!! $feature->details !!}</div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     </div>
                     @endif
                  </div>
                  @endforeach
               </div>
            </div>
            @endif

            <div class="col-12">
               <div class="mb-3">
                  <div id="grid" class="row">
                     @foreach($serviceGallery as $gallery)
                     <div class="col-md-3 col-12 spacing picture-item" data-groups='["service-gallery"]'>
                        <div class="card portfolio portfolio-classic portfolio-primary rounded overflow-hidden">
                           <div class="card-img position-relative">
                              <img src="{{ $gallery->original_url ?? null }}" class="img-fluid" alt="">
                              <div class="card-overlay"></div>

                              <div class="pop-icon">
                                 <a href="{{ $gallery->original_url ?? null }}"
                                    data-lightbox="service-gallery" class="btn btn-pills btn-icon lightbox">
                                    <i class="uil uil-search"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
@endsection
