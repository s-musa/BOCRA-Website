<?php
$backgroundImage = $customisation->media?->firstWhere('collection_name', 'banner-image');
$pageBackgroundImage = $page->media?->firstWhere('collection_name', 'page-banner-image');
?>
<section class="bg-half-80 d-table {{ $page->background_color ?? ($customisation->background_color ?? 'bg-gray') }} bg-gradient w-100">
   <div class="bg-overlay {{ $page->banner_background_color ?? ($customisation->banner_bg ?? 'bg-gradient-overlay') }}"></div>
   <div @class([
       $customisation->section_width_style ?? 'container',
       'w__' . ($customisation->section_width ?? '100') ])
   >
      <div class="row mt-5 justify-content-center">
         <div class="col-12">
            <div class="title-heading text-center">
               <small class="text-white-50 mb-1 fw-medium text-uppercase mx-auto details">{!! $page->description !!}</small>
               <h5 class="heading fw-semibold mb-0 page-heading text-white title-dark">{{ $page ? $page->title : $title }}</h5>
            </div>
         </div>
      </div>

      <div class="position-middle-bottom">
         <nav aria-label="breadcrumb" class="d-block">
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
</section>
