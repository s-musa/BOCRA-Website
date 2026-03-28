<section class="home-slider-2 align-items-center" id="{{$section->spa_section_id ?? ''}}">
   <div id="heroSectionSlider-{{$section->id}}" class="owl-carousel owl-theme">
      <div class="bg-home-2 d-flex align-items-center section-bg h-100"
              style="height: 90%; background:linear-gradient(to right,rgba(0, 0, 0, 0.8),rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2)),
              url({{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }}) no-repeat center / cover;">
         <div @class([
             $section->width_style ?: ($customisation->section_width_style ?? 'container'),
             'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
         >
            <div class="row">
               <div class="col-lg-8 text-start">
                  <div class="title-heading mt-4 h-100">
                     <h6 class="home-subtitle text-wrap text-secondary" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                        {{ $section->sub_title ?? '' }}
                     </h6>
                     <h2 class="fw-bold mb-3 title-dark text-uppercase text-primary line-height-1_4" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="800">
                        {{ $section->title ?? '' }}
                     </h2>
                     <div class="text-white para-desc details" data-aos="fade-up" data-aos-duration="1400" data-aos-delay="1000">
                        {!! $section->details !!}
                     </div>
                     @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
                        <div class="mt-4 pt-2" data-aos="fade-right" data-aos-duration="1600" data-aos-delay="1200">
                           @foreach($section->cta_buttons as $button)
                              <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2">
                                 {{ $button->cta_button_text }}
                              </a>
                           @endforeach
                        </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
      @foreach($section->hero_slides as $slide)
      <div class="bg-home-2 d-flex align-items-center section-bg h-100"
              style="height: 90%; background:linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2)),
              url({{ $slide->media[0]->original_url ?? asset('dummy-image.jpg') }}) no-repeat center / cover;">
         <div @class([
            $section->width_style ?: ($customisation->section_width_style ?? 'container'),
            'w__' . ($section->width ?? $customisation->section_width ?? '100') ]) id="{{$section->spa_section_id ?? ''}}"
         >
            <div class="row">
               <div class="col-lg-8 text-start">
                  <div class="title-heading mt-4 h-100">
                     <h6 class="home-subtitle text-wrap text-secondary" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                        {{ $slide->sub_title ?? '' }}
                     </h6>
                     <h2 class="fw-bold mb-3 title-dark text-uppercase text-primary line-height-1_4" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="800">
                        {{ $slide->title ?? '' }}
                     </h2>
                     <div class="text-white para-desc details" data-aos="fade-up" data-aos-duration="1400" data-aos-delay="1000">
                        {!! $slide->details !!}
                     </div>
                     @if($slide->page)
                        <div class="mt-4 pt-2" data-aos="fade-up" data-aos-duration="1600" data-aos-delay="1200">
                           <a href="{{ url($slide->page->slug ?? 'javascript:void(0)') }}" class="btn {{ $slide->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2">
                              {{ $slide->cta_button_text ?? $slide->page->title }}
                           </a>
                        </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
      @endforeach
   </div>
</section>

@push('scripts')
   <script>
      $('#heroSectionSlider-{{$section->id}}').owlCarousel({
         loop: true,
         items: 1,
         margin: 0,
         autoplay: true,
         autoplayTimeout: 6000,
         autoplayHoverPause: true,
         nav: false,
         dots: false,
         // animateOut: 'fadeOut',
         // animateIn: 'fadeIn',
         smartSpeed: 2000 // smooth transition speed
      });
      // heroSection.on('changed.owl.carousel', function(event) {
      //    let currentItem = $('.owl-item', this).eq(event.item.index);
      //    currentItem.find('.title-heading').addAttributes('animate__animated animate__fadeInUp');
      // });
   </script>
@endpush
