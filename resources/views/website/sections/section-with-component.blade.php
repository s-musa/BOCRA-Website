@php
   $backgroundImage = $section->media?->firstWhere('collection_name', 'section_bg');
@endphp
<section class="section component @if($section->section_has_bg) section-bg section-bg-fixed @endif" id="{{$section->spa_section_id ?? ''}}">
   @if($section->section_has_bg)
   <div class="{{ $section->section_background_color ?? '' }}">
      @if($section->section_background_type === 'image-bg')
      <img src="{{ $backgroundImage->getUrl() ?? asset('website/images/backgrounds/T5.jpg') }}" alt="">
      @endif
   </div>
   @endif
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      @if($section->title || $section->sub_title || $section->details)
      <div class="row justify-content-center">
         <div class="col-lg-7 mb-4">
            <div class="section-title text-center">
               @if($section->sub_title)
               <p class="sub-title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                  {{ $section->sub_title }}
               </p>
               @endif
               <h3 class="title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                  {{ $section->title }}
               </h3>
               @if($section->details)
               <div class="text-muted details" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                  {!! $section->details !!}
               </div>
               @endif
            </div>
         </div>
      </div>
      @endif
      @includeIf('website.components.' . $section->component_type, ['section' => $section, 'customisation' => $customisation])
   </div>
</section>
