<section class="home-slider align-content-center" id="{{$section->spa_section_id ?? ''}}">
   <div id="heroSectionSlider-{{$section->id}}" class="owl-carousel owl-theme">
      <div class="bg-home d-flex align-items-center section-bg"
           style="min-height: 750px; background:url({{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }}) no-repeat center / cover;">
         <div class="bg-overlay"></div>
         <div @class([
             $section->width_style ?: ($customisation->section_width_style ?? 'container'),
             'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
         >
            <div class="row justify-content-center">
               <div class="col-lg-12 text-center">
                  <div class="title-heading">
                     @if($section->sub_title)
                        <h6 class="home-subtitle mb-4 text-wrap" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="600">
                           {{ $section->sub_title }}
                        </h6>
                     @endif
                     <h1 class="fw-bold text-white mb-3" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="800">
                        {{ $section->title }}
                     </h1>
                     <div class="text-white mx-auto para-desc details" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1000">
                        {!! $section->details !!}
                     </div>

                     @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
                        <div class="mt-4 pt-2" data-aos="fade-right" data-aos-duration="1600" data-aos-delay="1200">
                           @foreach($section->cta_buttons as $button)
                              <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" class="btn btn-lg {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2">
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
      <div class="bg-home d-flex align-items-center section-bg"
           style="min-height: 750px; background:url({{ $slide->media[0]->original_url ?? asset('dummy-image.jpg') }}) no-repeat center / cover;">
         <div class="bg-overlay"></div>
         <div @class([
             $section->width_style ?: ($customisation->section_width_style ?? 'container'),
             'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
         >
            <div class="row justify-content-center">
               <div class="col-lg-12 text-center">
                  <div class="title-heading">
                     @if($slide->sub_title)
                        <h6 class="home-subtitle mb-4" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="600">
                           {{ $slide->sub_title }}
                        </h6>
                     @endif
                     <h1 class="fw-bold text-white mb-3" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="800">
                        {{ $slide->title }}
                     </h1>
                     <div class="text-white mx-auto para-desc" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1000">
                        {!! $slide->details !!}
                     </div>
                     @if($slide->page)
                     <div class="mt-4 pt-2" data-aos="fade-right" data-aos-duration="1600" data-aos-delay="1200">
                        <a href="{{ url($slide->page->slug ?? 'javascript:void(0)') }}" class="btn btn-lg {{ $slide->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2">
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
         dots: true,
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
