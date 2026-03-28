@push('styles')
   <link href="{{ asset('/website/css/tobii.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

<section class="section" id="{{$section->spa_section_id ?? ''}}">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center text-center">
         <div class="col-lg-7">
            <div class="mb-3 section-title">
               @if($section->sub_title)
                  <p class="sub-title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                     {{ $section->sub_title }}
                  </p>
               @endif
               <h2 class="title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                  {{ $section->title }}
               </h2>
               <div class="text-muted details" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                  {!! $section->details !!}
               </div>
            </div>
         </div>
      </div>
      @php
         $sectionGallery = $section->media->where('collection_name', 'section-gallery')->shuffle();
      @endphp
      @if($section->section_style)
         @include('website.designs.' . $section->section_style, ['section' => $section, 'sectionGallery' => $sectionGallery, 'customisation' => $customisation])
      @else
      <div id="grid" class="row">
         @foreach($sectionGallery as $key => $gallery)
            <div class="col-md-4 col-12 spacing picture-item" data-groups='["section-gallery"]'>
               <div class="card portfolio portfolio-classic portfolio-primary rounded overflow-hidden">
                  <div class="card-img position-relative">
                     <img src="{{ $gallery->original_url ?? null }}" class="img-fluid w-100" alt=""
                          style="height: 300px; object-fit: cover; object-position: center;">
                     <div class="card-overlay"></div>

                     <div class="content text-center pop-icon">
                        <a href="{{ $gallery->original_url ?? null }}"
                           data-lightbox="service-gallery" class="btn btn-pills btn-icon lightbox">
                           <i class="uil uil-search"></i>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         @endforeach
      </div>
      @endif
   </div>
</section>
