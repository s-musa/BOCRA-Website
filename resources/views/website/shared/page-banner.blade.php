<?php
   $backgroundImage = $customisation->media?->firstWhere('collection_name', 'banner-image');
   $pageBackgroundImage = $page ? $page->media?->firstWhere('collection_name', 'page-image') : null;
?>
@if($page->banner_style && $page->banner_style !== 'default')
   @include('website.designs.shared.' . $page->banner_style, ['backgroundImage' => $backgroundImage, 'page' => $page,
      'pageBackgroundImage' => $pageBackgroundImage, 'customisation' => $customisation])
@elseif($customisation->banner_style && $customisation->banner_style !== 'default')
   @include('website.designs.shared.' . $customisation->banner_style, ['backgroundImage' => $backgroundImage, 'page' => $page,
     'pageBackgroundImage' => $pageBackgroundImage, 'customisation' => $customisation])
@else
   <section class="bg-half-170 d-table w-100" style="background: url({{
                isset($pageBackgroundImage)
                    ? $pageBackgroundImage->getUrl()
                    : ($backgroundImage ? $backgroundImage->getUrl() : asset('website/dummy_800x600.jpg'))
            }}) no-repeat center / cover;">
      <div class="bg-overlay {{ $customisation->banner_bg ?? 'bg-gradient-overlay' }}"></div>
      <div @class([
       $customisation->section_width_style ?? 'container',
       'w__' . ($customisation->section_width ?? '100') ])
      >
         <div class="row g-0 align-items-center mt-5">
            <div class="col-lg-8 col-md-6">
               <div class="title-heading text-md-start text-center">
                  <h1 class="heading fw-semibold mb-0 page-heading text-white title-dark">{{ $title }}</h1>
                  @if($page->description)
                     <small class="text-white-50 mb-1 fw-medium mx-auto">{!! $page->description !!}</small>
                  @endif
               </div>
            </div>

            <div class="col-lg-4 col-md-6 mt-4 mt-sm-0">
               <div class="text-md-end text-center">
                  <nav aria-label="breadcrumb" class="d-inline-block">
                     <ul class="breadcrumb breadcrumb-muted mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        @if(!empty($page->parent_id))
                           <li class="breadcrumb-item"><a href="{{ url($page->parent?->slug ?? '') }}">{{ $page->parent->title }}</a></li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                     </ul>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </section>
@endif
