@foreach($section->section_cards as $key => $card)
   @php
      $cardMedia = $card->media?->firstWhere('collection_name', 'card-image')
   @endphp
   <div class="col-lg-4 col-md-6 mb-4 pb-2" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="500">
      <div class="card blog blog-primary shadow rounded overflow-hidden h-100">
         <a href="{{ $card->page ? url($card->page->slug) : ($card->custom_url ?? '#') }}" class="image position-relative overflow-hidden">
            <img src="{{ $cardMedia->original_url ?? asset('website/dummy_800x600.jpg') }}" alt=""
                 class="img-fluid w-100" style="height:250px;">
         </a>

         <div class="card-body content">
            <a href="{{ $card->page ? url($card->page->slug) : ($card->custom_url ?? '#') }}" class="h4 title text-dark d-block mb-0">
               {{ $card->title }}
            </a>
            @if($card->details)
               <p class="text-muted mt-2 mb-2">{!! $card->details !!}</p>
            @endif
         </div>
      </div>
   </div>
@endforeach
