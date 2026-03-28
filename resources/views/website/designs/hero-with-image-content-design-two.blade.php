<section class="bg-home d-flex align-items-center seo-home" id="{{$section->spa_section_id ?? ''}}">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row mt-5 justify-content-center">
         <div class="col-lg-12 text-center mt-0 mt-md-5 pt-0 pt-md-5">
            <div class="heading-title margin-top-100">
               <h1 class="heading fw-semibold text-white title-dark mb-3">{{ $section->title }}</h1>
               <div class="para-desc mx-auto text-white-50">{!! $section->details !!}</div>
            </div>

            <div class="row justify-content-center">
               <div class="col-lg-9 col-md-10">
                  <div class="home-dashboard mb-md-5 mb-0 pb-md-5 pb-0">
                     <img src="{{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }}" alt="" style="top: 0;" class="img-fluid">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
