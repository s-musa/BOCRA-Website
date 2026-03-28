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
               <div class="card pricing price-two rounded text-center border-0 shadow px-4 py-5 bg-white">
                  <div class="mb-5">
                     <h1 class="display-6 fw-medium mb-0">{{ number_format($plan->price * 0.01, 0, 2) }}</h1>
                     <p class="text-muted mb-0">{{ $plan->billing_method }}</p>
                  </div>

                  <div class="p-2 h6 mx-5 rounded-lg {{ $plan->featured ? 'bg-secondary' : 'bg-light' }}">
                     <span class="text-uppercase fw-normal {{ $plan->featured ? 'text-white' : 'text-dark' }}">{{ $plan->name }}</span>
                  </div>

                  <ul class="list-unstyled mb-0 mt-4">
                     @foreach($plan->features as $feature)
                     <li class="text-muted @if(!$feature->is_included) text-danger text-strike-through @endif mt-3">
                        {{ $feature->name }}
                     </li>
                     @endforeach
                  </ul>

                  <div class="mt-4 pt-2">
                     @if($plan->button_type === 'url')
                     <a href="{{ $plan->button_url ?? 'javascript:void(0)' }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-light' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @elseif($plan->button_type === 'section')
                     <a href="{{ '#' . $plan->section_url }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-light' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @elseif($plan->button_type === 'page')
                     <a href="{{ url($plan->page?->slug ?? 'javascript:void(0)') }}" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-light' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @else
                     <a href="javascript:void(0)" class="btn {{ $plan->featured ? 'btn-secondary' : 'btn-light' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @endif
                  </div>
               </div>
            </div>
         @endforeach
      </div>
   </div>
</section>
