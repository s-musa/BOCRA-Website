@php
   $backgroundImage = $section->media?->firstWhere('collection_name', 'section_bg');
@endphp
<section class="section" id="{{$section->spa_section_id ?? ''}}">
   @if($section->section_has_bg)
      <div class="{{ $section->section_background_color ?? '' }}">
         @if($section->section_background_type === 'image-bg')
            <img src="{{ $backgroundImage->getUrl() ?? asset('website/images/backgrounds/T5.jpg') }}" alt="image">
         @endif
      </div>
   @endif
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row align-items-center">
         <div class="col-12">
            <div class="section-title text-center mb-4 pb-2">
               @if($section->sub_title)
                  <p class="sub-title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                     {{ $section->sub_title }}
                  </p>
               @endif
               @if($section->title)
                  <h2 class="title" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                     {{ $section->title }}
                  </h2>
               @endif
               @if($section->details)
                  <div class="details" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                     {!! $section->details !!}
                  </div>
               @endif
            </div>
         </div>
      </div>

      <div class="row">
         @foreach($section->pricing_plans as $plan)
         <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
            <div class="card border-0 shadow h-100">
               <div class="p-4 border-bottom border-light">
                  <h6 class="fw-semibold mb-3 text-uppercase">{{ $plan->name }}</h6>
                  <p class="text-muted mb-0">{{ $plan->tagline }}</p>

                  <div class="d-flex my-4">
                     <span class="text-secondary small fw-bold me-2">Kshs.</span><span class="price h3 fw-semibold mb-0">{{ number_format($plan->price * 0.01, 0, 2) }}</span>
                     @if($plan->billing_period_label)
                     <span class="text-muted align-self-end mb-1">{{ '/' . $plan->billing_period_label }}</span>
                     @endif
                  </div>

                  @if($plan->button_type === 'url')
                  <a href="{{ $plan->button_url ?? 'javascript:void(0)' }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-soft-secondary' . ' ' . $customisation->button_style ?? 'default' }} w-100">
                     {{ $plan->button_text }}
                  </a>
                  @elseif($plan->button_type === 'section')
                  <a href="{{ '#' . $plan->section_url }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-soft-secondary' . ' ' . $customisation->button_style ?? 'default' }} w-100">
                     {{ $plan->button_text }}
                  </a>
                  @elseif($plan->button_type === 'page')
                  <a href="{{ url($plan->page?->slug ?? 'javascript:void(0)') }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-soft-secondary' . ' ' . $customisation->button_style ?? 'default' }} w-100">
                     {{ $plan->button_text }}
                  </a>
                  @else
                  <a href="javascript:void(0)" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-soft-secondary' . ' ' . $customisation->button_style ?? 'default' }} w-100">
                     {{ $plan->button_text }}
                  </a>
                  @endif
               </div>

               <div class="p-4">
                  <h6 class="mb-3">Features:</h6>
                  <ul class="mb-0 list-unstyled">
                     @foreach($plan->features as $feature)
                     <li class="text-muted">
                     <span class="{{ $feature->is_included ? 'text-success' : 'text-danger' }} h5 me-1">
                        <i class="align-middle uil {{ $feature->is_included ? 'uil-check-circle' : 'uil-times-circle' }}"></i>
                     </span>
                        {{ $feature->name }}
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</section>
