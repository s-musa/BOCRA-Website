<section class="section @if($section->section_has_bg) section-bg section-bg-fixed @endif" id="{{$section->spa_section_id ?? ''}}">

   @if($section->section_has_bg)
   <div class="{{ $section->section_background_color ?? 'bg-overlay ' }} section-bg-fixed">
      @if($section->section_background_type === 'image-bg')
         <img src="{{ $backgroundImage->getUrl() ?? asset('website/images/backgrounds/T5.jpg') }}" alt="">
      @endif
   </div>
   @endif

   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >

      <div class="row align-items-center">
         <div class="col-lg-7 col-md-8 col-12">
            <div class="section-title text-md-start text-center">
               @if($section->sub_title)
                  <p class="sub-title" data-aos="fade-right" data-aos-duration="1800" data-aos-delay="100">
                     {{ $section->sub_title }}
                  </p>
               @endif
               <h3 class="title" data-aos="fade-right" data-aos-duration="1800" data-aos-delay="200">
                  {{ $section->title }}
               </h3>
               <div class="text-muted para-desc details" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="300">
                  {!! $section->details !!}
               </div>
            </div>
         </div>
         <div class="col-lg-5 col-md-4 col-12" data-aos="zoom-in-up" data-aos-duration="1000" data-aos-delay="100">
            <div class="text-md-end text-center">
               @foreach($section->cta_buttons as $key => $button)
               <a href="{{ url($button->page->slug ?? '#') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }}"
                     data-aos="fade-left" data-aos-duration="1800" data-aos-delay="{{ ($key + 1) * 300 }}">
                  {{ $button->cta_button_text }}
               </a>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>
