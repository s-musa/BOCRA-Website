{{--<section class="section">--}}
   <div class="container-fluid w-100 fixed-bottom p-0 pricing-sticky">
      <div class="tiny-single-item-{{$section->id }}">
         @foreach($section->pricing_plans as $plan)
         <div class="tiny-slide">
            <div class="sticky-pricing-bar p-5">
               <div class="container d-flex flex-md-nowrap flex-wrap flex-grow-1 flex-sm-grow-0 gy-sm-3 gy-0 align-items-center justify-content-between">
                  <div class="pricing-info">
                     <span class="price">{{ number_format($plan->price * 0.01, 0, 2) }}</span>
                     <small class="text-muted fs-6">Selling Price</small>
                  </div>
                  <div class="pricing-info">
                     <span class="price">Tudor - Mombasa</span>
                     <small class="text-muted fs-6">Location</small>
                  </div>
                  <div class="pricing-info">
                     <span class="price">{{ $plan->deposit_percentage }}%</span>
                     <small class="text-muted fs-6">Deposit</small>
                  </div>
                  <div class="pricing-info">
                     <span class="price">{{ $plan->size_sqft }}</span>
                     <small class="text-muted fs-6">Size (sqft)</small>
                  </div>

                  <div class="pricing-cta">
                     @if($plan->button_type === 'url')
                        <a href="{{ $plan->button_url ?? 'javascript:void(0)' }}" class="btn btn-primary {{ $customisation->button_style ?? 'default' }} w-100">
                           {{ $plan->button_text }}
                        </a>
                     @elseif($plan->button_type === 'section')
                        <a href="{{ '#' . $plan->section_url }}" class="btn btn-primary {{ $customisation->button_style ?? 'default' }} w-100">
                           {{ $plan->button_text }}
                        </a>
                     @elseif($plan->button_type === 'page')
                        <a href="{{ url($plan->page?->slug ?? 'javascript:void(0)') }}" class="btn btn-primary {{ $customisation->button_style ?? 'default' }} w-100">
                           {{ $plan->button_text }}
                        </a>
                     @else
                        <a href="javascript:void(0)" class="btn btn-primary {{ $customisation->button_style ?? 'default' }} w-100">
                           {{ $plan->button_text }}
                        </a>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
{{--</section>--}}

@push('scripts')
    <script>
       if(document.getElementsByClassName('tiny-single-item-{{$section->id }}').length > 0) {
          tns({
             container: '.tiny-single-item-{{$section->id }}',
             items: 1,
             controls: true,
             mouseDrag: true,
             loop: true,
             rewind: false,
             autoplay: true,
             autoplayButtonOutput: false,
             autoplayTimeout: 6000,
             navPosition: "bottom",
             controlsText: ['<i class="mdi mdi-chevron-left "></i>', '<i class="mdi mdi-chevron-right"></i>'],
             nav: false,
             speed: 1500,
             gutter: 0,
          });
       };
    </script>
@endpush
