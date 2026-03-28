<section class="section partners" id="{{$section->spa_section_id ?? ''}}">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center gy-4">
         <div class="col-12">
            <div class="owl-carousel owl-theme">
               @foreach($resources['clients'] as $key => $client)
                  <img src="{{ $client->media[0]->original_url ??asset('/website/images/partner/logo-1.svg') }}" class="img-fluid" alt=""
                       data-aos="zoom-up" data-aos-duration="1000" data-aos-delay="{{ ($key + 1) * 200}}">
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>

@push('scripts')
<script>
   $(document).ready(function(){
      $('.owl-carousel').owlCarousel({
         loop: true,
         margin: 40,
         autoplay: true,
         autoplayTimeout: 2000,
         autoplayHoverPause: true,
         nav: true,
         responsive: {
            0: {
               items: 1
            },
            600: {
               items: 2
            },
            1000: {
               items: 4
            }
         }
      });
   });
</script>
@endpush
