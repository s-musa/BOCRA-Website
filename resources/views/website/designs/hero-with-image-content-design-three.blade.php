@php
$sectionImage = $section->getFirstMedia('section_image');
$sectionImageUrl = $sectionImage->getUrl();
@endphp
<section class="bg-half-170 bg-light d-table w-100" id="{{$section->spa_section_id ?? ''}}" style="background: url('{{ asset('website/images/backgrounds/corporate01.png') }}') center center;">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row mt-5 align-items-center">
         <div class="col-lg-7 col-md-6">
            <div class="title-heading">
               @if($section->title)
               <h4 class="display-3 mb-4 fw-bold title-dark">
                  {{ $section->title }}
               </h4>
               @endif
               @if($section->details)
               <div class="para-desc text-muted">{!! $section->details !!}</div>
               @endif

               @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
               <div class="mt-4 pt-2">
                  @foreach($section->cta_buttons as $key => $button)
                     @if($button->cta_link_type === 'custom')
                     <a href="{{ $button->custom_url }}" data-aos="fade-up" data-aos-duration="2500" data-aos-delay="{{ ($key + 1) * 600 }}"
                           class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-md-2 me-0 mb-md-0 mb-2">
                        {{ $button->cta_button_text }}
                     </a>
                     @elseif($button->cta_link_type === 'section')
                     <a href="{{ '#' . $button->section_url }}" data-aos="fade-up" data-aos-duration="2500" data-aos-delay="{{ ($key + 1) * 600 }}"
                           class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-md-2 me-0 mb-md-0 mb-2">
                        {{ $button->cta_button_text }}
                     </a>
                     @elseif($button->cta_link_type === 'page')
                     <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}" data-aos="fade-up" data-aos-duration="2500" data-aos-delay="{{ ($key + 1) * 600 }}"
                           class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-md-2 me-0 mb-md-0 mb-2">
                        {{ $button->cta_button_text }}
                     </a>
                     @else
                     <a href="#" data-aos="fade-up" data-aos-duration="2500" data-aos-delay="{{ ($key + 1) * 600 }}"
                           class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-md-2 me-0 mb-md-0 mb-2">
                        {{ $button->cta_button_text }}
                     </a>
                     @endif
                  @endforeach
               </div>
               @endif
            </div>
         </div>

         <div class="col-lg-5 col-md-6 mt-5 mt-sm-0">
            <img src="{{ $sectionImage ? $sectionImageUrl : asset('dummy-image.jpg') }}" class="img-fluid" alt="">
         </div>
      </div>
   </div>
</section>
