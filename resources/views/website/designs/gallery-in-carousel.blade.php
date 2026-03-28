<div class="row">
   <div class="col-12">
      <div class="tiny-three-item">
         @foreach($sectionGallery as $key => $gallery)
         <div class="tiny-slide">
            <img src="{{ $gallery->original_url ?? null }}" alt="" class="img-fluid h-100 w-auto">
         </div>
         @endforeach
      </div>
   </div>
</div>

@push('scripts')
    <script>
       if(document.getElementsByClassName('tiny-three-item').length > 0) {
          var slider = tns({
             container: '.tiny-three-item',
             controls: true,
             mouseDrag: true,
             loop: true,
             rewind: false,
             autoplay: true,
             autoplayButtonOutput: false,
             autoplayTimeout: 3000,
             navPosition: "bottom",
             controlsText: ['<i class="mdi mdi-chevron-left "></i>', '<i class="mdi mdi-chevron-right"></i>'],
             speed: 400,
             gutter: 12,
             responsive: {
                992: {
                   items: 3
                },

                767: {
                   items: 2
                },

                320: {
                   items: 1
                },
             },
          });
       };
    </script>
@endpush
