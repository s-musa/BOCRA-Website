<section class="bg-home d-flex align-items-center saas-background pt-0" id="{{$section->spa_section_id ?? ''}}" style="height: auto;">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center">
         <div class="col-lg-12 text-center mt-0 mt-md-5 pt-0 pt-md-5">
            <div class="title-heading margin-top-100">
               <h4 class="display-6 fw-bold mb-4">{{ $section->title }}</h4>
               <div class="text-muted mx-auto para-desc">{!! $section->details !!}</div>
            </div>

            <div class="home-dashboard position-relative">
               <img src="{{ $section->media[0]->original_url ?? asset('dummy-image.jpg') }}" alt="" class="img-fluid">
            </div>
         </div>
      </div>
   </div>
</section>
