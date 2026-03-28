@if($section->section_style)
   @include('website.designs.' . $section->section_style, ['section' => $section, 'customisation' => $customisation])
@else
<section class="bg-home align-content-center" id="home" style="max-height: 750px; min-height: 400px; background-image:url({{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }});">
   <div class="bg-overlay"></div>
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row">
         <div class="col-lg-12 text-center">
            <div class="title-heading">
               @if($section->sub_title)
               <h6 class="home-subtitle mb-4 text-wrap" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="600">
                  {{ $section->sub_title }}
               </h6>
               @endif
               <h1 class="fw-bold text-white" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="800">
                  {{ $section->title }}
               </h1>
               <div class="home-desc pt-3" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1000">
                  {!! $section->details !!}
               </div>
               @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
               <div class="mt-4 pt-3" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1200">
                  @foreach($section->cta_buttons as $button)
                     <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2">
                        {{ $button->cta_button_text }}
                     </a>
                  @endforeach
               </div>
               @endif
            </div>

            @if($restaurantEnabled === 'YES')
               <div class="booking-form-overlay position-absolute w-100">
                  @include('website.restaurant.hero-booking-form')
               </div>
            @endif
         </div>
      </div>
   </div>
</section>

@if($epropertyEnabled === 'YES')
   @include('website.shared.property-search-section')
@endif

@endif
