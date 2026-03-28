@php
   $backgroundImage = $section->getFirstMedia('section_bg');
   $bgImageUrl = $backgroundImage ? $backgroundImage->getUrl() : null;
@endphp
<section class="section {{ $section->section_has_bg ? 'section-bg section-bg-fixed' : '' }}" id="{{$section->spa_section_id ?? ''}}">
   @if($section->section_has_bg)
      <div class="bg-overlay {{ $section->section_background_color ?? '' }}">
         @if($section->section_background_type === 'image-bg')
            <img src="{{ $bgImageUrl ?? asset('website/images/backgrounds/T5.jpg') }}" alt="image">
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
            <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 d-flex">
               <div class="card pricing text-center border-0 rounded shadow overflow-hidden w-100">
                  <div class="px-4 py-5 border-bottom {{ $plan->featured ? 'bg-primary text-white' : 'bg-light' }}">
                     <h5 class="text-uppercase mb-0">{{ $plan->name }}</h5>
                  </div>

                  <div class="px-4 py-5 bg-white">
                     <ul class="list-unstyled mb-0">
                        @foreach($plan->features as $feature)
                        @if($feature->is_included)
                        <li class="border-bottom h6 pb-4 fw-normal mt-3">
                           {{ $feature->name }}
                        </li>
                        @endif
                        @endforeach
                     </ul>

                     <div class="my-4">
                        <span class="text-muted small fw-bold me-2">Ksh.</span><span class="fw-semibold mb-0 text-primary h3">{{ number_format($plan->price * 0.01, 0, 2) }}</span>
                     </div>

                     @if($plan->button_type === 'url')
                     <a href="{{ $plan->button_url ?? 'javascript:void(0)' }}" class="btn {{ $plan->featured ? 'btn-primary' : 'btn-soft-primary' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @elseif($plan->button_type === 'section')
                     <a href="{{ '#' . $plan->section_url }}" class="btn {{ $plan->featured ? 'btn-primary' : 'btn-soft-primary' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @elseif($plan->button_type === 'page')
                     <a href="{{ url($plan->page?->slug ?? 'javascript:void(0)') }}" class="btn {{ $plan->featured ? 'btn-primary' : 'btn-soft-primary' . ' ' . $customisation->button_style ?? 'default' }}">
                        {{ $plan->button_text }}
                     </a>
                     @else
                     <a href="javascript:void(0)" class="btn {{ $plan->featured ? 'btn-primary' : 'btn-soft-primary' . ' ' . $customisation->button_style ?? 'default' }}">
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
