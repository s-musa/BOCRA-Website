<section class="section" id="{{$section->spa_section_id ?? ''}}">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center text-center">
         <div class="col-12">
            <div class="mb-4">
               @if($section->sub_title)
                  <p class="sub-title" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="100">
                     {{ $section->sub_title }}
                  </p>
               @endif
               @if($section->title)
                  <h3 class="title" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="200">
                     {{ $section->title }}
                  </h3>
               @endif
               @if($section->details)
                  <div class="text-muted details" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="300">
                     {!! $section->details !!}
                  </div>
               @endif
               @foreach($section->cta_buttons as $key => $button)
                  <a href="{{ url($button->page->slug ?? '#') }}" class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }}"
                     data-aos="fade-up" data-aos-duration="2000" data-aos-delay="{{ ($key + 1) * 400 }}">
                     {{ $button->cta_button_text }}
                  </a>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>
