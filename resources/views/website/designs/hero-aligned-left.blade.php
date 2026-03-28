<section class="bg-home-2 align-content-center" id="home" style="max-height: 777px; min-height: 700px; background:linear-gradient(to right, #000000, #434242, transparent, transparent), url({{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }}) no-repeat center / cover;">
{{--   <div class="bg-overlay"></div>--}}
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ]) id="{{$section->spa_section_id ?? ''}}"
   >
      <div class="row">
         <div class="col-lg-7">
            <div class="text-start text-white">
               @if($section->sub_title)
               <h6 class="home-subtitle text-secondary text-wrap mb-3" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="600">
                  {{ $section->sub_title }}
               </h6>
               @endif
               <h3 class="fw-bold text-primary mb-4" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="800">
                  {{ $section->title }}
               </h3>
               <div class="home-desc-2" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1000">
                  {!! $section->details !!}
               </div>
               @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
               <div class="mt-4 pt-3">
                  @foreach($section->cta_buttons as $key => $button)
                     <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2"
                           data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1200">
                        {{ $button->cta_button_text }}
                     </a>
                  @endforeach
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</section>
