@php
   $backgroundImage = $section->media->firstWhere('collection_name', 'section_bg');
   $wordCount = str_word_count(strip_tags($section->details));
@endphp
<section class="section @if($section->section_background_type === 'image-bg') section-bg @endif" id="{{$section->spa_section_id ?? ''}}"
         @if($section->section_has_bg && $backgroundImage && !$section->section_background_color)
            style="background-image: url('{{ $backgroundImage->getUrl() }}'); background-size: cover; background-position: center;"
         @endif>
   @if($section->section_has_bg && $section->section_background_color)
   <div class="{{ $section->section_background_color }}">
      @if($section->section_background_type === 'image-bg')
         @if($backgroundImage)
         <img src="{{ $backgroundImage->getUrl() }}" alt="">
         @else
         <img src="{{ asset('website/dummy_1266x749.jpg') }}" alt="">
         @endif
      @endif
   </div>
   @endif
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center text-center">
         <div class="@if($wordCount > 20) col-lg-10 @else col-lg-6 @endif col-12">
            <div class="section-title title-2 mb-4">
               @if($section->sub_title)
               <p class="sub-title" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="100">
                  {{ $section->sub_title }}
               </p>
               @endif
               <h3 class="title" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="200">
                  {{ $section->title }}
               </h3>
               <div class="text-muted details" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="300">
                  {!! $section->details !!}
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-12 mx-auto">
            <div class="row">
               @if($section->section_style)
                  @include('website.designs.' . $section->section_style, ['section' => $section, 'customisation' => $customisation])
               @else
               @foreach($section->section_cards as $key => $card)
                  <div class="col-lg-4 col-md-6 col-12 mx-auto" data-aos="zoom-in-up" data-aos-duration="1800" data-aos-delay="{{ ($key + 1) * 400 }}">
                     <div class="mb-3">
                        <div class="card border-0 p-4 text-center rounded features features-classic feature-primary card-100">
                           <i class="mdi {{ $card->icon ?? '' }} icon h1"></i>
                           <div class="content">
                              <div class="title text-dark h5">{{ $card->title }}</div>
                              <div class="text-muted mb-0 mt-3">{!! $card->details !!}</div>
                           </div>
                        </div>
                     </div>
                  </div>
               @endforeach
               @endif
            </div>
         </div>
      </div>
   </div>
</section>
