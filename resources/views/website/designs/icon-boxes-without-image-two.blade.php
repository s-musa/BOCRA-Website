@php
   $backgroundImage = $section->media->firstWhere('collection_name', 'section_bg');
   $sectionImage = $section->media?->firstWhere('collection_name', 'section_image');
   $wordCount = str_word_count(strip_tags($section->details));
@endphp
<section class="section @if($section->section_has_bg) section-bg section-bg-fixed @endif" id="{{$section->spa_section_id ?? ''}}">
   @if($section->section_has_bg && $section->section_background_color)
   <div class="{{ $section->section_background_color }}">
      @if($section->section_background_type === 'image-bg')
      <img src="{{ $backgroundImage->getUrl() ?? asset('website/images/backgrounds/T5.jpg') }}" alt="image">
      @endif
   </div>
   @endif
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row">
         <div class="col-lg-4 col-12">
            <div class="sticky-bar">
               @if($section->sub_title || $section->title || $section->details)
               <div class="mb-lg-0 mb-3 section-title">
                  @if($section->sub_title)
                  <p class="sub-title" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="100">
                     {{ $section->sub_title }}
                  </p>
                  @endif
                  @if($section->title)
                  <h2 class="text-dark mb-4" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="200">
                     {{ $section->title }}
                  </h2>
                  @endif
                  @if($section->details)
                  <div class="details mb-4" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="300">
                     {!! $section->details !!}
                  </div>
                  @endif
                  @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
                  @foreach($section->cta_buttons as $button)
                  <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2"
                        data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                     {{ $button->cta_button_text }}
                  </a>
                  @endforeach
                  @endif
               </div>
               @endif
            </div>
         </div>
         <div class="col-lg-8 col-12">
            <div class="mb-3" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
               <div class="row">
                  @foreach($section->icon_boxes as $key => $card)
                     <div class="col-md-6">
                        <div class="card feature-card design-2 {{ $loop->odd ? 'mt-md-5' : '' }} mb-3 text-center rounded shadow-md">
                           @if($card->icon)
                           <i class="mdi {{ $card->icon }} h1"></i>
                           @else
                           <span class="fw-bold h1 text-primary">{{ $key + 1 }}</span>
                           @endif

                           <div class="content">
                              <div class="title h5 mb-3">{{ $card->title }}</div>
                              <div class="details mb-0">{!! $card->details !!}</div>
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
